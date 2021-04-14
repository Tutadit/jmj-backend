<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Journal;

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
            ]);

        return response()->json([
            'title' => $journal->title,
            'published_date' => $journal->published_date,
            'status' => $journal->status
        ]);
    }

    public function approveJournal(Request $request, $id) {
        return;
    }

    public function editJournal(Request $request, $id) {
        return;
    }

    public function removeJournal(Request $request, $id) {
        return;
    }
}
