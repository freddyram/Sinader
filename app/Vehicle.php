<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

	protected $table = 'vehicles';

    public function carrier()
	{
	   	return $this->belongsTo(Carrier::class, 'carrier_id');
	}

    public function vehicle_type()
	{
	   	return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
	}

}