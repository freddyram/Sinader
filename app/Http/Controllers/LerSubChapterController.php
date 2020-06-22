<?php

namespace App\Http\Controllers;

use App\LerSubChapter;
use Illuminate\Http\Request;

class LerSubChapterController extends Controller
{

	public function data($lersubchapter_id){

		$lerSubChapter = LerSubChapter::where('chapter_id', $lersubchapter_id)->get();
		return response()->json($lerSubChapter);
	}

	public function index(Request $request){
		$lersubchapter = lersubchapter::all();
		return response()->json($lersubchapter);
	}

	public function store(Request $request){


		$lersubchapter = $request->Input('lersubchapter');

        Info($request);

		//$lersubchapterNew = lersubchapter::where('id', $lersubchapter["id"])->get()->first();

        if(array_key_exists("id", $lersubchapter)){
            $lersubchapterNew = lersubchapter::where('id', $lersubchapter["id"])->get()->first();
        }else
        {
            $lersubchapterNew = new lersubchapter();
        }

        $lersubchapterNew->chapter_number  	= $lersubchapter['chapter_number'];
        $lersubchapterNew->name   			= $lersubchapter['name'];
        $lersubchapterNew->chapter_id  		= $lersubchapter['chapter_id'];
        $lersubchapterNew->active    		= $lersubchapter['active'];


        if ($lersubchapterNew->save() ){
        	Info('Registro Grabado Correctamente!!');
        }

        Info($lersubchapterNew);

        return response()->json($lersubchapterNew);
        
   	}

   	public function delete($id)
    {
        Info($id);

        lersubchapter::where('id',$id)->delete();
        
        return response()->json($id);
    }

}