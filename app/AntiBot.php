<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntiBot extends Model
{
    protected $fillable = [
    	'user_id',
    	'token',
	    'is_used'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
