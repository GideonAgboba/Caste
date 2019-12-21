<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBooking extends Model
{
    protected $fillable = [
    	'user_id',
		'booking_id',
        'type'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function booking(){
        return $this->belongsTo('App\User', 'booking_id');
    }
}
