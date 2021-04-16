<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;
use App\Models\User;

class PaperController extends Controller
{
    public function getPaperById(Request $request, $id) {
        return;
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
        return;
    }

    public function getPaperStatus(Request $request, $id) {
        return;
    }

    public function withdrawPaper(Request $request, $id) {
        return;
    }

    public function requestWithdrawPaper(Request $request, $id) {
        return;
    }

    public function editPaper(Request $request, $id) {
        return;
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
        }

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }
}
