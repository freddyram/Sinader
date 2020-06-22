<?php

namespace App\Http\Controllers;

use App\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
	public function data(Request $request){
		$vehicle_type = VehicleType::all();
		return response()->json($vehicle_type);
	}
}