<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers as Web;

Route::get('/dummy1', function ()
{
    dd(',ksdmksdmds = ');
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('login', [Web\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Web\AuthController::class, 'login'])->name('login.store');

    Route::get('forgot-password', [Web\AuthController::class, 'showForgotPassword'])
    ->name('forgot-password');
    Route::post('forgot-password', [Web\AuthController::class, 'forgotPassword'])
    ->name('forgot-password.store');
    Route::get('reset-password/{token}', [Web\AuthController::class, 'showResetPassword'])
    ->name('password.reset');
    Route::post('reset-password', [Web\AuthController::class, 'resetPassword'])
    ->name('password.update');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [Web\Users\UserController::class, 'index'])->name('user.dashboard');
    
    Route::get('users/change-password', [Web\Users\ChangeUserPasswordController::class, 'edit'])
    ->name('users.password.edit');
    Route::put('users/change-password', [Web\Users\ChangeUserPasswordController::class, 'update'])
    ->name('users.password.update');
    
    Route::get('impersonate/{user}', Web\Users\ImpersonateUserController::class)->name('users.impersonate');
    Route::delete('impersonate/{user}', Web\Users\StopImpersonatingUserController::class)->name('users.impersonate.destroy');
    
    Route::any('/logout', [Web\AuthController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => '/exams'], function () {
    Route::get('/login', Web\Exams\ExamPage\ExamLoginController::class)
        ->name('exams.login');
    Route::get('/display/{exam:exam_no}', Web\Exams\ExamPage\DisplayExamPageController::class)
        ->name('display-exam-page');
    Route::get('/exam-result/{exam:exam_no}', Web\Exams\ExamPage\ExamResultController::class)
        ->name('exam-result');
    Route::get('/completed-message', [Web\Exams\ExamController::class, 'examCompletedMessage'])
        ->name('exams.completed-message');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::any('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

