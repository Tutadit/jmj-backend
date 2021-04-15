<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paper;

class PaperController extends Controller
{
    public function getPaperById(Request $request, $id) {
        return;
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
        if ($request->user()->type != 'admin') {
            return response()->json([
                'error' => true,
                'message' => 'You are not alloweed to view this section'
            ], 401);
        }

        return response()->json([
            'papers' => Paper::all()
        ]);
    }
}
