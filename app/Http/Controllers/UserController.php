<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Degree;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function createToken(Request $request) {

        $validated = $request->validate([
            'tokenName' => 'required|string',
        ]);

        $token = $request->user()->createToken($request->tokenName);
        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }

    public function deleteToken(Request $request) {

        $validated = $request->validate([
            'tokenId' => 'required|integer',
        ]);
        $request->user()->tokens()->where('id', $request->tokenId)->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function getTokens(Request $request) {
        return response()->json([
            'tokens'=>$request->user()->tokens
        ]);
    }

    /**
     * Handle user registration
     */
    public function register(Request $request) {    
        $validated = $request->validate([
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:6',
            'passwordConfirmation' => 'required|string|same:password',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'type' => 'required|in:viewer,researcher,reviewer',
            'degrees' => 'required_if:type,researcher|array',
        ]);
        
        if ( $request->type === "researcher" ) {
            $index = 0;
            $errors = [];
            foreach ( $request->degrees as $degree) {
                $validator = Validator::make($degree, [
                    'title'=>'required|string',
                    'institution'=>'required|string',
                    'received'=>'required|date'
                ]);
                if ( $validator->fails()) 
                    $errors['degree_'.$index] = 
                        $validator->errors();

                $index++;
            }

            if (count($errors) > 0) {
                return response()->json([
                    "message"=>"The given data was invalid",
                    "errors"=> $errors
                ], 422);
            }      

        }
        
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = $request->type;
        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->save();
        
        if ($request->type == "researcher"){
            $degrees = [];

            foreach($request->degrees as $degree) {
                $deg = new Degree();
                $deg->title = $degree['title'];
                $deg->received = $degree['received'];
                $deg->institution = $degree['institution'];
                $deg->researcher_email = $user->email;
                $deg->save();
                array_push($degrees, $deg);
            }

            return response()->json([
                'success' => true,
                'user' => $user,
                'degrees' => $degrees
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'user' => $request->user()
            ]);
        }
    }   

     /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {   
        $validated = $request->validate([
            'email' => 'email|required|exists:users',
            'password' => 'required|string|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'user' => $request->user()
            ]);
        }

        return response()->json([
            'errors' => [
                'password' => ['The provided credentials do not match our records.'],
            ]
        ],422);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'success'=>true
        ]);
    }

    public function getAllUsers(Request $request) {

        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        return response()->json([
            'users' => User::all()
        ]);
    }

    public function getAllUsersOfType(Request $request, $type) {

        if ( $request->user()->type != 'admin') 
        return response()->json([
            'error' => true,
            'message' => 'You do not have the authority to perform this action'
        ], 401);


        return response()->json([
            'users' => User::where('type',$type)->get()
        ]);
    }

    public function getUserById(Request $request, $id) {

        if ( $request->user()->type != 'admin') 
        return response()->json([
            'error' => true,
            'message' => 'You do not have the authority to perform this action'
        ], 401);


        return response()->json([
            'user' => User::where('id',$id)->first()
        ]);
    }

    public function addUser(Request $request) {

        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        $validated = $request->validate([
            'email' => 'email|required|unique:users',
            'password' => 'required|string|min:6',
            'passwordConfirmation' => 'required|string|same:password',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'type' => 'required|in:viewer,researcher,reviewer,admin,editor',
            'admin_email' => 'required|email|exists:users',
            'degrees' => 'required_if:type,researcher|array',
        ]);

        if ( $request->type === "researcher" ) {
            $index = 0;
            $errors = [];
            foreach ( $request->degrees as $degree) {
                $validator = Validator::make($degree, [
                    'title'=>'required|string',
                    'institution'=>'required|string',
                    'received'=>'required|date'
                ]);
                if ( $validator->fails()) 
                    $errors['degree_'.$index] = 
                        $validator->errors();

                $index++;
            }

            if (count($errors) > 0) {
                return response()->json([
                    "message"=>"The given data was invalid",
                    "errors"=> $errors
                ], 422);
            }      

        }

        $newUser = new User;
        $newUser->email = $request->email;
        $newUser->first_name = $request->firstName;
        $newUser->last_name = $request->lastName;
        $newUser->type = $request->type;
        $newUser->password = Hash::make($request->password);
        $newUser->admin_email = $request->admin_email;
        $newUser->save();

        if ($request->type == "researcher"){
            $degrees = [];

            foreach($request->degrees as $degree) {
                $deg = new Degree();
                $deg->title = $degree['title'];
                $deg->received = $degree['received'];
                $deg->institution = $degree['institution'];
                $deg->researcher_email = $user->email;
                $deg->save();
                array_push($degrees, $deg);
            }

            return response()->json([
                'success' => true,
                'user' => $newUser,
                'degrees' => $degrees
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $newUser
        ]);
    }

    public function editUser(Request $request, $id) {


        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        
        $user = User::where('id',$id)->firstOrFail();
        
        $validated = $request->validate([
            'email' => 'email|unique:users',
            'password' => 'string|min:6',
            'firstName' => 'string',
            'lastName' => 'string',
            'type' => 'in:viewer,researcher,reviewer,admin,editor',
            'admin_email' => 'email|exists:users',
            'degrees' => 'array',
        ]);

        if ($request->has('email'))
            $user->email = $request->email;
        
        if ($request->has('password'))
            $user->password = $request->password;
        
        if ($request->has('firstName'))
            $user->first_name = $request->firstName;

        if ($request->has('lastName'))
            $user->last_name = $request->lastName;
        
        if ($request->has('type'))
            $user->type = $request->type;

        if ($request->has('admin_email'))
            $user->admin_email = $request->admin_email;
        
        $user->save();
        
        return response()->json([
            'success' => true,
        ]);
    }

    public function removeUser(Request $request, $id) {

        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);
            
        $user = User::where('id',$id)->firstOrFail();
        $user->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function approveRejectUser(Request $request, $id) {
        return;
    }
    
}
