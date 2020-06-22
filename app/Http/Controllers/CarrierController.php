<?php

namespace App\Http\Controllers;

use App\Carrier;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
	public function data(Request $request){
		$carriers = Carrier::orderBy('name')->get();
		return response()->json($carriers);
	}

	public function forid($id){
		$carrier = Carrier::where('id', $id)->get()->first();
		return response()->json($carrier);
	}

	public function search(Request $request){

		$rut = $request->input('rut');
		$name = $request->input('name');

		$carriers = Carrier::orderBy('name')->get();

		if($name){
        	$carriers = Carrier::where('name', 'ILIKE', '%'.$name.'%')->get()->toArray();
    	}
        if($rut){
        	$rut = substr ($rut, 0, -2);
			$carriers = Carrier::where('rut', $rut)->get()->toArray();        	
        }

		return response()->json($carriers);
	}


	public function index(Request $request){
		$carrier = carrier::all();
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



}