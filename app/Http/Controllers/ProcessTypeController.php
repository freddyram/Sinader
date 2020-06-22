<?php

namespace App\Http\Controllers;

use App\ProcessType;
use Illuminate\Http\Request;

class ProcessTypeController extends Controller
{
	public function data(Request $request){
		$processType = ProcessType::all();
		return response()->json($processType);
	}
	public function forid($id){
		$processType = ProcessType::where('id', $id)->get()->first();
		return response()->json($processType);
	}

}