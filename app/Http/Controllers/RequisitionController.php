<?php

namespace App\Http\Controllers;

use App\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Establishment;
use App\Company;
use App\UserEstablishment;



class RequisitionController extends Controller
{
    public function index(Request $request){

    	$requisitions = Requisition::all()->toArray();

    	return response()->json($requisitions);
    }
    
    public function approve(Request $request){

    	$retc_id =$request->input('retc_id');

    //	Info('approve');

    	$client = new Client();
    	$res = $client->post('https://vuprueba.mma.gob.cl/VUb2/ws/cde/access/token',['form_params' => ['client'=>'1', 'secret'=>'123']]);
        $jsonData = json_decode((string) $res->getBody()->getContents()) ;
        $token =  $jsonData->token;

    //    Info($token);

        $client = new Client();
        $res = $client->post('https://vuprueba.mma.gob.cl/VUb2/ws/cde/application/' . $retc_id . '/approve',['headers'=>['Authorization'=>$token]] );

        $jsonData = json_decode((string) $res->getBody()->getContents(), true) ;
     
        if($res->getStatusCode()==200){
        	$requisition = Requisition::where('retc_id', $retc_id)->get()->first();
        	$requisition->aprove_data = json_encode($jsonData);
        	$requisition->state = 'APROBADA';
        	$requisition->save();

        	$this->userCreate($jsonData);
        }

	//	Info($res->getBody());
    }



    public function userCreate($response){

    	//Info($response);
        
	    DB::transaction(function() use ($response){

	        $representante_legal = User::where('retcid',$response['empresa']['rretc'])->first();

	            //dd($userExists);
	            if($representante_legal){
	                $representante_legal->name=$response['empresa']['rnombre'].' '.$response['empresa']['rpaterno'].' '.$response['empresa']['rmaterno'];
	                $representante_legal->email=$response['empresa']['remail'];
	                $representante_legal->save();
	            }else{
	                $representante_legal=User::create(array(
	                    'name'=>$response['empresa']['rnombre'].' '.$response['empresa']['rpaterno'].' '.$response['empresa']['rmaterno'],
	                    'email'=>$response['empresa']['remail'],
	                    'retcid'=>$response['empresa']['rretc'],
	                    'password'=>Hash::make('Enrique Pino Liso'),
	                ));            
	            }

	            $encargado_establecimiento = User::where('retcid',$response['establecimiento']['rretc'])->first();
	            if($encargado_establecimiento){
	                $encargado_establecimiento->name = $response['establecimiento']['rnombre'].' '.$response['establecimiento']['rpaterno'].' '.$response['establecimiento']['rmaterno'];
	                $encargado_establecimiento->email=$response['establecimiento']['remail'];
	                $encargado_establecimiento->save();
	            }else{
	                $encargado_establecimiento=User::create(array(
	                    'name'=>$response['establecimiento']['rnombre'].' '.$response['establecimiento']['rpaterno'].' '.$response['establecimiento']['rmaterno'],
	                    'email'=>$response['establecimiento']['remail'],
	                    'retcid'=>$response['establecimiento']['rretc'],
	                    'password'=>Hash::make('Enrique Pino Liso'),
	                ));
	            }

	            $companyExists = Company::where('retc_code',$response['empresa']['retc'])->first();
	            if(!$companyExists){
	                $companyExists=new Company();
	                $companyExists->retc_code = $response['empresa']['retc'];       
	            }
	            $companyExists->user_id = $representante_legal->id;
	            $companyExists->rut=$response['empresa']['rut'];
	            $companyExists->name=$response['empresa']['razon'];
	            $companyExists->fantasy_name=$response['empresa']['fantasia'];
	            $companyExists->digit=$response['empresa']['digito'];
	            $companyExists->street=$response['empresa']['calle'];
	            $companyExists->number=$response['empresa']['numero'];
	            $companyExists->commune_id=$response['empresa']['comuna'];
	            $companyExists->save();

	            $establishmentExists= Establishment::where('retc_code',$response['establecimiento']['retc'])->first();
	            if(!$establishmentExists){
	                $establishmentExists=new Establishment();
	                $establishmentExists->retc_code =$response['establecimiento']['retc'];
	            }
	            $establishmentExists->name = $response['establecimiento']['nombre'];
	            $establishmentExists->commune_id = $response['establecimiento']['comuna'];
	            $establishmentExists->street = $response['establecimiento']['calle'];
	            $establishmentExists->number = $response['establecimiento']['numero'];
	            $establishmentExists->company_id = $companyExists->id;
	            $establishmentExists->user_id = $encargado_establecimiento->id;
	            $establishmentExists->save();

	            foreach($response['usuarios'] as $usuarios){
	                info($usuarios);
	                $usuario = User::where('retcid',$usuarios['per_retc'])->first();
	                if(!$usuario){
	                    $usuario= new User();
	                    $usuario->retcid=$usuarios['per_retc'];
	                }
	                $usuario->name=$usuarios['nombre'].' '.$usuarios['paterno'].' '.$usuarios['materno'];
	                $usuario->email=$usuarios['email'];
	                $usuario->password=Hash::make('CuantificacionEmisiones');
	                $usuario->save();
	                $userEstablishment = UserEstablishment::where('user_id',$usuario->id)->where('establishment_id',$establishmentExists->id)->first();
	                if(!$userEstablishment){
	                    $userEstablishment=new UserEstablishment();                    
	                    $userEstablishment->user_id=$usuario->id;
	                    $userEstablishment->establishment_id =$establishmentExists->id;
	                }
	                $userEstablishment->retc_id=$usuarios['retc'];
	                $userEstablishment->save();
	            }
	        });   
    }
}
