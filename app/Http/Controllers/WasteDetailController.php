<?php

namespace App\Http\Controllers;

use App\WasteDetail;
use Illuminate\Http\Request;

class WasteDetailController extends Controller
{
 
    
    public function index($declaration_id)
    {
        $waste_details = WasteDetail::where('declaration_id', $declaration_id)->get();

		return response()->json($waste_details);
    }


    public function forreceiver(Request $request)
    {

        Info("*******  forreceiver ***********");
        Info($request);
        
    	$declaration_id = $request->input('declaration_id');
    	$establishment_id = $request->input('establishment_id');
        $waste_details = WasteDetail::where('declaration_id', $declaration_id)->where('establishment_id', $establishment_id)->get();

		return response()->json($waste_details);
    }

    public function updateDiscrepancy(Request $request)
    {

        Info("******** updateDiscrepancy **********");
        Info($request);

        $waste_detail_id = $request->input('waste_detail_id');

        $waste_detail = WasteDetail::where('id', $waste_detail_id)->get()->first();

        $waste_detail->discrep_quantity = $request->input('disc_quantity');
        $waste_detail->discrep_unit     = $request->input('disc_unit');
        $waste_detail->discrep_comment  = $request->input('disc_comment');
        $waste_detail->save();

        return response()->json($waste_detail);
    }


    
}