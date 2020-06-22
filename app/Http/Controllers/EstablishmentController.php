<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserEstablishment;
use App\Establishment;

use App\Exports\EstablishmentExport;

use Maatwebsite\Excel\Facades\Excel;

class EstablishmentController extends Controller
{


	public function index($company_id){

		Info("******Index*******");
		$establishment = Establishment::where('company_id', $company_id)->where('type', 'DestinatarioFinal')->orWhere('type', 'CentroAcopio')->get();
		return response()->json($establishment);
	}

	public function data(Request $request){
		$user = Auth::user();
		$ue = UserEstablishment::where('user_id', $user->id)->get()->first();  

		$establishment = Establishment::where('id',$ue->establishment_id)->with('commune')->with('region')->get()->first();


		return response()->json($establishment);
	}

	public function forid($id){
		$establishment = Establishment::where('id', $id)->get()->first();
		return response()->json($establishment);
	}



    public function update(Request $request)
    {
    	return response()->json(['ok' => true, 'message' => "Establecimiento Actualizado" ]);
    }

	public function AllData($type){

		$establishment = Establishment::where('type',$type)->with('company')->with('commune')->with('region')->get();

		return response()->json($establishment);
	}    

	public function search(Request $request){

		Info($request);

		$rut  = $request->input('rut');
		$name = $request->input('establishment');
		$type = $request->input('type'); 


		$establishments = Establishment::where('type',$type)->with('company')->with('commune')->with('region')->get();

		if($rut){
			$rut = substr ($rut, 0, -2);
			$company = Company::where('rut',$rut)->get()->first();

			$establishments = Establishment::where('type',$type)->where('company_id',$company->id)->with('company')->with('commune')->with('region')->get();
		}

		if($name){
			$establishments = Establishment::where('type',$type)->where('name','ilike','%' . $name . '%')->with('company')->with('commune')->with('region')->get();
		}



		return response()->json($establishments);
	} 


    public function export() 
    {
    	Info('******** Export *********');
        return Excel::download(new EstablishmentExport, 'establishment.xlsx');
    }

}