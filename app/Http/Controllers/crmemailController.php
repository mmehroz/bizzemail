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
use App\Jobs\SendEmailJob;
use Image;
use DB;
use Input;
use App\Item;
use Session;
use Response;
use Validator;

class crmemailController extends Controller
{
	public function clientemaillist(Request $request){
		$getemailid = DB::table('emailldetail')
		->select('emailldetail_id')
		->where('emailldetail_sendby',$request->client_email)
		->where('status_id','=',1)
		->get()->toArray();
		$sortemailids = array();
		foreach ($getemailid as $getemailids) {
			$sortemailids[] = $getemailids->emailldetail_id;
		}
		$getclientlist = DB::table('emaillist')
		->select('*')
		->whereIn('emailldetail_id',$sortemailids)
		->where('emailsentordraft_id','=',1)
		->where('status_id','=',1)
		->orderBy('emailmaster_id','DESC')
		->groupBy('emailmaster_id')
		->get();
		$getclientlist = $this->paginate($getclientlist);
		if($getclientlist){
			return response()->json(['data' => $getclientlist,'message' => 'Client Email List'],200);
		}else{
			$emptyarray = array();
			return response()->json(['data' => $emptyarray,'message' => 'No Email'],200);
		}
	}
	public function paginate($items, $perPage = 30, $page = null, $options = []){
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return  new  LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}