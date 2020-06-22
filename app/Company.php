<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public function commune()
	{
	   	return $this->belongsTo(Commune::class, 'commune_id');
	}
    public function region()
	{
	   	return $this->belongsTo(Region::class, 'region_id');
	}
}
