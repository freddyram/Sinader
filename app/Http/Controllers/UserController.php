<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function update(Request $request)
    {
    	return response()->json(['ok' => true, 'message' => "Usuario Actualizada" ]);
    }
}