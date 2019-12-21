<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>500 | {{ config('app.name', 'NgCast') }}</title>
    <!-- Bootstrap CSS -->
    <link href="{{elixir('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Fonts Icon CSS -->
    <link href="{{elixir('assets/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{elixir('assets/css/main.css')}}" rel="stylesheet">
    <style type="text/css">
        .error-center{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;
        }
        .error-center img{
            width: 700px;
            height: auto;
        }
        .error-center h1{
            font-size: 100px !important;
            margin-bottom: 0px;
        }
        .error-center p{
            margin-top: 0px;
        }
        .error-center a{
        }
    </style>
</head>
<body>
    <div id="app" data-token="{{csrf_token()}}" data-url="{{url('')}}" data-elixir="{{elixir('')}}">
        <div class="error-center">
            <div class="row container-fluid">
                <div class="col-md-6">
                    <img src="{{elixir('assets/img/bg/500.png')}}">
                </div>
                <div class="col-md-6 text-center">
                    <h1>500</h1>
                    <p>
                        Internal Server Error
                    </p>
                    <button onclick="history.back();" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Go Back</button>
                    <br>
                    <a href="{{url('/')}}">Click to visit homepage</a>
                </div>
            </div>
        </div>
</body>
</html>
