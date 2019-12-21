<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['is_online']], function() {
    Route::get('/', function(){
    	// if(Auth::user()){
    	// 	return view('home');
    	// }
        return view('welcome');
    });

    Auth::routes();
    Route::get('register', 'AuthController@register');
    Route::get('logout', 'AuthController@userLogout');
    Route::post('user-login', 'AuthController@userLogin');
    Route::post('user-register', 'AuthController@userRegister');
    Route::get('reset', 'AuthController@passwordReset');
    Route::post('reset', 'AuthController@sendPasswordResetLink');
    Route::get('reset/{token}', 'AuthController@passwordResetForm');
    Route::post('reset-password', 'AuthController@reset');

    Route::get('get-users/{count}', 'GeneralController@getUsers');
    Route::get('hire-profile-rating/{username}', 'GeneralController@hireProfileRating');
    Route::get('get-user-gallery/{username}', 'GeneralController@getUserGallery');

    Route::group(['middleware' => ['auth']], function(){
        Route::get('settings', 'GeneralController@settings');
        Route::post('save-profile-image', 'GeneralController@saveProfileImage');
        Route::post('save-gallery-images', 'GeneralController@saveGalleryImages');
        Route::post('delete-gallery-image', 'GeneralController@deleteGalleryImage');
        Route::post('profile-update', 'GeneralController@profileUpdate');
        Route::post('add-social-link', 'GeneralController@addSocialLink');
        Route::post('delete-social-link', 'GeneralController@deleteSocialLink');
        Route::post('account-delete', 'GeneralController@accountDelete');

        Route::post('add-to-booking', 'GeneralController@addToBooking');
        Route::post('delete-booking', 'GeneralController@deleteBooking');
        Route::post('delete-all-booking', 'GeneralController@deleteAllBooking');
        Route::post('booking-checkout', 'GeneralController@bookingCheckout');
    });

    Route::get('/{page_user}', 'GeneralController@userView')->name('user');
});
