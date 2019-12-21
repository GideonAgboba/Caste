<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'country_id',
        'fullname',
        'username',
        'email',
        'email_verified_at',
        'password',
        'gender',
        'phone',
        'date_of_birth',
        'state',
        'address',
        'bio',
        'height',
        'weight',
        'shirt_size',
        'waist_size',
        'rating',
        'is_blocked',
        'is_verified',
        'is_suspended',
        'is_deleted',
        'path',
        'cover_path',
        'last_seen',
        'not_bot',
        'actor_type',
        'model_type',
        'crew_type',
        'crew_type_info'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_seen'
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function attachment(){
        return $this->hasMany('App\UserAttachment');
    }

    public function social(){
        return $this->hasMany('App\UserSocial');
    }

    public function notification(){
        return $this->hasMany('App\Notification');
    }

    public function booking(){
        return $this->hasMany('App\UserBooking');
    }

    public function checkout(){
        return $this->hasMany('App\UserCheckout');
    }

    public function isOnline(){
        $interval = 2;
        $user = User::find($this->id);
        $calc = Carbon::now()->subMinutes($interval)->toDateTimeString();
        // $calc = Carbon::now() - $user->last_seen;
        if($user->last_seen->gt($calc)){
            return true;
        }
        else{
            return false;
        }
    }
}
