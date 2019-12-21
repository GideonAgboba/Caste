<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Validator;
use Carbon\Carbon;
use App\User;
use App\UserAttachment;
use App\UserSocial;
use App\UserBooking;
use App\UserCheckout;

class GeneralController extends Controller
{
    public function settings(){
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    public function getUsers(Request $request, $count){
    	$users = [];
    	$lists = User::where('is_blocked', false)
                    ->where('is_suspended', false)
                    ->where('is_deleted', false)
                    ->where('role_id', '!=', 1)
		            ->skip($count)
		            ->take(10)
		            ->get();
		foreach ($lists as $list) {
            $isBooked = false;
            if(Auth::user() && UserBooking::where('user_id', Auth::user()->id)->where('booking_id', $list->id)->get()->first()){
                $isBooked = true;
            }

            $users[] = [
                'id' => $list->id,
                'fullname' => $list->fullname,
                'username' => $list->username,
                'email' => $list->email,
                'gender' => $list->gender,
                'path' => $list->path,
                'role' => $list->role->title,
                'rating' => $list->rating,
                'created_at' => $list->created_at->diffForHumans(),
                'is_booked' => $isBooked
            ];
        }

        if($request->ajax()){
            return response()->json([
	            'message' => 'successful',
	            'data' => $users
        	], 200);
        }
    	Session::flash('error', 'invalid call method');
    	return back();
    }

    public function userView($page_user){
        $user = User::where( 'username', $page_user )->get()->first();
        if($user){
            return view('user.profile', compact('user'));
        }else{
            return view('errors.404');
        }
    }

    public function getUserGallery(Request $request, $username){
    	$user = User::where( 'username', $username )->get()->first();
        if($user){
        	if($request->ajax()){
	            return response()->json([
		            'message' => 'successful',
		            'data' => $user->attachment
	        	], 200);
	        }
	    	Session::flash('error', 'invalid call method');
	    	return back();
        }else{
        	if($request->ajax()){
	            return response()->json([
		            'message' => 'user not found',
	        	], 404);
	        }
	    	Session::flash('error', 'invalid call method');
	    	return back();
            
        }
    }

    public function saveProfileImage(Request $request){
    	$user = Auth::user();
    	$validator = Validator::make($request->all(), [
            'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
        	if($request->ajax()){
	            return response()->json([
	                'message' => $validator->errors()
	            ], 500);
	        }
	        Session::flash('error', 'invalid call method');
	    	return back();
        }

 		// upload images
        if($request->hasFile('path')) {
    		$image = $request->file('path');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/profile/images');
            if($image->move($destinationPath, $name)){
            	$oldPath = $user->path;
                if($user->update([
                    'path' => 'uploads/profile/images/'.$name
                ])){
                	// 10 rating points for uploading a profile image
                    if($oldPath == 'user.png'){
                    	$user->update([
                    		'rating' => $user->rating + 10
                    	]);
                    }
                }else{
                	if($request->ajax()){
			            return response()->json([
				            'message' => 'failed to save profile image record',
			        	], 400);
			        }
			    	Session::flash('error', 'invalid call method');
			    	return back();
                }
            }else{
            	if($request->ajax()){
		            return response()->json([
			            'message' => 'failed to save profile image',
		        	], 400);
		        }
		    	Session::flash('error', 'invalid call method');
		    	return back();
            }
        }


    	if($request->ajax()){
            return response()->json([
	            'message' => 'profile image updated'
        	], 200);
        }
    	Session::flash('error', 'invalid call method');
    	return back();
    }

    public function saveGalleryImages(Request $request){
    	$user = Auth::user();
    	$validator = Validator::make($request->all(), [
            'path.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
        	if($request->ajax()){
	            return response()->json([
	                'message' => $validator->errors()
	            ], 500);
	        }
	        Session::flash('error', 'invalid call method');
	    	return back();
        }

 		// upload images
        if($request->hasFile('path')) {
        	$counter = 1;
            foreach ($request->file('path') as $image){
	            $name = $counter.time().'.'.$image->getClientOriginalExtension();
	            $destinationPath = public_path('/uploads/gallery/images');
	            if($image->move($destinationPath, $name)){
	                if(UserAttachment::create([
	                	'user_id' => $user->id,
	                    'path' => 'uploads/gallery/images/'.$name,
	                    'type' => 'image'
	                ])){
	                    // 1 rating point for each image added to gallery
                    	$user->update([
                    		'rating' => $user->rating + 1
                    	]);
	                }else{
	                	if($request->ajax()){
				            return response()->json([
					            'message' => 'failed to save gallery images record',
				        	], 400);
				        }
				    	Session::flash('error', 'invalid call method');
				    	return back();
	                }
	            }else{
                	if($request->ajax()){
			            return response()->json([
				            'message' => 'failed to save post gallery image',
			        	], 400);
			        }
			    	Session::flash('error', 'invalid call method');
			    	return back();
	            }

	            $counter++;
            }
        }


    	if($request->ajax()){
            return response()->json([
	            'message' => 'gallery updated successfully'
        	], 200);
        }
    	Session::flash('error', 'invalid call method');
    	return back();
    }

    public function deleteGalleryImage(Request $request){
    	$user = Auth::user();
    	$post = UserAttachment::find($request->id);
    	if($post){
    		if($post->user_id == $user->id){
    			$post->delete();
    			if($request->ajax()){
		            return response()->json([
			            'message' => 'image deleted',
		        	], 200);
		        }
		    	Session::flash('error', 'invalid call method');
		    	return back();
    		}else{
    			if($request->ajax()){
		            return response()->json([
			            'message' => 'authorization error',
		        	], 401);
		        }
		    	Session::flash('error', 'invalid call method');
		    	return back();
    		}
    	}else{
    		if($request->ajax()){
	            return response()->json([
		            'message' => 'image not found',
	        	], 404);
	        }
	    	Session::flash('error', 'invalid call method');
	    	return back();
    	}
    }

    public function profileUpdate(Request $request){
    	$user = Auth::user();
    	$oldPhone = $user->phone;
    	// update all
    	$user->update($request->all());

    	// 10 rating point for first time profile update
    	if($oldPhone == '' || $oldPhone == null){
	    	$user->update([
	    		'rating' => $user->rating + 10
	    	]);
	    }

	    if($request->ajax()){
            return response()->json([
	            'message' => 'profile updated',
        	], 200);
        }
    	Session::flash('error', 'invalid call method');
    	return back();
    }

    public function addSocialLink(Request $request){
    	$user = Auth::user();
    	UserSocial::create([
    		'user_id' => $user->id,
    		'title' => $request->title,
    		'url' => $request->url
    	]);

	    if($request->ajax()){
            return response()->json([
	            'message' => 'social link added',
        	], 200);
        }
    	Session::flash('error', 'invalid call method');
    	return back();
    }

    public function deleteSocialLink(Request $request){
    	$user = Auth::user();
    	$social = UserSocial::find($request->id);
    	if($social){
    		if($social->user_id == $user->id){
    			$social->delete();
    			if($request->ajax()){
		            return response()->json([
			            'message' => 'image deleted',
		        	], 200);
		        }
		    	Session::flash('error', 'invalid call method');
		    	return back();
    		}else{
    			if($request->ajax()){
		            return response()->json([
			            'message' => 'authorization error',
		        	], 401);
		        }
		    	Session::flash('error', 'invalid call method');
		    	return back();
    		}
    	}else{
    		if($request->ajax()){
	            return response()->json([
		            'message' => 'image not found',
	        	], 404);
	        }
	    	Session::flash('error', 'invalid call method');
	    	return back();
    	}
    }

    public function accountDelete(){
    	$user = Auth::user();
        $user->username = $user->username.'_deleted_'.time();
    	$user->is_deleted = true;
    	$user->save();

    	return redirect('/logout');
    }

    public function hireProfileRating($username){
        $user = User::where( 'username', $username )->get()->first();
        if($user){
            // 3 rating point for hire click
            $user->update([
                'rating' => $user->rating + 3
            ]);
        }
    }

    public function addToBooking(Request $request){
        $user = Auth::user();

        // make sure user is a customer
        if($user->role->title != 'customer'){
            if($request->ajax()){
                return response()->json([
                    'message' => 'authorization error',
                ], 401);
            }
            Session::flash('error', 'invalid call method');
            return back();
        }

        UserBooking::create([
            'user_id' => $user->id,
            'booking_id' => $request->id,
            'type' => $request->type
        ]);

        // 3 rating point for hire click
        $bookingUser = User::find($request->id);
        if($bookingUser){
            $bookingUser->update([
                'rating' => $user->rating + 3
            ]);
        }

        if($request->ajax()){
            return response()->json([
                'message' => $request->type.' booked',
            ], 200);
        }
        Session::flash('error', 'invalid call method');
        return back();
    }

    public function deleteBooking(Request $request){
        $user = Auth::user();

        // make sure user is a customer
        if($user->role->title != 'customer'){
            if($request->ajax()){
                return response()->json([
                    'message' => 'authorization error',
                ], 401);
            }
            Session::flash('error', 'invalid call method');
            return back();
        }

        UserBooking::find($request->id)->delete();

        if($request->ajax()){
            return response()->json([
                'message' => 'booking deleted',
                'count' => $user->booking->count()
            ], 200);
        }
        Session::flash('error', 'invalid call method');
        return back();
    }

    public function deleteAllBooking(Request $request){
        $user = Auth::user();

        // make sure user is a customer
        if($user->role->title != 'customer'){
            if($request->ajax()){
                return response()->json([
                    'message' => 'authorization error',
                ], 401);
            }
            Session::flash('error', 'invalid call method');
            return back();
        }

        foreach ($user->booking as $booking) {
            $booking->delete();
        }

        if($request->ajax()){
            return response()->json([
                'message' => 'bookings cleared',
                'count' => $user->booking->count()
            ], 200);
        }
        Session::flash('error', 'invalid call method');
        return back();
    }

    public function bookingCheckout(Request $request){
        $user = Auth::user();
        $bookingData = [];
        // make sure user is a customer
        if($user->role->title != 'customer'){
            if($request->ajax()){
                return response()->json([
                    'message' => 'authorization error',
                ], 401);
            }
            Session::flash('error', 'invalid call method');
            return back();
        }

        foreach ($user->booking as $booking) {
            if($booking->type == 'user'){
                $bookingData[] = [
                    'id' => $booking->booking_id,
                    'type' => $booking->type
                ];
            }

            $booking->delete();
        }

        UserCheckout::create([
            'user_id' => $user->id,
            'bookings' => $bookingData
        ]);
        

        if($request->ajax()){
            return response()->json([
                'message' => 'your checkout was successful...<br>we would begin processing your request, and return a response within 4 working days',
                'count' => $user->booking->count()
            ], 200);
        }
        Session::flash('error', 'invalid call method');
        return back();
    }
}
