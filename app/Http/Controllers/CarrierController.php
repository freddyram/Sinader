<?php

namespace App\Http\Controllers;

use App\Carrier;

use App\Vehicle;
use App\CarrierNotRegistered;
use App\UserEstablishment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class CarrierController extends Controller
{
	public function data(Request $request){
		$carriers = Carrier::orderBy('name')->get();
		return response()->json($carriers);
	}

	public function forid($id){

		$carrier = Carrier::where('id', $id)->where('registered',null)->get()->first();

		return response()->json($carrier);
	}

	public function search(Request $request){

		$rut = $request->input('rut');
		$name = $request->input('name');


		$carriers = Carrier::where('registered',null)->orderBy('name')->get();

		if($name){
        	$carriers = Carrier::where('name', 'ILIKE', '%'.$name.'%')->where('registered',null)->get()->toArray();
    	}
        if($rut){
        	$rut = substr ($rut, 0, -2);
			$carriers = Carrier::where('rut', $rut)->where('registered',null)->get()->toArray();        	


        }

		return response()->json($carriers);
	}


	public function index(Request $request){


		$carrier = carrier::where('registered',null)->get();
		return response()->json($carrier);
	}

	public function store(Request $request){


		$carrier = $request->Input('carrier');

        Info($request);

		//$carrierNew = carrier::where('id', $carrier["id"])->get()->first();

        if(array_key_exists("id", $carrier)){
            $carrierNew = carrier::where('id', $carrier["id"])->get()->first();
        }else
        {
            $carrierNew = new carrier();
        }

        $carrierNew->rut    	= $carrier['rut'];
        $carrierNew->dv   		= $carrier['dv'];
        $carrierNew->name   	= $carrier['name'];
        $carrierNew->address    = $carrier['address'];


        if ($carrierNew->save() ){
        	Info('Registro Grabado Correctamente!!');
        }

        Info($carrierNew);

        return response()->json($carrierNew);
        
   	}

   	public function delete($id)
    {
        Info($id);

        carrier::where('id',$id)->delete();
        
        return response()->json($id);
    }




    public function notRegistered (){
        $carriers = Carrier::where('registered','!=',null)->orderBy('name')->get();

        return response()->json($carriers);
    }

    public function saveNotRegistered(Request $request){

        Info($request);

        $carrierNew = carrier::where('rut', $request->rut )->get()->first();
        if(!$carrierNew){
            $carrierNew = new carrier();
        }

        $carrierNew->rut        = $request->rut;
        $carrierNew->dv         = $request->dv;
        $carrierNew->name       = $request->name;
        $carrierNew->establishment_id    = $request->establishment_id;
        $carrierNew->registered = 'NO';


        if ($carrierNew->save() ){
            Info('Registro Carrier Grabado Correctamente!!');

            $vehicleNew = Vehicle::where('plate',$request->plate)->get()->first();
            if(!$vehicleNew){
                $vehicleNew = new Vehicle();
                $vehicleNew->plate      = $request->plate;
                $vehicleNew->carrier_id = $carrierNew->id;
                $vehicleNew->vehicle_type_id = 1;
                $vehicleNew->save();
            }
        }

        Info($carrierNew);

        return response()->json($carrierNew);

    }

}