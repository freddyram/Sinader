<?php
namespace App\Http\Controllers;


use App\User;
use App\Establishment;
use App\Company;
use App\UserEstablishment;
use App\Requisition;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;

class ApiUserController extends Controller
{

    public function setSecretInvitation(Request $request)
    {

        $retcid = $request->input('retcid');
        $secret_a = $request->input('secret_a');
        $secret_b = $request->input('secret_b');
        $user = UserEstablishment::where('retc_id', '=', $retcid)->first();      
        if ($user){
            $user->secret_a = $secret_a;
            $user->secret_b = $secret_b;
            if ($user->save()){
                return response()->json(['response' => 1]);
            }
        }  else {
            
        }              
        return response()->json(['response' => 0]);
    }

    public function solicitudIndustrial(request $request){

    	// Info($request);
    	// Info($request->body);
    	// Info($request->objeto['retc']);

    	DB::transaction(function() use ($request){
            $requisition = Requisition::where('retc_id',$request->objeto['retc'])->first();
            info($requisition);
            //dd($userExists);
            if(!$requisition){
                $requisition=Requisition::create(array(
                    'retc_id'=> $request->objeto['retc'],
                    'data'=> json_encode($request->objeto),
                    'state'=>'PENDIENTE',
                ));
                
                $requisition->save(); 	
            };   
        });   

        return response()->json(['ok' => true, 'message' => "Solicitud recibida" ]);	
     
    }


    public function validateSecret(Request $request){
        Info('ValidateSecret');
        Info($request);

        $secret_a =$request->secret_a;
        $secret_b =$request->secret_b;
        $result = UserEstablishment::where('secret_a',$secret_a)->where('secret_b',$secret_b)->first();

        
        Info('validateSecret');

        if($result){
            $token = $result->user->createToken('VU')->accessToken;

            $result->secret_a = null;
            $result->secret_b = null;
            $result->save();

            Info('token');
            Info($token);    
            Session::flash('token', $token);
            return redirect('/');
        }else{
            return abort(404,'Error: UserAccess');
        }
    }

    
    public function setUserVue(Request $request){

       $user = Auth::user();
       $user_establishment = UserEstablishment::where('user_id', $user->id)->with('user')->with('establishment')->get();

        return response()->json($user_establishment);

    }


    public function vu_connect(Request $request){
        $user_id = $request->input('user_id');

        $ue = UserEstablishment::where('user_id', $user_id)->first();  


        Info("*******  vu_connect ********");
        Info($user_id);
        Info($ue);

        if($ue){
            $token = $ue->user->createToken('VU')->accessToken;

            Session::flash('token', $token);

            Info("***** vu_connect ******");
            Info($ue);
            Info($token);


            return redirect('/');
        } else {
            return abort(404,'Error: UserAccess');
        }
    }
}