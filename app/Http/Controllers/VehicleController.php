<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
	public function data($carrier_id){
		$vehicle = Vehicle::where('carrier_id', $carrier_id)->get();
		return response()->json($vehicle);
	}

	public function index(Request $request){
		$vehicle = Vehicle::all();
		return response()->json($vehicle);
	}

	public function store(Request $request){


		$vehicle = $request->Input('vehicle');

        Info($request);

		//$vehicleNew = Vehicle::where('id', $vehicle["id"])->get()->first();

        if(array_key_exists("id", $vehicle)){
            $vehicleNew = Vehicle::where('id', $vehicle["id"])->get()->first();
        }else
        {
            $vehicleNew = new Vehicle();
        }

        $vehicleNew->name    			= $vehicle['name'];
        $vehicleNew->plate   			= $vehicle['plate'];
        $vehicleNew->carrier_id   		= $vehicle['carrier_id'];
        $vehicleNew->vehicle_type_id    = $vehicle['vehicle_type_id'];


        if ($vehicleNew->save() ){
        	Info('Registro Grabado Correctamente!!');
        }

        Info($vehicleNew);

        return response()->json($vehicleNew);
        
   	}

   	public function delete($id)
    {
        Info($id);

        Vehicle::where('id',$id)->delete();
        
        return response()->json($id);
    }


}