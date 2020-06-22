<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{







	
	public function data(Request $request){
		$unit = Unit::all();
		return response()->json($unit);
	}
	public function forid($id){
		$unit = Unit::where('id',$id)->get()->first();
		return response()->json($unit);
	}

}