<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCheckout extends Model
{
    protected $fillable = [
    	'user_id',
		'bookings',
        'is_verified'
    ];

    protected $casts = [
        'bookings' => 'array'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function booking(){
        return $this->belongsTo('App\User', 'booking_id');
    }
}
