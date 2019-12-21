<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
		'name',
		'code_two',
		'code_three'
    ];
}
