<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;
use App\Models\User;
use App\Models\Assigned;

class AssignedsController extends Controller
{
    public function assignReviewer(Request $request) {
        return;
    }

    public function getAllPapersAssignedToReviewer(Request $request, $id) {
        if ($request->user()->type == 'editor' || $request->user()->type == 'reviewer') {
            $rev = User::find($id);
            if(!$rev) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 404);
            }
            /*if ($rev->type == 'reviewer') {
                return response()->json([
                    'papers' => Assigned::where('reviewer_email', $rev->email)->get(),
                ]);
            } else if($rev->type == 'researcher') {
                return;
            }*/

            return response()->json([
                'assigned' => Assigned::where('reviewer_email', $rev->email)->get(),
            ]);

        } 

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }
}
