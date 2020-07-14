<?php

namespace App\Http\Controllers;

use App\SubdereCost;
use Illuminate\Http\Request;

class SubdereCostController extends Controller
{
	
	public function forid($declaration_id){
		$subdere_cost = SubdereCost::where('declaration_id',$declaration_id)->get()->first();
		return response()->json($subdere_cost);
	}

}