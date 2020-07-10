<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SendRejectDeclaration;
use App\Declaration;
use App\User;
use App\UserEstablishment;

class MailController extends Controller 
{
  
   public function emailRejectedDeclaration($declaration_id){
      
      	Info("******* Envío de correo **********");
        Info($declaration_id);

       // $declaration_id = $request->input('declaration_id');
        $declaration = Declaration::where('id',$declaration_id)->first();
        $user_establishment = UserEstablishment::where('establishment_id', $declaration->establishment_id)->get()->first();
        $user = User::where('id', $user_establishment->user_id)->first();        

		$declaration_number = $declaration->correlative;

		Info($user->email);

      	Mail::to($user->email)->send(new SendRejectDeclaration($declaration_number));
      	echo "Correo Enviado";
   }

}