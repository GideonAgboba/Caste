@extends('layouts.app')
@section('title', 'Home')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{elixir('/assets/css/welcome.css')}}">
@stop
@section('content')
    <div class="fixed-search">
        <div class="search">
            <input type="" placeholder="Enter Search Keyword..." name="">
            <i class="fa fa-search"></i>
        </div>
    </div>
    <div class="main-header">
        <div class="row justify-content-center">
            <div class="col-md-9 text-center">
                <div class="pt50">
                    <div class="row justify-content-center text-center">
                        <div class="col-12 col-md-4 text-center">
                            <div class="counter_box text-center">
                                <span class="counter">12</span>
                                <h5>Years of experience</h5>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            <div class="counter_box text-center">
                                <span class="counter">257</span>
                                <h5>happy clients</h5>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 text-center">
                            <div class="counter_box text-center">
                                <span class="counter">192</span>
                                <h5>projects completed</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search">
                    <input type="" placeholder="Enter Search Keyword..." name="">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <div class="col-md-3">
                <img src="{{elixir('assets/img/bg/camera.jpg')}}">
            </div>
        </div>
    </div>
    <div class="portfolio">
        <div class="container-fluid">
            <div class="wall--post--holder text-center" data-url="{{url('/get-users')}}">
                <div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="fresh_________load"></div>
        </div>
    </div>
@stop
@section('scripts') @if(Auth::user())
    <script>
        var auth = true;
        const authUser = {
            id: "{{Auth::user()->id}}",
            fullname: "{{Auth::user()->fullname}}",
            username: "{{Auth::user()->username}}",
            role: "{{Auth::user()->role->title}}",
            phone: "{{Auth::user()->phone}}",
            path: "{{Auth::user()->path}}",
            rating: "{{Auth::user()->rating}}",
            is_verified: "{{Auth::user()->is_verified}}",
            is_blocked: "{{Auth::user()->is_blocked}}"
        }
    </script>
    @else
    <script>
        var auth = false;
    </script>
    @endif

    <script src="{{elixir('/assets/js/welcome.js')}}"></script>
@stop