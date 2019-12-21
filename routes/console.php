<?php

use Illuminate\Foundation\Inspiring;
use App\Role;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('role:setup', function () {
    if(Role::all()->count() > 0){
    	return $this->comment('Gwapo has already created the default roles');
    }else{
    	$roles = [
            [
                'title' => 'customer',
                'description' => ''
            ],
            [
                'title' => 'actor',
                'description' => ''
            ],
    		[
    			'title' => 'model',
    			'description' => ''
    		],
    		[
    			'title' => 'crew',
    			'description' => ''
    		]
    	];
    	foreach ($roles as $role) {
    		Role::create([
    			'title' => $role['title'],
    			'description' => $role['description']
    		]);
    	}
    	return $this->info('Gwapo just created the default roles');
    }
})->describe('setting up role');
