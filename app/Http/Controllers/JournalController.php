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
        if ($request->user()->type != 'editor' && $request->user()->type != 'admin') {
            // return error message
            return response()->json([
                'error'=>true,      // key
                'message'=>'only an editor or an admin can access this',
            ], 401);
        } 

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
        // timstamp autogen
        $Journal -> admin_email='admin@mail.com';//$request->user()->admin_email;  // admin_email
        $Journal -> save();                
        
    }

    public function getAllJournals(Request $request) {
        return response()->json([
            'journals'=> Journal::all(),
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
            'title' => $journal->title,
            'published_date' => $journal->published_date,
            'status' => $journal->status,
            'papers' => $papers
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

        $request->validate([
            'title'=>'string',
            'published_date'=>'date_format:Y-m-d',
            'status'=>'in:pending,approved,rejected',
            'admin_email'=>'email|exists:users'
        ]);

            

        

        if ($request->has('title'))
            $journal->title = $request->title;

        if ($request->has('published_date'))
            $journal->published_date = $request->published_date;

        if ($request->has('status'))
            $journal->status = $request->status;

        if($request->has('admin_email'))
            $journal->admin_email = $request->admin_email;

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
}
