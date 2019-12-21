<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttachment extends Model
{
    protected $fillable = [
		'user_id',
		'type',
		'path'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
