<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Validator;
use Hash;
use Session;
use App\User;
use Carbon\Carbon;
use DB;
use App\AntiBot;
use App\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetMail;
use App\Mail\RegisterMail;

class AuthController extends Controller
{
    public function register(){
        if($anti_bot = AntiBot::where('is_used', false)->where('user_id', 0)->get()->first()){
            $bot_token = $anti_bot->token;
        }else{
            // generate token
            $current_timestamp = Carbon::parse(Carbon::now());
            $bot_token = sha1(uniqid($current_timestamp));
            AntiBot::create([
                'token' => $bot_token
            ]);
        }
        return view('auth.register', compact('bot_token'));
    }

    public function userLogin(Request $request){
    	$validator = Validator::make($request->all(), [
            'user' => 'required|string',
            'password' => 'required|string',
        ]);
        if($validator->fails()) {
        	if($request->ajax()){
                return response()->json([
	        		'message' => $validator->errors()
	        	], 400);
            }
        	Session::flash('error', $validator->errors());
        	return back();
        }else{
        	$user = User::where('email', $request->user)
                    ->orWhere('username', $request->user)
                    ->orWhere('phone', $request->user)
                    ->get()
                    ->first();
        	if($user){
                if($user->is_deleted == true){
                    if($request->ajax()){
                        return response()->json([
                            'message' => 'account has been deleted'
                        ], 400);
                    }
                    Session::flash('error', 'account has been deleted');
                    return back();
                }

                if($user->is_blocked == false){
            		if($this->checkEmail($request->user)){
            		}else{
            			$request->user = $user->email;
            		}
            		// create our user data for the authentication
    			    $userdata = [
    			        'email'     => $request->user,
    			        'password'  => $request->password
    			    ];

    			    // attempt to do the login
    			    if (Auth::attempt($userdata)) {
                        // validation successful!
    			        if($request->ajax()){
			                return response()->json([
				        		'message' => 'login successful'
				        	], 200);
			            }
			        	Session::flash('success', 'login successful');
			        	return back();
    			    } else {
    			        // validation not successful, send back to form
    			        if($request->ajax()){
			                return response()->json([
				        		'message' => 'incorrect password🤓, please try again'
				        	], 400);
			            }
			        	Session::flash('error', 'incorrect password🤓, please try again');
			        	return back();
    			    }
                }else{
			        if($request->ajax()){
		                return response()->json([
			        		'message' => 'account has been suspended'
			        	], 400);
		            }
		        	Session::flash('error', 'account has been suspended');
		        	return back();
                }
        	}else{
        		if($request->ajax()){
	                return response()->json([
		        		'message' => 'credentials not found'
		        	], 404);
	            }
	        	Session::flash('error', 'credentials not found');
	        	return back();
        	}
        }
    }

    public function userRegister(Request $request){
    	$validator = Validator::make($request->all(), [
            'fullname' => ['required', 'string', 'max:255', 'min:10'],
            'username' => ['required', 'alpha_dash', 'regex:/^\S*$/u', 'max:12', 'string', 'unique:users', 'min:5'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'type' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

    	// basic validation
        if($validator->fails()) {
        	if($request->ajax()){
                return response()->json([
	        		'message' => $validator->errors()
	        	], 400);
            }
        	Session::flash('error', $validator->errors());
        	return back();
        }

        // validate for bot
        if(!isset($request->bot_token) ||
            empty($request->bot_token) ||
            $request->bot_token == '' ||
            AntiBot::where('is_used', false)->where('user_id', 0)->where('token', $request->bot_token)->get()->count() <= 0){
            
       	 	if($request->ajax()){
                return response()->json([
	                'message' => 'invalid registration'
	            ], 400);
            }
        	Session::flash('error', 'invalid registration');
        	return back();
        }

        $role = Role::where('title', $request->type)->get()->first();
        if(!$role){
        	if($request->ajax()){
                return response()->json([
	                'message' => 'unknown role'
	            ], 400);
            }
        	Session::flash('error', 'unknown role');
        	return back();
        }

        $current_timestamp = Carbon::parse(Carbon::now());
        $userCreate = User::create([
        	'role_id' => $role->id,
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    	if($userCreate){
    		// create our user data for the authentication
		    $userdata = array(
		        'email'     => $request->email,
		        'password'  => $request->password
		    );

		    // attempt to do the login
		    if (Auth::attempt($userdata)) {
		        // validation successful!
		        // send mail
                try {
                    $mailObj = new \stdClass();
                    $mailObj->username = $request->username;
                    $mailObj->sender = 'noreply@'.env('APP_NAME', 'NgCast');
                    $mailObj->receiver = $request->username;
                    Mail::to($request->email)->send(new RegisterMail($mailObj));
                } catch (\Exception $e) {
                    //
                }

                // update anti_bot
                $anti_bot = AntiBot::where('is_used', false)->where('user_id', 0)->where('token', $request->bot_token)->get()->first();
                $anti_bot->user_id = User::where('email', $request->email)->get()->first()->id;
                $anti_bot->is_used = true;
                $anti_bot->save();

		        if($request->ajax()){
	                return response()->json([
		                'message' => 'registration was successful',
		                'user' => $userCreate
		            ], 200);
	            }
	        	Session::flash('success', 'registration was successful');
	        	return redirect('/'.$userCreate->username);

		    } else {        
		        // validation not successful, send back to form
		        if($request->ajax()){
	                return response()->json([
		                'message' => 'Opps, login attempt failed'
		            ], 500);
	            }
	        	Session::flash('error', 'Opps, login attempt failed');
	        	return back();
		    }
    	}else{
    		if($request->ajax()){
                return response()->json([
	                'message' => 'Opps, could not register user, try again'
	            ], 500);
            }
        	Session::flash('error', 'Opps, could not register user, try again');
        	return back();
    	}
    }

    public function userLogout(){
        if(Auth::user()){
            Auth::logout();
            return redirect('/');
        }else{
            return back();
        }
    }

    // password reset
    public function passwordReset(){
        return view('auth.passwords.email');
    }

    public function sendPasswordResetLink(Request $request){
        $user = User::where('email', $request->email)->get()->first();
        if($user){
            // generate token
            $current_timestamp = Carbon::parse(Carbon::now());
            $token = sha1(uniqid($current_timestamp));
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => $current_timestamp
            ]);

            // send mail with token
            try {
                $mailObj = new \stdClass();
                $mailObj->token = $token;
                $mailObj->username = $user->username;
                $mailObj->sender = 'noreply@gqbuzz.com';
                $mailObj->receiver = $user->username;
                Mail::to($request->email)->send(new ResetMail($mailObj));
            } catch (Exception $e) {
                //
            }

            if($request->ajax()){
                return response()->json([
	                'message' => 'we have sent a password reset link to your email @'.$request->email
	            ], 200);
            }
        	Session::flash('success', 'we have sent a password reset link to your email @'.$request->email);
        	return back();
        }else{
        	if($request->ajax()){
                return response()->json([
	                'message' => 'opps! no record with the email found'
	            ], 404);
            }
        	Session::flash('error', 'opps! no record with the email found');
        	return back();
        }
    }

    public function passwordResetForm($token){
        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request){
        // verify token
        $reset = DB::table('password_resets')->whereDate('created_at', Carbon::today())->where('token', $request->token)->get();
        if($reset->count() <= 0){
            if($request->ajax()){
                return response()->json([
	                'message' => 'invalid or expired reset token'
	            ], 404);
            }
        	Session::flash('error', 'invalid or expired reset token');
        	return back();
        }

        $validator = Validator::make($request->all(), [
            'password' => 'string|confirmed',
        ]);

        if ($validator->fails())
        {
            if($request->ajax()){
                return response()->json([
	                'message' => 'password confirmation does not match'
	            ], 422);
            }
        	Session::flash('error', 'password confirmation does not match');
        	return back();
        }else{
            $u = User::where('email', $reset->first()->email)->first();
            $u->password = Hash::make($request->password);
            if($u->save()){
                //set notification
                Notification::create([
                    'user_id' => $u->id,
                    'title' => 'Password Reset',
                    'content' => 'You changed your password',
                    'type' => 'update',
                ]);

                if($request->ajax()){
	                return response()->json([
		                'message' => 'password updated'
		            ], 200);
	            }
	        	Session::flash('success', 'password updated');
	        	return back();
            }else{
            	if($request->ajax()){
	                return response()->json([
		                'message' => 'failed to update password'
		            ], 400);
	            }
	        	Session::flash('error', 'failed to update password');
	        	return back();
            }
        }
    }

    public function checkEmail($email) {
       $find1 = strpos($email, '@');
       $find2 = strpos($email, '.');
       return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }
}
