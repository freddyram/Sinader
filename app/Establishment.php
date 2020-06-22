<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    protected $table = 'establishments';

    public function commune()
	{
	   	return $this->belongsTo(Commune::class, 'commune_id');
	}
    public function company()
	{
	   	return $this->belongsTo(Company::class, 'company_id');
	}
    public function region()
	{
	   	return $this->belongsTo(Region::class, 'region_id');
	}
}
