@extends('frontend.layouts.app')

{{--@section('title', __('Home'))--}}

@section('content')
    <style>
        /* Always set the map height explicitly to define the size of the div
           * element that contains the map. */
          #map {
                height: 433px;
                width: 100%;
                display: inline-block;
            }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }
    </style>

    <section class="header-section">
        <div class="banner">
            <div class="container-fluid">
                <!-- Header Section -->
                <header class="header">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand-logo" href="{{route('frontend.home')}}">
                            <img src="{{asset('images/brand-logo.svg')}}" alt="brand logo">
                        </a>
                        <div class="right-side">
                            <div class="language-bar">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary dropdown-toggle"data-toggle="dropdown">
                                        <img src="{{asset('images/flag/'.App::getLocale().'.svg')}}"
                                            height="30px" width="30px"style="border-radius:50%;"> {{ App::getLocale() }}
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach($languages as $language)
                                        <a class="dropdown-item " href="{{ url('lang/'.$language->code) }}">
                                        <span class="lang-name">{{ucwords($language->name)}}</span>
                                        <img src="{{asset('images/flag/'.$language->code.'.svg')}}"height="30px">
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                             </div>
                            &nbsp; &nbsp;
                            @auth
                                <a href="{{route('frontend.user.dashboard')}}" class="get-started button">
                                    {{__('Dashboard')}}
                                </a>
                            @endauth

                            @guest
                                <a href="{{route('register')}}" class="get-started button">
                                    {{__('Get Started')}}
                                </a>
                            @endguest
                            <button class="nav-button" type="button" data-toggle="collapse"
                                    data-target="#navbarToggleExternalContent"><i class="fa fa-bars"></i>
                            </button>
                            <div class="custom-navbar-container">
                                <div class="collapse" id="navbarToggleExternalContent">
                                    <div class="bg-dark-orange">
                                        <ul class="navbar-nav flex-column">
                                            <li class="nav-item active">
                                                <a class="nav-link" href="#how-it-work">{{__('How it Works')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#faqs">{{__('FAQs')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="#about-us">{{__('About Us')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   href="{{route('frontend.contact')}}">{{__('Contact Us')}}</a>
                                            </li>
                                            @auth
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('logout') }}"
                                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                        <i class="ti-power-off"></i> <span>{{__('Log Out')}}</span>
                                                    </a>
                                                </li>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                      style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            @endauth
                                            @guest
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                       href="{{route('register')}}">{{__('Register')}}</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{route('login')}}">{{__('Login')}}</a>
                                                </li>
                                            @endguest
                                        </ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </nav>
                    <div class=row>
                        <div class="ml-auto mr-auto col-md-7 col-12">
                            @if(session()->get('flash_custom_message'))
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    {!! session()->get('flash_custom_message') !!}
                                </div>
                            @elseif (session()->get('flash_info'))
                                <div class="alert alert-info" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    @if(is_array(json_decode(session()->get('flash_info'), true)))
                                        {!! implode('', session()->get('flash_info')->all(':message<br/>')) !!}
                                    @else
                                        {!! session()->get('flash_info') !!}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </header>
                <!-- Main Icon and Tabs Secction -->
                <div class="row">
                    <div class="col-12">
                        <div class="dashboard-position-left">
                            <div class="row">
                                <div class="col-md-2 col-sm-2 col-2">
                                    <div class="icon-row">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                             aria-orientation="vertical">
                                            <!-- URL TAB ICON -->
                                            <a class="nav-link active icon-small-box" id="url-tab-icon"
                                               data-toggle="pill"
                                               href="#url-tab" onclick="getFormName('urlForm')" role="tab">

                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="hrettp://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 477.389 477.389"
                                                     style="enable-background:new 0 0 477.389 477.389;"
                                                     xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M451.209,68.647c-16.787-16.799-39.564-26.234-63.312-26.226v0c-23.739-0.056-46.516,9.376-63.266,26.197L209.056,184.194
                                                                    c-22.867,22.903-31.609,56.356-22.869,87.518c2.559,9.072,11.988,14.352,21.06,11.793c9.072-2.559,14.352-11.988,11.793-21.06
                                                                    c-5.388-19.271,0.018-39.95,14.148-54.118L348.763,92.768c21.608-21.613,56.646-21.617,78.259-0.008
                                                                    c21.613,21.608,21.617,56.646,0.009,78.259L311.456,286.594c-7.574,7.584-17.193,12.797-27.682,15.002
                                                                    c-9.228,1.921-15.151,10.959-13.23,20.187c1.652,7.935,8.657,13.613,16.762,13.588c1.193,0.001,2.383-0.125,3.55-0.375
                                                                    c16.951-3.575,32.494-12.007,44.732-24.269l115.576-115.558C486.114,160.243,486.134,103.598,451.209,68.647z"/>
                                                            </g>
                                                        </g>
                                                    <g>
                                                        <g>
                                                            <path d="M290.702,206.142c-2.559-9.072-11.988-14.352-21.06-11.793s-14.352,11.988-11.793,21.06
                                                                    c5.388,19.271-0.018,39.95-14.148,54.118L128.125,385.103c-21.608,21.613-56.646,21.617-78.259,0.008
                                                                    c-21.613-21.608-21.617-56.646-0.009-78.259l115.576-115.593c7.562-7.582,17.17-12.795,27.648-15.002
                                                                    c9.243-1.849,15.237-10.84,13.388-20.082s-10.84-15.237-20.082-13.388c-0.113,0.023-0.225,0.046-0.337,0.071
                                                                    c-16.954,3.579-32.502,12.011-44.749,24.269L25.725,282.703c-34.676,35.211-34.242,91.865,0.969,126.541
                                                                    c34.827,34.297,90.731,34.301,125.563,0.008l115.575-115.593C290.7,270.756,299.442,237.303,290.702,206.142z"/>
                                                        </g>
                                                    </g>

                                                    </svg>
                                                <span class="custom--icon-label hover-label">{{__('URL')}}</span>
                                            </a>
                                            <!-- END URL TAB ICON -->

                                            <!-- TEXT TAB ICON -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('textForm')"
                                               id="text-tab-icon" data-toggle="pill"
                                               href="#text-tab" role="tab">
                                                <svg id="Capa_1" enable-background="new 0 0 467.765 467.765"
                                                     viewBox="0 0 467.765 467.765"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="m175.412 87.706h58.471v29.235h58.471v-87.706h-292.354v87.706h58.471v-29.235h58.471v292.353h-58.471v58.471h175.383v-58.471h-58.442z"/>
                                                    <path
                                                        d="m233.882 175.412v87.706h58.471v-29.235h29.235v146.176h-29.235v58.471h116.941v-58.471h-29.235v-146.177h29.235v29.235h58.471v-87.706h-233.883z"/>
                                                </svg>
                                                <span class="custom--icon-label hover-label">{{__('Text')}}</span>
                                            </a>
                                            <!-- END TEXT TAB ICON -->

                                            <!-- EMAIL TAB ICON -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('emailForm')"
                                               id="email-tab-icon" data-toggle="pill"
                                               href="#email-tab" role="tab">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="
                                                        http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                                     xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M485.743,85.333H26.257C11.815,85.333,0,97.148,0,111.589V400.41c0,14.44,11.815,26.257,26.257,26.257h459.487
                                                                    c14.44,0,26.257-11.815,26.257-26.257V111.589C512,97.148,500.185,85.333,485.743,85.333z M475.89,105.024L271.104,258.626
                                                                    c-3.682,2.802-9.334,4.555-15.105,4.529c-5.77,0.026-11.421-1.727-15.104-4.529L36.109,105.024H475.89z M366.5,268.761
                                                                    l111.59,137.847c0.112,0.138,0.249,0.243,0.368,0.368H33.542c0.118-0.131,0.256-0.23,0.368-0.368L145.5,268.761
                                                                    c3.419-4.227,2.771-10.424-1.464-13.851c-4.227-3.419-10.424-2.771-13.844,1.457l-110.5,136.501V117.332l209.394,157.046
                                                                    c7.871,5.862,17.447,8.442,26.912,8.468c9.452-0.02,19.036-2.6,26.912-8.468l209.394-157.046v275.534L381.807,256.367
                                                                    c-3.42-4.227-9.623-4.877-13.844-1.457C363.729,258.329,363.079,264.534,366.5,268.761z"/>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                <span class="custom--icon-label hover-label">{{__('Email')}}</span>
                                            </a>
                                            <!-- END EMAIL TAB ICON -->

                                            <!--PHONE TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('phoneForm')"
                                               id="phone-tab-icon" data-toggle="pill"
                                               href="#phone-tab" role="tab">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 473.806 473.806"
                                                     style="enable-background:new 0 0 473.806 473.806;"
                                                     xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <path d="M374.456,293.506c-9.7-10.1-21.4-15.5-33.8-15.5c-12.3,0-24.1,5.3-34.2,15.4l-31.6,31.5c-2.6-1.4-5.2-2.7-7.7-4
                                                            c-3.6-1.8-7-3.5-9.9-5.3c-29.6-18.8-56.5-43.3-82.3-75c-12.5-15.8-20.9-29.1-27-42.6c8.2-7.5,15.8-15.3,23.2-22.8
                                                            c2.8-2.8,5.6-5.7,8.4-8.5c21-21,21-48.2,0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5c-6-6.2-12.3-12.6-18.8-18.6
                                                            c-9.7-9.6-21.3-14.7-33.5-14.7s-24,5.1-34,14.7c-0.1,0.1-0.1,0.1-0.2,0.2l-34,34.3c-12.8,12.8-20.1,28.4-21.7,46.5
                                                            c-2.4,29.2,6.2,56.4,12.8,74.2c16.2,43.7,40.4,84.2,76.5,127.6c43.8,52.3,96.5,93.6,156.7,122.7c23,10.9,53.7,23.8,88,26
                                                            c2.1,0.1,4.3,0.2,6.3,0.2c23.1,0,42.5-8.3,57.7-24.8c0.1-0.2,0.3-0.3,0.4-0.5c5.2-6.3,11.2-12,17.5-18.1c4.3-4.1,8.7-8.4,13-12.9
                                                            c9.9-10.3,15.1-22.3,15.1-34.6c0-12.4-5.3-24.3-15.4-34.3L374.456,293.506z M410.256,398.806
                                                            C410.156,398.806,410.156,398.906,410.256,398.806c-3.9,4.2-7.9,8-12.2,12.2c-6.5,6.2-13.1,12.7-19.3,20
                                                            c-10.1,10.8-22,15.9-37.6,15.9c-1.5,0-3.1,0-4.6-0.1c-29.7-1.9-57.3-13.5-78-23.4c-56.6-27.4-106.3-66.3-147.6-115.6
                                                            c-34.1-41.1-56.9-79.1-72-119.9c-9.3-24.9-12.7-44.3-11.2-62.6c1-11.7,5.5-21.4,13.8-29.7l34.1-34.1c4.9-4.6,10.1-7.1,15.2-7.1
                                                            c6.3,0,11.4,3.8,14.6,7c0.1,0.1,0.2,0.2,0.3,0.3c6.1,5.7,11.9,11.6,18,17.9c3.1,3.2,6.3,6.4,9.5,9.7l27.3,27.3
                                                            c10.6,10.6,10.6,20.4,0,31c-2.9,2.9-5.7,5.8-8.6,8.6c-8.4,8.6-16.4,16.6-25.1,24.4c-0.2,0.2-0.4,0.3-0.5,0.5
                                                            c-8.6,8.6-7,17-5.2,22.7c0.1,0.3,0.2,0.6,0.3,0.9c7.1,17.2,17.1,33.4,32.3,52.7l0.1,0.1c27.6,34,56.7,60.5,88.8,80.8
                                                            c4.1,2.6,8.3,4.7,12.3,6.7c3.6,1.8,7,3.5,9.9,5.3c0.4,0.2,0.8,0.5,1.2,0.7c3.4,1.7,6.6,2.5,9.9,2.5c8.3,0,13.5-5.2,15.2-6.9
                                                            l34.2-34.2c3.4-3.4,8.8-7.5,15.1-7.5c6.2,0,11.3,3.9,14.4,7.3c0.1,0.1,0.1,0.1,0.2,0.2l55.1,55.1
                                                            C420.456,377.706,420.456,388.206,410.256,398.806z"/>
                                                        <path d="M256.056,112.706c26.2,4.4,50,16.8,69,35.8s31.3,42.8,35.8,69c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2
                                                            c7.4-1.2,12.3-8.2,11.1-15.6c-5.4-31.7-20.4-60.6-43.3-83.5s-51.8-37.9-83.5-43.3c-7.4-1.2-14.3,3.7-15.6,11
                                                            S248.656,111.506,256.056,112.706z"/>
                                                        <path d="M473.256,209.006c-8.9-52.2-33.5-99.7-71.3-137.5s-85.3-62.4-137.5-71.3c-7.3-1.3-14.2,3.7-15.5,11
                                                            c-1.2,7.4,3.7,14.3,11.1,15.6c46.6,7.9,89.1,30,122.9,63.7c33.8,33.8,55.8,76.3,63.7,122.9c1.1,6.6,6.8,11.2,13.3,11.2
                                                            c0.8,0,1.5-0.1,2.3-0.2C469.556,223.306,474.556,216.306,473.256,209.006z"/>
                                                    </g>
                                                </g>
                                            </svg>
                                                <span class="custom--icon-label hover-label">{{__('Phone')}}</span>
                                            </a>
                                            <!--END PHONE TAB ICON  -->

                                            <!--SMS TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('smsForm')"
                                               id="sms-tab-icon" data-toggle="pill"
                                               href="#sms-tab" role="tab">
                                                <svg viewBox="-21 -47 682.66669 682" width="682pt"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="m552.011719-1.332031h-464.023438c-48.515625 0-87.988281 39.464843-87.988281 87.988281v283.972656c0 48.414063 39.300781 87.816406 87.675781 87.988282v128.863281l185.191407-128.863281h279.144531c48.515625 0 87.988281-39.472657 87.988281-87.988282v-283.972656c0-48.523438-39.472656-87.988281-87.988281-87.988281zm50.488281 371.960937c0 27.835938-22.648438 50.488282-50.488281 50.488282h-290.910157l-135.925781 94.585937v-94.585937h-37.1875c-27.839843 0-50.488281-22.652344-50.488281-50.488282v-283.972656c0-27.84375 22.648438-50.488281 50.488281-50.488281h464.023438c27.839843 0 50.488281 22.644531 50.488281 50.488281zm0 0"/>
                                                    <path d="m171.292969 131.171875h297.414062v37.5h-297.414062zm0 0"/>
                                                    <path d="m171.292969 211.171875h297.414062v37.5h-297.414062zm0 0"/>
                                                    <path d="m171.292969 291.171875h297.414062v37.5h-297.414062zm0 0"/>
                                                </svg>
                                                <span class="custom--icon-label hover-label">{{__('SMS')}}</span>
                                            </a>
                                            <!--END SMS TAB ICON  -->

                                            <!--VCard TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('vcardForm')"
                                               id="vcard-tab-icon" data-toggle="pill"
                                               href="#vcard-tab" role="tab">
                                                <svg id="light" xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 512 512">
                                                    <path
                                                        d="M457.7,447.3H52.3A53.3,53.3,0,0,1-1,394V116.7A53.4,53.4,0,0,1,52.3,63.3h128A10.7,10.7,0,0,1,191,74c0,5.9-4.8,23.7-10.7,23.7H62.3a32,32,0,0,0-32,32V381a32.1,32.1,0,0,0,32,32H446.7a32.1,32.1,0,0,0,32-32V129.7a32,32,0,0,0-32-32h-117C323.8,97.7,319,79.9,319,74a10.7,10.7,0,0,1,10.7-10.7h128A53.4,53.4,0,0,1,511,116.7V394A53.3,53.3,0,0,1,457.7,447.3Z"/>
                                                    <path
                                                        d="M183.6,272.7A39.7,39.7,0,1,1,223.3,233,39.7,39.7,0,0,1,183.6,272.7Zm0-63.5A23.8,23.8,0,1,0,207.4,233,23.9,23.9,0,0,0,183.6,209.2Z"/>
                                                    <path
                                                        d="M247.1,352a8,8,0,0,1-8-7.9V328.2a23.8,23.8,0,0,0-23.8-23.8H151.9a23.8,23.8,0,0,0-23.8,23.8v15.9a8,8,0,0,1-15.9,0V328.2a39.7,39.7,0,0,1,39.7-39.7h63.4A39.7,39.7,0,0,1,255,328.2v15.9A8,8,0,0,1,247.1,352Z"/>
                                                    <path
                                                        d="M389.9,225.1H294.7a8,8,0,1,1,0-15.9h95.2a8,8,0,0,1,0,15.9Z"/>
                                                    <path
                                                        d="M389.9,288.5H294.7a7.9,7.9,0,1,1,0-15.8h95.2a7.9,7.9,0,1,1,0,15.8Z"/>
                                                    <path d="M389.9,352H294.7a8,8,0,1,1,0-15.9h95.2a8,8,0,0,1,0,15.9Z"/>
                                                </svg>
                                                <span class="custom--icon-label hover-label">{{__('VCard')}}</span>
                                            </a>
                                            <!--END VCard TAB ICON  -->

                                            <!--MeCard TAB ICON  -->
                                        {{--                                        <a class="nav-link icon-small-box" onclick="getFormName('mecardForm')"--}}
                                        {{--                                           id="mcard-tab-icon" data-toggle="pill"--}}
                                        {{--                                           href="#mcard-tab" role="tab">--}}
                                        {{--                                            <svg id="light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 426.7">--}}
                                        {{--                                                <path--}}
                                        {{--                                                    d="M458.7,469.3H53.3A53.3,53.3,0,0,1,0,416V138.7A53.4,53.4,0,0,1,53.3,85.3h128A10.7,10.7,0,0,1,192,96c0,5.9-4.8,23.7-10.7,23.7H63.3a32,32,0,0,0-32,32V403a32.1,32.1,0,0,0,32,32H447.7a32.1,32.1,0,0,0,32-32V151.7a32,32,0,0,0-32-32h-117c-5.9,0-10.7-17.8-10.7-23.7a10.7,10.7,0,0,1,10.7-10.7h128A53.4,53.4,0,0,1,512,138.7V416A53.3,53.3,0,0,1,458.7,469.3Z"--}}
                                        {{--                                                    transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path--}}
                                        {{--                                                    d="M184.6,294.7A39.7,39.7,0,1,1,224.3,255,39.7,39.7,0,0,1,184.6,294.7Zm0-63.5A23.8,23.8,0,1,0,208.4,255,23.9,23.9,0,0,0,184.6,231.2Z"--}}
                                        {{--                                                    transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path--}}
                                        {{--                                                    d="M248.1,374a8,8,0,0,1-8-7.9V350.2a23.8,23.8,0,0,0-23.8-23.8H152.9a23.8,23.8,0,0,0-23.8,23.8v15.9a8,8,0,0,1-15.9,0V350.2a39.7,39.7,0,0,1,39.7-39.7h63.4A39.7,39.7,0,0,1,256,350.2v15.9A8,8,0,0,1,248.1,374Z"--}}
                                        {{--                                                    transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path d="M390.9,247.1H295.7a8,8,0,1,1,0-15.9h95.2a8,8,0,0,1,0,15.9Z"--}}
                                        {{--                                                      transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path--}}
                                        {{--                                                    d="M390.9,310.5H295.7a7.9,7.9,0,1,1,0-15.8h95.2a7.9,7.9,0,1,1,0,15.8Z"--}}
                                        {{--                                                    transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path d="M390.9,374H295.7a8,8,0,1,1,0-15.9h95.2a8,8,0,0,1,0,15.9Z"--}}
                                        {{--                                                      transform="translate(0 -42.7)"/>--}}
                                        {{--                                                <path--}}
                                        {{--                                                    d="M309.3,149.3H202.7a32,32,0,0,1-32-32V74.7a32,32,0,0,1,32-32H309.3a32,32,0,0,1,32,32v42.6A32,32,0,0,1,309.3,149.3ZM202.7,64A10.7,10.7,0,0,0,192,74.7v42.6A10.7,10.7,0,0,0,202.7,128H309.3A10.7,10.7,0,0,0,320,117.3V74.7A10.7,10.7,0,0,0,309.3,64Z"--}}
                                        {{--                                                    transform="translate(0 -42.7)"/>--}}
                                        {{--                                            </svg>--}}
                                        {{--                                            <span class="custom--icon-label hover-label">MeCard</span>--}}
                                        {{--                                        </a>--}}
                                        <!--END MeCard TAB ICON  -->

                                            <!--Location TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('mapsForm')"
                                               id="location-tab-icon" data-toggle="pill"
                                               href="#location-tab" role="tab">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="
                                                        http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 512 512" xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path d="M256,0C150.112,0,64,86.112,64,192c0,133.088,173.312,307.936,180.672,315.328C247.808,510.432,251.904,512,256,512
                                                                s8.192-1.568,11.328-4.672C274.688,499.936,448,325.088,448,192C448,86.112,361.888,0,256,0z M256,472.864
                                                                C217.792,431.968,96,293.664,96,192c0-88.224,71.776-160,160-160s160,71.776,160,160C416,293.568,294.208,431.968,256,472.864z"/>
                                                            </g>
                                                        </g>
                                                    <g>
                                                        <g>
                                                            <path d="M256,96c-52.928,0-96,43.072-96,96s43.072,96,96,96c52.928,0,96-43.072,96-96C352,139.072,308.928,96,256,96z M256,256
                                                                c-35.296,0-64-28.704-64-64s28.704-64,64-64s64,28.704,64,64S291.296,256,256,256z"/>
                                                        </g>
                                                    </g>
                                                    </svg>
                                                <span class="custom--icon-label hover-label">{{__('Location')}}</span>
                                            </a>
                                            <!--END Location TAB ICON  -->

                                            <!--Facebook TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('facebookForm')"
                                               id="facebook-tab-icon" data-toggle="pill"
                                               href="#facebook-tab"
                                               role="tab">
                                               <svg viewBox="-110 1 511 511.99996" xmlns="http://www.w3.org/2000/svg"><path d="m180 512h-81.992188c-13.695312 0-24.835937-11.140625-24.835937-24.835938v-184.9375h-47.835937c-13.695313 0-24.835938-11.144531-24.835938-24.835937v-79.246094c0-13.695312 11.140625-24.835937 24.835938-24.835937h47.835937v-39.683594c0-39.347656 12.355469-72.824219 35.726563-96.804688 23.476562-24.089843 56.285156-36.820312 94.878906-36.820312l62.53125.101562c13.671875.023438 24.792968 11.164063 24.792968 24.835938v73.578125c0 13.695313-11.136718 24.835937-24.828124 24.835937l-42.101563.015626c-12.839844 0-16.109375 2.574218-16.808594 3.363281-1.152343 1.308593-2.523437 5.007812-2.523437 15.222656v31.351563h58.269531c4.386719 0 8.636719 1.082031 12.289063 3.121093 7.878906 4.402344 12.777343 12.726563 12.777343 21.722657l-.03125 79.246093c0 13.6875-11.140625 24.828125-24.835937 24.828125h-58.46875v184.941406c0 13.695313-11.144532 24.835938-24.839844 24.835938zm-76.8125-30.015625h71.632812v-193.195313c0-9.144531 7.441407-16.582031 16.582032-16.582031h66.726562l.027344-68.882812h-66.757812c-9.140626 0-16.578126-7.4375-16.578126-16.582031v-44.789063c0-11.726563 1.191407-25.0625 10.042969-35.085937 10.695313-12.117188 27.550781-13.515626 39.300781-13.515626l36.921876-.015624v-63.226563l-57.332032-.09375c-62.023437 0-100.566406 39.703125-100.566406 103.609375v53.117188c0 9.140624-7.4375 16.582031-16.578125 16.582031h-56.09375v68.882812h56.09375c9.140625 0 16.578125 7.4375 16.578125 16.582031zm163.0625-451.867187h.003906zm0 0"/></svg>
                                                <span class="custom--icon-label hover-label">{{__('Facebook')}}</span>
                                            </a>
                                            <!--END Facebook TAB ICON  -->

                                            <!--Twitter TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('twitterForm')"
                                               id="twitter-tab-icon" data-toggle="pill"
                                               href="#twitter-tab"
                                               role="tab">
                                               <svg id="regular" enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m.473 19.595c2.222 1.41 4.808 2.155 7.478 2.155 3.91 0 7.493-1.502 10.09-4.229 2.485-2.61 3.852-6.117 3.784-9.676.942-.806 2.05-2.345 2.05-3.845 0-.575-.624-.94-1.13-.647-.885.52-1.692.656-2.522.423-1.695-1.652-4.218-2-6.344-.854-1.858 1-2.891 2.83-2.798 4.83-3.139-.383-6.039-1.957-8.061-4.403-.332-.399-.962-.352-1.226.1-.974 1.668-.964 3.601-.117 5.162-.403.071-.652.41-.652.777 0 1.569.706 3.011 1.843 3.995-.212.204-.282.507-.192.777.5 1.502 1.632 2.676 3.047 3.264-1.539.735-3.241.98-4.756.794-.784-.106-1.171.948-.494 1.377zm7.683-1.914c.561-.431.263-1.329-.441-1.344-1.24-.026-2.369-.637-3.072-1.598.339-.022.69-.074 1.024-.164.761-.206.725-1.304-.048-1.459-1.403-.282-2.504-1.304-2.917-2.62.377.093.761.145 1.144.152.759.004 1.046-.969.427-1.376-1.395-.919-1.99-2.542-1.596-4.068 2.436 2.468 5.741 3.955 9.237 4.123.501.031.877-.44.767-.917-.475-2.059.675-3.502 1.91-4.167 1.222-.66 3.184-.866 4.688.712.447.471 1.955.489 2.722.31-.344.648-.873 1.263-1.368 1.609-.211.148-.332.394-.319.651.161 3.285-1.063 6.551-3.358 8.96-2.312 2.427-5.509 3.764-9.004 3.764-1.39 0-2.753-.226-4.041-.662 1.54-.298 3.003-.95 4.245-1.906z"/></svg>

                                                <span class="custom--icon-label hover-label">{{__('Twitter')}}</span>
                                            </a>
                                            <!--END Twitter TAB ICON  -->

                                            <!--Youtube TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('youtubeForm')"
                                               id="youtube-tab-icon" data-toggle="pill"
                                               href="#youtube-tab"
                                               role="tab">
                                             <svg viewBox="0 -62 512.00199 512" xmlns="http://www.w3.org/2000/svg"><path d="m334.808594 170.992188-113.113282-61.890626c-6.503906-3.558593-14.191406-3.425781-20.566406.351563-6.378906 3.78125-10.183594 10.460937-10.183594 17.875v122.71875c0 7.378906 3.78125 14.046875 10.117188 17.832031 3.308594 1.976563 6.976562 2.96875 10.652344 2.96875 3.367187 0 6.742187-.832031 9.847656-2.503906l113.117188-60.824219c6.714843-3.613281 10.90625-10.59375 10.9375-18.222656.027343-7.628906-4.113282-14.640625-10.808594-18.304687zm-113.859375 63.617187v-91.71875l84.539062 46.257813zm0 0"/><path d="m508.234375 91.527344-.023437-.234375c-.433594-4.121094-4.75-40.777344-22.570313-59.421875-20.597656-21.929688-43.949219-24.59375-55.179687-25.871094-.929688-.105469-1.78125-.203125-2.542969-.304688l-.894531-.09375c-67.6875-4.921874-169.910157-5.5937495-170.933594-5.59765575l-.089844-.00390625-.089844.00390625c-1.023437.00390625-103.246094.67578175-171.542968 5.59765575l-.902344.09375c-.726563.097657-1.527344.1875-2.398438.289063-11.101562 1.28125-34.203125 3.949219-54.859375 26.671875-16.972656 18.445312-21.878906 54.316406-22.382812 58.347656l-.058594.523438c-.152344 1.714844-3.765625 42.539062-3.765625 83.523437v38.3125c0 40.984375 3.613281 81.808594 3.765625 83.527344l.027344.257813c.433593 4.054687 4.746093 40.039062 22.484375 58.691406 19.367187 21.195312 43.855468 24 57.027344 25.507812 2.082031.238282 3.875.441406 5.097656.65625l1.183594.164063c39.082031 3.71875 161.617187 5.550781 166.8125 5.625l.15625.003906.15625-.003906c1.023437-.003907 103.242187-.675781 170.929687-5.597657l.894531-.09375c.855469-.113281 1.816406-.214843 2.871094-.324218 11.039062-1.171875 34.015625-3.605469 54.386719-26.019532 16.972656-18.449218 21.882812-54.320312 22.382812-58.347656l.058594-.523437c.152344-1.71875 3.769531-42.539063 3.769531-83.523438v-38.3125c-.003906-40.984375-3.617187-81.804687-3.769531-83.523437zm-26.238281 121.835937c0 37.933594-3.3125 77-3.625 80.585938-1.273438 9.878906-6.449219 32.574219-14.71875 41.5625-12.75 14.027343-25.847656 15.417969-35.410156 16.429687-1.15625.121094-2.226563.238282-3.195313.359375-65.46875 4.734375-163.832031 5.460938-168.363281 5.488281-5.082032-.074218-125.824219-1.921874-163.714844-5.441406-1.941406-.316406-4.039062-.558594-6.25-.808594-11.214844-1.285156-26.566406-3.042968-38.371094-16.027343l-.277344-.296875c-8.125-8.464844-13.152343-29.6875-14.429687-41.148438-.238281-2.710937-3.636719-42.238281-3.636719-80.703125v-38.3125c0-37.890625 3.304688-76.914062 3.625-80.574219 1.519532-11.636718 6.792969-32.957031 14.71875-41.574218 13.140625-14.453125 26.996094-16.054688 36.160156-17.113282.875-.101562 1.691407-.195312 2.445313-.292968 66.421875-4.757813 165.492187-5.464844 169.046875-5.492188 3.554688.023438 102.589844.734375 168.421875 5.492188.808594.101562 1.691406.203125 2.640625.3125 9.425781 1.074218 23.671875 2.699218 36.746094 16.644531l.121094.128906c8.125 8.464844 13.152343 30.058594 14.429687 41.75.226563 2.558594 3.636719 42.171875 3.636719 80.71875zm0 0"/></svg>
                                                <span class="custom--icon-label hover-label">{{__('Youtube')}}</span>
                                            </a>
                                            <!--END Youtube TAB ICON  -->

                                            <!--WIFI TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('wifiForm')"
                                               id="wifi-tab-icon" data-toggle="pill"
                                               href="#wifi-tab" role="tab">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 489.3 489.3"
                                                     style="enable-background:new 0 0 489.3 489.3;"
                                                     xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path d="M79.55,229.675c-10.2,10.2-10.2,26.8,0,37.1c10.2,10.2,26.8,10.2,37.1,0c70.6-70.6,185.5-70.6,256.1,0
                                                                c5.1,5.1,11.8,7.7,18.5,7.7s13.4-2.6,18.5-7.7c10.2-10.2,10.2-26.8,0-37.1C318.75,138.575,170.55,138.575,79.55,229.675z"/>
                                                            <path d="M150.35,300.475c-10.2,10.2-10.2,26.8,0,37.1c10.2,10.2,26.8,10.2,37.1,0c31.5-31.6,82.9-31.6,114.4,0
                                                                c5.1,5.1,11.8,7.7,18.5,7.7s13.4-2.6,18.5-7.7c10.2-10.2,10.2-26.8,0-37C286.95,248.475,202.35,248.475,150.35,300.475z"/>
                                                            <circle cx="244.65" cy="394.675" r="34.9"/>
                                                            <path d="M481.65,157.675c-130.7-130.6-343.3-130.6-474,0c-10.2,10.2-10.2,26.8,0,37.1c10.2,10.2,26.8,10.2,37.1,0
                                                                c110.2-110.3,289.6-110.3,399.9,0c5.1,5.1,11.8,7.7,18.5,7.7s13.4-2.6,18.5-7.7C491.85,184.575,491.85,167.975,481.65,157.675z"/>
                                                        </g>
                                                    </g>
                                                    </svg>

                                                <span class="custom--icon-label hover-label">{{__('WIFI')}}</span>
                                            </a>
                                            <!--END WIFI TAB ICON  -->

                                            <!--EVENT TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('eventForm')"
                                               id="event-tab-icon" data-toggle="pill"
                                               href="#event-tab" role="tab">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 433.633 433.633" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <path d="M388.749,47.038c-0.886-0.036-1.773-0.042-2.66-0.017h-33.437V18.286C352.653,6.792,341.681,0,330.187,0h-30.824
                                                                    c-11.494,0-19.853,6.792-19.853,18.286V47.02H154.122V18.286C154.122,6.792,145.763,0,134.269,0h-30.825
                                                                    C91.951,0,80.979,6.792,80.979,18.286V47.02H47.543C26.199,46.425,8.414,63.246,7.819,84.589
                                                                    c-0.025,0.886-0.019,1.774,0.017,2.66v301.975c0,22.988,16.718,44.408,39.706,44.408H386.09c22.988,0,39.706-21.42,39.706-44.408
                                                                    V87.249C426.67,65.915,410.083,47.912,388.749,47.038z M299.363,20.898h32.392v57.469h-32.392V20.898z M103.445,20.898h29.78
                                                                    v57.469h-29.78V20.898z M404.898,389.224c0,11.494-7.314,23.51-18.808,23.51H47.543c-11.494,0-18.808-12.016-18.808-23.51
                                                                    V167.184h376.163V389.224z M404.898,87.249v59.037H28.734V87.249c-0.885-9.77,6.318-18.408,16.088-19.293
                                                                    c0.904-0.082,1.814-0.094,2.72-0.037h33.437v11.494c0,11.494,10.971,19.853,22.465,19.853h30.825
                                                                    c10.672,0.293,19.56-8.122,19.853-18.794c0.01-0.353,0.01-0.706,0-1.059V67.918H279.51v11.494
                                                                    c-0.293,10.672,8.122,19.56,18.794,19.853c0.353,0.01,0.706,0.01,1.059,0h30.825c11.494,0,22.465-8.359,22.465-19.853V67.918
                                                                    h33.437c9.791-0.617,18.228,6.82,18.845,16.611C404.992,85.435,404.98,86.345,404.898,87.249z"/>
                                                                <path d="M158.824,309.812l-9.404,52.245c-0.372,2.241-0.004,4.543,1.049,6.556c2.675,5.113,8.989,7.09,14.102,4.415l47.02-24.555
                                                                    l47.02,24.555l4.702,1.045c2.267,0.04,4.48-0.698,6.269-2.09c3.123-2.281,4.73-6.099,4.18-9.927l-9.404-52.245l38.139-36.571
                                                                    c2.729-2.949,3.719-7.109,2.612-10.971c-1.407-3.57-4.577-6.145-8.359-6.792l-52.245-7.837l-23.51-47.543
                                                                    c-1.025-2.116-2.733-3.825-4.849-4.849c-5.194-2.515-11.443-0.344-13.959,4.849l-23.51,47.543l-52.245,7.837
                                                                    c-3.782,0.646-6.952,3.222-8.359,6.792c-1.107,3.862-0.116,8.022,2.612,10.971L158.824,309.812z M187.559,267.494
                                                                    c3.281-0.491,6.061-2.675,7.314-5.747l16.718-33.437l16.718,33.437c1.254,3.072,4.033,5.256,7.314,5.747l37.094,5.224
                                                                    l-26.645,25.6c-2.482,2.457-3.646,5.949-3.135,9.404l6.269,37.094l-32.914-17.763l-4.702-1.045l-4.702,1.045l-32.914,17.763
                                                                    l6.269-37.094c0.512-3.455-0.653-6.947-3.135-9.404l-26.645-25.6L187.559,267.494z"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    </svg>

                                                <span class="custom--icon-label hover-label">{{__('Event')}}</span>
                                            </a>
                                            <!--END Event TAB ICON  -->

                                            <!--Bitcoin TAB ICON  -->
                                        {{--                                        <a class="nav-link icon-small-box" onclick="getFormName('bitcoinForm')"--}}
                                        {{--                                           id="bitcoin-tab-icon" data-toggle="pill"--}}
                                        {{--                                           href="#bitcoin-tab"--}}
                                        {{--                                           role="tab">--}}
                                        {{--                                            <svg id="Layer_1" enable-background="new 0 0 512 512" viewBox="0 0 512 512"--}}
                                        {{--                                                 width="512" xmlns="http://www.w3.org/2000/svg">--}}
                                        {{--                                                <g>--}}
                                        {{--                                                    <path--}}
                                        {{--                                                        d="m256 512c-68.38 0-132.668-26.628-181.02-74.98s-74.98-112.64-74.98-181.02 26.629-132.667 74.98-181.02 112.64-74.98 181.02-74.98 132.668 26.628 181.02 74.98 74.98 112.64 74.98 181.02-26.629 132.667-74.98 181.02-112.64 74.98-181.02 74.98zm0-480c-123.514 0-224 100.486-224 224s100.486 224 224 224 224-100.486 224-224-100.486-224-224-224z"/>--}}
                                        {{--                                                    <path--}}
                                        {{--                                                        d="m314.264 256c13.314-11.737 21.736-28.899 21.736-48 0-26.804-16.571-49.798-40-59.313v-20.687c0-8.836-7.163-16-16-16s-16 7.164-16 16v16h-32v-16c0-8.836-7.163-16-16-16s-16 7.164-16 16v16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16h16v80 80h-16c-8.837 0-16 7.164-16 16s7.163 16 16 16h32v16c0 8.836 7.163 16 16 16s16-7.164 16-16v-16h32v16c0 8.836 7.163 16 16 16s16-7.164 16-16v-20.687c23.429-9.515 40-32.509 40-59.313 0-19.101-8.422-36.263-21.736-48zm-98.264-80h56c17.645 0 32 14.355 32 32s-14.355 32-32 32h-56zm56 160h-56v-64h56c17.645 0 32 14.355 32 32s-14.355 32-32 32z"/>--}}
                                        {{--                                                </g>--}}
                                        {{--                                            </svg>--}}
                                        {{--                                            <span class="custom--icon-label hover-label">Bitcoin</span>--}}
                                        {{--                                        </a>--}}
                                        <!--END Bitcoin TAB ICON  -->

                                            <!--Downloadable TAB ICON  -->
                                            <a class="nav-link icon-small-box" onclick="getFormName('downloadableForm')"
                                               id="downloadable-tab-icon" data-toggle="pill"
                                               href="#downloadable-tab" role="tab">
                                                <!-- <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="hrettp://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 477.389 477.389"
                                                     style="enable-background:new 0 0 477.389 477.389;"
                                                     xml:space="preserve"> -->
                                                <!-- <g>
                                                    <g>
                                                        <path d="M451.209,68.647c-16.787-16.799-39.564-26.234-63.312-26.226v0c-23.739-0.056-46.516,9.376-63.266,26.197L209.056,184.194
                                                            c-22.867,22.903-31.609,56.356-22.869,87.518c2.559,9.072,11.988,14.352,21.06,11.793c9.072-2.559,14.352-11.988,11.793-21.06
                                                            c-5.388-19.271,0.018-39.95,14.148-54.118L348.763,92.768c21.608-21.613,56.646-21.617,78.259-0.008
                                                            c21.613,21.608,21.617,56.646,0.009,78.259L311.456,286.594c-7.574,7.584-17.193,12.797-27.682,15.002
                                                            c-9.228,1.921-15.151,10.959-13.23,20.187c1.652,7.935,8.657,13.613,16.762,13.588c1.193,0.001,2.383-0.125,3.55-0.375
                                                            c16.951-3.575,32.494-12.007,44.732-24.269l115.576-115.558C486.114,160.243,486.134,103.598,451.209,68.647z"/>
                                                    </g>
                                                </g>
                                        <g>
                                            <g>
                                                <path d="M290.702,206.142c-2.559-9.072-11.988-14.352-21.06-11.793s-14.352,11.988-11.793,21.06
                                                            c5.388,19.271-0.018,39.95-14.148,54.118L128.125,385.103c-21.608,21.613-56.646,21.617-78.259,0.008
                                                            c-21.613-21.608-21.617-56.646-0.009-78.259l115.576-115.593c7.562-7.582,17.17-12.795,27.648-15.002
                                                            c9.243-1.849,15.237-10.84,13.388-20.082s-10.84-15.237-20.082-13.388c-0.113,0.023-0.225,0.046-0.337,0.071
                                                            c-16.954,3.579-32.502,12.011-44.749,24.269L25.725,282.703c-34.676,35.211-34.242,91.865,0.969,126.541
                                                            c34.827,34.297,90.731,34.301,125.563,0.008l115.575-115.593C290.7,270.756,299.442,237.303,290.702,206.142z"/>
                                            </g>
                                        </g> -->
                                                <!-- </svg> -->
                                                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="Solid">
                                                        <path
                                                            d="m239.029 384.97a24 24 0 0 0 33.942 0l90.509-90.509a24 24 0 0 0 0-33.941 24 24 0 0 0 -33.941 0l-49.539 49.539v-262.059a24 24 0 0 0 -48 0v262.059l-49.539-49.539a24 24 0 0 0 -33.941 0 24 24 0 0 0 0 33.941z"></path>
                                                        <path
                                                            d="m464 232a24 24 0 0 0 -24 24v184h-368v-184a24 24 0 0 0 -48 0v192a40 40 0 0 0 40 40h384a40 40 0 0 0 40-40v-192a24 24 0 0 0 -24-24z"></path>
                                                    </g>
                                                </svg>
                                                <span
                                                    class="custom--icon-label hover-label">{{__('Downloadable')}}</span>
                                            </a>
                                            <!--END Downloadable TAB ICON  -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10 col-sm-10 col-9 margn---left">
                                    <div class="tab-content index-banner-tabs ml-md-4" id="v-pills-tabContent">
                                        <!-- URL TAB PANE -->
                                        <div class="tab-pane fade show active" id="url-tab" role="tabpanel"
                                             aria-labelledby="url-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseOne"
                                                                aria-expanded="true" aria-controls="collapseOne">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>
                                                    <input type="hidden" name="selectedType" value="urlForm"
                                                           id="selectedType">
                                                    <div id="collapseOne" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="urlForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-md-10 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Your URL')}}<span class="text-danger"> *</span></label>
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeUrl"
                                                                                   placeholder="http://" required
                                                                                   value="{{Request::url()}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="url">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END URL TAB PANE -->

                                        <!-- TEXT TAB PANE -->
                                        <div class="tab-pane fade" id="text-tab" role="tabpanel"
                                             aria-labelledby="text-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseTwo"
                                                                aria-expanded="true" aria-controls="collapseTwo">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="textForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-md-10 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Your Text')}}<span class="text-danger"> *</span></label>
                                                                            <textarea class="form-control"
                                                                                      name="qrcodeText"
                                                                                      rows="3" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="text">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END TEXT TAB PANE -->

                                        <!-- EMAIL TAB PANE -->
                                        <div class="tab-pane fade" id="email-tab" role="tabpanel"
                                             aria-labelledby="email-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseThree"
                                                                aria-expanded="true" aria-controls="collapseThree">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseThree" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="emailForm" class="show-hide">

                                                                <div class="row">
                                                                    <div class="col-md-10 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Your Email')}}<span class="text-danger"> *</span></label>
                                                                            <input type="email" name="qrcodeEmail"
                                                                                   id="qrcodeEmail" class="form-control"
                                                                                   placeholder="email@mail.com"
                                                                                   required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Subject')}}</label>
                                                                            <input type="text" name="qrcodeEmailSubject"
                                                                                   id="qrcodeEmailSubject"
                                                                                   class="form-control"
                                                                                   placeholder="">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Message')}}</label>
                                                                            <textarea class="form-control"
                                                                                      name="qrcodeEmailMessage"
                                                                                      id="qrcodeEmailMessage"
                                                                                      rows="3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="email">

                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- EMAIL URL TAB PANE -->

                                        <!-- PHONE TAB PANE -->
                                        <div class="tab-pane fade" id="phone-tab" role="tabpanel"
                                             aria-labelledby="phone-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseFourth"
                                                                aria-expanded="true" aria-controls="collapseFourth">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>
                                                    <div id="collapseFourth" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="phoneForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-md-10 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Your Phone')}}<span class="text-danger"> *</span></label>
                                                                            <input type="tel" name="qrcodePhone"
                                                                                   maxlength="16"
                                                                                   id="qrcodePhone" class="form-control"
                                                                                   placeholder="+49 172 45921...">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="phone">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END PHONE TAB PANE -->

                                        <!-- SMS TAB PANE -->
                                        <div class="tab-pane fade" id="sms-tab" role="tabpanel"
                                             aria-labelledby="sms-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseFive"
                                                                aria-expanded="true" aria-controls="collapseFive">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>
                                                    <div id="collapseFive" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="smsForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-md-10 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Phone')}}<span class="text-danger"> *</span></label>
                                                                            <input type="tel" name="qrcodeSmsPhone"
                                                                                   id="qrcodeSmsPhone"
                                                                                   class="form-control"
                                                                                   placeholder="+49 172 45921..."
                                                                                   maxlength="16"
                                                                                   required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Message')}}</label>
                                                                            <textarea class="form-control" name="qrcodeSmsText" id="qrcodeSmsText" maxlength="400" rows="3" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="sms">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END SMS TAB PANE -->

                                        <!-- VCARD TAB PANE -->
                                        <div class="tab-pane fade" id="vcard-tab" role="tabpanel"
                                             aria-labelledby="vcard-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseSix"
                                                                aria-expanded="true" aria-controls="collapseSix">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseSix" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="vcardForm" class="show-hide">

                                                                <div class="row">
                                                                    <div class="col-md-10 mb-3">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="vcardVersion"
                                                                                   id="version2" value="2.1" checked>
                                                                            <label class="form-check-label"
                                                                                   for="version2">{{__('Version')}}
                                                                                2.1</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="vcardVersion"
                                                                                   id="version3" value="3">
                                                                            <label class="form-check-label"
                                                                                   for="version3">{{__('Version')}}
                                                                                3.0</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardFirstName">{{__('First Name')}}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardFirstName"
                                                                                   id="qrcodeVcardFirstName"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('First Name') }}...">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardLastName">{{ __('Last Name') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardLastName"
                                                                                   id="qrcodeVcardLastName"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Last Name') }}...">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardOrganization">{{ __('Organization') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardOrganization"
                                                                                   id="qrcodeVcardOrganization"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Organization') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" style="display: none">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardTitle">{{ __('Position Work') }}
                                                                                </label>
                                                                            <input type="text" name="qrcodeVcardTitle"
                                                                                   id="qrcodeVcardTitle"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Position Work') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardPhoneWork">{{ __('Phone Work') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardPhoneWork"
                                                                                   id="qrcodeVcardPhoneWork"
                                                                                   class="form-control"
                                                                                   maxlength="16"
                                                                                   placeholder="{{ __('Phone Work') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" style="display: none">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardPhonePrivate">{{ __('Phone Private') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardPhonePrivate"
                                                                                   id="qrcodeVcardPhonePrivate"
                                                                                   class="form-control"
                                                                                   maxlength="16"
                                                                                   placeholder="{{ __('Phone Private') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="qrcodeVcardPhoneMobile">{{ __('Phone (Mobile)') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardPhoneMobile"
                                                                                   id="qrcodeVcardPhoneMobile"
                                                                                   class="form-control"
                                                                                   maxlength="16"
                                                                                   placeholder="{{ __('Phone (Mobile)') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardFaxWork">{{ __('Fax Work') }}</label>
                                                                            <input type="text" name="qrcodeVcardFaxWork"
                                                                                   id="qrcodeVcardFaxWork"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Fax Work') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" style="display: none">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardFaxPrivate">{{ __('Fax Private') }}</label>
                                                                            <input type="text"
                                                                                   name="qrcodeVcardFaxPrivate"
                                                                                   id="qrcodeVcardFaxPrivate"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Fax Private') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardEmail">{{ __('Email') }}</label>
                                                                            <input type="email" name="qrcodeVcardEmail"
                                                                                   id="qrcodeVcardEmail"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Email') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardUrl">{{ __('Website') }}</label>
                                                                            <input type="url" name="qrcodeVcardUrl"
                                                                                   id="qrcodeVcardUrl"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Website') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="qrcodeVcardStreet">{{ __('Street') }}</label>
                                                                            <input type="text" name="qrcodeVcardStreet"
                                                                                   id="qrcodeVcardStreet"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Street') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="qrcodeVcardZipcode">{{ __('Zipcode') }}</label>
                                                                            <input type="text" name="qrcodeVcardZipcode"
                                                                                   id="qrcodeVcardZipcode"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Zipcode') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardCity">{{ __('City') }}</label>
                                                                            <input type="text" name="qrcodeVcardCity"
                                                                                   id="qrcodeVcardCity"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('City') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="qrcodeVcardState">{{ __('State') }}</label>
                                                                            <input type="text" name="qrcodeVcardState"
                                                                                   id="qrcodeVcardState"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('State') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="qrcodeVcardCountry">{{ __('Country') }}</label>
                                                                            <input type="text" name="qrcodeVcardCountry"
                                                                                   id="qrcodeVcardCountry"
                                                                                   class="form-control"
                                                                                   placeholder="{{ __('Country') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="type" value="vcard">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END VCARD TAB PANE -->

                                        <!-- MECARD TAB PANE -->
                                    {{--                                    <div class="tab-pane fade" id="mcard-tab" role="tabpanel"--}}
                                    {{--                                         aria-labelledby="mcard-tab-icon">--}}
                                    {{--                                        <div class="accordion" id="accordionExample">--}}
                                    {{--                                            <div class="card">--}}
                                    {{--                                                <div class="card-header" id="headingOne">--}}
                                    {{--                                                    <button class="btn btn-link btn-block text-left" type="button"--}}
                                    {{--                                                            data-toggle="collapse" data-target="#collapseSeven"--}}
                                    {{--                                                            aria-expanded="true" aria-controls="collapseSeven">--}}
                                    {{--                                                        <h2>Enter Your Content</h2>--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                    <span class="form-sub-heading">Create, track and edit all your QR codes in one place</span>--}}
                                    {{--                                                </div>--}}

                                    {{--                                                <div id="collapseSeven" class="collapse show" aria-labelledby="headingOne"--}}
                                    {{--                                                     data-parent="#accordionExample">--}}
                                    {{--                                                    <div class="card-body">--}}
                                    {{--                                                        <form name="mecardForm" class="show-hide">--}}
                                    {{--                                                            <div class="row">--}}

                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardFirstname"--}}
                                    {{--                                                                               id="qrcodeMecardFirstname"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="First Name...">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardLastname"--}}
                                    {{--                                                                               id="qrcodeMecardLastname"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Last Name...">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardNickname"--}}
                                    {{--                                                                               id="qrcodeMecardNickname"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Nick Name...">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardPhone1"--}}
                                    {{--                                                                               id="qrcodeMecardPhone1"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Phone 1">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardPhone2"--}}
                                    {{--                                                                               id="qrcodeMecardPhone2"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Phone 2">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}

                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardPhone3"--}}
                                    {{--                                                                               id="qrcodeMecardPhone3"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Phone 3">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="email" name="qrcodeMecardEmail"--}}
                                    {{--                                                                               id="qrcodeMecardEmail"--}}
                                    {{--                                                                               class="form-control" placeholder="Email">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="url" name="qrcodeMecardUrl"--}}
                                    {{--                                                                               id="qrcodeMecardUrl" class="form-control"--}}
                                    {{--                                                                               placeholder="Website">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardBirthday"--}}
                                    {{--                                                                               id="qrcodeMecardBirthday"--}}
                                    {{--                                                                               class="form-control" placeholder="">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardStreet"--}}
                                    {{--                                                                               id="qrcodeMecardStreet"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Street">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardZipcode"--}}
                                    {{--                                                                               id="qrcodeMecardZipcode"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Zipcode">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardCity"--}}
                                    {{--                                                                               id="qrcodeMecardCity"--}}
                                    {{--                                                                               class="form-control" placeholder="City">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardState"--}}
                                    {{--                                                                               id="qrcodeMecardState"--}}
                                    {{--                                                                               class="form-control" placeholder="State">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardCountry"--}}
                                    {{--                                                                               id="qrcodeMecardCountry"--}}
                                    {{--                                                                               class="form-control"--}}
                                    {{--                                                                               placeholder="Country">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <input type="text" name="qrcodeMecardNotes"--}}
                                    {{--                                                                               id="qrcodeMecardNotes"--}}
                                    {{--                                                                               class="form-control" placeholder="Notes">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}

                                    {{--                                                            </div>--}}
                                    {{--                                                            <input type="hidden" name="type" value="mecard">--}}

                                    {{--                                                        </form>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <!-- END MECARD TAB PANE -->

                                        <!-- Location TAB PANE -->
                                        <div class="tab-pane fade" id="location-tab" role="tabpanel"
                                             aria-labelledby="location-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseNine"
                                                                aria-expanded="true" aria-controls="collapseNine">
                                                            <h2></h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseNine" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="mapsForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-sm-12 col-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="">{{__('Search Your Address')}}</label>
                                                                            <div  id="googleMapInput">
                                                                                <input type="text" id="pac-input"
                                                                                       class="form-control ml-0"
                                                                                       placeholder="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Latitude')}}</label>
                                                                            <input type="number" class="form-control"
                                                                                   name="qrcodeMapsLatitude"
                                                                                   id="qrcodeMapsLatitude" min="-90"
                                                                                   max="90"
                                                                                   placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Longitude')}}</label>
                                                                            <input type="number" class="form-control"
                                                                                   name="qrcodeMapsLongitude"
                                                                                   id="qrcodeMapsLongitude" min="-180"
                                                                                   max="180"
                                                                                   placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div id="map"></div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="location">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Location TAB PANE -->

                                        <!-- Facebook TAB PANE -->
                                        <div class="tab-pane fade" id="facebook-tab" role="tabpanel"
                                             aria-labelledby="facebook-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseTen"
                                                                aria-expanded="true" aria-controls="collapseTen">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseTen" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="facebookForm" class="show-hide">

                                                                <div class="row">
                                                                    <div class="col-md-10 mb-3">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeFacebookType"
                                                                                   id="qrcodeFacebookTypeUrl"
                                                                                   value="url"
                                                                                   checked>
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeFacebookTypeUrl">{{__('Facebook URL')}}</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeFacebookType"
                                                                                   id="qrcodeFacebookTypeShare"
                                                                                   value="share">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeFacebookTypeShare">{{__('Share URL')}}</label>
                                                                        </div>
                                                                    </div>
                                                                    <h3 class="col-12 social-heading">{{__('Facebook')}}<span class="text-danger"> *</span>
                                                                        :</h3>

                                                                    <div class="col-sm-6 col-12"
                                                                         id="qrcodeFacebookUrlDiv">
                                                                        <div class="form-group">
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeFacebookUrl"
                                                                                   id="qrcodeFacebookUrl" required
                                                                                   placeholder="https://facebook.com">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12"
                                                                         id="qrcodeFacebookShareDiv">
                                                                        <div class="form-group">
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeFacebookShare"
                                                                                   id="qrcodeFacebookShare" required
                                                                                   placeholder="http://">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <input type="hidden" name="type" value="facebook">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Facebook TAB PANE -->

                                        <!-- Twitter TAB PANE -->
                                        <div class="tab-pane fade" id="twitter-tab" role="tabpanel"
                                             aria-labelledby="twitter-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseEleven"
                                                                aria-expanded="true" aria-controls="collapseEleven">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseEleven" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="twitterForm" class="show-hide">

                                                                <div class="row">
                                                                    <div class="col-md-10 mb-3">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeTwitterType"
                                                                                   id="qrcodeTwitterTypeUrl" value="url"
                                                                                   checked>
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeTwitterTypeUrl">{{__('Twitter URL')}}</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeTwitterType"
                                                                                   id="qrcodeTwitterTypeTweet"
                                                                                   value="tweet">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeTwitterTypeTweet">{{__('Tweet')}}</label>
                                                                        </div>
                                                                    </div>
                                                                    <h3 class="col-12 social-heading">{{__('Twitter')}}<span class="text-danger"> *</span>
                                                                        :</h3>
                                                                    <div class="col-sm-6 col-12"
                                                                         id="qrcodeTwitterUrlDiv">
                                                                        <div class="form-group">
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeTwitterUrl"
                                                                                   id="qrcodeTwitterUrl" required
                                                                                   placeholder="https://twitter.com">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-12 col-12"
                                                                         id="qrcodeTwitterTweetDiv">
                                                                        <div class="form-group">
                                                                        <textarea type="text" class="form-control"
                                                                                  name="qrcodeTwitterTweet"
                                                                                  id="qrcodeTwitterTweet" required
                                                                                  placeholder="@qrcode{{ __('the free qr code generator') }}  #qrcode"
                                                                                  maxlength="280"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="type" value="twitter">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Twitter TAB PANE -->

                                        <!-- Youtube TAB PANE -->
                                        <div class="tab-pane fade" id="youtube-tab" role="tabpanel"
                                             aria-labelledby="youtube-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseTweleve"
                                                                aria-expanded="true" aria-controls="collapseTweleve">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseTweleve" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="youtubeForm" class="show-hide">

                                                                <div class="row">
                                                                    <h3 class="col-12 social-heading">{{__('Youtube')}}<span class="text-danger"> *</span>
                                                                        :</h3>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeYoutubeUrl"
                                                                                   id="qrcodeYoutubeUrl" required
                                                                                   placeholder="https://youtube.com">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="type" value="youtube">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Youtube TAB PANE -->

                                        <!-- WIFI TAB PANE -->
                                        <div class="tab-pane fade" id="wifi-tab" role="tabpanel"
                                             aria-labelledby="wifi-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseThirteen"
                                                                aria-expanded="true" aria-controls="collapseThirteen">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseThirteen" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="wifiForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Wirless SSID')}}<span class="text-danger"> *</span></label>
                                                                            <input type="text" class="form-control"
                                                                                   name="ssid"
                                                                                   id="ssid"
                                                                                   placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Password')}} <span class="text-danger"> *</span></label>
                                                                            <input type="text" class="form-control"
                                                                                   name="password"
                                                                                   id="password"
                                                                                   placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Encryption')}}</label>
                                                                            <select class="form-control"
                                                                                    name="encryption"
                                                                                    id="encryption">
                                                                                <option
                                                                                    value="nopass">{{__('No Encryption')}}
                                                                                </option>
                                                                                <option
                                                                                    value="WEP">{{__('WEP')}}</option>
                                                                                <option
                                                                                    value="WPA">{{__('WPA/WPA2')}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="type" value="wifi">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END WIFI TAB PANE -->

                                        <!-- EVENT TAB PANE -->
                                        <div class="tab-pane fade" id="event-tab" role="tabpanel"
                                             aria-labelledby="event-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseFourtheen"
                                                                aria-expanded="true" aria-controls="collapseFourtheen">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseFourtheen" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="eventForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Event Title')}}<span class="text-danger"> *</span></label>
                                                                            <input type="text" class="form-control"
                                                                                   name="summary"
                                                                                   id="summary"
                                                                                   placeholder="" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="">{{__('Event Location')}}<span class="text-danger"> *</span></label>
                                                                            <input type="text" class="form-control"
                                                                                   name="location"
                                                                                   id="location"
                                                                                   placeholder="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('Start Time')}}<span class="text-danger"> *</span></label>
                                                                            <input type="datetime-local"
                                                                                   name="startDateTime"
                                                                                   id="startDateTime"
                                                                                   class="form-control"
                                                                                   placeholder="12:00"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="">{{__('End Time')}}<span class="text-danger"> *</span></label>
                                                                            <input type="datetime-local"
                                                                                   name="endDateTime"
                                                                                   id="endDateTime"
                                                                                   class="form-control"
                                                                                   placeholder="12:00"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="type" value="event">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END EVENT TAB PANE -->

                                        <!-- BITCoin TAB PANE -->
                                    {{--                                    <div class="tab-pane fade" id="bitcoin-tab" role="tabpanel"--}}
                                    {{--                                         aria-labelledby="bitcoin-tab-icon">--}}
                                    {{--                                        <div class="accordion" id="accordionExample">--}}
                                    {{--                                            <div class="card">--}}
                                    {{--                                                <div class="card-header" id="headingOne">--}}
                                    {{--                                                    <button class="btn btn-link btn-block text-left" type="button"--}}
                                    {{--                                                            data-toggle="collapse" data-target="#collapseFifteen"--}}
                                    {{--                                                            aria-expanded="true" aria-controls="collapseFifteen">--}}
                                    {{--                                                        <h2>Enter Your Content</h2>--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                    <span class="form-sub-heading">Create, track and edit all your QR codes in one place</span>--}}
                                    {{--                                                </div>--}}

                                    {{--                                                <div id="collapseFifteen" class="collapse show" aria-labelledby="headingOne"--}}
                                    {{--                                                     data-parent="#accordionExample">--}}
                                    {{--                                                    <div class="card-body">--}}
                                    {{--                                                        <form name="bitcoinForm" class="show-hide" action="">--}}
                                    {{--                                                            <div class="row">--}}
                                    {{--                                                                <div class="col-md-12 mb-3" style="display: none">--}}
                                    {{--                                                                    <div class="form-check form-check-inline">--}}
                                    {{--                                                                        <input class="form-check-input" type="radio"--}}
                                    {{--                                                                               name="bitcoinType" value="bitcoin"--}}
                                    {{--                                                                               checked>--}}
                                    {{--                                                                        <label class="form-check-label"--}}
                                    {{--                                                                               for="inlineRadio1">Bitcoin</label>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                    <div class="form-check form-check-inline">--}}
                                    {{--                                                                        <input class="form-check-input" type="radio"--}}
                                    {{--                                                                               name="bitcoinType" value="bitcoincash">--}}
                                    {{--                                                                        <label class="form-check-label"--}}
                                    {{--                                                                               for="inlineRadio1">Bitcoin Cash</label>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                    <div class="form-check form-check-inline">--}}
                                    {{--                                                                        <input class="form-check-input" type="radio"--}}
                                    {{--                                                                               name="bitcoinType" value="ethereum">--}}
                                    {{--                                                                        <label class="form-check-label"--}}
                                    {{--                                                                               for="inlineRadio1">Ethereum</label>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                    <div class="form-check form-check-inline">--}}
                                    {{--                                                                        <input class="form-check-input" type="radio"--}}
                                    {{--                                                                               name="bitcoinType" value="litecoin">--}}
                                    {{--                                                                        <label class="form-check-label"--}}
                                    {{--                                                                               for="inlineRadio1">Litecoin</label>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                    <div class="form-check form-check-inline">--}}
                                    {{--                                                                        <input class="form-check-input" type="radio"--}}
                                    {{--                                                                               name="bitcoinType" value="dash">--}}
                                    {{--                                                                        <label class="form-check-label"--}}
                                    {{--                                                                               for="inlineRadio1">Dash</label>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-8">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <label for="">Address</label>--}}
                                    {{--                                                                        <input type="text" class="form-control"--}}
                                    {{--                                                                               name="qrcodeBitcoinAddress"--}}
                                    {{--                                                                               id="qrcodeBitcoinAddress"--}}
                                    {{--                                                                               placeholder="1FwFqqh71mUTENcRe9q4s9AWFgoc8BA9ZU"--}}
                                    {{--                                                                               value="1FwFqqh71mUTENcRe9q4s9AWFgoc8BA9ZU"--}}
                                    {{--                                                                               required>--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                                <div class="col-md-4">--}}
                                    {{--                                                                    <div class="form-group">--}}
                                    {{--                                                                        <label for="">Amount</label>--}}
                                    {{--                                                                        <input type="number" class="form-control"--}}
                                    {{--                                                                               name="qrcodeBitcoinAmount"--}}
                                    {{--                                                                               id="qrcodeBitcoinAmount"--}}
                                    {{--                                                                               placeholder="">--}}
                                    {{--                                                                    </div>--}}
                                    {{--                                                                </div>--}}
                                    {{--                                                            </div>--}}

                                    {{--                                                            <input type="hidden" name="type" value="bitcoin">--}}

                                    {{--                                                        </form>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <!-- END BITCoin TAB PANE -->

                                        <!-- DownloadableForm TAB PANE -->
                                        <div class="tab-pane fade" id="downloadable-tab" role="tabpanel"
                                             aria-labelledby="downloadable-tab-icon">
                                            <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <button class="btn btn-link btn-block text-left" type="button"
                                                                data-toggle="collapse" data-target="#collapseSixteen"
                                                                aria-expanded="true" aria-controls="collapseSixteen">
                                                            <h2>{{__('Enter Your Content')}}</h2>
                                                        </button>
                                                        <span
                                                            class="form-sub-heading">{{__('Create, track and edit all your QR codes in one place')}}</span>
                                                    </div>

                                                    <div id="collapseSixteen" class="collapse show"
                                                         aria-labelledby="headingOne"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <form name="downloadableForm" class="show-hide">
                                                                <div class="row">
                                                                    <div class="col-md-10 mb-3">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeDownloadableType"
                                                                                   id="qrcodeDownloadTypeApps"
                                                                                   value="apps"
                                                                                   checked>
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeDownloadTypeApps">{{__('Apps')}}</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeDownloadableType"
                                                                                   id="qrcodeDownloadTypePdfs"
                                                                                   value="pdf">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeDownloadTypePdfs">{{__('PDFs')}}<span class="text-danger"> *</span></label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeDownloadableType"
                                                                                   id="qrcodeDownloadTypeImage"
                                                                                   value="image">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeDownloadTypeImage">{{__('Image')}}<span class="text-danger"> *</span></label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeDownloadableType"
                                                                                   id="qrcodeDownloadTypeMusic"
                                                                                   value="music">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeDownloadTypeMusic">{{__('Music')}}<span class="text-danger"> *</span></label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                   name="qrcodeDownloadableType"
                                                                                   id="qrcodeDownloadTypeVideo"
                                                                                   value="video">
                                                                            <label class="form-check-label"
                                                                                   for="qrcodeDownloadTypeVideo">{{__('Video')}}<span class="text-danger"> *</span></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-12 col-12"
                                                                         id="qrcodeDownloableUrlDiv">
                                                                        <div class="form-group">
                                                                            <input type="url" class="form-control"
                                                                                   name="qrcodeDownloadableUrl"
                                                                                   id="qrcodeDownloadableUrl" required
                                                                                   placeholder="https://play.google.com/store/apps/details?id=">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <input type="hidden" name="type" value="downloadable">

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END DownloadableForm TAB PANE -->
                                    </div>

                                    <!------- Customize Design Tab--------------- -->
                                    <div class="tab-content index-banner-tabs ml-md-4 form-group input-group4">
                                        <div class="card ">
                                            <div class="card-header" id="headingTwo">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapse2"
                                                        aria-expanded="false" aria-controls="collapse2">
                                                    <h2>{{__('Customize Design')}}</h2>
                                                </button>
                                            </div>

                                            <div id="collapse2" class="collapse" aria-labelledby="heading2"
                                                 data-parent="#accordionExample">
                                                <div class="card-body customized-tab-body clearfix">
                                                    <div class="card-body-in
                                                      Set Color
                                                    ner">
                                                        <label><b>{{__('Body Shape')}}</b></label>
                                                        <div class="form-group presets clearfix">
                                                            @foreach($bodyShapes as $index => $bodyShape)
                                                                <div
                                                                    class="item body-shape {{$index == 0 ? 'active': ''}}"
                                                                    id="shapeName{{$bodyShape->name}}"
                                                                    onclick="getShapeName('{{$bodyShape->name}}')">
                                                                    <img
                                                                        src="{{ asset('storage/shapes/'.$bodyShape->image) }}">
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                        <label><b>{{__('Eye Frame Shape')}}</b></label>
                                                        <div class="form-group presets clearfix">
                                                            @foreach($eyeFrames as $index => $eyeFrame)
                                                                <div
                                                                    class="item eye-frame {{$index == 0 ? 'active': ''}}"
                                                                    id="frameName{{$eyeFrame->name}}"
                                                                    onclick="getFrameName('{{$eyeFrame->name}}')">
                                                                    <img
                                                                        src="{{ asset('storage/shapes/'.$eyeFrame->image) }}">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <label><b>{{__('Eye Ball Shape')}}</b></label>
                                                        <div class="form-group presets clearfix">
                                                            @foreach($eyeBallShapes as $index => $eyeBallShape)
                                                                <div
                                                                    class="item eye-shape {{$index == 0 ? 'active': ''}}"
                                                                    id="eyeShape{{$eyeBallShape->name}}"
                                                                    onclick="getEyeBallName('{{$eyeBallShape->name}}')">
                                                                    <img
                                                                        src="{{ asset('storage/shapes/'.$eyeBallShape->image) }}">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card ">
                                            <div class="card-header" id="headingTwo">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapse4"
                                                        aria-expanded="false" aria-controls="collapse4">
                                                    <h2>{{__('Choose Frame Design')}}</h2>
                                                </button>
                                            </div>

                                            <div id="collapse4" class="collapse" aria-labelledby="heading2"
                                                 data-parent="#accordionExample">
                                                <div class="card-body customized-tab-body clearfix">
                                                    <div class="card-body-in
                                                      Set Color
                                                    ner">
                                                        <label><b>{{__('Frame Style')}}</b></label>
                                                        <div class="form-group presets clearfix">
                                                            <div
                                                                class="item qr-frames active"
                                                                id="qrCodeFrame0"
                                                                onclick="getQrCodeFrame('0')">
                                                                <div class="image-tile__none"></div>

                                                            </div>
                                                            @foreach($qrCodeFrames as $index => $qrCodeFrame)
                                                                <div
                                                                    class="item qr-frames"
                                                                    id="qrCodeFrame{{$qrCodeFrame->id}}"
                                                                    onclick="getQrCodeFrame('{{$qrCodeFrame->id}}')">
                                                                    <img
                                                                        src="{{ asset('storage/shapes/'.$qrCodeFrame->image) }}">
                                                                </div>
                                                            @endforeach

                                                        </div>
{{--                                                        <label><b>{{__('Eye Frame Shape')}}</b></label>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card ">
                                            <div class="card-header" id="heading4">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapse4"
                                                        aria-expanded="false" aria-controls="collapse4">
                                                    <h2>{{__('Set Color')}}</h2>
                                                </button>
                                            </div>

                                            <div id="collapse4" class="collapse add-logo-image"
                                                 aria-labelledby="heading4"
                                                 data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="pane-content">
                                                        <label>{{__('Foreground Color')}}</label>
                                                        <div>
                                                            <div class="form-check form-check-inline" id="single">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input radio"
                                                                           {{--                                                                           name="customColorMode"--}}
                                                                           type="radio" value="single"
                                                                           onclick="foregroundColor('single')" checked>
                                                                    {{__('Single Color')}}
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline" id="gradient">
                                                                <label class="form-check-label">
                                                                    <input
                                                                        class="form-check-input ng-valid ng-dirty ng-touched radio"
                                                                        {{--                                                                                                                                                name="customColorMode"--}}
                                                                        type="radio"
                                                                        value="gradient"
                                                                        onclick="foregroundColor('gradient')"> {{__('Gradient Color')}}
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <label class="form-check-label">
                                                                    <input id="customEyeColor"
                                                                           class="form-check-input ng-untouched ng-pristine ng-valid"
                                                                           type="checkbox"
                                                                           onclick="eyeColorStatus()"> {{__('Custom Eye Colors')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-3">
                                                            <div class="col-sm-5 col-lg-4">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorOne"
                                                                           {{--                                                                           name="color-picker-1"--}}
                                                                           value="#000000"
                                                                           onchange="colorPicker(this,1)">
                                                                    <div class="alert alert-warning color-warning" id="color-warning-1" style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5 col-lg-4 hide-show">
                                                                <div class="form-group input-group">
                                                                <!-- <label class="mr-2 mt-1">{{__('Foreground Color')}}</label> -->
                                                                    <input class="form-control" type="color"
                                                                           id="colorTwo"
                                                                           {{--                                                                           name="color-picker-2"--}}
                                                                           value="#000000"
                                                                           onchange="colorPicker(this,2)">
                                                                    <div class="alert alert-warning color-warning" id="color-warning-2" style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5 col-lg-4 hide-show">
                                                                <div class="input-group form-group">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-secondary"
                                                                            style="border-radius: 0px"
                                                                            type="button"
                                                                            onclick="colorOverlap(1)">
                                                                        <i class="fa fa-exchange"></i>
                                                                    </button>
                                                                </span>
                                                                    <select class="form-control" id="colorSchema"
                                                                            onchange="colorSchema(this)">
                                                                        <option
                                                                            value="vertical">{{__('Vertical')}}</option>
                                                                        <option value="radial">{{__('Radial')}}</option>
                                                                        <option
                                                                            value="horizontal">{{__('Horizontal')}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-3 eye-hide-show">
                                                            <!-- <div class="col-12"> -->
                                                            <div class="col-sm-5 col-lg-4">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorThree"
                                                                           {{--                                                                           name="eye-frame-color-picker"--}}
                                                                           value="#000000"
                                                                           onchange="colorPicker(this,3)">
                                                                    <div class="alert alert-warning color-warning" id="color-warning-3" style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5 col-lg-4">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorFour"
                                                                           {{--                                                                           name="eye-ball-color-picker"--}}
                                                                           value="#000000"
                                                                           onchange="colorPicker(this,4)">
                                                                    <div class="alert alert-warning color-warning" id="color-warning-4" style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-auto col-lg-auto">
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-btn image-upload-btn">
                                                                        <button class="btn btn-secondary"
                                                                                style="border-radius: 0px"
                                                                                type="button" onclick="colorOverlap(2)">
                                                                            <i class="fa fa-exchange"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="input-group-btn image-upload-btn flex-button">
                                                                        <button class="btn btn-secondary" type="button"
                                                                                onclick="colorOverlap(3)"
                                                                                style="border-radius: 0px;"> {{__('Copy Foreground')}}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- </div> -->
                                                        <div class="row pt-3">
                                                            <!-- <div class="col-12"> -->
                                                            <div class="col-sm-5 col-lg-4">
                                                                <label>{{__('Background Colour')}}</label>
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorFive"
                                                                           value="#ffffff"
                                                                           {{--                                                                           name="bg-color-picker"--}}
                                                                           onchange="colorPicker(this,5)">
                                                                    <div class="alert alert-warning color-warning" id="color-warning-5" style="display: none;">{{__('Make sure there is enough contrast to the darker foreground.')}}</div>
                                                                </div>
                                                            </div>
                                                            <!-- </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card ">
                                                <div class="card-header" id="headingTwo">
                                                    <button class="btn btn-link btn-block text-left collapsed"
                                                            type="button"
                                                            data-toggle="collapse" data-target="#collapse3"
                                                            aria-expanded="false" aria-controls="collapse3">
                                                        <h2>{{__('Add Logo Image')}}</h2>
                                                    </button>
                                                </div>

                                                <div id="collapse3" class="collapse add-logo-image"
                                                     aria-labelledby="heading3"
                                                     data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="custom-file-upload">
                                                            <button class="file-upload-btn" type="button"
                                                                    onclick="$('.file-upload-input').trigger( 'click' )">{{__('Add Image')}}
                                                            </button>

                                                            <div class="image-upload-wrap">
                                                                <input class="file-upload-input" type="file"
                                                                       id="upload-logo-image"
                                                                       name="logo_image" style="pointer-events: none;"
                                                                       onchange="getExtension(this)" accept="image/png">
                                                                {{__('Upload')}}
                                                            </div>
                                                            <div class="file-upload-content mr-auto ml-auto ">
                                                                <img class="file-upload-image" src="#" alt="your image">
                                                                <div class="image-title-wrap">
                                                                    <button type="button" id="remove-image"
                                                                            onclick="removeImage(this)"
                                                                            class="remove-image">{{__('Remove')}} <span
                                                                            class="image-title">{{__('Uploaded Image')}}</span>
                                                                    </button>
                                                                </div>
                                                                <span class="d-none" id="allowd_image"
                                                                      style="color: #FC4014">{{__('The image must be a file of type: image/png')}}</span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group presets clearfix pt-3">
                                                            @foreach($logos as $logo)
                                                                <div class="item logo-image" id="logoImage{{$logo->id}}"
                                                                     onclick="getLogoId({{$logo->id}})">
                                                                    <img id="{{$logo->id}}"
                                                                         src="{{ asset('storage/logos/'.$logo->image) }}">
                                                                </div>
                                                            @endforeach
                                                            <div class="clearfix"></div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dark Blue Section COL 4 -->
                        <!-- <div class="position-right-side col-12 col-md-5 col-xl-4 margn---left  custom-margin-r"> -->
                        <div class="position-right-side col-md-5 col-xl-4 ">
                            <div class="bg-dark-blue banner-qrcode-section">
                                <div class="rigtside-content">
                                    <div class="row justify-content-center">
                                        <div class="image-parent">
                                            <div class=" text-center Custom_image_range gape-tp">
                                                <div class="img-contaner img-contane-color" id="qrCodeImageAppend">                                                  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="loading__screen">
                                            <div class="fa-5x mt-4 align-items-center" id="loading"
                                                 style="display: none; color: white;">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="range-slidecontainer">
                                                <input type="range" step="25" min="200" max="2000" value="50%"
                                                       class="range-slider"
                                                       id="myRange">
                                                <div class="d-flex justify-content-between values-labels">
                                                    <div class="low-quality">{{__('Low Quality')}}</div>
                                                    <div class="ranges-value"><span id="value"></span> X <span
                                                            id="value1"></span>px
                                                    </div>
                                                    <div class="high-quality">{{__('High Quality')}}</div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="alert alert-warning" id="color-warning" style="display: none;">{{__('Warning! We recommend to give your colors more contrast between back- and foreground to work with all QR code readers.')}}</div>
                                        </div>
                                        <div class="col-md-12">
                                            <!-- Accordian Section -->
                                            <!-- @if($adminQrCodes->count() > 0) -->

                                                <!-- <div id="accordion">
                                                    <div class="qrcode-tabs">
                                                        <div class="qrcode-tabs-header" id="headingOne">
                                                            <h5 class="mb-0">
                                                                {{__('QR Code Templates')}}
                                                            </h5>
                                                        </div>
                                                        <div id="TabcollapseOne" class="collapse show"
                                                             aria-labelledby="headingOne" data-parent="#accordion">
                                                            <div class="form-group">
                                                                <div id="carouselExampleIndicators"
                                                                     class="carousel slide"
                                                                     data-ride="carousel">
                                                                    <ol class="carousel-indicators custom-carousel-indicators-s custom2_indicator">
                                                                        @foreach($adminQrCodes->chunk(3) as $index => $chunk)
                                                                            <li data-target="#carouselExampleIndicators"
                                                                                data-slide-to="{{$index}}"
                                                                                class="{{$index == 0 ? 'active': ''}}"></li>
                                                                        @endforeach
                                                                    </ol>
                                                                    <div class="carousel-inner">

                                                                        @foreach($adminQrCodes->chunk(3) as $index => $chunk)
                                                                            <div
                                                                                class="carousel-item {{$index == 0 ? 'active': ''}} custom-carousal">
                                                                                @foreach($chunk as $index => $adminQrCode)
                                                                                    <img
                                                                                        onclick="copyQrCodeConfig('{{Hashids::encode($adminQrCode->id)}}')"
                                                                                        src="{{checkImage(asset('storage/admin-qr-codes/' . $adminQrCode->image),'placeholder.png',$adminQrCode->image)}}"
                                                                                        class="d-block w-20"
                                                                                        alt="classic">
                                                                                @endforeach
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            <!-- @endif -->

                                            <div class="download-qrcode-buttons generate--code">
                                                <div class="d-flex flex-sm-row flex-wrap flex-md-nowrap justify-content-center">
                                                    <button type="button" id="genQrCode" class="btn btn-success mb-1 mt-sm-0 mt-1 mr-sm-0 mr-1 "
                                                            onclick="generateQRCode(this)">
                                                        {{__('Generate')}}
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary mb-1 mt-sm-0 mt-1 mr-1 ml-sm-1"
                                                            type="button"
                                                            data-toggle="modal" onclick="getQrCodeType('png')"
                                                            data-target="#download-model">.PNG
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary mb-1 mt-sm-0 mt-1 mr-1"
                                                            type="button"
                                                            data-toggle="modal" onclick="getQrCodeType('svg')"
                                                            data-target="#download-model">.SVG
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary mb-1 mr-1 mt-sm-0 mt-1"
                                                            type="button"
                                                            data-toggle="modal" onclick="getQrCodeType('pdf')"
                                                            data-target="#download-model">.PDF
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary mb-1 mt-sm-0 mt-1"
                                                            type="button"
                                                            data-toggle="modal" onclick="getQrCodeType('eps')"
                                                            data-target="#download-model">.EPS
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Button trigger modal -->

                                            <p class="bottom-text text-center mt-3">
                                                <span>* {{__('no support for color gradients')}}</span>
                                            </p>
                                            <p class="alerttxt bottom-text text-center mb-3">
                                                <span>* {{__('Complex design or data may cause delay in scanning. Try keeping it simple')}}</span>
                                            </p>
                                            <div class="d-flex justify-content-center mb-5 pb-3 qrcode---tamplate-buttons" >
                                                <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-md button-orange" data-toggle="modal" data-target="#qrCodeTemplatesModal">
                                            {{__('QR Code Templates')}}
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade home-qr-template-model" id="qrCodeTemplatesModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeTemplatesModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="qrCodeTemplatesModalLabel">{{__('QR Code Templates')}}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        <div class="modal-body">
                                                            <div class="row row designsetting qrdesigner-iner">
                                                                   @foreach($adminQrCodes as $index => $adminQrCode)
                                                                        <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                                                                            <div class="--template">
                                                                                <div class="qrcode--inner-container">
                                                                                    <img
                                                                                        onclick="copyQrCodeConfig('{{Hashids::encode($adminQrCode->id)}}')"
                                                                                        src="{{checkImage(asset('storage/admin-qr-codes/' . $adminQrCode->image),'placeholder.png',$adminQrCode->image)}}"
                                                                                        class="d-block w-20"
                                                                                        alt="classic">

                                                                                </div>
                                                                                <div class="options">
                                                                                <h6>{{$adminQrCode->name}}</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Large modal -->
    <!-- Model Download -->
    <div class="modal fade" id="download-model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Download QR Code')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('frontend.download.qr.code')}}" method="post">
                    @csrf
                    @method('get')
                    <input type="hidden" value="" name="fileType" id="file-type">
                    <input type="hidden" value="" name="formData" id="generatedFormData">
                    <input type="hidden" value="" name="generatedLogoConfigData" id="generatedLogoConfigData">
                    <input type="hidden" value="" name="generatedLogoEyeStatus" id="generatedLogoEyeStatus">
                    <input type="hidden" value="" name="generatedLogoImage" id="generatedLogoImage">
                    <input type="hidden" value="" name="imageName" id="image-name">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="range-slidecontainer">
                                        <input type="range" step="25" min="100" max="2000" value="50%"
                                               class="range-slider w-100" id="my-range"
                                               name="size">
                                        <div
                                            class="d-flex justify-content-between values-labels">
                                            <div class="low-quality">{{__('Small')}}</div>
                                            <div class="ranges-value"><span id="qr-code-height">1050</span> x <span
                                                    id="qr-code-width">1050</span> px
                                            </div>
                                            <div class="high-quality">{{__('Big')}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="submit" class="btn btn-primary">{{__('Download Image')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('frontend.home.qr_studio_code')

    @include('frontend.home.qr_code_generator')

    @include('frontend.home.faq')

    <div class="donate-and-support-bg">

        @include('frontend.home.donate_to_support')

        @include('frontend.sections.footer')

    </div>
@endsection

@section('after-js')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script>

        // This example adds a search box to a map, using the Google Place Autocomplete
        // feature. People can enter geographical searches. The search box will return a
        // pick list containing a mix of places and predicted search terms.
        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
        function initAutocomplete() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13,
                mapTypeId: "roadmap",
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
            $('#googleMapInput').html(input)
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                    $('#qrcodeMapsLatitude').val(place.geometry.location.lat)
                    $('#qrcodeMapsLongitude').val(place.geometry.location.lng)
                });
                map.fitBounds(bounds);
            });
        }
    </script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsnKAS_7rfZxChem34dqx6RL5qWmKbbOQ&callback=initAutocomplete&libraries=places&v=weekly">
    </script>

    <script>
        var scrollTopDifference = 110;
        var slider = document.getElementById("myRange");
        var output = document.getElementById("value");
        var output1 = document.getElementById("value1");
        var shapeName = 'square';
        var frameName = 'square';
        var eyeName = 'square';
        var qrCodeFrameid = '';
        var eyeStatus = false;
        var colorOne = "#000000";
        var colorTwo = "#000000";
        var frameColor = "#000000";
        var eyeBallColor = "#000000";
        var bodyColor = "#ffffff";
        var colorStructure = 'vertical';
        var logoId = 0;
        var colorType = true;
        var selectedCollapse = '#collapseOne';
        var imageName = '';
        var isLoadMore = 1;
        var generatedFormData = '';

        output.innerHTML = slider.value;
        output1.innerHTML = slider.value;

        slider.oninput = function () {
            output.innerHTML = this.value;
            output1.innerHTML = this.value;
        }
        $('.subscriber-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    email: {
                        email: true,
                        required: true,
                    },
                },

                messages: {
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}'
                    },
                },

                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');
                    $(e).remove();
                },
                errorPlacement: function (error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1)
                            controls.append(error);
                        else
                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
                },
                invalidHandler: function (form) {
                }
            });
        function frontendValidation(formName) {
            let formId = 'form[name=' + formName + ']';

            $(formId).validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    qrcodeMapsLatitude: {
                        max: 90,
                        min: -90,
                    },
                    qrcodeMapsLongitude: {
                        max: 180,
                        min: -180,
                    },
                    qrcodeUrl: {
                        required: true,
                        url: true
                    },
                    qrcodeText: {
                        required: true
                    },
                    qrcodeEmail: {
                        required: true,
                        email: true,
                    },
                    qrcodePhone: {
                        required: true,
                    },
                    qrcodeSmsPhone: {
                        required: true,
                    },
                    qrcodeSmsText: {
                        required : false,
                        maxlength: 400,
                    },
                    summary: {
                        required: true,
                    },
                    location: {
                        required: true,
                    },
                    qrcodeEventTimezone: {
                        required: true,
                    },
                    qrcodeEventReminder: {
                        required: true,
                    },
                    qrcodeEventDescription: {
                        required: true,
                        maxlength: true,
                    },
                    startDateTime: {
                        required: true,
                    },
                    endDateTime: {
                        required: true,
                    },
                    ssid: {
                        required: true,
                    },
                    password: {
                        required: true
                    },
                    qrcodeFacebookUrl: {
                        required: true,
                        url: true
                    },
                    qrcodeFacebookShare: {
                        required: true
                    },
                    qrcodeTwitterUrl: {
                        required: true,
                        url: true
                    },
                    qrcodeTwitterTweet: {
                        required: true
                    },
                    qrcodeYoutubeUrl: {
                        required: true,
                        url: true
                    },
                    qrcodeDownloadableUrl: {
                        required: true,
                        url: true
                    },
                },

                messages: {
                    qrcodeMapsLatitude: {
                        max: '{{__('Please enter a value less than or equal to 90.')}}',
                        min: '{{__('Please enter a value greater than or equal to -90.')}}',
                    },
                    qrcodeMapsLongitude: {
                        max: '{{__('Please enter a value less than or equal to 180.')}}',
                        min: '{{__('Please enter a value greater than or equal to -180.')}}',
                    },
                    qrcodeUrl: {
                        required: '{{__('This field is required')}}',
                        url: '{{__('Please enter a valid URL')}}'
                    },
                    qrcodeText: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEmail: {
                        required: '{{__('This field is required')}}',
                        email: '{{__('Please enter a valid email address')}}'
                    },
                    qrcodePhone: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeSmsPhone: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeSmsText: {
                        maxlength: '{{__('Must be less than 400')}}'
                    },
                    summary: {
                        required: '{{__('This field is required')}}'
                    },
                    location: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEventTimezone: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEventReminder: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEventDescription: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('Must be less than 400')}}'
                    },
                    startDateTime: {
                        required: '{{__('This field is required')}}',
                    },
                    endDateTime: {
                        required: '{{__('This field is required')}}',
                    },
                    ssid: {
                        required: '{{__('This field is required')}}'
                    },
                    password: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeFacebookUrl: {
                        required: '{{__('This field is required')}}',
                        url: '{{__('Please enter a valid URL')}}'
                    },
                    qrcodeFacebookShare: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeTwitterUrl: {
                        required: '{{__('This field is required')}}',
                        url: '{{__('Please enter a valid URL')}}'
                    },
                    qrcodeTwitterTweet: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeYoutubeUrl: {
                        required: '{{__('This field is required')}}',
                        url: '{{__('Please enter a valid URL')}}'
                    },
                    qrcodeDownloadableUrl: {
                        required: '{{__('This field is required')}}',
                        url: '{{__('Please enter a valid URL')}}'
                    },
                },

                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');
                    $(e).remove();
                },
                errorPlacement: function (error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1)
                            controls.append(error);
                        else
                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
                },
                invalidHandler: function (form, validator) {
                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
                    }, 500);
                },
                submitHandler: function (form, validator) {
                    if ($(validator.errorList).length == 0) {
                        document.getElementById("page-overlay").style.display = "block";
                        return true;
                    }
                }
            })
        }

        $(document).ready(function () {
            $('.hide-show').hide();
            $('.eye-hide-show').hide();

            getShapeName('square');
            getFrameName('square');
            getEyeBallName('square');

            {{--Facebook Url hide and Show--}}
            $('input:radio[name="qrcodeFacebookType"]').change(function () {
                if ($(this).val() == 'url') {
                    $('#qrcodeFacebookUrlDiv').show();
                    $('#qrcodeFacebookShareDiv').hide();
                } else if ($(this).val() == 'share') {
                    $('#qrcodeFacebookShareDiv').show();
                    $('#qrcodeFacebookUrlDiv').hide();
                }
            });
            $('#qrcodeFacebookShareDiv').hide();
            {{--Facebook Url hide and Show--}}

            {{--Twitter Url hide and Show--}}
            $('input:radio[name="qrcodeTwitterType"]').change(function () {
                if ($(this).val() == 'url') {
                    $('#qrcodeTwitterUrlDiv').show();
                    $('#qrcodeTwitterTweetDiv').hide();
                    $('#qrcodeTwitterTweet').val('')
                } else if ($(this).val() == 'tweet') {
                    $('#qrcodeTwitterUrl').val('')
                    $('#qrcodeTwitterTweetDiv').show();
                    $('#qrcodeTwitterUrlDiv').hide();
                }
            });
            $('#qrcodeTwitterTweetDiv').hide()
            {{--Twitter Url hide and Show--}}

            generateQRCode(true);
        });

        //Get Form Name
        function getFormName(name) {
            topFunction()
            $('#selectedType').val(name);
            switch (name) {
                case 'urlForm':
                    selectedCollapse = '#collapseOne';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'textForm':
                    selectedCollapse = '#collapseTwo';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'emailForm':
                    selectedCollapse = '#collapseThree';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'phoneForm':
                    selectedCollapse = '#collapseFourth';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'smsForm':
                    selectedCollapse = '#collapseFive';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'vcardForm':
                    selectedCollapse = '#collapseSix';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'mecardForm':
                    selectedCollapse = '#collapseSeven';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'mapsForm':
                    selectedCollapse = '#collapseNine';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'facebookForm':
                    selectedCollapse = '#collapseTen';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'twitterForm':
                    selectedCollapse = '#collapseEleven';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'youtubeForm':
                    selectedCollapse = '#collapseTweleve';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'wifiForm':
                    selectedCollapse = '#collapseThirteen';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'eventForm':
                    selectedCollapse = '#collapseFourtheen';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'bitcoinForm':
                    selectedCollapse = '#collapseFifteen';
                    $(selectedCollapse).addClass("show");
                    break;
                case 'downloadableForm':
                    selectedCollapse = '#collapseSixteen';
                    $(selectedCollapse).addClass("show");
                    break;
            }
        }

        //Placeholder change in input
        $('input:radio[name="qrcodeDownloadableType"]').change(function () {
            $('#qrcodeDownloadableUrl').val('');
            switch ($(this).val()) {
                case 'apps':
                    $('#qrcodeDownloadableUrl').attr("placeholder", "https://play.google.com/store/apps/details?id=");
                    break;
                case 'pdf':
                    $('#qrcodeDownloadableUrl').attr("placeholder", "http://file.pdf");
                    break;
                case 'image':
                    $('#qrcodeDownloadableUrl').attr("placeholder", "http://file.jpg");
                    break;
                case 'music':
                    $('#qrcodeDownloadableUrl').attr("placeholder", "http://file.mp3");
                    break;
                case 'video':
                    $('#qrcodeDownloadableUrl').attr("placeholder", "http://file.mp4");
                    break;
            }
        });

        function removeImageInput(ele) {
            $('#upload-logo-image').val(null);
            removeUpload(1)
            this.logoId = 0;
        }

        function generateQRCode(ele) {
            //Get Active Form Id
            let formName = $('#selectedType').val();
            //End

            //Start show the selected content Type
            $(selectedCollapse).addClass('show')
            // End

            // Page reload make Qr Code for Current URl and Validate form Data
            frontendValidation(formName)

            if (ele == true || $('form[name="' + formName + '"]').valid()) {
                let formData = new FormData($('form[name="' + formName + '"]')[0]);
                // Page Reload
                if (ele == true) {
                    formData.append('qrcodeUrl', window.location.href)
                }
                //Config data
                let data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
                        "qrCodeFrameId": qrCodeFrameid,
                        "eyeBall": eyeName,
                        "bodyColor": bodyColor,
                        "bgColor": bodyColor,
                        "eye1Color": eyeStatus ? frameColor : '#000000',
                        "eye2Color": eyeStatus ? frameColor : '#000000',
                        "eye3Color": eyeStatus ? frameColor : '#000000',
                        "eyeBall1Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall2Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall3Color": eyeStatus ? eyeBallColor : '#000000',
                        "gradientColor1": colorOne,
                        "gradientColor2": colorType ? colorOne : colorTwo,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 500,
                    "download": false,
                    "file": "svg"
                };
                // Get uploaded Image
                var fileToUpload = $('#upload-logo-image').prop('files')[0];

                formData.append('config', JSON.stringify(data));
                formData.append('logo_image', fileToUpload);
                formData.append('eyeStatus', eyeStatus);

                generatedFormData = $('form[name="' + formName + '"]').serializeArray();
                generatedLogoConfigData = JSON.stringify(data);
                generatedLogoEyeStatus = eyeStatus;

                $('#generatedFormData').val(JSON.stringify(generatedFormData))
                $('#generatedLogoConfigData').val(generatedLogoConfigData)
                $('#generatedLogoEyeStatus').val(generatedLogoEyeStatus)

                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('frontend.qr.code.generator') }}",
                    data: formData,
                    enctype: 'multipart/form-data',
                    beforeSend: function () {
                        $('#qrCodeImageAppend').hide();
                        $('#loading').show();
                    },
                    complete: function () {
                        $('#loading').hide();
                        $('#qrCodeImageAppend').show();
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            $('#qrCodeImageAppend').empty();
                            $('#qrCodeImageAppend').append(response.html);
                            imageName = response.image_id;
                            generatedLogoImage = response.logo_image;
                            $('#generatedLogoImage').val(generatedLogoImage)

                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
        }

        //Colors Overlap by suing this
        function colorOverlap(index) {

            let singleColor = $('#colorOne').val();
            let gradientColor = $('#colorTwo').val();

            switch (index) {
                case 1:
                    $('#colorOne').val(gradientColor);
                    $('#colorTwo').val(singleColor);
                    this.colorOne = gradientColor;
                    this.colorTwo = singleColor;
                    break;
                case 2:
                    let eyeColor = $('#colorThree').val();
                    let frameColor = $('#colorFour').val();
                    $('#colorThree').val(frameColor);
                    $('#colorFour').val(eyeColor);
                    this.frameColor = frameColor;
                    this.eyeBallColor = eyeColor;
                    break;
                case 3:
                    let getFrameColor = this.colorType ? singleColor : gradientColor;
                    $('#colorThree').val(singleColor);
                    $('#colorFour').val(getFrameColor);
                    this.frameColor = singleColor;
                    this.eyeBallColor = getFrameColor;
                    break;
            }
        }

        //Get Shape Name
        function getShapeName(selectedShapeName) {
            let shapeNameId = '#shapeName' + selectedShapeName;
            $('.body-shape').removeClass('active');
            $(shapeNameId).addClass('active');
            shapeName = selectedShapeName;
        }

        function getFrameName(selectedFrameName) {
            let frameNameId = '#frameName' + selectedFrameName;
            $('.eye-frame').removeClass('active');
            $(frameNameId).addClass('active');
            frameName = selectedFrameName;
        }

        function getEyeBallName(selectedEyeBallName) {
            let eyeShapeId = '#eyeShape' + selectedEyeBallName;
            $('.eye-shape').removeClass('active');
            $(eyeShapeId).addClass('active');
            eyeName = selectedEyeBallName;
        }

        function getQrCodeFrame(frameId) {
            let eyeShapeId = '#qrCodeFrame' + frameId;
            $('.qr-frames').removeClass('active');
            $(eyeShapeId).addClass('active');
            qrCodeFrameid = frameId;
        }

        function foregroundColor(colorType) {
            $('.radio').change(function () {
                $('.radio').not(this).prop('checked', false);
            });

            if (colorType == 'single') {
                this.colorType = true;
                $('.hide-show').hide();

                $('#color-warning-2').hide();

                if($(".color-warning").css('display') != 'none')
                {
                    $('#color-warning').show();
                }
                else
                {
                    $('#color-warning').hide();
                }
            }

            if (colorType == 'gradient') {
                this.colorType = false;
                $('.hide-show').show()
            }
        }

        function eyeColorStatus() {

            if (!this.eyeStatus) {
                $('.eye-hide-show').show();
                this.eyeStatus = true;
            } else {
                $('.eye-hide-show').hide();
                this.eyeStatus = false;

                $('#color-warning-3').hide();
                $('#color-warning-4').hide();

                if($(".color-warning").css('display') != 'none')
                {
                    $('#color-warning').show();
                }
                else
                {
                    $('#color-warning').hide();
                }
            }
        }

        function colorPicker(ele, id) {
            switch (id) {
                case 1:
                    this.colorOne = ele.value;
                    break;
                case 2:
                    this.colorTwo = ele.value;
                    break;
                case 3:
                    this.frameColor = ele.value;
                    break;
                case 4:
                    this.eyeBallColor = ele.value;
                    break;
                case 5:
                    this.bodyColor = ele.value;
                    break;
            }

            // brightness value can be 0-255, 0 is dark and 255 is light

            var brightness = getColorBrightness(ele.value);

            if(id == 1 || id == 2 || id == 3 || id == 4)
            {
                if (brightness <= 150) {
                    // dark color
                    $('#color-warning-'+id).hide();

                    if(!$('.color-warning').is(":visible"))
                    {
                        $('#color-warning').hide();
                    }
                }
                else
                {
                    // light color
                    $('#color-warning-'+id).show();
                    $('#color-warning').show();
                }
            }
            else if(id == 5)
            {
                if (brightness <= 200) {
                    // dark color
                    $('#color-warning-'+id).show();
                    $('#color-warning').show();
                }
                else
                {
                    // light color
                    $('#color-warning-'+id).hide();
                    if(!$('.color-warning').is(":visible"))
                    {
                        $('#color-warning').hide();
                    }
                }
            }
        }

        function getColorBrightness(color)
        {
            var c = color.substring(1);      // strip #
            var rgb = parseInt(c, 16);   // convert rrggbb to decimal
            var r = (rgb >> 16) & 0xff;  // extract red
            var g = (rgb >>  8) & 0xff;  // extract green
            var b = (rgb >>  0) & 0xff;  // extract blue

            var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
            return luma;
        }

        function colorSchema(ele) {
            this.colorStructure = ele.value;
        }

        function getLogoId(id) {
            let logoImageId = '#logoImage' + id;
            $('.logo-image').removeClass('active');
            $(logoImageId).addClass('active');

            $('#upload-logo-image').val(null);
            let firstImageId = '#' + id;
            $(".file-upload-image").attr("src", $(firstImageId).attr('src'));

            (id == 0 ? $(".file-upload-content").css("display", 'none') : $(".file-upload-content").css("display", 'block'));
            (id == 0 ? $(".image-upload-wrap").css("display", 'block') : $(".image-upload-wrap").css("display", 'none'));
            // $(".image-upload-wrap").css("display", 'none');

            $('#genQrCode').attr('disabled', false);
            $('#allowd_image').removeClass('d-block');
            $('#allowd_image').addClass('d-none');

            this.logoId = id;
        }

        /*Laravel Validation Message*/
        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li id=' + key + '>' + value + '</li>');
                removeErrorMessage(key)
            });
        }


        /*Remove alert after sometime*/

        function removeErrorMessage(key) {
            let index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');

                $(".print-error-msg").css('display', 'none');

            }, 5000);
        }

        function removeImage(ele) {
            $('.file-upload-image').attr('src', '#');
            $('.logo-image').removeClass('active');
            removeImageInput()
        }

        /*whether image is Url or base64*/
        $('img.file-upload-image').on('load', function () {
            if ($("img.file-upload-image[src*='base64']").length == 1) {
                $('.logo-image').removeClass('active');
            }
        })
        /*End*/

        //    Copy Admin QR Code Config
        function copyQrCodeConfig(id) {
            $.ajax({
                type: "get",
                url: "{{ route('frontend.admin.qr.code.data') }}",
                data: {'id': id},
                beforeSend: function () {
                    $('#qrCodeImageAppend').hide();
                    $('#loading').show();
                },
                complete: function () {
                    $('#loading').hide();
                    $('#qrCodeImageAppend').show();
                },
                success: function (response) {
                    $('#qrCodeTemplatesModal').modal('toggle');
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    if (response.status == 1) {
                        shapeName = response.config_data.body;
                        frameName = response.config_data.frame;
                        eyeName = response.config_data.eyeBall;
                        eyeStatus = !response.config_data.eyeStatus;
                        colorOne = response.config_data.gradientColor1;
                        colorTwo = response.config_data.gradientColor2;
                        frameColor = response.config_data.eye1Color;
                        eyeBallColor = response.config_data.eyeBall1Color;
                        bodyColor = response.config_data.bodyColor;
                        colorStructure = response.config_data.gradientType;
                        logoId = response.config_data.logo;
                        colorType = response.config_data.colorType;

                        $('#qrCodeImageAppend').empty();
                        $('#qrCodeImageAppend').append(response.image)

                        getShapeName(shapeName);
                        getFrameName(frameName);
                        getEyeBallName(eyeName);
                        eyeColorStatus();
                        getLogoId(logoId)
                        $('#colorOne').val(colorOne);
                        $('#colorTwo').val(colorTwo);
                        $('#colorThree').val(eyeBallColor);
                        $('#colorFour').val(frameColor);
                        $('#colorFive').val(bodyColor);
                        colorTypeValue = colorType == true ? 'single' : 'gradient';
                        $("input.radio").prop('checked', false)
                        $("input.radio[value=" + colorTypeValue + "]").prop('checked', true)
                        $("input[name=qrcodeUrl]").val(response.data)
                        foregroundColor(colorTypeValue);
                        $('#customEyeColor').prop('checked', eyeStatus);
                        $("#colorSchema").val(colorStructure);

                        // Active Tab and button
                        $('.icon-small-box').removeClass('active');
                        $('.tab-pane').removeClass('active show');
                        $('#url-tab').addClass('active show');
                        $('#url-tab-icon').addClass('active show');
                        getFormName('urlForm')
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        }

        // Get Type of Qr Code and set in the form
        function getQrCodeType(type) {
            $('#file-type').val(type)
            $('#image-name').val(imageName)
        }

        function getExtension(val) {
            var file = $('.file-upload-input').val();
            var exten = file.split('.').pop();
            if (exten != 'png') {
                $('#genQrCode').attr('disabled', true);
                $('#allowd_image').removeClass('d-none');
                $('#allowd_image').addClass('d-block');
            } else {
                $('#genQrCode').attr('disabled', false);
                $('#upload-logo-image').empty();
                $('#allowd_image').removeClass('d-block');
                $('#allowd_image').addClass('d-none');
            }
            readURL(val);
        }

        $('#remove-image').on('click', function () {
            $('#genQrCode').attr('disabled', false);
            $('#upload-logo-image').empty();
            $('#allowd_image').removeClass('d-block');
            $('#allowd_image').addClass('d-none');
            $('.image-title').html(' ')
        });

        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        function loadMore(loadMoreStatus) {
            if(loadMoreStatus == 1){
                let countFaq = '{{$faqs->count()}}';
                for (i = 7; i <= countFaq; i++) {
                    $('#faqIndex' + i).css('display', 'block');
                }

                this.isLoadMore = 2;
                $('#load-more').parent().removeClass('loadmore-option');
                $('#load-more').parent().addClass('lessmore-option');
                $('#load-more').html('{{__('Load Less')}}')
            } else{
                let countFaq = '{{$faqs->count()}}';
                for (i = 7; i <= countFaq; i++) {
                    $('#faqIndex' + i).css('display', 'none');
                }
                this.isLoadMore = 1;
                $('#load-more').parent().removeClass('lessmore-option');
                $('#load-more').parent().addClass('loadmore-option');
                $('#load-more').html('{{__('Load More')}}')
            }
        }
    </script>
@endsection
