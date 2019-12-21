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
                    <div class="widget widget_categories">
                        <h4 class="widget-title">
                            UPDATE PROFILE IMAGE
                        </h4>
                        <div class="profile-update">
                            @if($user->role->title == 'model' && $user->path == 'user.png')
                            <div data-url="{{elixir('model.png')}}" class="profile__img__" style="background: url('{{elixir('model.png')}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;"></div>
                            @elseif($user->role->title == 'actor' && $user->path == 'user.png')
                            <div data-url="{{elixir('actor.png')}}" class="profile__img__" style="background: url('{{elixir('actor.png')}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;"></div>
                            @else
                            <div data-url="{{elixir('')}}{{$user->path}}" class="profile__img__" style="background: url('{{elixir('')}}{{$user->path}}');background-size: cover;background-repeat: no-repeat;min-width: 100%;height: 370px;"></div>
                            @endif
                            <i class="ion ion-camera" data-target="#uploadModal" data-toggle="modal"></i>
                        </div>
                    </div>
                    @if($user->role->title != 'customer')
                    <div class="widget widget_instagram">
                        <h4 class="widget-title">
                            EDIT GALLERY
                        </h4>
                        <div class="gallery--container" data-url="{{url('/get-user-gallery')}}/{{$user->username}}" data-delete-url="{{url('/delete-gallery-image')}}">
                            <div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                    @endif
                    <div class="widget widget_tags">
                        <h4 class="widget-title">
                            EXTRA
                        </h4>
                        <div class="form-check form-check-inline">
                          <label style="font-size: 12px;" for="inlineCheckbox1" class="form-check-label">Switch Account On/Off</label>
                          <br>
                          <input id="inlineCheckbox1" class="form-check-input" type="checkbox" data-toggle="toggle" data-style="mr-1" checked>
                        </div>
                        <br>
                        <button data-target="#deleteModal" data-toggle="modal" class="btn" style="background: #de0808;color: #fff;">DELETE ACCOUNT</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-12">
                <form class="profile__update__form" action="{{url('/profile-update')}}" method="POST" onsubmit="event.preventDefault(); Setting.updateProfile(this);">
                    @csrf
                    <h4 class="widget-title">
                        PROFILE UPDATE
                    </h4>
                    <div class="row justify-content-center">
                        <div class="col-lg-7 col-12">
                            <input type="text" placeholder="Fullname" name="fullname" value="{{$user->fullname}}" class="form-control" required>
                        </div>
                        <div class="col-lg-5 col-12">
                            <input type="text" placeholder="Username" disabled value="{{$user->username}}" class="form-control" required>
                        </div>
                        <div class="col-lg-6 col-12">
                            <input type="email" placeholder="E-Mail" disabled value="{{$user->email}}" class="form-control" required>
                        </div>
                        <div class="col-lg-6 col-12">
                            <input type="text" placeholder="Phone" name="phone" value="{{$user->phone}}" class="form-control" required>
                        </div>
                        <div class="col-lg-4 col-12">
                            <select type="text" name="gender" class="form-control" required>
                                <option value="">Gender</option>
                                <option @if($user->gender == 'male') selected @endif value="male">Male</option>
                                <option @if($user->gender == 'female') selected @endif value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-12">
                            <select type="text" name="country_id" class="form-control" required>
                                <option value="">Country</option>
                                @forelse(App\Country::all() as $country)
                                <option @if($user->country_id == $country->id) selected @endif value="{{$country->id}}">{{ucwords($country->name)}}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-lg-4 col-12">
                            <input type="text" placeholder="State" name="state" value="{{$user->state}}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <input type="text" placeholder="Address" name="address" value="{{$user->address}}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <textarea placeholder="Bio" name="bio" class="form-control" cols="4" rows="4">{{$user->bio}}</textarea>
                        </div>
                        @if($user->role->title != 'customer')
                        <div class="col-lg-3 col-12">
                            <input type="number" step=".1" placeholder="Height" name="height" value="{{$user->height}}" class="form-control" required>
                        </div>
                        <div class="col-lg-3 col-12">
                            <input type="number" placeholder="Weight (KG)" value="{{$user->weight}}" name="weight" class="form-control" required>
                        </div>
                        <div class="col-lg-3 col-12">
                            <input type="text" placeholder="Shirt Size" name="shirt_size" value="{{$user->shirt_size}}" class="form-control" required>
                        </div>
                        <div class="col-lg-3 col-12">
                            <input type="number" placeholder="Waist Size (Inches)" value="{{$user->waist_size}}" name="waist_size" class="form-control" required>
                        </div>
                        @if($user->role->title == 'actor')
                        <div class="col-12">
                            <select type="text" name="actor_type" class="form-control" required>
                                <option value="">Select Actor Category</option>
                                <option @if($user->actor_type == 'english') selected @endif value="english">English</option>
                                <option @if($user->actor_type == 'igbo') selected @endif value="igbo">Igbo</option>
                                <option @if($user->actor_type == 'hausa') selected @endif value="hausa">Hausa</option>
                                <option @if($user->actor_type == 'kid') selected @endif value="kid">Kid Actor</option>
                            </select>
                        </div>
                        @elseif($user->role->title == 'model')
                        <div class="col-12">
                            <select type="text" name="model_type" class="form-control" required>
                                <option value="">Select Model Category</option>
                                <option @if($user->model_type == 'beauty') selected @endif value="beauty">Beauty</option>
                                <option @if($user->model_type == 'runway') selected @endif value="runway">Runway</option>
                            </select>
                        </div>
                        @elseif($user->role->title == 'crew')
                        <div class="col-lg-6 col-12">
                            <select type="text" name="crew_type" class="form-control" onchange="Setting.populateCrewCategoryInfo(this);" required>
                                <option value="">Select Crew Category</option>
                                <option @if($user->crew_type == 'production') selected @endif value="production">Production</option>
                                <option @if($user->crew_type == 'technical') selected @endif value="technical">Technical</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-12">
                            <select type="text" name="crew_type_info" data-old="{{$user->crew_type_info}}" class="form-control" required>
                                <option value="">Select Crew Category Info</option>
                                @if($user->crew_type_info != '')
                                <option selected value="{{$user->crew_type_info}}">{{ucwords($user->crew_type_info)}}</option>
                                @endif
                            </select>
                        </div>
                        @endif
                        @endif
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-right">Update</button>
                        </div>
                    </div>
                </form>
                @if($user->role->title != 'customer')
                <br><br>
                <form class="social__link__form" action="{{url('/add-social-link')}}" method="POST" onsubmit="event.preventDefault(); Setting.addSocialLink(this);">
                    @csrf
                    <h4 class="widget-title">
                        SOCIAL UPDATE
                    </h4>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <input type="text" placeholder="Title" name="title" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <input type="text" placeholder="Url" name="url" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-right">Add</button>
                        </div>
                    </div>
                    <div class="widget widget_tags">
                        <ul>
                            @forelse($user->social as $social)
                            <li><a onclick="Setting.deleteSocialLink('{{$social->id}}', this);" data-url="{{url('/delete-social-link')}}"><i class="fa fa-trash"></i> {{$social->title}}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
@stop
@section('modals')
<!-- upload modal -->
<div id="uploadModal" class="modal fade upload-modal" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form enctype="multipart/form-data" action="{{url('/save-profile-image')}}" class="modal-body" onsubmit="event.preventDefault();">
                @csrf
                <input type="file" name="path" class="gallery--file--input" data-url="{{url('/save-profile-image')}}" accept="image/*" onchange="Setting.galleryFilesPreview(this);" style="display: none;">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <span class="text-center" onclick="Setting.triggergalleryFileInput();" style="cursor: pointer;">
                            <i class="ion ion-images" style="font-size: 200px"></i>
                            <br>
                            <span>Select Image</span>
                        </span>
                    </div>
                    <div class="col-md-9">
                        <div class="gallery--file--preview" style="overflow: auto;overflow-x: hidden !important;height: 85vh;"></div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="Setting.saveGalleryImages(this);">Upload Profile Image</button>
            </div>
        </div>
    </div>
</div>
<!-- delete modal -->
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" action="{{url('/account-delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <p>
                    Sure you want to delete this account?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-secondary">Yes</button>
            </div>
        </form>
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
    <script src="{{elixir('/assets/js/settings.js')}}"></script>
@stop