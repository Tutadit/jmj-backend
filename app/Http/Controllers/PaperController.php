<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\NominatedReviewer;
use App\Models\Assigned;

class PaperController extends Controller
{
    public function getPaperById(Request $request, $id) {

        $paper = Paper::find($id);

        if (!$paper) {
            return response()->json([
                'error' => true,
                'message' => 'Paper with id ' . $id . ' not found'
            ],404);
        }

        if ( $paper->status !== 'published') {
            if ($request->user()->type == 'editor') {            
                if ($paper->editor_email != $request->user()->email)
                    return response()->json([
                        'error' => true,
                        'message' => 'You are not alloweed to view this section'
                    ],401);        
            } else if ($request->user()->type == 'researcher') {
                if ($paper->researcher_email != $request->user()->email) {
                    return response()->json([
                        'error' => true,
                        'message' => 'You are not alloweed to view this section'
                    ],401);
                }
            } else if ($request->user()->type != 'admin')
                return response()->json([
                    'error' => true,
                    'message' => 'You are not allowed to view this section'
                ]);
        }   


        $nominated = NominatedReviewer::where('paper_id',$id)
        ->join('users','users.email','nominated_reviewers.reviewer_email')
        ->selectRaw('CONCAT(first_name, CONCAT(" ", last_name)) as reviewer, users.id as reviewer_id, reviewer_email')->get();

        $assigned = Assigned::where('paper_id',$id)
        ->join('users','users.email','assigneds.reviewer_email')
                    ->selectRaw('CONCAT(first_name, CONCAT(" ", last_name)) as reviewer, users.id as reviewer_id, reviewer_email')->get();
        
        $researcher = User::where('email',$paper->researcher_email)->first();
        $editor = User::where('email',$paper->editor_email)->first(); 

        if ( !$researcher || !$editor ) 
            return response()->json([
                'error' => true,
                'message' => 'This paper is wild and free, not meant for you'
            ], 422);


        return response()->json([
            'success' => true,
            'paper' =>  array(
                    'id' => $paper->id,
                    'title' => $paper->title,
                    'status' => $paper->status,
                    'file_path' => $paper->file_path,
                    'researcher' => $researcher->first_name . " " . $researcher->last_name,
                    'researcher_id' => $researcher->id,
                    'editor' => $editor->first_name . " " . $editor->last_name,
                    'editor_id' => $editor->id,
                    'editor_email' => $editor->email,
                    'researcher_email' => $researcher->email
            ),
            'nominated' => $nominated,
            'assigned' => $assigned                
        ]);
        
    }

    public function getAllPapersByResearcher(Request $request, $id) {
        if ($request->user()->type == 'editor' || $request->user()->type == 'researcher') {
            $researcher = User::find($id);
            if(!$researcher) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 404);
            }

            $papers = Paper::where('researcher_email', $researcher->email);
            $papers = User::joinSub($papers,'papers', function($join){
                $join->on('users.email', '=', 'papers.researcher_email');
            })
            ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                        CONCAT(first_name,CONCAT(" ",last_name)) as researcher, users.id as researcher_id');
            $papers = User::joinSub($papers,'papers', function($join){
                $join->on('users.email', '=', 'papers.editor_email');
            })
            ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                        researcher_id, researcher,
                        CONCAT(first_name,CONCAT(" ",last_name)) as editor, users.id as editor_id')
            ->get();
            

            return response()->json([
                'papers' => $papers,
            ]);
        } 

        return response()->json([
            'error' => true,
            'message' => 'You are not allowed to view this section'
        ], 401);
    }

    public function uploadPaper(Request $request) {
        if ( $request->user()->type == 'researcher') {            

            $request->validate([
                'title' => 'required|string',
                'file' => 'required|file',                
            ]);

            $extension = $request->file->extension();
            if ($extension != 'pdf')
                return response()->json([
                    'error' => true,
                    'message' => 'Only pdfs allowed',
                    'given' => $extension
                ],401);

            $paper = new Paper;
            
            $paper->title = $request->title;  
            $path = str_replace('public/','',$request->file->store('public'));                  
            $paper->file_path = $path;  
            $paper->researcher_email = $request->user()->email;
            $paper->editor_email = 'editor@mail.com';   
            $paper->em_name = 'Number Eval';
            $paper->status = 'pending_minor_revision';            
            $paper->save();
            
             
            $researcher = User::where('email',$paper->researcher_email)->first();
            $editor = User::where('email',$paper->editor_email)->first();   
        
        if ( !$researcher || !$editor ) 
            return response()->json([
                'error' => true,
                'message' => 'This paper is wild and free, not meant for you'
            ], 422);
            return response()->json([
                'success' => true,
                'paper' => array(
                    'id' => $paper->id,
                    'title' => $paper->title,
                    'status' => $paper->status,
                    'file_path' => $paper->file_path,
                    'researcher' => $researcher->first_name . " " . $researcher->last_name,
                    'researcher_id' => $researcher->id,
                    'editor' => $editor->first_name . " " . $editor->last_name,
                    'editor_id' => $editor->id,
                    'editor_email' => $editor->email,
                    'researcher_email' => $researcher->email
                )
            ]);            

        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

    public function getPaperStatus(Request $request, $id) {
        
        if ( $request->user()->type != 'admin' && $request->user()->type != 'editor')
            return response()->json([
                'error' => true,
                'message' => 'You are not allowed to view this section'
            ],401);

        $paper = Paper::find($id);

        if (!$paper) 
            return response()->json([
                'error' => true,
                'message' => 'Paper with id ' . $id . 'does not exist'
            ]);

        return response()->json([
            'success' => true,
            'status' => $paper->status
        ]);

    }

    public function getPapersWithdrawn(Request $request) {
        if ($request->user()->type != 'admin') {
            return response()->json([
                'error' => true,
                'message' => 'You are not alloweed to view this section'
            ], 401);
        } 


        $papers = Paper::where('status','withdraw_request')
        ->orWhere('status','withdrawn');
        $papers = User::joinSub($papers,'papers', function($join){
            $join->on('users.email', '=', 'papers.researcher_email');
        })
        ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                    CONCAT(first_name,CONCAT(" ",last_name)) as researcher, users.id as researcher_id');
        $papers = User::joinSub($papers,'papers', function($join){
            $join->on('users.email', '=', 'papers.editor_email');
        })
        ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                    researcher_id, researcher,
                    CONCAT(first_name,CONCAT(" ",last_name)) as editor, users.id as editor_id')
        ->get();

        return response()->json([
            'papers' => $papers
        ]);
    }

    public function withdrawPaper(Request $request, $id) {
        if ($request->user()->type != 'admin') {
            return response()->json([
                'error' => true,
                'message' => 'You are not alloweed to view this section'
            ], 401);
        } 
        
        $paper = Paper::find($id);

        if (!$paper)
            return response()->json([
                'error' => true,
                'message' => 'Paper withdrawPaper id ' . $id . 'does not exist'
            ]);
        
        $request->validate([
            'approved' => 'boolean'
        ]);

        if ($request->has('approved'))        
            $paper->status = $request->approved ? 'withdrawn' : 'withdraw_request';
        else
            $paper->status = 'withdrawn';

        $paper->save();
        
        return response()->json([
            'success' => true,
        ]);
    }

    public function requestWithdrawPaper(Request $request, $id) {
        if ( $request->user()->type != 'researcher') 
            return response()->json([
                'error' => true,
                'message' => 'you do not have permission to perform that action'
            ], 401);
        
        $paper = Paper::find($id);

        if ( !$paper )
            return response()->json([
                'error' => true,
                'message' => 'Paper with id of ' . $id . ' does not exist'
            ], 404);
        
        if ( $paper->researcher_email != $request->user()->email)
            return response()->json([
                'error' => true,
                'message' => 'You do not own this paper'
            ],401);

        $paper->status = 'withdraw_request';
        $paper->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function editPaper(Request $request, $id) {
        
        if ( $request->user()->type == 'researcher') {

            $paper = Paper::find($id);

            if (!$paper)
                return response()->json([
                    'error' => true,
                    'message' => 'paper with id '. $id . 'does not exist'
                ]);

            $request->validate([
                'title' => 'string',
                'file' => 'file',                
            ]);

            

            if ($request->has('title'))
                $paper->title = $request->title;
            
                $path = null;
            if ($request->hasFile('file')) {

                $extension = $request->file->extension();
                if ($extension != 'pdf')
                    return response()->json([
                        'error' => true,
                        'message' => 'Only pdfs allowed',
                        'given' => $extension
                    ], 401);

                $path = str_replace('public/','',$request->file->store('public'));
                $paper->file_path = $path;
            }

            $paper->save();

            return response()->json([
                'success' => true,
                'new_file_path' => $path 
            ]);            

        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

    public function getAllPapers(Request $request) {

        $papers;

        if ($request->user()->type == 'admin') {            
                $papers = Paper::select('*');
        } else if ($request->user()->type == 'editor') {                        
                $papers = Paper::where('editor_email', $request->user()->email)
                ->orWhere('status','approved');
        } else if ($request->user()->type == 'researcher') {            
                $papers = Paper::where('researcher_email', $request->user()->email)
                        ->orWhere('status','approved');;
        } else {
            $papers = Paper::where('status','approved');
        }

        $papers = User::joinSub($papers,'papers', function($join){
            $join->on('users.email', '=', 'papers.researcher_email');
        })
        ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                    CONCAT(first_name,CONCAT(" ",last_name)) as researcher, users.id as researcher_id');
        $papers = User::joinSub($papers,'papers', function($join){
            $join->on('users.email', '=', 'papers.editor_email');
        })
        ->selectRaw('papers.id as id, researcher_email, papers.status, editor_email, file_path, 
                    researcher_id, researcher,
                    CONCAT(first_name,CONCAT(" ",last_name)) as editor, users.id as editor_id')
        ->get();

        return response()->json([
            'success' => true,
            'papers' => $papers
        ], 401);
    }
}
