
<div class=" content-head">
    <div class="titles">
        <h2 class="title">@yield('title')
        </h2>
    </div>
    <div class="account-information">
        <div class="user-info">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img
                        src="{{checkImage(asset('storage/users/'. Auth::user()->id.'/' . Auth::user()->profile_image),'avatar.png',Auth::user()->profile_image)}}"
                        class="user-image" alt="User Image">
                    <?php $fullname = auth()->user()->name;?>
                    {{ Str::limit($fullname, 10) }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <div class="account-info profile-account">
                        <div class="account-fact custom-border">
                            <div class="user-title pr-2 cursor-point"> {{__('Account')}}:</div>
                            <p class="value cursor-point text-control" title="{{auth()->user()->email}}"> {{auth()->user()->email}}
                            </p>
                        </div>
                        <div class="dynamicqrcode">
                            <div class="account-fact custom-border">
                                <div class="user-title pr-2 cursor-point account-fact custom-border"> {{__('Dynamic QR Codes')}}:</div>
                                <p class="value cursor-point" id="auth-user-dynamic-qr-code"> {{ getSubscriptionFeatureCount(1) - auth()->user()->dynamic_qr_codes }}/{{!empty(getSubscriptionFeatureCount(1)) ? getSubscriptionFeatureCount(1) : ''}}</p>
                            </div>
                            <div class="account-fact custom-border">
                                <div class="user-title pr-2 cursor-point">{{__('Current Plan')}}:</div>
                                <p class="value cursor-point" id="user-package-name">{{isset(auth()->user()->subscription) ? auth()->user()->package->title : 'None'}}</p>
                            </div>
                            <div class="account-fact custom-border">
                                <p class="user-title pr-2 cursor-point"> {{__('Plan Expiry')}}:</p>

                                @if(auth()->user()->on_trial == 1)
                                    @php
                                        $date =\Carbon\Carbon::parse(auth()->user()->subscription->end_date)->diffInDays(\Carbon\Carbon::now()) .' '. __('Day').'(s)';
                                    @endphp

                                    <p class="value" id="user-package-expiry">{{isset(auth()->user()->subscription) ? $date : '0 '.__('Day')}} {{__('Left')}}</p>
                                @elseif(auth()->user()->package_id == 2)
                                    <p class="value" id="user-package-expiry">{{__('Lifetime')}}</p>
                                @else
                                    <p class="value" id="user-package-expiry">{{ \Carbon\Carbon::createFromTimeStamp(auth()->user()->subscription->end_date, "UTC")->format('d M, Y') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="upgrade text-center profile-btns d-flex flex-column">
                            <a class="btn btn-orange" href="{{route('frontend.user.upgrade.package')}}"><i
                                    class="fa fa-star"></i> {{__('Subscribe Now')}}</a>
                            <a class="btn btn-orange mt-1" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> {{__('Log Out')}}</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="header-parent"> -->
    <div class="container">
            @if(auth()->user()->package_updated_by_admin)
                <div class="alert alert-success persist-alert text-left alert_header">
                    {{__('Your package has been updated by administrator.')}}
                    <a href="{{ url('/update-package-by-admin-flag') }}">
                        <!-- <button type="button" class="btn btn-sm btn-primary ml-auto" class="close">Close</button> -->
{{--                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
                            <span aria-hidden="true" class="fa fa-close float-right"></span>
{{--                        </button>--}}
                    </a>
                </div>
            @elseif(auth()->user()->unpaid_package_email_by_admin)
                <div class="alert alert-success persist-alert text-left alert_header">
                    {{__('Please check your email for payment process against package updated by administrator.')}}
                    <a href="{{ url('/unpaid-package-email-by-admin-flag') }}">
{{--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
                        <span aria-hidden="true" class="fa fa-close float-right"></span>
{{--                    </button>--}}
                        <!-- <button type="button" class="btn btn-sm btn-primary ml-auto" class="close">Close</button> -->
                    </a>
                </div>
            @elseif(auth()->user()->expired_package_disclaimer)
                <div class="alert alert-danger persist-alert text-left alert_header">
                    {{__('Your plan has been expired. Please upgrade your plan.')}}
                    <a href="{{ url('/expired-package-disclaimer-flag') }}">
{{--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
                        <span aria-hidden="true" class="fa fa-close float-right"></span>
{{--                    </button>--}}
                        <!-- <button type="button" class="btn btn-sm btn-primary ml-auto" class="close">Close</button> -->
                    </a>
                </div>
            @endif
    </div>
 <!-- <div>    -->
