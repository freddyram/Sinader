<?php

namespace App\Http\Controllers;

use App\Company;
use App\UserEstablishment;
use App\Establishment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function update(Request $request)
    {
    	return response()->json(['ok' => true, 'message' => "Empresa Actualizada" ]);
    }
    
	public function data(Request $request){
		$user = Auth::user();	
		$ue = UserEstablishment::where('user_id',$user->id)->get()->first();
		$establishment = Establishment::where('id',$ue->establishment_id)->get()->first();
		$company = Company::where('id', $establishment->company_id)->get()->first();

		return response()->json($company);
	}

	public function index(){
		$company = Company::all();
		return response()->json($company);
	}
	

	public function forid($id){
		$company = Company::where('id',$id)->get()->first();
		return response()->json($company);
	}

	public function search(Request $request){

		$rut = $request->input('rut');
		$name = $request->input('name');

		$companies = Company::orderBy('name')->get();

		if($name){
        	$companies = Company::where('name', 'ILIKE', '%'.$name.'%')->get()->toArray();
    	}
        if($rut){
        	$rut = substr ($rut, 0, -2);
			$companies = Company::where('rut', $rut)->get()->toArray();        	
        }

		return response()->json($companies);
	}

	public function searchSii($rut){

        $company        = 'Generador A';
        $rut            = '11333777';
        $digit			= '9';
        $address        = 'San Martin';
        $number			= '76';
        $comune_id		= '1';
        $commune_name   = 'Santiago';
        $region_id      = '13';
        $region_name    = 'Metropolitana de Santiago';

        $generator = ['company_name'=> $company, 'rut' => $rut, 'digit'=>$digit, 'street' =>$address, 'number'=> $number, 'commune_name'=> $commune_name, 'region_name'=>$region_name ];

        return response()->json($generator);
	}



}