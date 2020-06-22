<?php

namespace App\Http\Controllers;

use App\RecolectionType;
use Illuminate\Http\Request;

class RecolectionTypeController extends Controller
{
	public function data(Request $request){

		$recolectionType = RecolectionType::all();
		Info("******* recolectionType **********");
		Info($recolectionType);

		return response()->json($recolectionType);
	}
}