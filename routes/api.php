<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\emailController;
use App\Http\Controllers\IncomingMail;
use App\Http\Controllers\userController;
use App\Http\Controllers\crmemailController;
/*
|---------------------------------------------------------------------	-----
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/login', [loginController::class, 'login']);

Route::middleware('login.check')->group(function(){	
Route::any('/logout',   [loginController::class, 'logout']);
Route::any('/useraccounts', [loginController::class, 'useraccounts']);

Route::any('/createuser', [userController::class, 'createuser']);
Route::any('/updateuser', [userController::class, 'updateuser']);
Route::any('/userlist', [userController::class, 'userlist']);
Route::any('/userdetails', [userController::class, 'userdetails']);
Route::any('/deleteuser', [userController::class, 'deleteuser']);

Route::any('/receiveemail', [IncomingMail::class, 'receiveemail']);

Route::any('/sendemail', [emailController::class, 'sendemail']);
Route::any('/inboxemaillist', [emailController::class, 'inboxemaillist']);
Route::any('/sentemaillist', [emailController::class, 'sentemaillist']);
Route::any('/spamemaillist', [emailController::class, 'spamemaillist']);
Route::any('/draftemaillist', [emailController::class, 'draftemaillist']);
Route::any('/emaildetail', [emailController::class, 'emaildetail']);
Route::any('/createlabel', [emailController::class, 'createlabel']);
Route::any('/labellist', [emailController::class, 'labellist']);
Route::any('/labelemaillist', [emailController::class, 'labelemaillist']);
Route::any('/movetolabel', [emailController::class, 'movetolabel']);
Route::any('/readunread', [emailController::class, 'readunread']);
Route::any('/deleteemail', [emailController::class, 'deleteemail']);
Route::any('/trashemaillist', [emailController::class, 'trashemaillist']);
Route::any('/deletetrashemail', [emailController::class, 'deletetrashemail']);
Route::any('/restoreemail', [emailController::class, 'restoreemail']);
Route::any('/savetemplate', [emailController::class, 'savetemplate']);
Route::any('/deletettemplate', [emailController::class, 'deletettemplate']);
Route::any('/templatelist', [emailController::class, 'templatelist']);
Route::any('/searchemailbysubject', [emailController::class, 'searchemailbysubject']);
Route::any('/deletemasteremail', [emailController::class, 'deletemasteremail']);
Route::any('/restoremasteremail', [emailController::class, 'restoremasteremail']);
Route::any('/deletetrashmasteremail', [emailController::class, 'deletetrashmasteremail']);
Route::any('/trashemaildetail', [emailController::class, 'trashemaildetail']);
Route::any('/userdrive', [emailController::class, 'userdrive']);

Route::any('/clientemaillist', [crmemailController::class, 'clientemaillist']);
});