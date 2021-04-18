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

    public function getAllInfo(Request $request) {
        if ($request->user()->type != 'editor' ) {
            return response()->json([
                'error' => true,
                'message' => 'You are not alloweed to view this section'
            ], 401);
        }

        $assigneds = Assigned::join('papers', 'assigneds.paper_id', '=', 'papers.id')->
                join('users', 'assigneds.reviewer_email', '=', 'users.email')->get();

        return response()->json([
            'assigneds' => $assigneds
        ]);

    }

    public function getAllPapersAssignedToReviewer(Request $request, $id) {
        if ($request->user()->type == 'editor' || $request->user()->type == 'reviewer') {
            $rev = User::find($id);
            if(!$rev) {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 404);
            } elseif ($rev->type != 'reviewer') {
                return response()->json([
                    'error' => true,
                    'message' => 'User is not a reviewer'
                ], 422);
            }

            $assigneds = Assigned::join('papers', 'assigneds.paper_id', '=', 'papers.id')->
                join('users', 'assigneds.reviewer_email', '=', 'users.email')->
                where('reviewer_email', $rev->email)->get();

            return response()->json([
                'assigneds' => $assigneds
            ]);

        } 

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

    public function getAllPapersAssignedToResearcher(Request $request, $id) {
        if ($request->user()->type == 'editor' || $request->user()->type == 'reviewer') {
            $res = User::find($id);
            if(!$res || $res->type != 'researcher') {
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ], 404);
            }

            $assigneds = Assigned::join('papers', 'assigneds.paper_id', '=', 'papers.id')->
                join('users', 'assigneds.researcher_email', '=', 'users.email')->
                where('assigneds.researcher_email', $res->email)->get();

            return response()->json([
                'assigneds' => $assigneds
            ]);

        } 

        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

    public function getReviewersAssignedToPaper(Request $request, $id) {
        if ($request->user()->type == 'editor') {
            $paper = Paper::find($id);
            if(!$paper) {
                return response()->json([
                    'error' => true,
                    'message' => 'paper not found'
                ], 404);
            }

            $assigneds = Assigned::join('papers', 'assigneds.paper_id', '=', 'papers.id')->
            join('users', 'assigneds.reviewer_email', '=', 'users.email')->
            where('assigneds.paper_id', $paper->id)->get();

            return response()->json([
                'assigneds' => $assigneds,
            ]);

        }
        
        return response()->json([
            'error' => true,
            'message' => 'You are not alloweed to view this section'
        ], 401);
    }

}
