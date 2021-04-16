<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;
use App\Models\User;
use App\Models\NominatedReviewer;

class PaperController extends Controller
{
    public function getPaperById(Request $request, $id) {
        if ($request->user()->type == 'editor') {

            $paper = Paper::find($id);

            if (!$paper) {
                return response()->json([
                    'error' => true,
                    'message' => 'Paper with id ' . $id . ' not found'
                ],404);
            }

            if ($paper->editor_email != $request->user()->email) {
                return response()->json([
                    'error' => true,
                    'message' => 'You are not alloweed to view this section'
                ],401);
            }

            return response()->json([
                'success' => true,
                'paper' => $paper
            ]);
        } else if ($request->user()->type == 'researcher') {
            $paper = Paper::find($id);

            if (!$paper) {
                return response()->json([
                    'error' => true,
                    'message' => 'Paper with id ' . $id . ' not found'
                ],404);
            }

            if ($paper->researcher_email != $request->user()->email) {
                return response()->json([
                    'error' => true,
                    'message' => 'You are not alloweed to view this section'
                ],401);
            }

            $nominated = NominatedReviewer::where('researcher_email',$request->user()->email)->get();

            return response()->json([
                'success' => true,
                'paper' => $paper,
                'nominated' => $nominated
            ]);

        } else if ($request->user()->type == 'admin') {
            $paper = Paper::find($id);

            if (!$paper) {
                return response()->json([
                    'error' => true,
                    'message' => 'Paper with id ' . $id . ' not found'
                ],404);
            }

            return response()->json([
                'success' => true,
                'paper' => $paper
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ],401);
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
            return response()->json([
                'papers' => Paper::where('researcher_email', $researcher->email)->get(),
            ]);
        } 

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
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

            return response()->json([
                'success' => true,
                'paper' => $paper
            ]);            

        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

    public function getPaperStatus(Request $request, $id) {
        return;
    }

    public function getPapersWithdrawn(Request $request) {
        if ($request->user()->type != 'admin') {
            return response()->json([
                'error' => true,
                'message' => 'You are not alloweed to view this section'
            ], 401);
        } 

        return response()->json([
            'papers' => Paper::where('status','withdraw_request')
                            ->orWhere('status','withdrawn')->get()
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
        return;
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

        if ($request->user()->type == 'admin') {
            return response()->json([
                'papers' => Paper::all()
            ]);
        } else if ($request->user()->type == 'editor') {            
            return response()->json([
                'papers' => Paper::where('editor_email', $request->user()->email)->get()
            ]);
        } else if ($request->user()->type == 'researcher') {
            return response()->json([
                'papers' => Paper::where('researcher_email', $request->user()->email)->get()
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }
}
