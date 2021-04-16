<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PaperController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// method makes sure user is authenticated
Route::middleware(['auth:sanctum'])->group(function () {
    // method is run when api/user is called
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user'=>$request->user()    // given user is passed as argument
        ]);
    });

    // string is function name
    Route::get('/users/all',[UserController::class,'getAllUsers']);
    Route::post('/users/new',[UserController::class,'addUser']);
    Route::get('/users/of_type/{type}',[UserController::class,'getAllUsersOfType']);
    Route::get('/users/{id}',[UserController::class,'getUserById']);
    Route::post('/users/{id}/edit',[UserController::class,'editUser']);
    Route::post('/users/{id}/remove',[UserController::class,'removeUser']);
    Route::get('/users/{id}/degrees',[UserController::class,'getUserDegrees']);
    Route::post('/users/{id}/degrees/new',[UserController::class,'addDegree']);
    Route::post('/users/{id}/degrees/delete',[UserController::class,'removeDegree']);

    Route::post('/tokens/create', [UserController::class,'createToken']);
    Route::post('/tokens/delete', [UserController::class,'deleteToken']);
    Route::get('/tokens/all', [UserController::class,'getTokens']); 

    Route::post('/journals/create',[JournalController::class,'createJournal']);
    Route::get('/journals/all', [JournalController::class,'getAllJournals']);
    Route::get('/journals/{id}', [JournalController::class,'getJournalById']);
    Route::post('/journals/{id}/edit',[JournalController::class,'editJournal']);
    Route::post('/journals/{id}/remove',[JournalController::class,'removeJournal']);
    Route::post('/journals/{id}/change_status',[JournalController::class,'changeStatusJournal']);
    Route::post('/journals/{id}/add_paper',[JournalController::class,'addPaperToJournal']);
    Route::post('/journals/{id}/remove_paper',[JournalController::class,'removePaperFromJournal']);


    Route::get('/paper/all', [PaperController::class,'getAllPapers']);
    Route::get('/paper/withdrawn',[PaperController::class,'getPapersWithdrawn']);
    Route::get('/paper/by_researcher/{id}', [PaperController::class, 'getAllPapersByResearcher']);
    Route::get('/paper/{id}', [PaperController::class,'getPaperById']);
    Route::post('/paper/{id}/edit', [PaperController::class,'editPaper']);
    Route::get('/paper/{id}/status',[PaperController::class,'getPaperStatus']);    
    Route::post('/paper/{id}/withdraw',[PaperController::class,'withdrawPaper']);
    Route::post('/paper/{id}/request_withdraw',[PaperController::class,'requestWithdrawPaper']);
    Route::post('/paper/upload', [PaperController::class,'uploadPaper']);

    Route::post('/nominated/new', [NominatedReviewersController::class,'nominateForPaper']);

    Route::get('/reviews/{id}',[ReviewController::class,'getReviewsForPaper']);
    Route::post('/reviews/{id}',[ReviewController::class,'approveRejectReview']);

    Route::post('/assigned/new',[AssignedController::class,'assignReviewer']);
    Route::get('/assigned/reviewer/{id}', [AssignedController::class, 'getAllPapersAssignedToReviewer']);
});
