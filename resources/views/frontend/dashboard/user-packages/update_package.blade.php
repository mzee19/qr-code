@extends('frontend.layouts.dashboard')

@section('title', __('Subscriptions'))

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-center">
                        @if(auth()->user()->package_recurring_flag && auth()->user()->subscription->package_id != 1)
                            @php
                                $dt = new DateTime();
                                $dt->setTimezone(new DateTimeZone(auth()->user()->timezone));
                                $dt->setTimestamp(auth()->user()->subscription->end_date);
                            @endphp
                            <ul class="nav nav-tabs justify-content-center d-flex upgrate-package-tab" id="myTab" role="tablist">
                                <li class="nav-item mr-2 mt-2 due-date-des">
                                    {{__('Next payment due date:').' '. $dt->format('F d, Y')}}
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('frontend.user.cancel.current.package')}}"
                                       class="nav-link btn btn-primary">{{__('Cancel Subscription')}}</a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="section-title text-center">
                        <h2 class="sub-title">{{__('Choose Your Plan')}}</h2>
                        <p>{{__('Choose a Plan that suits your needs.')}}</p>
                    </div>
                </div>
            </div>
            <div class="tab-section-subscription -mt-12">
                @include('frontend.messages')
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link active" id="month-tab"
                           onclick="setPackageType('monthly')">{{__('Monthly')}}</a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" id="year-tab"
                           onclick="setPackageType('yearly')">{{__('Yearly')}}</a>
                    </li>
                </ul>
                <div class="edit--compaigns tabs-content subscription-tabs" id="myTabContent">
                    <div class="edit--compaigns tabs-content subscription-tabs head" id="myTabContent">
                        <div class="tab-pane fade show active" id="dynamic" role="tabpanel"
                             aria-labelledby="dynamic-tab">
                            <div class="container pricing-table package-sec">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- packages section -->
            <div class="subscription-pricing--layout">
                <div class="subscription-pricing--tiers">
                    <div class="tab-content">
                        <div class="subscription-pricing-panel--row row " style="flex-wrap: nowrap;">
                            @foreach($packages as $package)
                                <div
                                    class="subscription-pricing-summary--panel subscription-5-panel col-12 col-sm-7 col-md-5 col-lg-4 col-xl-3">
                                    <div class="tier-summary--panel">
                                        <div class="price-col-bg">
                                            <svg class="img-fluid" xmlns="http://www.w3.org/2000/svg"
                                                 xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px"
                                                 y="0px" width="490.768px" height="500px" viewBox="0 0 490.768 500"
                                                 enable-background="new 0 0 490.768 500" space="preserve">
                                                <g>
                                                    <g id="OBJECTS">
                                                        <path
                                                            d="M340.104,174.298c-4.801-8.493-1.847-19.202,6.277-24.003l144.387-83.457V0H0v422.083l490.768-278.435    v-35.82l-126.661,73.117C355.612,185.746,344.903,182.792,340.104,174.298z"></path>
                                                        <path
                                                            d="M237.445,342.688c-4.801-8.492-15.51-11.447-24.003-6.646L0,459.38V500l230.798-133.309    C239.291,361.521,242.244,351.182,237.445,342.688z"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="tier-summary-panel--header text-center">
                                            <p class="tier-summary--title"><span
                                                    class="text-uppercase">{{$package->title}}</span></p>
                                            <div class="tier-summary--price">
                                            <span class="vat-prices d-block">
                                                <p class="monthly-price">Net: {{config('constants.currency')['symbol'] .$package->monthly_price}}</p>
                                                <p class="yearly-price">Net: {{config('constants.currency')['symbol'] .$package->yearly_price}}</p>
{{--                                                <p class="monthly-price">{{config('constants.currency')['symbol'] .$package->monthly_price}}--}}
{{--                                                            / {{__('month')}}</p>--}}
{{--                                                        <p class="yearly-price">{{config('constants.currency')['symbol'] . $package->yearly_price}}--}}
{{--                                                            / {{__('Year')}}</p>--}}
{{--                                                <p>--}}
                                                    @php
                                                $totalMonthlyPrice = $vatAmount * $package->monthly_price + $package->monthly_price;
                                                $totalYearlyPrice = $vatAmount * $package->yearly_price + $package->yearly_price;
                                                @endphp
                                                            <small
                                                                class="monthly-price">@if($package->id != 2){{'('.($vatAmount * 100).'%)'}}
                                                                @else
                                                                    {{'(0%)'}}
                                                                @endif {{(__('VAT inc.'))}} </small>
{{--                                                                class="monthly-price">@if($package->id != 2){{'('.($vatAmount * 100).'%)'}}@endif {{(__('VAT inc.')).' '.config('constants.currency')['symbol']. ($vatAmount * $package->monthly_price + $package->monthly_price)}} </small>--}}
                                                            <small
                                                                class="yearly-price">@if($package->id != 2){{'('.($vatAmount * 100).'%)'}}
                                                                @else
                                                                    {{'(0%)'}}
                                                                @endif {{(__('VAT inc.'))}} </small>
{{--                                                                class="yearly-price">@if($package->id != 2){{'('.($vatAmount * 100).'%)'}}@endif {{(__('VAT inc.')).' '.config('constants.currency')['symbol']. ($vatAmount * $package->yearly_price + $package->yearly_price)}} </small>--}}
                                            </span>
                                                <p class="monthly-price">{{config('constants.currency')['symbol']. ($totalMonthlyPrice == '0' ? $totalMonthlyPrice : number_format($totalMonthlyPrice,2,'.',''))}}
                                                     <span class="tier-summary-month-descriptor"> /{{__('mo')}}</span>
                                                </p>
                                                <p class="yearly-price">{{config('constants.currency')['symbol']. ($totalYearlyPrice == '0' ? $totalYearlyPrice : number_format($totalYearlyPrice,2,'.',''))}}
                                                   <span class="tier-summary-month-descriptor"> /{{__('yr')}}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="tier-summary-panel--body">
                                            <div class="tier-summary--description">{{translation($package->id,2,App::getLocale(),'description',strip_tags($package->description))}}</div>
                                            <div class="short-details-holder">
                                                <ul class="tier-summary-consumables--list text-center">
                                                    @php
                                                        $packageFeatureDynamicQrCode = $package->linkedFeatures->where('feature_id' , 1)->first();
                                                        $packageFeatureStatic = $package->linkedFeatures->where('feature_id' , 9)->first();
                                                    @endphp
                                                    <li>
                                                        <strong
                                                            class="feature-value d-block text-capitalize">{{$packageFeatureDynamicQrCode->count ?? ''}}</strong>
                                                        <span class="feature-name d-block text-uppercase">{{__('Dynamic QR Codes')}}</span>
                                                    </li>
                                                    <li>
                                                        <strong
                                                            class="feature-value d-block text-capitalize">{{$packageFeatureStatic->count}}</strong>
                                                        <span class="feature-name d-block text-uppercase">{{__('Statistics')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="bottom-btn-holder">
                                                @if($packageSubscription->package_id == $package->id && $package->id == 2)
                                                    <button class="btn btn-primary" type="button"
                                                            disabled="disabled">
                                                        {{__('Free')}}
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-primary current-btn disabled text-uppercase {{$package->id== 2 ? 'd-none' : ''}} 1-{{$package->id}}-btn"
                                                        type="button" disabled="disabled"
                                                        id="">{{__('Current')}}
                                                    </button>
                                                    <form action="{{route('frontend.user.subscribe')}}"
                                                          method="post" class="current-form 1-{{$package->id}}-form"
                                                          id="">
                                                        @csrf
                                                        @method('GET')
                                                        <input name="type" value="1" type="hidden">
                                                        <input name="lang" value="en" type="hidden">
                                                        <input name="package_id" value="{{$package->id}}"
                                                               type="hidden">
                                                        <button class="btn btn-primary text-uppercase"
                                                                type="submit">{{$package->id == 2 ? __('Free') : __('Subscribe')}}</button>
                                                    </form>
                                                @endif
                                                {{--                                                <button class="btn btn-pink" tabindex="0">Get started</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!--packages detail  -->
                    <div class="subscription-title--container">
                        <h2 class="text-center">
                            <span class="d-inline-block align-top">{{__('Detailed Comparison')}}</span>
                        </h2>
                        <p class="text-center mt-n3  mb-0">{{__('See what these packages have to offer you.')}}</p>
                    </div>
                    <div class="subscription-pricing--hidden-mobile floink-pricing-block">
                        <div class="subscription-pricing-comparison-matrix floink-pricing-row">
                            <div class="subscription-pricing-comparison-label-column floink-pricing-col ">
                                <ul>
                                    <div class="tier-comparison-label--section">
                                        <li class="section--title"><strong>{{__('Features')}}</strong></li>
                                        @foreach($packageFeatures as $packageFeature)
                                            <div>
                                                <li>
                                                    <p>{{translation($packageFeature->id,1,App::getLocale(),'name',$packageFeature->name)}}
                                                        <span data-toggle="tooltip" data-placement="top" title=""
                                                              data-original-title="{{translation($packageFeature->id,1,App::getLocale(),'info',$packageFeature->info)}}">
                                                    <i class="fa fa-question-circle cur-poi"></i>
                                                    </span>
                                                    </p>
                                                </li>
                                            </div>
                                        @endforeach

                                    </div>
                                </ul>

                            </div>
                            @foreach($packages as $package)

                                <div class="subscription-pricing-comparison-column subscription-pricing-col">
                                    <ul>
                                        <li class="tier-comparison-column--title">
                                            <strong>{{$package->title}}</strong>
                                        </li>
                                        <div class="tier-comparison-column--section">
                                            @foreach($packageFeatures as $packageFeature)

                                                @php
                                                    $packageLinkFeature = $package->linkedFeatures->where('feature_id' , $packageFeature->id)->first();
                                                @endphp
                                                <div>
                                                    <li>{!!  isset($packageLinkFeature) ? (empty($packageLinkFeature->count) ? '<span><i class="fa fa-check-circle"></i></span>' : ucwords($packageLinkFeature->count)) : '' !!}
                                                    </li>
                                                </div>
                                            @endforeach
                                        </div>
                                    </ul>
                                    <div class="sticky-upgrade-button--column"><span><div
                                                class="sticky-upgrade-button--column text-center">
                                                 @if($packageSubscription->package_id == $package->id && $package->id == 2)
                                                    <button class="btn btn-primary text-uppercase" type="button"
                                                            disabled="disabled">
                                                        {{__('Free')}}
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-primary current-btn disabled {{$package->id== 2 ? 'd-none' : ''}} 1-{{$package->id}}-btn"
                                                        type="button" disabled="disabled"
                                                        id="">{{__('Current')}}
                                                    </button>
                                                    <form action="{{route('frontend.user.subscribe')}}"
                                                          method="post" class="current-form 1-{{$package->id}}-form"
                                                          id="">
                                                        @csrf
                                                        @method('GET')
                                                        <input name="type" value="1" type="hidden">
                                                        <input name="lang" value="en" type="hidden">
                                                        <input name="package_id" value="{{$package->id}}"
                                                               type="hidden">
                                                        <button class="btn btn-primary text-uppercase"
                                                                type="submit">{{$package->id == 2 ? __('Free') : __('Subscribe')}}</button>
                                                    </form>
                                                @endif
                                            </div></span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-center">{{__('For further information :')}} <a href="{{route('frontend.contact')}}">{{__('Contact Us')}}</a> {{__('or drop us your query at')}} <a
                        href="mailto:{{settingValue('contact_email')}}">{{settingValue('contact_email')}} </a></p>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        var paymentType = '{{isset($packageSubscription) ? $packageSubscription->type : ''}}';
        var packageId = '{{isset($packageSubscription) ? $packageSubscription->package->id : ''}}';
        var packageNameAndType = paymentType + '-' + packageId;
        $(document).ready(function () {
            $('.current-btn').hide();
            $('#month-tab').addClass('active');
            $('.yearly-price').hide();
            setPackageType('monthly')
        });

        function setPackageType(type) {
            let selectedPackageBtn = '.' + packageNameAndType + '-btn';
            let selectedPackageForm = '.' + packageNameAndType + '-form';
            if (type == 'monthly') {
                if (paymentType == 1) {
                    $(selectedPackageBtn).show();
                    $(selectedPackageForm).hide();
                } else {
                    $('.current-btn').hide();
                    $('.current-form').show();
                }

                $('input[name="type"]').val('1')
                $('#month-tab').addClass('active');
                $('#year-tab').removeClass('active');
                $('.monthly-price').show();
                $('.yearly-price').hide();

                $('.current-btn').each(function () {
                    var res = $(this).attr('class').replace("1", "2")
                    $(this).attr('class', res);
                });

                $('.current-form').each(function () {
                    var res = $(this).attr('class').replace("1", "2")
                    $(this).attr('class', res);
                });

            } else if (type == 'yearly') {

                if (paymentType == 2) {
                    $(selectedPackageBtn).show();
                    $(selectedPackageForm).hide();
                } else {
                    $('.current-btn').hide();
                    $('.current-form').show();
                }
                $('input[name="type"]').val('2')
                $('#year-tab').addClass('active');
                $('#month-tab').removeClass('active');
                $('.yearly-price').show();
                $('.monthly-price').hide();

                $('.current-btn').each(function () {
                    var res = $(this).attr('class').replace("2", "1")
                    $(this).attr('class', res);
                });

                $('.current-form').each(function () {
                    var res = $(this).attr('class').replace("2", "1")
                    $(this).attr('class', res);
                });
            }
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection

