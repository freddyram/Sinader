<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LerSubchapter extends Model
{
    protected $table = 'ler_subchapter';

    public function chapter()
	{
	   	return $this->belongsTo(LerChapter::class, 'chapter_id');
	}
}
