<?php

namespace App\Http\Controllers;

use App\WasteType;
use Illuminate\Http\Request;

class WasteTypeController extends Controller
{

	public function data($wastetype_id){
		$WasteType = WasteType::where('chapter_id', $wastetype_id)->get();
		return response()->json($WasteType);
	}

	public function index(Request $request){
		$wastetype = wastetype::all();
		return response()->json($wastetype);
	}

}
