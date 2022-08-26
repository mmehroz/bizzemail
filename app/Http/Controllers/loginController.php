<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Image;
use DB;
use Input;
use App\Item;
use Session;
use Response;
use Validator;

class loginController extends Controller
{
	public function login(Request $request){
	    $validate = Validator::make($request->all(), [ 
		      'email' 		=> 'required',
		      'password'	=> 'required',
		    ]);
	     	if ($validate->fails()) {    
				return response()->json("Enter Credentials To Signin", 400);
			}
		    $getprofileinfo = DB::table('emailuser')
			->select('emailuser_id as user_id','emailuser_token','emailuser_name','emailuser_email','emailuser_picture','emailuser_themepicture','role_id')
			->where('emailuser_email','=',$request->email)
			->where('emailuser_password','=',$request->password)
			->where('status_id','=',1)
			->first();
			if ($getprofileinfo) {
				return response()->json(['data' => $getprofileinfo, 'message' => 'Login Successfully'],200);
			}else{
				return response()->json('Invalid Email Or Password', 400);
			}
	}
	public function useraccounts(Request $request){
	    $validate = Validator::make($request->all(), [ 
	      'emailuser_token'		=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Token Required", 400);
		}
		$getaccounts = DB::table('emailuser')
		->select('emailuser_id as user_id','emailuser_token','emailuser_name','emailuser_email','emailuser_picture','emailuser_themepicture','role_id')
		->where('emailuser_token','=',$request->emailuser_token)
		->where('status_id','=',1)
		->get();
		if ($getaccounts) {
			return response()->json(['accounts' => $getaccounts, 'message' => 'Accounts List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['accounts' => $emptyarray, 'No Accounts Available'], 200);
		}
	}
	public function logout(Request $request){
		return response()->json(['message' => 'Logout Successfully'],200);
	}
}