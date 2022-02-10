@extends('frontend.layouts.dashboard')

@section('title',__('Select Content Type'))

@section('content')
    <div class="content-body">
        <div class="comon-title section-title pt-3 pb-3 text-center">
            <h2 class="welcome">{{__('Select Content Type')}}</h2>
        </div>
        @include('frontend.messages')
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3 class="sub-title">{{__('Select Content Type')}}</h3>
                </div>
                <div class="tab-section">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="dynamic-tab" data-toggle="tab" href="#dynamic" role="tab"
                               aria-controls="dynamic" aria-selected="true">{{__('Dynamic')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="static-tab" data-toggle="tab" href="#static" role="tab"
                               aria-controls="static" aria-selected="false">{{__('Static')}}</a>
                        </li>
                    </ul>
                    <!-- Body -->
                    <div class="edit--compaigns tabs-content bulk-import-tabs" id="myTabContent">
                    <!-- <div class="bulk-import-tabs" id="myTabContent"> -->
                        <div class="tab-pane fade show active" id="dynamic" role="tabpanel"
                             aria-labelledby="dynamic-tab">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'url','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-link"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Url')}}</div>
                                                <div class="info">{{__('Link to a website')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'vcard','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-address-card"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('VCard')}}</div>
                                                <div class="info">{{__('Get vCard contact data')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'text','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Text')}}</div>
                                                <div class="info">{{__('Show a text when scanning')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'email','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-envelope-o"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Email')}}</div>
                                                <div class="info">{{__('Send an email')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'phone','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Phone')}}</div>
                                                <div class="info">{{__('Call a phone number.')}}</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'sms','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-commenting"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('SMS')}}</div>
                                                <div class="info">{{__('Send a SMS')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
{{--                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">--}}
{{--                                    <div class="cardbox">--}}
{{--                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'app-store','type'=> 'dynamic'])}}" class="content-type">--}}
{{--                                            <div class="icon">--}}
{{--                                                <i class="fa fa-apple"></i>--}}
{{--                                            </div>--}}
{{--                                            <div class="text">--}}
{{--                                                <div class="title">{{__('App Store')}}</div>--}}
{{--                                                <div class="info">{{__('Download an app from app stores')}}.</div>--}}
{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'event','type'=> 'dynamic'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Event')}}</div>
                                                <div class="info">{{__('Add an event to calendar')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-12 pt-4">
                                    <div class="alert alert-light persist-alert"> {{__('You miss a QR code content type? Write us at')}} <a
                                            href="mailto:{{settingValue('contact_email')}}">{{settingValue('contact_email')}} </a>{{__('and we will try to improve QR Code')}}!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="static" role="tabpanel" aria-labelledby="static-tab">

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-light persist-alert"> {{__('Static QR codes can not be edited after being downloaded or printed. Tracking scan statistics is only available for dynamic QR codes')}}.
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'url','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-link"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title ">{{__('Url')}}</div>
                                                <div class="info">{{__('Link to a website')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'vcard','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-address-card"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('VCard')}}</div>
                                                <div class="info">{{__('Get vCard contact data')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'text','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Text')}}</div>
                                                <div class="info">{{__('Show a text when scanning')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'email','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-envelope-o"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Email')}}</div>
                                                <div class="info">{{__('Send an email')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'phone','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Phone')}}</div>
                                                <div class="info">{{__('Call a phone number.')}}</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'sms','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-commenting"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('SMS')}}</div>
                                                <div class="info">{{__('Send a SMS')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'wifi','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-wifi"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('WiFi')}}</div>
                                                <div class="info">{{__('Scan and get WiFi access')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 ng-star-inserted">
                                    <div class="cardbox">
                                        <a href="{{route('frontend.user.bulk-import.index',['content_type' => 'event','type'=> 'static'])}}" class="content-type">
                                            <div class="icon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div class="text">
                                                <div class="title">{{__('Event')}}</div>
                                                <div class="info">{{__('Add an event to calendar')}}.</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

