<?php
$segment1 = Request::segment(1);
?>

<nav id="sidebar" class="">
    <div class="custom-menu">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu </span>
        </button>
    </div>
    <div class="p-3">
        <a href="{{route('frontend.home')}}" class="nav-brand"><img class="logo"
                                                                    src="{{asset('images/dash-brand-logo.png')}}"></a>
    </div>
    <div class="menu">
        <ul class="list-unstyled components">
            <li class="{{$segment1 == 'dashboard' ? 'active' : ''}}">
                <a href="{{route('frontend.user.dashboard')}}"><span class="fa fa-tachometer mr-3"></span>{{__('Dashboard')}}</a>
            </li>
            <li class="{{$segment1 == 'qr-codes' ? 'active' : ''}}">
                <a href="{{route('frontend.user.qr-codes.index')}}"><span class="fa fa-qrcode mr-3"></span>{{__('QR Codes')}}
                    <span
                        class="stats">{{ getCount('generate_qr_codes', array('user_id' => auth()->user()->id,'archive' => 0,'template'=>0)) }}</span></a>
            </li>
            @if(checkFieldStatus(4))
                <li class="{{$segment1 == 'campaigns' ? 'active' : ''}}">
                    <a href="{{route('frontend.user.campaigns.index')}}"><span class="fa fa-folder-o mr-3"></span>{{__('Campaigns')}}
                        <span
                            class="stats">{{ getCount('campaigns', array('user_id' => auth()->user()->id)) }}</span></a>
                </li>
            @endif
            @if(checkFieldStatus(12))
                <li class="{{$segment1 == 'bulk-import' ? 'active' : ''}}">
                    <a href="{{route('frontend.user.bulk-import.select.content.type')}}"><span
                            class="fa fa-upload mr-3"></span>{{__('Bulk Import')}} </a>
                </li>
            @endif
        </ul>
        {{--        {{ dd(getCount('generate_qr_codes', array('archive' => 1))) }}--}}
        <ul class="list-unstyled components menu-secondry mb-5">
            <li class="{{$segment1 == 'archive' ? 'active' : ''}}">
                <a href="{{route('frontend.user.archive.index')}}"><span class="fa fa-trash mr-3"></span>{{__('Archive')}} <span
                        class="stats">{{ getCount('generate_qr_codes' , array('archive' => 1,'user_id'=>\Illuminate\Support\Facades\Auth::id())) }}</span></a>
            </li>
            <li class="{{($segment1 == 'account' || $segment1 == 'setting' || $segment1 == 'subscriptions' || $segment1 == 'invoices') ? 'active' : ''}}">
                <a href="{{route('frontend.user.account')}}"><span class="fa fa-user mr-3"></span>{{__('Account')}}</a>
            </li>
            <li class="{{$segment1 == 'supports'  ? 'active' : ''}}">
                <a href="{{route('frontend.user.support')}}"><span class="fa fa-question-circle mr-3"></span>{{__('Support')}}</a>
            </li>
        </ul>
    </div>
</nav>
