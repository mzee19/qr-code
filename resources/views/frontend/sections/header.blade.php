<?php
$segment_1 = Request::segment(1);
$segment_2 = Request::segment(2);
?>
<header class="main-header fixed-top ">
    <div class="container">
        <nav class="navbar navbar-expand-lg align-items-center">
            <a class="navbar-brand" href="{{route('frontend.home')}}">
                <img src="{{ asset('images/brand-logo.png') }}" alt="brand logo">
            </a>
            <button class="navbar-toggler nav-button" type="button" data-toggle="collapse" data-target="#custom-navbar"
                    aria-controls="custom-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
            </button>
            <!-- End of .navbar-brand -->

            <div class="collapse navbar-collapse" id="custom-navbar">
                <ul class="navbar-nav ml-auto align-items-center">
                    <!-- <li class="nav-item header-buttons pricing-button">
                        <a href="#" class="nav-link">{{__('Pricing')}}</a>
                    </li> -->
                    @if (Auth::check())
                        <li class="nav-item header-buttons">
                            <a href="{{route('frontend.user.dashboard')}}" class="nav-link">{{__('Dashboard')}}</a>
                        </li>
                    @else
                        <li class="nav-item header-buttons {{$segment_1 == 'login' ? 'signup-button' : ''}}">
                            <a href="{{route('login')}}" class="nav-link">{{__('Login')}}</a>
                        </li>
                        <li class="nav-item header-buttons {{$segment_1 == 'register' ? 'signup-button' : ''}}">
                            <a href="{{route('register')}}" class="nav-link">{{__('Sign up')}}</a>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- End of .navbar-nav -->
        </nav>
    </div>
</header>
