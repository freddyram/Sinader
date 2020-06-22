<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LerWaste extends Model
{
    protected $table = 'ler_waste';

    public function subchapter()
	{
	   	return $this->belongsTo(LerSubChapter::class, 'subchapter_id');
	}
}
