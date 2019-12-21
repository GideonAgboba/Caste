<div class="side--menu">
    <!--logo -->
    <div class="logo_box" style="position: sticky;top: 0px;background-color: #18181c;">
        <a href="#">
            <!-- <img src="" alt="NgCast"> -->
            <img src="{{elixir('logo.png')}}" alt="NgCast">
        </a>
    </div>
    <!--logo end-->

    <!--main menu -->
    <div class="side_menu_section">
        <ul class="menu_nav">
            <li class="@if(\Request::is('/')) active @endif">
                <a href="{{url('/')}}">
                    Home
                </a>
            </li>
            <li class="@if(\Request::is('about')) active @endif">
                <a href="{{url('/about')}}">
                    About Us
                </a>
            </li>
            <li class="@if(\Request::is('how-it-works')) active @endif">
                <a href="{{url('/how-it-works')}}">
                    How It Works
                </a>
            </li>
            <li class="@if(\Request::is('services')) active @endif">
                <a href="{{url('/services')}}">
                    Services
                </a>
            </li>
            <li class="@if(\Request::is('blog')) active @endif">
                <a href="{{url('/blog')}}">
                    Blog
                </a>
            </li>
            <li class="@if(\Request::is('contact')) active @endif">
                <a href="{{url('/contact')}}">
                    Contact
                </a>
            </li>
        </ul>
    </div>
    <!--main menu end -->

    <!--filter menu -->
    <div class="side_menu_section">
        <h4 class="side_title">Explore:</h4>
        <ul class="filter_nav">
            <li class="@if(\Request::is('actors') || \Request::is('actors/*')) active @endif"><a href="{{url('/actors')}}" >Actors</a></li>
            <li class="@if(\Request::is('models') || \Request::is('models/*')) active @endif"> <a href="{{url('/models')}}">Models</a></li>
            <li class="@if(\Request::is('crews') || \Request::is('crews/*')) active @endif"> <a href="{{url('/crews')}}">Crews</a></li>
            <li class="@if(\Request::is('equipments') || \Request::is('equipments/*')) active @endif"> <a href="{{url('/equipments')}}">Equipments</a></li>
            @if(Auth::guest())
            <li class="@if(\Request::is('login') || \Request::is('login/*')) active @endif"><a href="{{url('/login')}}">Login</a></li>
            <li class="@if(\Request::is('register') || \Request::is('register/*')) active @endif"><a href="{{url('/register')}}">Sign Up</a></li>
            @endif
        </ul>
    </div>
    <!--filter menu end -->

    @if(\Request::is('/'))
    <!--filter menu -->
    <div class="side_menu_section">
        <h4 class="side_title">Filter By:</h4>
        <ul  id="filtr-container"  class="filter_nav">
            <li  data-filter="*"><a href="javascript:void(0)">All</a></li>
            <li  data-filter=".actor"><a href="javascript:void(0)">Actors</a></li>
            <li data-filter=".model"> <a href="javascript:void(0)">Models</a></li>
            <li data-filter=".crew"> <a href="javascript:void(0)">Crews</a></li>
        </ul>
    </div>
    @endif
    <!--filter menu end -->

    <!--social and copyright -->
    <div class="side_menu_bottom">
        <div class="side_menu_bottom_inner">
            <ul class="social_menu">
                <li>
                    <a href="#"> <i class="ion ion-social-pinterest"></i> </a>
                </li>
                <li>
                    <a href="#"> <i class="ion ion-social-facebook"></i> </a>
                </li>
                <li>
                    <a href="#"> <i class="ion ion-social-twitter"></i> </a>
                </li>
                <li>
                    <a href="#"> <i class="ion ion-social-dribbble"></i> </a>
                </li>
            </ul>
            <div class="copy_right">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                <p class="copyright">NgCast &copy;<script>document.write(new Date().getFullYear());</script></p>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </div>
        </div>
    </div>
</div>