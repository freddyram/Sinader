<?php

namespace App\Http\Controllers;

use App\ActiveLine;
use Illuminate\Http\Request;

class ActiveLineController extends Controller
{
    public function data($activeline_id){
		$ActiveLine = WasteType::where('chapter_id', $activeline_id)->get();
		return response()->json($ActiveLine);
	}

	public function index(Request $request){
		$activeline = activeline::all();
		return response()->json($activeline);
	}
}
