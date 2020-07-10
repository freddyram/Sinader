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

   public function emailCertDeclaration($declaration_id){
      
      	Info("******* Envío de correo **********");
        Info($declaration_id);

       // $declaration_id = $request->input('declaration_id');
        $declaration = Declaration::where('id',$declaration_id)->first();
        $user_establishment = UserEstablishment::where('establishment_id', $declaration->establishment_id)->get()->first();
        $user = User::where('id', $user_establishment->user_id)->first();        

		$declaration_number = $declaration->correlative;

        $objSend= new \stdClass();
        $objSend->establishment_id = $declaration->establishment_id;
        $objSend->declaration_id = $declaration_id;
        $objSend->year = 2020;
        $objSend->zip_file = $zip_file;


		$pdf_file = storage_path().'declaration_'.$id.'.pdf';
		$zip_file = storage_path().'declaration_'.$id.'.zip';

        shell_exec("zip ".$zip_file." ".$pdf_file);

		Info($user->email);

      	Mail::to($user->email)->send(new SendRejectDeclaration($objSend));
      	echo "Correo Enviado";
   }
}