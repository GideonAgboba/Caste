@extends('mails.app')
@section('placeholder') Welcome | {{ config('app.name', 'NgCast') }} @endsection
@section('content')
	<div style="Margin-left: 30px;Margin-right: 30px;Margin-top: 24px;Margin-bottom: 24px;">
      <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;text-align: center;">
      	<h2 style="Margin-top: 16px;Margin-bottom: 10;font-style: normal;font-weight: normal;color: #000;font-size: 22px;line-height: 31px;text-align: center;"><strong>
      		Welcome! {{$username}}
      	</strong></h2>
      	<!-- <img src="{{env('APP_URL').'/assets/images/404.png'}}"> -->
      	<img src="{{'/assets/images/404.png'}}" style="margin: 0 auto;width: 200px;height: auto;">
      	<h3 style="Margin-top: 16px;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #000;font-size: 16px;line-height: 24px;text-align: center;">
      		<small>
      			{{ config('app.name', 'NgCast') }} is an online platform that offers actors and models an online presence for marketing.
      		</small>
      	</h3>
      </div>
    </div>

	<div style="Margin-left: 30px;Margin-right: 30px;Margin-top: 20px;">
	  <div style="mso-line-height-rule: exactly;mso-text-raise: 4px;">
		<div style="Margin-left: 30px;Margin-right: 30px;Margin-top: 30px;">
			<div class="btn btn--ghost btn--large" style="Margin-bottom: 20px;text-align: center;">
			    <a style="border-radius: 0;display: inline-block;font-size: 14px;font-weight: bold;line-height: 24px;padding: 12px 24px;text-align: center;text-decoration: none !important;transition: opacity 0.1s ease-in;
			    border: 1.5px solid #d1005b;
			    background: linear-gradient(to right, #fb8400, #d1005b) 1 1;
			    background-image: linear-gradient(79deg, #fb8400, #d1005b 60%, #d1005b);
			    background-position-x: initial;
			    background-position-y: initial;
			    background-size: initial;
			    background-repeat-x: initial;
			    background-repeat-y: initial;
			    background-attachment: initial;
			    background-origin: initial;
			    background-clip: initial;
			    background-color: initial;
			    color: transparent;
			    background-clip: text;
			    -webkit-background-clip: text;
			    text-fill-color: transparent;
			    width: -webkit-fill-available;
			    -webkit-text-fill-color: transparent;" href="{{url('/')}}">Visit Platform</a>
			</div>
		</div>
	  </div>
	</div>
@endsection