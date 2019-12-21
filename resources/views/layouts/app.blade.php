<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'NgCast') }}</title>
    <!-- Bootstrap CSS -->
    <link href="{{elixir('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Fonts Icon CSS -->
    <link href="{{elixir('assets/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{elixir('assets/css/et-line.css')}}" rel="stylesheet">
    <link href="{{elixir('assets/css/ionicons.min.css')}}" rel="stylesheet">
    <!-- Carousel CSS -->
    <link href="{{elixir('assets/css/slick.css')}}" rel="stylesheet">
    <!-- Magnific-popup -->
    <link rel="stylesheet" href="{{elixir('assets/css/magnific-popup.css')}}">
    <!-- Alertify Css -->
    <link rel="stylesheet" href="{{elixir('assets/css/alertify.css')}}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{elixir('assets/css/animate.min.css')}}">
    <!-- Custom styles for this template -->
    <link href="{{elixir('assets/css/main.css')}}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div id="app" data-token="{{csrf_token()}}" data-url="{{url('')}}" data-elixir="{{elixir('')}}">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
        </div>

        <div class="body-container container-fluid">
            <a class="menu-btn" href="javascript:void(0)">
                <i class="ion ion-grid"></i>
            </a>
            <div class="row justify-content-center">
                <!--=================== side menu ====================-->
                <div class="col-lg-2 col-md-3 col-12 menu_block">
                    @include('layouts.navbars.sidemenu')
                </div>
                <!--=================== side menu end====================-->

                <!--=================== content body ====================-->
                <div class="col-lg-10 col-md-9 col-12 body_block  align-content-center">
                    <div class="body--content" style="position: sticky;top: 5px;">
                        @yield('content')
                    </div>
                </div>
                <!--=================== content body end ====================-->
            </div>
        </div>
    </div>

    @include('layouts.navbars.mini-nav')


    @yield('modals')
    <!-- jquery -->
    <script src="{{elixir('assets/js/jquery.min.js')}}"></script>
    <!-- bootstrap -->
    <script src="{{elixir('assets/js/popper.js')}}"></script>
    <script src="{{elixir('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{elixir('assets/js/waypoints.min.js')}}"></script>
    <script data-pace-options='{ "ajax": false }' src="{{elixir('assets/js/pace.js')}}"></script>
    <script src="{{elixir('assets/js/alertify.js')}}"></script>
    <!--slick carousel -->
    <script src="{{elixir('assets/js/slick.min.js')}}"></script>
    <!--Portfolio Filter-->
    <script src="{{elixir('assets/js/imgloaded.js')}}"></script>
    <script src="{{elixir('assets/js/isotope.js')}}"></script>
    <!-- Magnific-popup -->
    <script src="{{elixir('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <!--Counter-->
    <script src="{{elixir('assets/js/jquery.counterup.min.js')}}"></script>
    <!-- WOW JS -->
    <script src="{{elixir('assets/js/wow.min.js')}}"></script>
    <!-- Custom js -->
    <script src="{{elixir('assets/js/main.js')}}"></script>
    @yield('scripts')
</body>
</html>
