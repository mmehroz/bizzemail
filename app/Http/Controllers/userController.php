<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Image;
use DB;
use Input;
use App\Item;
use Session;
use Response;
use Validator;

class userController extends Controller
{
	public function createuser(Request $request){
		$validate = Validator::make($request->all(), [ 
		      'emailuser_name' 			=> 'required',
		      'emailuser_email'			=> 'required',
		      'emailuser_emailpassword' => 'required',
		      'emailuser_emailhost'		=> 'required',
		      'emailuser_password' 		=> 'required',
		      'emailuser_token' 		=> 'required',
		    ]);
	     	if ($validate->fails()) {    
				return response()->json("Fields Required", 400);
			}
			$validateunique = Validator::make($request->all(), [ 
		      'emailuser_email' 		=> 'unique:emailuser,emailuser_email',
		    ]);
	     	if ($validateunique->fails()) {    
				return response()->json("User Email Already Exist", 400);
			}
			$validatepicture = Validator::make($request->all(), [ 
		    	'emailuser_picture'=>'mimes:jpeg,bmp,png,jpg|max:1000000',
		    ]);
			if ($validatepicture->fails()) {    
				return response()->json("Invalid Format", 400);
			}
			if ($request->emailuser_token == "New") {
				$usertoken = mt_rand(10,9999999999);
			}else{
				$usertoken = $request->emailuser_token;
			}
			$userpicturename;
        	if ($request->has('emailuser_picture')) {
            		if( $request->emailuser_picture->isValid()){
			            $number = rand(1,999);
				        $numb = $number / 7 ;
						$name = "userpicture";
				        $extension = $request->emailuser_picture->extension();
			            $userpicturename  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
			            $userpicturename = $request->emailuser_picture->move(public_path('userpicture/'),$userpicturename);
					    $img = Image::make($userpicturename)->resize(800,800, function($constraint) {
			                    $constraint->aspectRatio();
			            });
			            $img->save($userpicturename);
					    $userpicturename = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
			        }
            }else{
    	        $userpicturename = 'no_image.jpg'; 
	        }
		$adds[] = array(
		'emailuser_name' 			=> $request->emailuser_name,
		'emailuser_email'			=> $request->emailuser_email,
		'emailuser_emailpassword' 	=> $request->emailuser_emailpassword,
		'emailuser_emailhost' 		=> $request->emailuser_emailhost,
		'emailuser_password' 		=> $request->emailuser_password,
		'emailuser_picture'			=> $userpicturename,
		'emailuser_token'			=> $usertoken,
		'role_id' 					=> 1,
		'status_id'		 			=> 1,
		'created_by'	 			=> $request->user_id,
		'created_at'	 			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('emailuser')->insert($adds);
		if($save){
			return response()->json(['data' => $adds,'message' => 'User Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateuser(Request $request){
		$validate = Validator::make($request->all(), [ 
		      'emailuser_id'			=> 'required',
		      'emailuser_name' 			=> 'required',
		      'emailuser_email'			=> 'required',
		      'emailuser_emailpassword' => 'required',
		      'emailuser_emailhost'		=> 'required',
		      'emailuser_password'		=> 'required',
		    ]);
	     	if ($validate->fails()) {    
				return response()->json("Fields Required", 400);
			}
			$getuseremail = DB::table('emailuser')
			->select('emailuser_email')
			->where('emailuser_id','=',$request->emailuser_id)
			->first();
			if ($getuseremail->emailuser_email != $request->emailuser_email) {
			$validateunique = Validator::make($request->all(), [ 
		      'emailuser_email' 		=> 'unique:emailuser,emailuser_email',
		    ]);
	     	if ($validateunique->fails()) {    
				return response()->json("User Email Already Exist", 400);
			}
			}
			$userpicturename;
        	if ($request->has('emailuser_picture')) {
			$validatepicture = Validator::make($request->all(), [ 
		    	'emailuser_picture'=>'mimes:jpeg,bmp,png,jpg|max:1000000',
		    ]);
			if ($validatepicture->fails()) {    
				return response()->json("Invalid Format", 400);
			}
            		if( $request->emailuser_picture->isValid()){
			            $number = rand(1,999);
				        $numb = $number / 7 ;
						$name = "userpicture";
				        $extension = $request->emailuser_picture->extension();
			            $userpicturename  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
			            $userpicturename = $request->emailuser_picture->move(public_path('userpicture/'),$userpicturename);
					    $img = Image::make($userpicturename)->resize(800,800, function($constraint) {
			                    $constraint->aspectRatio();
			            });
			            $img->save($userpicturename);
					    $userpicturename = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
			        }
            }else{
    	        $userpicturename = 'no_image.jpg'; 
	        }
	    $updateuser  = DB::table('emailuser')
			->where('emailuser_id','=',$request->emailuser_id)
			->update([
			'emailuser_name' 			=> $request->emailuser_name,
			'emailuser_email'			=> $request->emailuser_email,
			'emailuser_emailpassword' 	=> $request->emailuser_emailpassword,
			'emailuser_emailhost' 		=> $request->emailuser_emailhost,
			'updated_by'	 			=> $request->user_id,
			'updated_at'	 			=> date('Y-m-d h:i:s'),
		]); 
		if ($userpicturename != 'no_image.jpg') {
			DB::table('emailuser')
			->where('emailuser_id','=',$request->emailuser_id)
			->update([
			'emailuser_picture'			=> $userpicturename,
			]); 
		}
		if ($request->password != "") {
			DB::table('emailuser')
			->where('emailuser_id','=',$request->emailuser_id)
			->update([
			'emailuser_password' 		=> $request->emailuser_password,
			]); 
		}
		if($updateuser){
			return response()->json(['message' => 'User Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function userlist(Request $request){
		$getuserlist = DB::table('emailuser')
		->select('emailuser_id','emailuser_token','emailuser_name','emailuser_email','emailuser_picture')
		->where('status_id','=',1)
		->get();
		$getuserlist = $this->paginate($getuserlist);
		if($getuserlist){
		return response()->json(['userlist' => $getuserlist, 'message' => 'User List'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function userdetails(Request $request){
		$validate = Validator::make($request->all(), [ 
		      'emailuser_id'			=> 'required',
		    ]);
	     	if ($validate->fails()) {    
				return response()->json("Fields Required", 400);
			}
		$getuserdetails = DB::table('emailuser')
		->select('*')
		->where('emailuser_id','=',$request->emailuser_id)
		->where('status_id','=',1)
		->first();
		if($getuserdetails){
		return response()->json(['data' => $getuserdetails,'message' => 'User Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteuser(Request $request){
		$validate = Validator::make($request->all(), [ 
		      'emailuser_id'	=> 'required',
		    ]);
	     	if ($validate->fails()) {    
				return response()->json("Fields Required", 400);
			}
		$updateuserstatus  = DB::table('emailuser')
			->where('emailuser_id','=',$request->emailuser_id)
			->update([
			'status_id' 		=> 2,
			]); 
		if($updateuserstatus){
		return response()->json(['message' => 'User Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function paginate($items, $perPage = 30, $page = null, $options = []){
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return  new  LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}