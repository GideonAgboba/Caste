<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    protected $fillable = [
    	'user_id',
		'title',
		'url'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
