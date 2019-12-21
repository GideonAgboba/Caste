@extends('layouts.app')
@section('title', 'Login')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{elixir('assets/css/login.css')}}">
@stop
@section('content')
    <div class="redirect-to" data-url="{{url()->previous()}}"></div>
    <div class="login-container row justify-content-center">
        <div class="col-md-7" style="display: flex;">
            <img src="{{elixir('assets/img/bg/crew.jpg')}}">
        </div>
        <div class="col-md-5">
            <div class="form">
                <div class="title">
                    <h1>welcome back</h1>
                    <span>Login to continue exploring our services</span>
                </div>
                <form method="POST" action="{{ url('user-login') }}" onsubmit="event.preventDefault(); Main.Login.submit(this);">
                    @csrf
                    <input id="user" type="text" class="form-control @error('user') is-invalid @enderror" name="user" value="{{ old('user') }}" placeholder="Email Address Or Username" required autocomplete="user" autofocus>
                    <input id="password" type="password" placeholder="Enter Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    <div class="info">
                        <small>Forgot password? <a href="{{url('/reset')}}">click to reset</a></small>
                    </div>
                    <br>
                    <button type="submit" style="width: -webkit-fill-available;" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>
                    <div class="info">
                        <small>Dont have an account? <a href="{{url('/register')}}">click to register</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection