<?php

namespace App\Http\Controllers;

use App\LerChapter;
use Illuminate\Http\Request;

class LerChapterController extends Controller
{
	public function data(Request $request){
		$lerChapter = LerChapter::all();
		return response()->json($lerChapter);
	}
	public function forid($id){
		$lerChapter = LerChapter::where('id', $id)->get()->first();
		return response()->json($lerChapter);
	}

	public function index(Request $request){
		$lerchapter = lerchapter::all();
		return response()->json($lerchapter);
	}

	public function store(Request $request){


		$lerchapter = $request->Input('lerchapter');

        Info($request);

		//$lerchapterNew = lerchapter::where('id', $lerchapter["id"])->get()->first();

        if(array_key_exists("id", $lerchapter)){
            $lerchapterNew = lerchapter::where('id', $lerchapter["id"])->get()->first();
        }else
        {
            $lerchapterNew = new lerchapter();
        }

        $lerchapterNew->chapter_number  = $lerchapter['chapter_number'];
        $lerchapterNew->name   			= $lerchapter['name'];
        $lerchapterNew->active    		= $lerchapter['active'];


        if ($lerchapterNew->save() ){
        	Info('Registro Grabado Correctamente!!');
        }

        Info($lerchapterNew);

        return response()->json($lerchapterNew);
        
   	}

   	public function delete($id)
    {
        Info($id);

        lerchapter::where('id',$id)->delete();
        
        return response()->json($id);
    }

}