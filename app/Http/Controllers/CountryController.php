<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    
    public function index(){
		$country = Country::all();
		return response()->json($country);
	}
    public function forid($id){
		$country = Country::where('id',$id)->get()->first();
		return response()->json($country);
	}

}
