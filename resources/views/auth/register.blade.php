@extends('layouts.app')
@section('title', 'Register')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{elixir('assets/css/register.css')}}">
@stop
@section('content')
    <div class="register-container row justify-content-center">
        <div class="col-md-5">
            <div class="form">
                <div class="title">
                    <h1>get started</h1>
                    <span>Few details to begin exploring our services</span>
                </div>
                <!-- <form method="POST" action="{{ url('/user-register') }}"> -->
                <form method="POST" action="{{ url('/user-register') }}" onsubmit="event.preventDefault(); Main.Register.submit(this);">
                    @csrf
                    <input id="fullname" type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname" value="{{ old('fullname') }}" placeholder="Fullname" required autocomplete="fullname" autofocus>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username">
                        </div>
                        <div class="col-lg-6 col-12">
                            <select id="type" type="type" class="form-control @error('type') is-invalid @enderror" name="type" required autocomplete="type">
                                <option disabled selected value="">Choose Account</option>
                                <option style="color: #fd66c3 !important;" @if(old('type') == 'customer') selected @endif value="customer">Customer</option>
                                <option style="color: #fd66c3 !important;" @if(old('type') == 'actor') selected @endif value="actor">Actor</option>
                                <option style="color: #fd66c3 !important;" @if(old('type') == 'model') selected @endif value="model">Model</option>
                                <option style="color: #fd66c3 !important;" @if(old('type') == 'crew') selected @endif value="crew">Crew</option>
                            </select>
                        </div>
                    </div>
                    <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    <div class="checkbox">
                        <div onclick="Main.Register.toggleAntiBot(this)"></div>
                        <small style="margin-top: 3px;">I accept privacy policy and terms & condition</small>
                        <input class="form-check-input" type="checkbox" onchange="Main.Register.antiBot('{{$bot_token}}', this)" required> 
                    </div>
                    <br>
                    <button type="submit" style="width: -webkit-fill-available;" class="btn btn-primary">
                        {{ __('Register') }}
                    </button>
                    <div class="info">
                        <small>Have an account already? <a href="{{url('/login')}}">click here</a></small>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-7" style="display: flex;">
            <img src="{{elixir('assets/img/bg/crew4.jpg')}}">
        </div>
    </div>
@endsection