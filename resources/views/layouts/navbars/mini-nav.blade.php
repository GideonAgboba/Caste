@if(Auth::user())
@if(Auth::user()->role->title == 'customer')
<div class="booking-shell down">
    <div class="control" onclick="$(this).parents('.booking-shell').first().toggleClass('up'); $(this).parents('.booking-shell').first().toggleClass('down'); $(this).find('i.fa').toggleClass('fa-list'); $(this).find('i.fa').toggleClass('fa-close');">
        <span class="booking--counter">{{Auth::user()->booking->count()}}</span>
        <i class="fa fa-list"></i>
    </div>
    <div class="link">
        <div class="list">
            @php $bookings = Auth::user()->booking; @endphp
            @if($bookings->count() > 0)
                @foreach($bookings as $booking)
                    @if($booking->type == 'user')
                    @php $user = $booking->booking; @endphp
                    <div class="media" ondblclick="Main.Booking.deleteBookingItem(this);" data-booking-user-id="{{$user->id}}" data-id="{{$booking->id}}" data-url="{{url('/delete-booking')}}">
                        @if($user->role->title == 'model' && $user->path == 'user.png')
                        <img src="{{elixir('model.png')}}">
                        @elseif($user->role->title == 'actor' && $user->path == 'user.png')
                        <img src="{{elixir('actor.png')}}">
                        @else
                        <img src="{{elixir('')}}{{$user->path}}">
                        @endif
                        <div class="media-content pl-2">
                            <p>{{$user->fullname}} <i class="fa fa-trash"></i></p>
                            <span>{{$user->role->title}}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
            <div style="text-align: center;">
                <small style="font-size: 10px">No booking</small>
            </div>
            @endif
        </div>
        @if($bookings->count() > 0)
        <ul>
            <li><a onclick="Main.Booking.deleteAllBookingItem(this);" data-url="{{url('/delete-all-booking')}}">Clear All</a></li>
            <li><a onclick="Main.Booking.checkout(this);" data-url="{{url('/booking-checkout')}}">Checkout</a></li>
        </ul>
        @else
        <ul>
            <li><a style="pointer-events: none;filter: brightness(0.5);" class="text-muted">Clear All</a></li>
            <li><a style="pointer-events: none;filter: brightness(0.5);" class="text-muted">Checkout</a></li>
        </ul>
        @endif
    </div>
</div>
@endif
<div class="auth-shell down">
    <div class="link">
        <div class="media">
            @if(Auth::user()->role->title == 'model' && Auth::user()->path == 'user.png')
            <img src="{{elixir('model.png')}}">
            @elseif(Auth::user()->role->title == 'actor' && Auth::user()->path == 'user.png')
            <img src="{{elixir('actor.png')}}">
            @else
            <img src="{{elixir('')}}{{Auth::user()->path}}">
            @endif
            <div class="media-content pl-2">
                <p>{{Auth::user()->fullname}}</p>
                <span>{{Auth::user()->role->title}}</span>
                <div style="margin-top: -16px;"></div>
                @if(Auth::user()->role->title != 'customer')
                <small>Profile rating: {{Auth::user()->rating.'%'}}</small>
                @endif
            </div>
        </div>
        <br>
        <ul>
            <li><a href="{{url('')}}/{{Auth::user()->username}}">Visit Profile</a></li>
            <li><a href="{{url('/logout')}}">Logout</a></li>
        </ul>
    </div>
    <div class="control" onclick="$(this).parents('.auth-shell').first().toggleClass('up'); $(this).parents('.auth-shell').first().toggleClass('down'); $(this).find('i.fa').toggleClass('fa-angle-down'); $(this).find('i.fa').toggleClass('fa-angle-up');">
        <i class="fa fa-angle-down"></i>
    </div>
</div>
@endif