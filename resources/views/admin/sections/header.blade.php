<nav class="navbar navbar-default navbar-fixed-top admin-pannel-styling">
    <div class="brand logo-sty">
        <a href="{{route('admin.dashboard')}}">
            <img src="{{ asset('images/favicon.png') }}" alt="logo" class="img-responsive logo"><span>QR Code</span>
        </a>
        <div id="tour-fullwidth" class="navbar-btn-togl">
            <button type="button" class="btn-toggle-fullwidth"><i class="ti-arrow-circle-left"></i></button>
        </div>
    </div>
    <div class="right-menu-bar">
        <div id="navbar-menu" class="navbar-menu head-sec-des">
            <div class="heading hidden-xs">
                <h1 class="page-title">@yield('title')</h1>
                <p class="page-subtitle">@yield('sub-title')</p>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <!-- @php
                    $notifications = getNotifications(NULL,-1,1);
                    $notification_counter = count(getNotifications(NULL,0,1));
                    $class='block';
                @endphp

                @if(have_right(106))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown" style="color: gray;">
                            <i class="ti-bell"></i>
                            @if($notification_counter > 0)
                                <span class="badge bg-danger">{{ $notification_counter }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu">
                        <div class="noti-head-sett">You have {{ $notification_counter }} new notifications</div>
                        <ul class="notifications not-list-sett">
                            @foreach($notifications as $key => $notification)
                                @if($key > 4)
                                    @php
                                        $class = 'none';
                                    @endphp
                                @endif
                                <li style="display:{{$class}}">
                                    <a href="{{ url($notification->link.'?notification_id='.$notification->id) }}" class="notification-item" style="background:{{ ($notification->is_read == 0) ? '#e4edfc' : '' }}">
                                        <i class="{{ $notification->fa_class }}"></i>
                                        <p>
                                            @php $message = str_replace("[name]" , $notification->user->name , $notification->message ) @endphp

                                            <span class="text">{{ $message }}</span>
                                            <span class="timestamp">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at), "UTC")->diffForHumans() }}</span>
                                        </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if(count($notifications) > 5)
                        <div class="last-child-noti">
                                <a href="javascript:void(0)" class="more" data="0">Show all</a>
                        </div>
                        @endif
                        </div>
                    </li>
                @endif -->

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle user-status-div" data-toggle="dropdown">
                        <div class="user-name-sty">
                            <span>{{ Auth::user()->name }}</span>
                            <!-- <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.2px" height="8.399px" viewBox="0 0 13.2 8.399" enable-background="new 0 0 13.2 8.399" xml:space="preserve">
							<polygon fill="#A4AFB7" points="6.601,8.399 0,1.729 1.711,0 6.601,4.94 11.489,0 13.2,1.729 "></polygon>
							</svg> -->
                        </div>
                        <div class="user-img-sty">
                            <img src="{{checkImage(asset('storage/admins/profile-images/' . Auth::user()->profile_image),'avatar.png',Auth::user()->profile_image)}}"
                                alt="Avatar">
                        </div>

                    </a>
                    <ul class="dropdown-menu logged-user-menu">
                        @if(have_right(75))<li><a href="{{ route('admin.profile') }}"><i class="ti-user"></i> <span>My
                                    Profile</span></a></li>@endif
                        <!-- @if(have_right(92))<li><a href="{{ route('admin.settings') }}"><i class="ti-settings"></i> <span>Settings</span></a></li>@endif -->
                        <li>
                            <a href="{{ route('admin.auth.logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="ti-power-off"></i> <span>Logout</span>
                            </a>
                        </li>

                        <form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST"
                            style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </ul>
                </li>
                <li class="xs-visi-btn">
                    <button class="navicon navbar-toggler btn-toggle-fullwidth" type="button" id="tour-fullwidth">
                        <div class="navicon__holder">
                            <div style="display:inline-block">
                                <div class="navicon__line"></div>
                                <div class="navicon__line"></div>
                                <div class="navicon__line"></div>
                            </div>
                        </div>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
