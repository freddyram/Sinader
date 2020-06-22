<?php

namespace App\Http\Controllers;

use App\LerWaste;
use Illuminate\Http\Request;

class LerWasteController extends Controller
{

	public function data($subchapter_id){
		$lerWaste = LerWaste::where('subchapter_id', $subchapter_id)->get();
		return response()->json($lerWaste);
	}

	public function forid($id){
		$lerWaste = LerWaste::where('id', $id)->with('waste')->get()->first();
		return response()->json($lerWaste);
	}

	public function index(Request $request){
		$lerwaste = lerwaste::all();
		return response()->json($lerwaste);
	}

	public function store(Request $request){


		$lerwaste = $request->Input('lerwaste');

        Info($request);

		//$lerwasteNew = lerwaste::where('id', $lerwaste["id"])->get()->first();

        if(array_key_exists("id", $lerwaste)){
            $lerwasteNew = lerwaste::where('id', $lerwaste["id"])->get()->first();
        }else
        {
            $lerwasteNew = new lerwaste();
        }

        $lerwasteNew->waste_number  	= $lerwaste['waste_number'];
        $lerwasteNew->subchapter_id  	= $lerwaste['subchapter_id'];
        $lerwasteNew->name   			= $lerwaste['name'];
        $lerwasteNew->waste_code	  	= $lerwaste['waste_code'];
        $lerwasteNew->type_id  			= $lerwaste['type_id'];
        $lerwasteNew->active    		= $lerwaste['active'];


        if ($lerwasteNew->save() ){
        	Info('Registro Grabado Correctamente!!');
        }

        Info($lerwasteNew);

        return response()->json($lerwasteNew);
        
   	}

   	public function delete($id)
    {
        Info($id);

        lerwaste::where('id',$id)->delete();
        
        return response()->json($id);
    }

}