@extends('layouts.app')
@section('title', ucfirst($user->username))
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{elixir('/assets/css/profile.css')}}">
@stop
@section('content')
    <div class="blog">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-12 col-12">
                <div class="sidebar">
                    @if($user->role->title != 'customer')
                    <div class="widget widget_categories">
                        <h4 class="widget-title">
                            ATTRIBUTES
                        </h4>
                        <ul>
                            <li>
                                <a><strong>Gender:</strong> <span class="float-right">{{ucwords($user->gender)}}</span></a>
                            </li>
                            <li>
                                <a><strong>Height:</strong> <span class="float-right">{{ucwords($user->height)}}</span></a>
                            </li>
                            <li>
                                <a><strong>Weight (KG):</strong> <span class="float-right">{{ucwords($user->weight)}}</span></a>
                            </li>
                            <li>
                                <a><strong>Shirt Size:</strong> <span class="float-right">{{ucwords($user->shirt_size)}}</span></a>
                            </li>
                            <li>
                                <a><strong>Waist Size (Inches):</strong> <span class="float-right">{{ucwords($user->waist_size)}}</span></a>
                            </li>
                            <li>
                                @php error_reporting(0); @endphp
                                <a><strong>Nationality:</strong> <span class="float-right">{{ucwords($user->country->name)}}</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget widget_instagram">
                        <h4 class="widget-title">
                            GALLERY 
                            @if(Auth::user() && Auth::user()->username == $user->username)
                            <i class="ion ion-camera" style="font-size: 30px !important;cursor: pointer;transform: translate(1px, 5px);" data-target="#uploadModal" data-toggle="modal"></i>
                            @endif
                        </h4>
                        <div class="gallery--container" data-url="{{url('/get-user-gallery')}}/{{$user->username}}">
                            <div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                    <div class="widget widget_tags">
                        <h4 class="widget-title">
                            SOCIAL MEDIAS
                        </h4>
                        <ul>
                            @forelse($user->social as $social)
                            <li><a target="_blank" href="{{$social->url}}">{{$social->title}}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    @else
                    <div class="widget widget_categories">
                        <h4 class="widget-title">
                            CHECKOUTS
                        </h4>
                        <div id="accordion" role="tablist">
                            @forelse($user->checkout as $checkout)
                            @php $count = count($checkout->bookings) @endphp
                            <div class="card accordion_card">
                                <div class="card-header" role="tab" id="headingOne">
                                    <h5>
                                        <a data-toggle="collapse" href="#collapseOne{{$checkout->id}}" role="button" aria-expanded="true" aria-controls="collapseOne{{$checkout->id}}">
                                            {{$count}} @if($count > 1) items were @else item was @endif requested - @if($checkout->is_verified == false) <span style="color: orange;">Pending</span> @else <span style="color: green;">Completed</span> @endif
                                            <p class="text-muted" style="margin: 0px;font-size: 10px;">{{$checkout->created_at->diffForHumans()}}</p>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseOne{{$checkout->id}}" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                    @foreach($checkout->bookings as $booking)
                                        @php $bookingUser = App\User::find($booking['id']); @endphp
                                        @if($booking['type'] == 'user')
                                        <div class="media mb-2">
                                            <div class="media-content">
                                                <h5 style="margin: 0px;">{{$bookingUser->fullname}}</h5>
                                                <small>{{$bookingUser->role->title}} - {{$bookingUser->gender ?? 'unknown'}}</small>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            @empty
                                <small style="color: red;">No checkout made</small>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-12">
                <article class="blog_card">
                    <div class="blog_card_top">
                        @if($user->role->title == 'model' && $user->path == 'user.png')
                        <div data-url="{{elixir('model.png')}}" class="profile__img" style="background: url('{{elixir('model.png')}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;height: 370px;"></div>
                        @elseif($user->role->title == 'actor' && $user->path == 'user.png')
                        <div data-url="{{elixir('actor.png')}}" class="profile__img" style="background: url('{{elixir('actor.png')}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;height: 370px;"></div>
                        @else
                        <div data-url="{{elixir('')}}{{$user->path}}" class="profile__img" style="background: url('{{elixir('')}}{{$user->path}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;height: 370px;"></div>
                        @endif

                        @if(Auth::user() && Auth::user()->username == $user->username)
                        <div class="blog_date" onclick="window.location = `{{url('/settings')}}`">
                            <i class="ion ion-settings"></i>
                            <span>
                                Edit
                            </span>
                        </div>
                        @else
                        <div class="blog_date" data-id="{{$user->id}}" data-type="user" data-url="{{url('/add-to-booking')}}" onclick="Main.Booking.addItemToCart(this);">
                            <i class="ion ion-briefcase"></i>
                            <span>
                                Hire
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="blog_card_bottom">
                        <h4>
                            <a href="#">
                                {{ucwords($user->fullname)}}
                            </a>
                        </h4>
                        <div class="meta_data">
                            <span>{{ucfirst($user->role->title)}}</span>
                            <span>
                                @if($user->isOnline() == false)
                                <m>
                                   <i class="text-muted fa fa-circle"></i> Last seen {{$user->last_seen->diffForHumans()}}
                                </m>
                                @else
                                <m>
                                    <i class="text-success fa fa-circle"></i> Online
                                </m>
                                @endif
                            </span>
                            @if($user->role->title != 'customer')
                            <span>
                                <m style="width: fit-content;">
                                    @if($user->rating >= 20)
                                    <m><i class="text-warning fa fa-star"></i></m>
                                    @else
                                    <m><i class="text-muted fa fa-star"></i></m>
                                    @endif
                                    @if($user->rating >= 40)
                                    <m><i class="text-warning fa fa-star"></i></m>
                                    @else
                                    <m><i class="text-muted fa fa-star"></i></m>
                                    @endif
                                    @if($user->rating >= 60)
                                    <m><i class="text-warning fa fa-star"></i></m>
                                    @else
                                    <m><i class="text-muted fa fa-star"></i></m>
                                    @endif
                                    @if($user->rating >= 80)
                                    <m><i class="text-warning fa fa-star"></i></m>
                                    @else
                                    <m><i class="text-muted fa fa-star"></i></m>
                                    @endif
                                    @if($user->rating >= 100)
                                    <m><i class="text-warning fa fa-star"></i></m>
                                    @else
                                    <m><i class="text-muted fa fa-star"></i></m>
                                    @endif
                                </m>
                            </span>
                            @endif
                        </div>
                        <p>
                            {{$user->bio}}
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
@stop
@section('modals')
<!-- upload modal -->
<div id="uploadModal" class="modal fade upload-modal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form enctype="multipart/form-data" action="{{url('/save-gallery-images')}}" class="modal-body" onsubmit="event.preventDefault();">
                @csrf
                <input type="file" name="path[]" class="gallery--file--input" data-url="{{url('/save-gallery-images')}}" multiple accept="image/*" onchange="Profile.galleryFilesPreview(this);" style="display: none;">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <span class="text-center" onclick="Profile.triggergalleryFileInput();" style="cursor: pointer;">
                            <i class="ion ion-images" style="font-size: 200px"></i>
                            <br>
                            <span>Select Images</span>
                        </span>
                    </div>
                    <div class="col-md-9">
                        <div class="gallery--file--preview" style="overflow: auto;overflow-x: hidden !important;height: 85vh;"></div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="Profile.saveGalleryImages(this);">Upload Images</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
    <script>
        const user = {
            id: "{{$user->id}}",
            fullname: "{{$user->fullname}}",
            username: "{{$user->username}}",
            role: "{{$user->role->title}}",
            phone: "{{$user->phone}}",
            path: "{{$user->path}}",
            rating: "{{$user->rating}}",
            is_verified: "{{$user->is_verified}}",
            is_blocked: "{{$user->is_blocked}}"
        }
    </script>
    @if(Auth::user())
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
    <script src="{{elixir('/assets/js/profile.js')}}"></script>
@stop