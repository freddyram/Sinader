<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
  
   public function emailRejectedDeclaration(Request $request)) {
      
        $declaration_id = $request->input('declaration_id');
        $declaration = Declaration::where('id',$declaration_id)->get()->first();
        $user_establishment = UserEstablishment::where('entablishment_id', $declaration->entablishment_id)->get()->first();
        $user = User::where('id', $user_establishment->user_id)->first();        

		$declaration_number = $declaration->correlative;

      	Mail::to($user->email)->send(new SendRejectDeclaration($declaration_number);
      	echo "Correo Enviado";
   }

}