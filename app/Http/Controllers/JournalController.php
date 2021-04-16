<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Journal;
use App\Models\Paper;
use App\Models\PaperJournal;

class JournalController extends Controller
{
    public function createJournal(Request $request) {
        // check user is editor
        if ($request->user()->type == 'editor') {         

            // validate user is an editor or admin
            $validated = $request->validate([
                'title' => 'required',
                'published_date' => 'required|date_format:Y-m-d',            
            ]);

            // add to journal
            $Journal = new Journal;
            $Journal -> title=$request->title;
            $Journal -> published_date=$request->published_date;
            $Journal -> status='pending';    // status
            $Journal -> editor_email = $request->user()->email;
            // timstamp autogen
            $Journal -> admin_email='admin@mail.com';//$request->user()->admin_email;  // admin_email
            $Journal -> save();                
        } elseif ($request->user()->type == 'admin') {
            
            // validate user is an editor or admin
            $validated = $request->validate([
                'title' => 'required',
                'published_date' => 'required|date_format:Y-m-d',  
                'status' => 'required|in:pending,approved,rejected',
                'admin_email' => 'required|email|exists:users,email',
                'editor_email' => 'required|email|exists:users,email',
            ]);

            // add to journal
            $Journal = new Journal;
            $Journal -> title=$request->title;
            $Journal -> published_date=$request->published_date;
            $Journal -> status = $request->status;
            $Journal -> editor_email = $request->editor_email;
            // timstamp autogen
            $Journal -> admin_email=$request->admin_email;//$request->user()->admin_email;  // admin_email
            $Journal -> save();   
            
            return response()->json([
                'success' => true,
                'journal' => $Journal
            ]);
        } else 
                // return error message
            return response()->json([
                'error'=>true,      // key
                'message'=>'only an editor or an admin can access this',
            ], 401);        
    }

    public function getAllJournals(Request $request) {


        if ($request->user()->type == 'editor') {         

            // return error message        
            return response()->json([
                'success' => true,
                'journals'=> Journal::where('editor_email',$request->user()->email)->get(),
            ]);
        } elseif ($request->user()->type == 'admin') {                        
            
            return response()->json([
                'success' => true,
                'journals' => Journal::all()
            ]);
        }       
        return response()->json([
            'success' => true,
            'journals'=> Journal::where('status','approved')->get(),
        ]);
    }

    public function getJournalById(Request $request, $id) {
        $journal = Journal::find($id);

        if (!$journal)
            return response()->json([
                'error'=>true,
                'message'=> 'Could not find Journal with id of ' . $id
            ], 404);
        
        $papers_in_journal = PaperJournal::select('paper_id')->where('journal_id',$journal->id)->get();
        $papers = [];

        foreach($papers_in_journal as $p) {
            $paper = Paper::find($p->paper_id);
            if (!$p) 
                continue;
            array_push($papers, $paper);
        }

        return response()->json([
            'journal' => [
                'title' => $journal->title,
                'published_date' => $journal->published_date,
                'status' => $journal->status,
                'admin_email' => $journal->admin_email,
                'editor_email' => $journal->editor_email,
                'papers' => $papers
            ]
        ]);
    }

    public function changeStatusJournal(Request $request, $id) {

        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        
        $request->validate([
            'status'=>'required|in:pending,approved,rejected',
        ]);

        $journal = Journal::find($id);

        if (!$journal)
            return response()->json([
                'error' => true,
                'message' => 'Journal of id ' . $id . 'not found'
            ],404);

        $journal->status = $request->status;
        $journal->save();

        return response()->json([
            'success' => true,
        ]);
        
    }

    public function editJournal(Request $request, $id) {
        
        if ( $request->user()->type != 'admin' && $request->user()->type != 'editor') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        $journal = Journal::find($id);
        if (!$journal) 
            return response()->json([
                'error' => true,
                'message' => 'Journal of id ' . $id . 'not found'
            ],404);

        $request->validate([
            'title'=>'string',
            'published_date'=>'date_format:Y-m-d',
            'status'=>'in:pending,approved,rejected',
            'admin_email'=>'email|exists:users,email',
            'editor_email' =>'email|exists:users,email',
        ]);
                    

        if ($request->has('title'))
            $journal->title = $request->title;

        if ($request->has('published_date'))
            $journal->published_date = $request->published_date;

        if ($request->has('status'))
            $journal->status = $request->status;

        if($request->has('admin_email'))
            $journal->admin_email = $request->admin_email;

        if($request->has('editor_email'))
            $journal->editor_email = $request->editor_email;

        $journal->save();

        return response()->json([
            'success' => true
        ]);

    }

    public function removeJournal(Request $request, $id) {
        if ( $request->user()->type != 'admin') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);

        $journal = Journal::find($id);

            if (!$journal)
                return response()->json([
                    'error' => true,
                    'message' => 'Journal of id ' . $id . 'not found'
                ],404);

        $journal->delete();
        
        return response()->json([
            'success' => true
        ]);

    }


    public function addPaperToJournal(Request $request, $id) {
        if ( $request->user()->type != 'admin' && $request->user()->type != 'editor') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);
        
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
        ]);

        $journal = Journal::find($id);
        $paper = Paper::find($request->paper_id);

        if ($request->user() == 'editor') {
            if ($journal->editor_email != $request->user()->email ||
                $paper->editor_email != $request->user()->email) {
                return response()->json([
                    'error' => true,
                    'message' => 'You do not have the authority to perform this action'
                ], 401);
            }
        }

        if (!$journal)
            return response()->json([
                'error' => true,
                'message' => 'Journal of id ' . $id . 'not found'
            ],404);

        $paperJournal = new PaperJournal;
        $paperJournal->paper_id = $request->paper_id;
        $paperJournal->journal_id = $id;
        $paperJournal->save();
        
        return response()->json([
            'success' => true,
            'paper' => $paper
        ]);
        
    }

    public function removePaperFromJournal(Request $request, $id) {
        if ( $request->user()->type != 'admin' && $request->user()->type != 'editor') 
            return response()->json([
                'error' => true,
                'message' => 'You do not have the authority to perform this action'
            ], 401);
        
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
        ]);

        $journal = Journal::find($id);
        $paper = Paper::find($request->paper_id);
        if (!$journal)
            return response()->json([
                'error' => true,
                'message' => 'Journal of id ' . $id . 'not found'
            ],404);

        $paperJournal = PaperJournal::where('paper_id',$request->paper_id)
                        ->where('journal_id',$id)->first();

        if ($paperJournal)        
            $paperJournal->delete();

        return response()->json([
            'success' => true,            
        ]);

    }


}
