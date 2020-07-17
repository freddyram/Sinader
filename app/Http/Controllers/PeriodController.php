<?php

namespace App\Http\Controllers;

use App\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function data($subchapter_id){
        $Period = Period::where('subchapter_id', $subchapter_id)->get();
        return response()->json($Period);
    }

    public function forid($id){
        $Period = Period::where('id', $id)->with('waste')->get()->first();
        return response()->json($Period);
    }

    public function index(Request $request){
        $Period = Period::all();
        return response()->json($Period);
    }

    public function store(Request $request){


        $Period = $request->Input('Period');

        Info($request);

        //$PeriodNew = Period::where('id', $Period["id"])->get()->first();

        if(array_key_exists("id", $Period)){
            $PeriodNew = Period::where('id', $Period["id"])->get()->first();
        }else
        {
            $PeriodNew = new Period();
        }

        $PeriodNew->year      = $Period['year'];
        $PeriodNew->month     = $Period['month'];

        if ($PeriodNew->save() ){
            Info('Registro Grabado Correctamente!!');
        }

        Info($PeriodNew);

        return response()->json($PeriodNew);
        
    }

    public function delete($id)
    {
        Info($id);

        Period::where('id',$id)->delete();
        
        return response()->json($id);
    }
}
