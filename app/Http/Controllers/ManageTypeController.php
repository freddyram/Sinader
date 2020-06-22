<?php

namespace App\Http\Controllers;

use App\ManageType;
use Illuminate\Http\Request;

class ManageTypeController extends Controller
{
	public function data(Request $request){
		$manageType = ManageType::all();
		return response()->json($manageType);
	}

	public function forid($id){
		$manageType = ManageType::where('id', $id)->get()->first();
		return response()->json($manageType);
	}
}