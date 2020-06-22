<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEstablishment extends Model
{
    public function user()
	{
	   	return $this->belongsTo(User::class, 'user_id');
	}

    public function establishment()
	{
	   	return $this->belongsTo(Establishment::class, 'establishment_id');
	}
}
