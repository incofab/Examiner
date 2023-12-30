<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Web;

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

Route::middleware('auth:sanctum')->any('/user', function (Request $request) {
    
    return $request->user();
    
});

Route::group(['prefix' => '/exams'], function () {
    Route::post('/store', [Web\Exams\ExamController::class, 'store'])
        ->name('exams.store');
    Route::post('/pause/{exam}', Web\Exams\ExamPage\PauseExamController::class)
        ->name('pause-exam');
    Route::post('/end/{exam}', Web\Exams\ExamPage\EndExamController::class)
        ->name('end-exam');
        
    Route::delete('/exams/{exam}/delete', [Web\Exams\ExamController::class, 'destroy'])
        ->name('exams.destroy');
});