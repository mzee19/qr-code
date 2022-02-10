@extends('frontend.layouts.dashboard')
@section('title', $tabTitle.' '.__('QR Code'))

@section('content')
    <style type="text/css">
        #image_cropper {
            display: block;
            max-width: 100%;
        }
    </style>
    <div class="content-body">
        <div class="row">
            <div class="col-md-12">
                <div class="fixed--left--qrbar">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title">
                                <h3 class="sub-title">{{__('General')}}</h3>
                                <div class="text-right">@isset($generateQrCode->created_at)
                                        {{__('Created')}}
                                        : {{ \Carbon\Carbon::createFromTimeStamp(strtotime($generateQrCode->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}@endisset</div>
                            </div>
                            <div class="cardbox">
                                <div class="cardbox-inner">
                                    <form id="save-qr-code-form">

                                        <div class="form-group">
                                            <label><b>{{__('Name')}} </b><span class="text-danger"> *</span></label>
                                            <span> {{__('A short title that identifies your QR code and helps you find it again')}}</span>
                                            <input class="form-control" type="text" name="name" id="general-name"
                                                   maxlength="20"
                                                   placeholder="{{__('e.g. vCard John Doe')}}" required
                                                   value="{{ ($action == 'Add') ? old('name') : $generateQrCode->name}}">
                                        </div>
                                        @if(checkFieldStatus(4))
                                            <div class="form-group">
                                                <label>{{__('Campaign')}}</label>
                                                <select-campaign>

                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-folder-o"></i>
                                                        </div>
                                                        <select id="add-campaign" class="form-control" name="campaignId"
                                                        >
                                                            <option value="">- {{__('no campaign')}} -</option>
                                                            @php $campaignId = $action == 'Add' ? old('campaignId') : $generateQrCode->campaign_id @endphp

                                                            @foreach($campaigns as $campaign)
                                                                <option
                                                                    value="{{$campaign->id}}" {{ $campaignId == $campaign->id ? 'selected' : ''}}>{{$campaign->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-btn add-campaigns">
                                                            <button class="btn btn-primary" type="button">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </select-campaign>
                                            </div>
                                        @endif

                                    </form>
                                    @if(checkFieldStatus(4))
                                        <form id="campaign-form">
                                            <div class="subform form-new-campaign pt-3" style="display: none;">
                                                <label>{{__('New Campaign')}}<span class="text-danger"> *</span></label>
                                                <div class="input-group">
                                                    <input type="hidden" value="Add" name="action">
                                                    <input class="form-control" id="campaign-name" name="name"
                                                           placeholder="{{__('e.g. QR Codes 2018')}}"
                                                           type="text" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" type="button"
                                                                id="campaign-btn">
                                                            {{__('Create')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="section-title pt-2">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="sub-title">{{__('Content')}}</h3>
                                    </div>
                                    @if($parameters['type'] == 'dynamic')
                                        <div class="col-auto">
                                            <div class="url">
                                                <i title="{{__('Short URL of dynamic QR code')}}"
                                                   id="ned-link"> {{($action == 'Add' ? Request::getSchemeAndHttpHost().'/qr-code/'.$parameters['unique_id'] : ($generateQrCode->ned_link ? $generateQrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$generateQrCode->unique_id))}}
                                                </i>
                                                <button class="btn btn-sm btn-icon" type="button" data-toggle="modal"
                                                        data-target="#change-setting"><i class="fa fa-cog"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="cardbox section">
                                <div class="cardbox-inner cardbox-head">
                                    <div class="title text-left ">
                                        @switch($parameters['content_type'])
                                            @case('url')
                                            <i class="fa fa-link"></i> {{__('Url')}}
                                            @break
                                            @case('vcard')
                                            <i class="fa fa-address-card"></i> {{__('VCard')}}
                                            @break
                                            @case('text')
                                            <i class="fa fa-file-text-o"></i> {{__('Text')}}
                                            @break
                                            @case('email')
                                            <i class="fa fa-envelope-o"></i> {{__('Email')}}
                                            @break
                                            @case('phone')
                                            <i class="fa fa-phone"></i> {{__('Phone')}}
                                            @break
                                            @case('sms')
                                            <i class="fa fa-sms"></i> {{__('SMS')}}
                                            @break
                                            @case('app_store')
                                            <i class="fab fa-app-store-ios"></i> {{__('App Store')}}
                                            @break
                                            @case('event')
                                            <i class="fa fa-calendar"></i> {{__('Event')}}
                                            @break
                                            @case('wifi')
                                            <i class="fa fa-wifi"></i> {{__('WiFi Connectivity')}}
                                            @break
                                        @endswitch
                                    </div>
                                    @if($action == 'Add')
                                        <div class="options">
                                            <a class="btn btn-outline-secondary btn-sm"
                                               href="{{route('frontend.user.qr-codes.select.content.type')}}">
                                                <i class="fa fa-pencil"></i> {{__('Edit Type')}}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="cardbox section">
                                    <div class="cardbox-inner cardbox-head">
                                        <form id="dynamic-content-type" style="width:100%">
                                            <input name="qrCodeType" type="hidden" value="{{$parameters['type']}}"/>
                                            <input name="type" type="hidden" value="{{$parameters['content_type']}}"/>
                                            <input name="action" type="hidden" value="{{$action}}"/>
                                            <input id="uniqueId" name="uniqueId" type="hidden"
                                                   value="{{$action == 'Add' ? $parameters['unique_id'] : $generateQrCode->unique_id}}"/>
                                            <input name="id" type="hidden" value="{{$generateQrCode->id}}"/>
                                            <div class="row">
                                                @switch($parameters['content_type'])
                                                    @case('url')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="url">{{__('Url')}}<span
                                                                    class="text-danger"> *</span> <span
                                                                    class="shortinfo">{{__('Set the link to your website')}}.</span>
                                                            </label>
                                                            <input class="form-control" name="qrcodeUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeUrl']}}"
                                                                   placeholder="http://..." type="url" required
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    @break
                                                    @case('vcard')
                                                    <input name="vcardVersion"
                                                           value="3.0" type="hidden">
                                                    <div class="col-sm-6 col-12">
                                                        <div class="form-group"><label>{{__('First Name')}}</label>
                                                            <input class="form-control" id="qrcodeVcardFirstName"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardFirstName']}}"
                                                                   name="qrcodeVcardFirstName" type="text"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Last Name')}}</label>
                                                            <input class="form-control" id="qrcodeVcardLastname"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardLastName']}}"
                                                                   name="qrcodeVcardLastName" type="text"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Organization')}}</label>
                                                            <input class="form-control" id="qrcodeVcardOrganization"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardOrganization']}}"
                                                                   name="qrcodeVcardOrganization" type="text"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12" style="display: none">
                                                        <div class="form-group">
                                                            <label>{{__('Position (Work)')}} </label>
                                                            <input class="form-control" id="qrcodeVcardTitle"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardTitle']}}"
                                                                   name="qrcodeVcardTitle" type="text"
                                                                   onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Phone (Work)')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <input class="form-control" id="qrcodeVcardPhoneWork"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardPhoneWork']}}"
                                                                   name="qrcodeVcardPhoneWork" type="tel"
                                                                   onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12" style="display:none">
                                                        <div class="form-group"><label
                                                                for="qrcodePhoneMobile">{{__('Phone (Private)')}}</label><input
                                                                class="form-control"
                                                                id="qrcodeVcardPhonePrivate"
                                                                name="qrcodeVcardPhonePrivate"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardPhonePrivate']}}"
                                                                type="tel"
                                                                onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodePhoneMobile">{{__('Phone (Mobile)')}}</label><input
                                                                class="form-control"
                                                                id="qrcodeVcardPhoneMobile"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardPhoneMobile']}}"
                                                                name="qrcodeVcardPhoneMobile" type="tel"
                                                                onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeFaxWork">{{__('Fax (Work)')}}</label>
                                                            <input
                                                                class="form-control"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardFaxWork']}}"
                                                                id="qrcodeVcardFaxWork" name="qrcodeVcardFaxWork"
                                                                type="text"
                                                                onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12" style="display: none">
                                                        <div class="form-group"><label
                                                                for="qrcodeFaxWork">{{__('Fax (Private)')}}</label><input
                                                                class="form-control"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardFaxPrivate']}}"
                                                                id="qrcodeVcardFaxPrivate"
                                                                name="qrcodeVcardFaxPrivate"
                                                                type="text" onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeEmail">{{__('Email')}}</label><input
                                                                class="form-control" id="qrcodeVcardEmail"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardEmail']}}"
                                                                name="qrcodeVcardEmail" type="email"
                                                                onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeUrl">{{__('Website')}}</label><input
                                                                class="form-control" id="qrcodeVcardUrl"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardUrl']}}"
                                                                name="qrcodeVcardUrl" type="url"
                                                                onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardStreet">{{__('Street')}}</label><input
                                                                class="form-control" id="qrcodeVcardStreet"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardStreet']}}"
                                                                name="qrcodeVcardStreet" type="text"
                                                                onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeZipcode">{{__('Zipcode')}}</label><input
                                                                class="form-control"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardZipcode']}}"
                                                                id="qrcodeVcardZipcode"
                                                                name="qrcodeVcardZipcode"
                                                                type="text"
                                                                onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardCity">{{__('City')}}</label><input
                                                                class="form-control" id="qrcodeVcardCity"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardCity']}}"
                                                                name="qrcodeVcardCity" type="text"
                                                                onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeState">{{__('State')}}</label><input
                                                                class="form-control" id="qrcodeVcardState"
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardState']}}"
                                                                name="qrcodeVcardState" type="text"
                                                                onchange="getQrCodeByField(this)"></div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeCountry">{{__('Country')}}</label><input
                                                                class="form-control"
                                                                dynamic-content-type
                                                                value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeVcardCountry']}}"
                                                                id="qrcodeVcardCountry"
                                                                name="qrcodeVcardCountry"
                                                                type="text"
                                                                onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    @break
                                                    @case('text')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Your Text')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <textarea class="form-control"
                                                                      id="qrcodeText"
                                                                      name="text" rows="6" required
                                                                      onchange="getQrCodeByField(this)">{{ ($action == 'Add') ? old('qrcodeText') : json_decode($generateQrCode->fields, true)['text']}}</textarea>
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('email')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Your Email')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <input class="form-control" id="qrcodeEmail"
                                                                   name="qrcodeEmail"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeEmail']}}"
                                                                   placeholder="name@mail.com" type="email"
                                                                   required
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeEmailMessage">{{__('Subject')}}</label>
                                                            <input class="form-control" type="text"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeEmailSubject']}}"
                                                                   id="qrcodeEmailSubject"
                                                                   name="qrcodeEmailSubject"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeEmailMessage">{{__('Message')}}</label>
                                                            <textarea class="form-control"
                                                                      id="qrcodeEmailMessage"
                                                                      maxlength="400" name="qrcodeEmailMessage"
                                                                      rows="3"
                                                                      onchange="getQrCodeByField(this)">{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeEmailMessage']}}</textarea>
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('phone')
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url">{{__('Phone Number')}}<span
                                                                    class="text-danger"> *</span>
                                                            </label>
                                                            <input class="form-control" name="qrcodePhone"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodePhone']}}"
                                                                   placeholder="+49 172 45921..." type="tel"
                                                                   required
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('sms')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Phone Number')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <input class="form-control"
                                                                   name="qrcodeSmsPhone"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeSmsPhone']}}"
                                                                   placeholder="+49 172 45921..."
                                                                   type="tel"
                                                                   required
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeEmailMessage">{{__('Message')}}</label>
                                                            <textarea class="form-control"
                                                                      id="qrcodeSmsText" maxlength="400"
                                                                      name="qrcodeSmsText" rows="3"
                                                                      onchange="getQrCodeByField(this)">{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeSmsText']}} </textarea>
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('app_store')
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url">{{__('Default URL')}}
                                                                <span
                                                                    class="shortinfo">{{__('Fallback URL for devices without available app store')}}.</span>
                                                            </label>
                                                            <input class="form-control"
                                                                   name="qrcodeAppStoreUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeAppStoreUrl']}}"
                                                                   placeholder="http://..."
                                                                   type="url"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url">{{__('iOS App Store')}}
                                                            </label>
                                                            <input class="form-control"
                                                                   name="qrcodeAppStoreAppStoreUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeAppStoreAppStoreUrl']}}"
                                                                   placeholder="http://..."
                                                                   type="url"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label
                                                                for="url">{{__('Google Play Store')}}
                                                            </label>
                                                            <input class="form-control"
                                                                   name="qrcodeAppStoreGooglePlayStoreUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeAppStoreGooglePlayStoreUrl']}}"
                                                                   placeholder="http://..."
                                                                   type="url"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url">{{__('Window Store')}}
                                                            </label>
                                                            <input class="form-control"
                                                                   name="qrcodeAppStoreWindowStoreUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeAppStoreWindowStoreUrl']}}"
                                                                   placeholder="http://..."
                                                                   type="url"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url">{{__('Blackberry')}}
                                                            </label>
                                                            <input class="form-control"
                                                                   name="qrcodeAppStoreBlackberryUrl"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeAppStoreBlackberryUrl']}}"
                                                                   placeholder="http://..."
                                                                   type="url"
                                                                   onchange="getQrCodeByField(this)">
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('event')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Title')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <input class="form-control"
                                                                   name="summary"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['summary']}}"
                                                                   placeholder="{{__('e.g. Johns Birthday Party')}}"
                                                                   type="text"
                                                                   onchange="getQrCodeByField(this)"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    {{--                                                    @if($parameters['type'] == 'dynamic')--}}
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Location')}}<span class="text-danger"> *</span></label>
                                                            <input class="form-control"
                                                                   name="location" type="text"
                                                                   value="{{ ($action == 'Add') ? old('name') : (isset(json_decode($generateQrCode->fields, true)['location']) ? json_decode($generateQrCode->fields, true)['location'] : '' )}}"
                                                                   onchange="getQrCodeByField(this)"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    {{--                                                    @endif--}}
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Timezone')}}</label>
                                                            <select class="form-control"
                                                                    id="qrcodeEventTimezone"
                                                                    name="qrcodeEventTimezone"
                                                                    required>
                                                                @foreach($timezones as $index => $timezone)
                                                                    <option
                                                                        value="{{$timezone->name}}" {{ ($action == 'Add') ? ($index == 0 ? 'selected':'') : (json_decode($generateQrCode->fields, true)['qrcodeEventTimezone'] == $timezone->name ? 'selected' : '') }}>
                                                                        {{$timezone->name}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if($parameters['type'] == 'dynamic')
                                                        <div class="col-12"
                                                             style="display: {{ $parameters['type'] == 'static' ? 'none' : 'block'}}">
                                                            <div class="form-group">
                                                                <label>{{__('Reminder')}}</label>
                                                                <select class="form-control"
                                                                        id="reminder"
                                                                        name="qrcodeEventReminder"
                                                                        required>
                                                                    <option
                                                                        value="0" {{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '0' ? 'selected' :'') : '') : ''}}>
                                                                        {{__('Auto')}}
                                                                    </option>
                                                                    <option
                                                                        value="5" {{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '5' ? 'selected' :'') : '') : ''}}>
                                                                        5 {{__('Minutes')}}
                                                                    </option>
                                                                    <option
                                                                        value="15" {{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '15' ? 'selected' :'') : '') : ''}}>
                                                                        15 {{__('Minutes')}}
                                                                    </option>
                                                                    <option
                                                                        value="30" {{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '30' ? 'selected' :'') : '') : ''}}>
                                                                        30 {{__('Minutes')}}
                                                                    </option>
                                                                    <option
                                                                        value="60" {{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '60' ? 'selected' :'') : '') : ''}}>
                                                                        1 {{__('Hour')}}
                                                                    </option>
                                                                    <option
                                                                        value="720"{{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '720' ? 'selected' :'') : '') : ''}}>
                                                                        12 {{__('Hour')}}
                                                                    </option>
                                                                    <option
                                                                        value="1440"{{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '1440' ? 'selected' :'') : '') : ''}}>
                                                                        1 {{__('Day')}}
                                                                    </option>
                                                                    <option
                                                                        value="10080"{{($action == 'Edit') ? (isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) ? (json_decode($generateQrCode->fields, true)['qrcodeEventReminder'] == '10080' ? 'selected' :'') : '') : ''}}>
                                                                        1 {{__('Week')}}
                                                                    </option>
                                                                    @if($action == 'Edit' && isset(json_decode($generateQrCode->fields, true)['qrcodeEventReminder']) && !in_array(json_decode($generateQrCode->fields, true)['qrcodeEventReminder'],[0,5,15,30,60,720,1440,10080]))
                                                                        <option
                                                                            value="{{json_decode($generateQrCode->fields, true)['qrcodeEventReminder']}}"
                                                                            selected>
                                                                            {{json_decode($generateQrCode->fields, true)['qrcodeEventReminder']}} {{__('Minutes')}}
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12"
                                                             style="display: {{ $parameters['type'] == 'static' ? 'none' : 'block'}}">
                                                            <div class="form-group">
                                                                <label>{{__('Description')}}<span
                                                                        class="text-danger"> *</span></label>
                                                                <textarea class="form-control"
                                                                          id="qrcodeEventDescription"
                                                                          maxlength="400"
                                                                          name="qrcodeEventDescription"
                                                                          rows="3" required
                                                                >{{ ($action == 'Add') ? old('name') : (isset(json_decode($generateQrCode->fields, true)['qrcodeEventDescription']) ? json_decode($generateQrCode->fields, true)['qrcodeEventDescription'] : '')}}</textarea>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{__('Start Date')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <input class="form-control"
                                                                   name="startDateTime"
                                                                   id="startDateTime"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['startDateTime']}}"
                                                                   placeholder="mm/dd/years"
                                                                   type="datetime-local"
                                                                   onchange="getQrCodeByField(this)"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{__('End Date')}}<span class="text-danger"> *</span></label>
                                                            <input class="form-control"
                                                                   name="endDateTime"
                                                                   id="endDateTime"
                                                                   value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['endDateTime']}}"
                                                                   placeholder="mm/dd/years"
                                                                   type="datetime-local"
                                                                   onchange="getQrCodeByField(this)"
                                                                   required>
                                                        </div>
                                                    </div>
                                            </div>
                                            @break
                                            @case('wifi')
                                            <div class="col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label
                                                        for="">{{__('Wirless SSID')}}<span class="text-danger"> *</span></label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name="ssid"
                                                           value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['ssid'] }}"
                                                           id="qrcode-wifi-ssid"
                                                           placeholder="" required
                                                           onchange="getQrCodeByField(this)">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label
                                                        for="">{{__('Password')}}<span
                                                            class="text-danger"> *</span></label>
                                                    <input type="text"
                                                           class="form-control"
                                                           name="password"
                                                           id="password" placeholder=""
                                                           required
                                                           value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['password']}}"
                                                           onchange="getQrCodeByField(this)">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label
                                                        for="">{{__('Encryption')}}</label>
                                                    <select class="form-control"
                                                            name="encryption"
                                                            id="encryption"
                                                            required
                                                            onchange="getQrCodeByField(this)">
                                                        <option
                                                            @isset(json_decode($generateQrCode->fields, true)['encryption']) @if(json_decode($generateQrCode->fields, true)['encryption'] == 'WEP') selected
                                                            @endif @endisset value="WEP">
                                                            WEP
                                                        </option>
                                                        <option
                                                            @isset(json_decode($generateQrCode->fields, true)['encryption']) @if(json_decode($generateQrCode->fields, true)['encryption'] == 'WPA') selected
                                                            @endif @endisset value="WPA">
                                                            WPA/WPA2
                                                        </option>
                                                        <option
                                                            @isset(json_decode($generateQrCode->fields, true)['encryption']) @if(json_decode($generateQrCode->fields, true)['encryption'] == 'nopass') selected
                                                            @endif @endisset value="nopass">
                                                            {{__('No Encryption')}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            @break

                                            @default
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="url">{{__('URL')}}<span class="text-danger"> *</span>
                                                        <span
                                                            class="shortinfo">{{__('Set the link to your website')}}.</span>
                                                    </label>
                                                    <input class="form-control"
                                                           name="qrcodeUrl"
                                                           value="{{ ($action == 'Add') ? old('name') : json_decode($generateQrCode->fields, true)['qrcodeUrl']}}"
                                                           placeholder="http://..."
                                                           type="url" required
                                                           onchange="getQrCodeByField(this)">
                                                </div>
                                            </div>
                                        @endswitch
                                    </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fixed QR code Right sidebar -->
            <!-- <div class="col-md-4"> -->
            <div class="fixed-right-qrbar">
                <div class="col-12">
                    <button class="btn btn-orange w-100 mb-3" type="button"
                            id="save-qr-code-btn" {{ ($action == 'Add') ? 'disabled' : ''}} >
                        <i class="fa fa-plus"></i> {{ ($action == 'Add') ? __('Create') : __('Save')}}
                    </button>
                </div>
                <div class="cardbox">
                    <div class="qrcode-container">
                        <div class="qrcode dashboard-generated-qr-code mt-3"
                             id="generated-qr-code">
                            <img class="qr-code-scan"
                                 src="{{checkImage(asset('storage/users/'.$generateQrCode->user_id.'/qr-codes/' . $generateQrCode->image),'default.svg',$generateQrCode->image)}}">
                        </div>
                        <div class="fa-5x mt-4" id="loading"
                             style="display: none">
                            <i class="fa fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    @if(checkFieldStatus(5))

                        <div class="cardbox-inner">
                            <button id="custom-qr-btn"
                                    class="btn btn-primary w-100"
                                    type="button">
                                <i class="fa fa-paint-brush"></i> {{__('Design QR Code')}}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- End -->
    </div>
    <!--------------------- Model Popups ------------------------->
    <!-- Change Setting Popup -->
    <div class="modal fade  " id="change-setting">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">{{__('Edit Short URL')}}</h5>
                        <button type="button" class="close"
                                data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                {{--                                    @if($action == 'Add')--}}
                                {{--                                                <span--}}
                                {{--                                                    class="input-group-addon">{{ Request::getSchemeAndHttpHost().'/qr-code/' }}</span>--}}
                                {{--                                    @endif--}}
                                <input class="form-control" type="text"
                                       id="ned-link-input"
                                       value="{{$action == 'Add' ? $parameters['unique_id'] : ($generateQrCode->ned_link ? $generateQrCode->ned_link : url('qr-code',$generateQrCode->unique_id))}}"
                                       readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Change URL</button> -->
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- design qr code -->
    <div class="modal fade manager  design-qrcode-model" id="design-qrcode">
        <div class="modal-dialog modal-lg" role="document">
            <div id="qr-code-height-change" class="qr-designer visible">
                <div class="qr-designer">
                    <div class="qr-designer-inner">
                        <div class="modal-content">
                            <div class="modal-body pb-0">
                                <div class="tab-section content-body p-0">
                                    <ul class="nav nav-tabs" id="myTab"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active"
                                               id="classic-tab"
                                               onclick="classicQrCode()"
                                               data-toggle="tab"
                                               href="#classic"
                                               role="tab"
                                               aria-controls="classic"
                                               aria-selected="true">{{__('Classic')}}</a>
                                        </li>
                                        @if(checkFieldStatus(6))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   id="static-tab"
                                                   onclick="makeTransparentQrCode()"
                                                   data-toggle="tab"
                                                   href="#static"
                                                   role="tab"
                                                   aria-controls="static"
                                                   aria-selected="false"> {{__('Transparent')}} </a>
                                            </li>
                                        @endif
                                        @if(checkFieldStatus(7))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   id="templates-tab"
                                                   onclick="templateQrCode()"
                                                   data-toggle="tab"
                                                   href="#templates"
                                                   role="tab"
                                                   aria-controls="templates"
                                                   aria-selected="false"> <i
                                                        class="fa fa-star"></i> {{__('Templates')}}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    <!-- Body -->
                                    <div class="tabs-content row"
                                         id="myTabContent">
                                        <div
                                            class="tab-pane fade show active col-sm-12 "
                                            id="classic" role="tabpanel"
                                            aria-labelledby="classic-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="accordion">
                                                        <div class="card">
                                                            <div
                                                                class="card-header"
                                                                id="headingOne">
                                                                <h5 class="mb-0">
                                                                    <div
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseOne"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseOne">
                                                                        <div
                                                                            class="pane-header">
                                                                            <div
                                                                                class="icon">
                                                                                <i
                                                                                    class="fa fa-paint-brush"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                            <h3 class="title">{{__('Set Color')}}</h3>
                                                                            <div
                                                                                class="plus">
                                                                                <i class="fa fa-plus"
                                                                                   aria-hidden="true"></i>
                                                                            </div>
                                                                            <div
                                                                                class="minus">
                                                                                <i
                                                                                    class="fa fa-minus"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>

                                                            <div
                                                                id="collapseOne"
                                                                class="collapse show"
                                                                aria-labelledby="headingOne"
                                                                data-parent="#accordion">
                                                                <div
                                                                    class="card-body">
                                                                    <div
                                                                        class="pane-content">
                                                                        <label>{{__('Foreground Color')}}</label>
                                                                        <div>
                                                                            <div
                                                                                class="form-check form-check-inline"
                                                                                id="single">
                                                                                <label
                                                                                    class="form-check-label">
                                                                                    <input
                                                                                        class="form-check-input radio"
                                                                                        id="single-color-type"
                                                                                        {{--                                                                           name="customColorMode"--}}
                                                                                        type="radio"
                                                                                        value="single"
                                                                                        onclick="foregroundColor('single')"
                                                                                        {{ ($action == 'Add') ? 'checked' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'checked': '')}}>
                                                                                    {{__('Single Color')}}
                                                                                </label>
                                                                            </div>
                                                                            <div
                                                                                class="form-check form-check-inline"
                                                                                id="gradient">
                                                                                <label
                                                                                    class="form-check-label">
                                                                                    <input
                                                                                        class="form-check-input ng-valid ng-dirty ng-touched radio"
                                                                                        id="gradient-color-type"
                                                                                        {{--                                                                                                                                                name="customColorMode"--}}
                                                                                        type="radio"
                                                                                        {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['colorType'] == 0 ? 'checked': '')}}
                                                                                        value="gradient"
                                                                                        onclick="foregroundColor('gradient')">
                                                                                    {{__('Gradient Color')}}
                                                                                </label>
                                                                            </div>
                                                                            <div
                                                                                class="form-check form-check-inline">
                                                                                <label
                                                                                    class="form-check-input">
                                                                                    <input
                                                                                        id="custom-eye-color"
                                                                                        {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? 'checked' : '')}}
                                                                                        class="form-check-input ng-untouched ng-pristine ng-valid"
                                                                                        type="checkbox"
                                                                                        onclick="eyeColorStatus()">
                                                                                    {{__('Custom Eye Colors')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="row pt-3">
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4">
                                                                                <div
                                                                                    class="form-group input-group">
                                                                                    <input
                                                                                        class="form-control"
                                                                                        type="color"
                                                                                        id="colorOne"
                                                                                        {{--                                                                           name="color-picker-1"--}}
                                                                                        value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}"
                                                                                        onchange="colorPicker(this,1)">
                                                                                </div>
                                                                                <div
                                                                                    class="alert alert-warning color-warning"
                                                                                    id="color-warning-1"
                                                                                    style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4 hide-show">
                                                                                <div
                                                                                    class="form-group input-group">
                                                                                    <input
                                                                                        class="form-control"
                                                                                        type="color"
                                                                                        id="colorTwo"
                                                                                        {{--                                                                           name="color-picker-2"--}}
                                                                                        value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}"
                                                                                        onchange="colorPicker(this,2)">
                                                                                </div>
                                                                                <div
                                                                                    class="alert alert-warning color-warning"
                                                                                    id="color-warning-2"
                                                                                    style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4 hide-show">
                                                                                <div
                                                                                    class="input-group form-group">
                                                                                            <span
                                                                                                class="input-group-btn">
                                                                                                <button
                                                                                                    class="btn btn-secondary"
                                                                                                    style="border-radius: 0px;font-size:17px"
                                                                                                    type="button"
                                                                                                    onclick="colorOverlap(1)">
                                                                                                    <i class="fa fa-exchange"></i>
                                                                                                </button>
                                                                                            </span>
                                                                                    @php

                                                                                        @endphp
                                                                                    <select
                                                                                        class="form-control"
                                                                                        id="color-schema"
                                                                                        onchange="colorSchema(this)">
                                                                                        <option
                                                                                            value="vertical" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'vertical' ? 'selected' : '') }}>
                                                                                            {{__('Vertical')}}
                                                                                        </option>
                                                                                        <option
                                                                                            value="radial" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'radial' ? 'selected' : '') }}>
                                                                                            {{__('Radial')}}
                                                                                        </option>
                                                                                        <option
                                                                                            value="horizontal" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'horizontal' ? 'selected' : '') }}>
                                                                                            {{__('Horizontal')}}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="row pt-3 eye-hide-show">
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4">
                                                                                <div
                                                                                    class="form-group input-group">
                                                                                    <input
                                                                                        class="form-control"
                                                                                        type="color"
                                                                                        id="colorThree"
                                                                                        {{--                                                                           name="eye-frame-color-picker"--}}
                                                                                        value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}"
                                                                                        onchange="colorPicker(this,3)">
                                                                                </div>
                                                                                <div
                                                                                    class="alert alert-warning color-warning"
                                                                                    id="color-warning-3"
                                                                                    style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4">
                                                                                <div
                                                                                    class="form-group input-group">
                                                                                    <input
                                                                                        class="form-control"
                                                                                        type="color"
                                                                                        id="colorFour"
                                                                                        {{--                                                                           name="eye-ball-color-picker"--}}
                                                                                        value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}"
                                                                                        onchange="colorPicker(this,4)">
                                                                                </div>
                                                                                <div
                                                                                    class="alert alert-warning color-warning"
                                                                                    id="color-warning-4"
                                                                                    style="display: none;">{{__('We recommend to make your color darker.')}}</div>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-auto">
                                                                                <div
                                                                                    class="input-group form-group image-upload-btn">
                                                                                    <div
                                                                                        class="input-group-btn">
                                                                                        <button
                                                                                            class="btn btn-secondary"
                                                                                            style="border-radius: 0px"
                                                                                            type="button"
                                                                                            onclick="colorOverlap(2)">
                                                                                            <i class="fa fa-exchange"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div
                                                                                        class="input-group-btn flex-button image-upload-btn">
                                                                                        <button
                                                                                            class="btn btn-secondary"
                                                                                            type="button"
                                                                                            onclick="colorOverlap(3)"
                                                                                            style="border-radius: 0px">
                                                                                            {{__('Copy Foreground')}}
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="row pt-3">
                                                                            <div
                                                                                class="col-12 col-sm-6 col-md-4">
                                                                                <div
                                                                                    class="form-group input-group">
                                                                                    <input
                                                                                        class="form-control"
                                                                                        type="color"
                                                                                        id="body-color"
                                                                                        value="{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}"

                                                                                        {{--                                                                           name="bg-color-picker"--}}
                                                                                        onchange="colorPicker(this,5)">
                                                                                </div>
                                                                                <div
                                                                                    class="alert alert-warning color-warning"
                                                                                    id="color-warning-5"
                                                                                    style="display: none;">{{__('Make sure there is enough contrast to the darker foreground.')}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div
                                                                class="card-header"
                                                                id="headingThree">
                                                                <h5 class="mb-0">
                                                                    <div
                                                                        class="collapsed"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseThree"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseThree">
                                                                        <div
                                                                            class="pane-header">
                                                                            <div
                                                                                class="icon">
                                                                                <i
                                                                                    class="fa fa-qrcode"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                            <h3 class="title">{{__('Customize Shape')}}</h3>
                                                                            <div
                                                                                class="plus">
                                                                                <i class="fa fa-plus"
                                                                                   aria-hidden="true"></i>
                                                                            </div>
                                                                            <div
                                                                                class="minus">
                                                                                <i
                                                                                    class="fa fa-minus"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                            <div
                                                                id="collapseThree"
                                                                class="collapse"
                                                                aria-labelledby="headingThree"
                                                                data-parent="#accordion">
                                                                <div
                                                                    class="card-body  customized-2nd-tab-body clearfix">
                                                                    <label><b>{{__('Body Shape')}}</b></label>
                                                                    <div
                                                                        class="form-group presets clearfix">
                                                                        @foreach($bodyShapes as $index => $bodyShape)
                                                                            <div
                                                                                class="item body-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['body'] == $bodyShape->name ? 'active' : '') }}"
                                                                                id="shapeName{{$bodyShape->name}}"
                                                                                onclick="getShapeName('{{$bodyShape->name}}')">
                                                                                <img
                                                                                    src="{{ asset('storage/shapes/'.$bodyShape->image) }}">
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                    <label><b>{{__('Eye Frame Shape')}}</b></label>
                                                                    <div
                                                                        class="form-group presets clearfix">
                                                                        @foreach($eyeFrames as $index => $eyeFrame)
                                                                            <div
                                                                                class="item eye-frame {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['frame'] == $eyeFrame->name ? 'active' : '') }}"
                                                                                id="frameName{{$eyeFrame->name}}"
                                                                                onclick="getFrameName('{{$eyeFrame->name}}')">
                                                                                <img
                                                                                    src="{{ asset('storage/shapes/'.$eyeFrame->image) }}">
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                    <label><b>{{__('Eye Ball Shape')}}</b></label>
                                                                    <div
                                                                        class="form-group presets clearfix">
                                                                        @foreach($eyeBallShapes as $index => $eyeBallShape)
                                                                            <div
                                                                                class="item eye-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['eyeBall'] == $eyeBallShape->name ? 'active' : '') }}"
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
                                                        <div class="card">
                                                            <div
                                                                class="card-header"
                                                                id="headingTwo">
                                                                <h5 class="mb-0">
                                                                    <div
                                                                        class="collapsed"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwo"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseTwo">
                                                                        <div
                                                                            class="pane-header">
                                                                            <div
                                                                                class="icon">
                                                                                <i
                                                                                    class="fa fa-picture-o"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                            <h3 class="title">{{__('Add Logo Image')}}</h3>
                                                                            <div
                                                                                class="plus">
                                                                                <i class="fa fa-plus"
                                                                                   aria-hidden="true"></i>
                                                                            </div>
                                                                            <div
                                                                                class="minus">
                                                                                <i
                                                                                    class="fa fa-minus"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                            <div
                                                                id="collapseTwo"
                                                                class="collapse"
                                                                aria-labelledby="headingTwo"
                                                                data-parent="#accordion">
                                                                <div
                                                                    class="card-body customized-2nd-tab-body clearfix">
                                                                    <div
                                                                        class="custom-file-upload">
                                                                        <button
                                                                            class="file-upload-btn"
                                                                            type="button"
                                                                            onclick="$('.file-upload-input').trigger( 'click' );">
                                                                            {{__('Add Image')}}
                                                                        </button>

                                                                        <div
                                                                            class="image-upload-wrap col-6 mr-auto ml-auto"
                                                                            style="display: {{($action == 'Add') ? 'block' : ($generateQrCode->logo_image ? 'none' : 'block')}}">
                                                                            <input
                                                                                class="file-upload-input"
                                                                                type="file"
                                                                                id="upload-logo-image"
                                                                                onchange="addLogoImage(this)"
                                                                                name="logo_image"
                                                                                style="pointer-events: none;"
                                                                                accept="image/png">
                                                                            {{__('Upload')}}
                                                                        </div>
                                                                        <span
                                                                            class="d-none"
                                                                            id="allowd_image"
                                                                            style="color: #FC4014">{{__('The image must be a file of type: image/png')}}</span>

                                                                        <div
                                                                            class="file-upload-content col-6 ml-auto mr-auto"
                                                                            style="display: {{($action == 'Add') ? 'none' : ($generateQrCode->logo_image ? 'block' : 'none')}}">
                                                                            <img
                                                                                class="file-upload-image"
                                                                                src="{{($action == 'Add') ? '#' : (!empty($generateQrCode->logo_image) ? asset('storage/users/'.auth()->user()->id.'/qr-codes/logo-images/'.$generateQrCode->logo_image) : '#')}}"
                                                                                alt="your image">

                                                                            <div
                                                                                class="image-title-wrap">
                                                                                <button
                                                                                    type="button"
                                                                                    onclick="removeImageInput()"
                                                                                    class="remove-image">{{__('Remove')}}
                                                                                    <span
                                                                                        class="image-title">{{__('Uploaded Image')}}</span>

                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="form-group presets clearfix pt-3">
                                                                        @foreach($logos as $index => $logo)
                                                                            @if($action == 'Edit' ? json_decode($generateQrCode->config, true)['logo'] == $logo->id : false)
                                                                                <script
                                                                                    type='text/javascript'>
                                                                                    setTimeout(function () {
                                                                                        getLogoId({{$logo->id}});
                                                                                    }, 1000)
                                                                                </script>
                                                                            @endif
                                                                            <div
                                                                                {{--                                                                                    class="item logo-image {{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['logo'] == $logo->id ? 'active' : '') }}"--}}
                                                                                class="item logo-image"
                                                                                id="logoImage{{$logo->id}}"
                                                                                onclick="getLogoId({{$logo->id}})">
                                                                                <img
                                                                                    id="{{$logo->id}}"
                                                                                    src="{{ asset('storage/logos/'.$logo->image) }}">
                                                                            </div>
                                                                        @endforeach
                                                                        <div
                                                                            class="clearfix"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="tab-pane fade col-sm-12"
                                             id="static" role="tabpanel"
                                             aria-labelledby="static-tab">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div
                                                        class="transparent-qr">
                                                        <div
                                                            class="cardbox-inner line">
                                                            <div
                                                                class="row">
                                                                <div
                                                                    class="col">
                                                                    <div
                                                                        class="cropper-area"
                                                                        id="uploadedTransparentImage">
                                                                        <div
                                                                            class="fa-5x"
                                                                            id="transparent-loading"
                                                                            style="display: none">
                                                                            <i class="fa fa-spinner fa-spin"></i>
                                                                        </div>
                                                                        <img
                                                                            hidden=""
                                                                            id="image_cropper"
                                                                            src="{{$action == 'Add' ? '#' : ($generateQrCode->crop ? asset('storage/temp/'.$generateQrCode->transparent_background) : '#')}}">

                                                                    <!-- <img
                                                                                            style="display:{{$action == 'Add' ? 'none' : ($generateQrCode->crop ? 'block' : 'none')}}"
                                                                                            id="image_cropper"
                                                                                            src="{{$action == 'Add' ? '#' : ($generateQrCode->crop ? asset('storage/temp/'.$generateQrCode->transparent_background) : '#')}}"> -->


                                                                        <div
                                                                            class="cropper-empty">
                                                                            <div
                                                                                class="text-center">
                                                                                <div>
                                                                                    {{--                                                                                        <div class="custom-file-upload">--}}
                                                                                    {{--                                                                                            <button--}}
                                                                                    {{--                                                                                                class="file-upload-btn"--}}
                                                                                    {{--                                                                                                type="button"--}}
                                                                                    {{--                                                                                                >--}}
                                                                                    {{--                                                                                                {{__('Add Image')}}--}}
                                                                                    {{--                                                                                            </button>--}}
                                                                                    <div
                                                                                        class=" d-none">
                                                                                        <input
                                                                                            class="transparent-image"
                                                                                            type="file"
                                                                                            id="transparentImage"
                                                                                            onchange="uploadedTransparentImage(this)"
                                                                                            name="transparentImage"
                                                                                            style="pointer-events: none;"
                                                                                            accept="image/*">
                                                                                        {{__('Upload')}}
                                                                                    </div>

                                                                                {{--                                                                                            <span--}}
                                                                                {{--                                                                                                class="d-none"--}}
                                                                                {{--                                                                                                id="allowd_image"--}}
                                                                                {{--                                                                                                style="color: #FC4014">The image must be a file of type: image/png</span>--}}

                                                                                {{--                                                                                            <div--}}
                                                                                {{--                                                                                                class="file-upload-content">--}}
                                                                                {{--                                                                                                <img id="image_cropper"--}}
                                                                                {{--                                                                                                    class="file-upload-image"--}}
                                                                                {{--                                                                                                    src="#"--}}
                                                                                {{--                                                                                                    alt="your image">--}}

                                                                                {{--                                                                                                <div--}}
                                                                                {{--                                                                                                    class="image-title-wrap">--}}
                                                                                {{--                                                                                                    <button--}}
                                                                                {{--                                                                                                        type="button"--}}
                                                                                {{--                                                                                                        onclick="removeImageInput()"--}}
                                                                                {{--                                                                                                        class="remove-image">{{__('Remove')}}--}}
                                                                                {{--                                                                                                        <span--}}
                                                                                {{--                                                                                                            class="image-title">{{__('Uploaded Image')}}</span>--}}

                                                                                {{--                                                                                                    </button>--}}
                                                                                {{--                                                                                                </div>--}}
                                                                                {{--                                                                                            </div>--}}
                                                                                {{--                                                                                        </div>--}}

                                                                                <!-- <div
                                                                                        id="uploadedTransparentImage" style="max-height: 445px; max-width:770px">
                                                                                        <div
                                                                                            class="fa-5x mt-4"
                                                                                            id="transparent-loading"
                                                                                            style="display: none">
                                                                                            <i class="fa fa-spinner fa-spin"></i>
                                                                                        </div>
                                                                                        <img
                                                                                            style="display:{{$action == 'Add' ? 'none' : ($generateQrCode->crop ? 'block' : 'none')}}"
                                                                                            id="image_cropper"
                                                                                            src="{{$action == 'Add' ? '#' : ($generateQrCode->crop ? asset('storage/temp/'.$generateQrCode->transparent_background) : '#')}}">
                                                                                    </div> -->
                                                                                    <div
                                                                                        id="transparentImageTypeMessage"
                                                                                        style="display:none">
                                                                                        <span
                                                                                            class="text-danger">{{__('The image must be jpg,png,jpeg.')}}</span>
                                                                                    </div>

                                                                                    <div id="transparent-image-data"
                                                                                         style="display:{{$action == 'Add' ? 'block' : ($generateQrCode->crop ? 'none' : 'block')}}">
                                                                                        <p>{{__('Place your transparent QR Code on any background')}}</p>
                                                                                        <p>
                                                                                            - {{__('OR')}}
                                                                                            -</p>
                                                                                    </div>

                                                                                    <button id="upload-background-image"
                                                                                            onclick="$('.transparent-image').trigger( 'click' )"
                                                                                            {{--                                                                                            onchange="makeTransparentQrCode(this)"--}}
                                                                                            class="btn btn-primary"
                                                                                            type="button">
                                                                                        {{__('Upload Background Image')}}
                                                                                    </button>
                                                                                    <input
                                                                                        class="d-none"
                                                                                        type="file"
                                                                                        id="upload-transparent-image"
                                                                                        onchange="makeTransparentQrCode(this)"
                                                                                        name="transparent-image"
                                                                                        accept="image/png">
                                                                                </div>
                                                                                <div
                                                                                    class="loading-screen active"
                                                                                    hidden="">
                                                                                    <div
                                                                                        class="loader"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="d-flex  justify-content-sm-start justify-content-center">
                                                                        <div
                                                                            class="d-flex flex-sm-row flex-column btn-row">
                                                                            <button id="replace-transparent-image"
                                                                                    onclick="$('.transparent-image').trigger( 'click' )"
                                                                                    {{--                                                                                            onchange="makeTransparentQrCode(this)"--}}
                                                                                    class="btn btn-primary d-none  mb-sm-0 mb-1 mr-sm-2"
                                                                                    type="button">
                                                                                {{__('Replace Background')}}
                                                                            </button>
                                                                            <button id="remove-transparent-image"
                                                                                    onclick="removeTransparentImage(this)"
                                                                                    {{--                                                                                            onchange="makeTransparentQrCode(this)"--}}
                                                                                    class="btn btn-danger d-none"
                                                                                    type="button">
                                                                                {{__('Remove')}}
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                    <!---->
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade col-sm-12"
                                             id="templates" role="tabpanel"
                                             aria-labelledby="templates-tab">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div
                                                        class="row designsetting qrdesigner-iner"
                                                        id="templateImageAppend">
                                                        @foreach($templateImages as $index => $templateImage)
                                                            <div
                                                                class="col-md-3 col-sm-4 col-6"
                                                                id="template-image-id-{{Hashids::encode($templateImage->id)}}">
                                                                <div
                                                                    class="template">
                                                                    <div
                                                                        class="qrcode-container">
                                                                        <div
                                                                            class="qrcode"
                                                                            onclick="templateConfigData('{{Hashids::encode($templateImage->id)}}')">
                                                                            <img
                                                                                src="{{checkImage(asset('storage/users/' . $templateImage->user_id . '/qr-codes/templates/' . $templateImage->image), 'default.svg', $templateImage->image)}}"
                                                                                class=" ng-lazyloaded">
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="options">
                                                                        <div
                                                                            class="row">
                                                                            <div
                                                                                class="col type">
                                                                                <!---->
                                                                                <span>{{$templateImage->crop == true ? __('Transparent') : __('Classic')}}</span>
                                                                                <!---->
                                                                            </div>
                                                                            <div
                                                                                class="col-auto">
                                                                                <a href="javascript:void(0)"
                                                                                   onclick="deleteTemplate('{{Hashids::encode($templateImage->id)}}')">
                                                                                    <i class="fa fa-trash delete"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="design-preview mt-3 mt-md-0">
                                            <div
                                                class="fixed----right-qrbar">
                                                <div class="cardbox ">
                                                    <div
                                                        class="qrcode-container">
                                                        <div
                                                            class="qrcode mt-3"
                                                            id="classic-qr-code">
                                                            <img
                                                                class="qr-code-scan "
                                                                src="{{checkImage(asset('storage/users/'.$generateQrCode->user_id.'/qr-codes/' . $generateQrCode->image),'default.svg',$generateQrCode->image)}}">
                                                        </div>
                                                        <div
                                                            class="fa-5x mt-4"
                                                            id="design-loading"
                                                            style="display: none">
                                                            <i class="fa fa-spinner fa-spin"></i>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="alert alert-warning" id="color-warning"
                                                         style="display: none;">{{__('Warning: To avoid scanning problems we recommend to give your colors more contrast to the background color. The background should be lighter than the foreground.')}}</div>
                                                    <div
                                                        class="cardbox-inner">
                                                        <div
                                                            class="row">
                                                            <div
                                                                class="col-10">
                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm"
                                                                    type="button"
                                                                    onclick="resetQrCode()">
                                                                    <i class="fa fa-undo"></i> {{__('Reset')}}
                                                                </button>
                                                            </div>
                                                            @if(checkFieldStatus(7))
                                                                <div
                                                                    class="col text-right">
                                                                    <i class="fa fa-star-o add-template"
                                                                       id="classic-fa-star-o"></i>
                                                                    <i class="fa fa-star added-template"
                                                                       id="classic-fa-star"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="qrcode-footer modal-footer">
                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal">{{__('Cancel')}}
                                                </button>
                                                <button type="button"
                                                        id="genQrCode"
                                                        class="btn btn-orange"
                                                        onclick="useDesign()">{{__('Use Design')}}
                                                </button>
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
@endsection

@section('js')
    <script>
        var shapeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['body']}}';
        var frameName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['frame']}}';
        var eyeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['eyeBall']}}';
        var eyeStatus = '{{ ($action == 'Add') ? false : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true : false)}}';
        var colorOne = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}';
        var colorTwo = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}';
        var frameColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}';
        var eyeBallColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}';
        var bodyColor = '{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}';
        var colorStructure = '{{ ($action == 'Add') ? 'vertical' : json_decode($generateQrCode->config, true)['gradientType']}}';
        var logoId = '{{ ($action == 'Add') ? 0 : json_decode($generateQrCode->config, true)['logo']}}';
        var colorType = '{{($action == 'Add') ? true : json_decode($generateQrCode->config, true)['colorType']}}';
        var formData = '';
        var tabType = '{{($action == 'Add') ? 'classic' : (!empty($generateQrCode->transparent_background) ? 'static' : 'classic')}}';
        var config1 = '';
        var config2 = '';
        // var tabType = 'classic';
        var saved_image_id = '{{($action == 'Add') ? '' : (!empty($generateQrCode->image) ? $generateQrCode->image : '')}}';
        var main_image = '';
        var firstImageAppend = true;
        var editCallBackFirst = false;
        var modalLoading = false;
        var dynamicContentField = '{{($parameters['type'] == 'dynamic') ? true : false}}';
        var dynamicContentFieldAction = '{{($action == 'Add') ? true : false}}';
        var template = 0;
        var generateShortLink = 0;
        var nedLink = '{{($action == 'Add') ? null : $generateQrCode->ned_link }}';
        var transparentImageId = '{{($action == 'Add') ? null : (!empty($generateQrCode->transparent_background) ? $generateQrCode->transparent_background : null)}}';
        var transparentImageStatus = '{{($action == 'Add') ? false : (!empty($generateQrCode->crop) ? $generateQrCode->crop : false)}}';
        var cropper = '';
        var cropperImageX = '{{($action == 'Add') ? 0 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['x']: 0)}}';
        var cropperImageY = '{{($action == 'Add') ? 0 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['y']: 0)}}';
        var cropperImageSize = '{{($action == 'Add') ? 500 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['size']: 500)}}';
        var cropperImageData = {x: cropperImageX, y: cropperImageY, size: cropperImageSize};
        var useDesignCropperImageData = {x: cropperImageX, y: cropperImageY, size: cropperImageSize};
        var cropperImageStatus = '{{($action == 'Add') ? false : ($generateQrCode->crop == 1 ? true : false)}}';
        var temporaryLogoImage = '{{($action == 'Add') ? '' : ($generateQrCode->logo_image ? $generateQrCode->logo_image : '')}}';
        var mainLogoImage = '{{($action == 'Add') ? '' : ($generateQrCode->logo_image ? $generateQrCode->logo_image : '')}}';
        //Reset Value
        var defaultShapeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['body']}}';
        var defaultFrameName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['frame']}}';
        var defaultEyeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['eyeBall']}}';
        var defaultEyeStatus = '{{ ($action == 'Add') ? false : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true : false)}}';
        var defaultColorOne = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}';
        var defaultColorTwo = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}';
        var defaultFrameColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}';
        var defaultEyeBallColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}';
        var defaultBodyColor = '{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}';
        var defaultColorStructure = '{{ ($action == 'Add') ? 'vertical' : json_decode($generateQrCode->config, true)['gradientType']}}';
        var defaultLogoId = '{{ ($action == 'Add') ? 0 : json_decode($generateQrCode->config, true)['logo']}}';
        var defaultColorType = '{{($action == 'Add') ? true : json_decode($generateQrCode->config, true)['colorType']}}';
        var defaultForegroundColorCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'single': 'gradient')}}';
        var defaultEyeStatusCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true: false)}}';
        var defaultCropperImageX = '{{($action == 'Add') ? 0 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['x']: 0)}}';
        var defaultCropperImageY = '{{($action == 'Add') ? 0 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['y']: 0)}}';
        var defaultCropperImageSize = '{{($action == 'Add') ? 500 : ($generateQrCode->crop == 1 ? json_decode($generateQrCode->crop_data, true)['size']: 500)}}';
        var defaultCropperImageStatus = '{{($action == 'Add') ? false : ($generateQrCode->crop == 1 ? true : false)}}';
        var defaultTransparentImageId = '{{($action == 'Add') ? null : (!empty($generateQrCode->transparent_background) ? $generateQrCode->transparent_background : null)}}';
        var defaultTemporaryLogoImage = '{{($action == 'Add') ? '' : ($generateQrCode->logo_image ? $generateQrCode->logo_image : '')}}';
        var defaultMainLogoImage = '{{($action == 'Add') ? '' : ($generateQrCode->logo_image ? $generateQrCode->logo_image : '')}}';

        $(document).ready(function () {
            config2 = {
                "config": {
                    "body": shapeName,
                    "frame": frameName,
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
                    "colorType": colorType,
                    "eyeStatus": eyeStatus,
                    "gradientType": colorStructure,
                    "gradientOnEyes": false,
                    "logo": logoId
                },
                "size": 300,
                "download": false,
                "file": "svg"
            };
            config1 = config2;
            $('.hide-show').hide();
            $('.eye-hide-show').hide();
            $('#classic-fa-star').hide();
            editCallBackFirst = '{{($action == 'Add') ? true : false}}';
            var foregroundColorCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'single': 'gradient')}}';
            var eyeStatusCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true: false)}}';
            if (foregroundColorCheck) {
                foregroundColor(foregroundColorCheck)
            }
            if (eyeStatusCheck) {
                eyeStatus = false;
                eyeColorStatus()
            }
            // Check the dynamic Field
            if (dynamicContentField && dynamicContentFieldAction) {
                makeQrCode();
            }
            setTimeout(function () {
                editCallBackFirst = true
            }, 1000)

            // setInterval(function(){
            //     console.log(useDesignCropperImageData,'every 1s useDesignCropperImageData')
            // },3000)
        });

        // Timezone dopdown
        $('#qrcodeEventTimezone ').select2(
            {
                placeholder: 'Select a Timezone',
                allowClear: true
            });

        //      Check Frontend Validation
        function frontendValidation(formName) {
            var formId = '#' + formName;
            $(formId).validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    text: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    qrcodeUrl: {
                        required: true,
                        url: true,
                    },
                    qrcodeText: {
                        required: true,
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
                        required: false,
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
                        maxlength: 400,
                    },
                    startDateTime: {
                        required: true,
                    },
                    endDateTime: {
                        required: true,
                        greaterThan: "#startDateTime",
                    },
                    ssid: {
                        required: true,
                    },
                    password: {
                        required: true
                    },
                    qrcodeVcardPhoneWork: {
                        required: true
                    },
                    qrcodeVcardEmail: {
                        required: false,
                        email: true,
                    },
                    qrcodeVcardUrl: {
                        required: false,
                        url: true,
                    },
                },

                messages: {
                    text: {
                        required: '{{__('This field is required')}}'
                    },
                    name: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeUrl: {
                        url: '{{__('Please enter a valid URL.')}}',
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeText: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEmail: {
                        email: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}'
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
                        greaterThan: '{{__('Date must be greater than start date')}}',
                    },
                    ssid: {
                        required: '{{__('This field is required')}}'
                    },
                    password: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeVcardPhoneWork: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeVcardEmail: {
                        email: '{{__('Please enter a valid email address')}}',
                    },
                    qrcodeVcardUrl: {
                        url: '{{__('Please enter a valid URL.')}}',
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

            jQuery.validator.addMethod("greaterThan",
                function (value, element, params) {

                    if (!/Invalid|NaN/.test(new Date(value))) {
                        return new Date(value) > new Date($(params).val());
                    }

                    return isNaN(value) && isNaN($(params).val())
                        || (Number(value) > Number($(params).val()));
                }, 'Must be greater than {0}.');
        }

        //      Add Campaign
        $('#campaign-btn').on('click', function () {
            frontendValidation('campaign-form');
            $('#campaign-form').valid();
        });

        //      Validation data and open Modal
        $('#custom-qr-btn').on('click', function () {
            frontendValidation('dynamic-content-type')
            if ($('#dynamic-content-type').valid()) {
                $('#design-qrcode').modal('show');

                if (transparentImageStatus) {
                    $('#static-tab').trigger('click');

                    // Tab
                    $('#classic-tab').removeClass('active');
                    $('#templates-tab').removeClass('active');
                    $('#static-tab').addClass('active');
                    // Content
                    $('#classic').removeClass('active');
                    $('#classic').removeClass('show');
                    $('#static').addClass('active');
                    $('#static').addClass('show');
                    $('#templates').removeClass('active');
                    $('#templates').removeClass('show');
                    if (cropper != '') {
                        cropper.destroy()
                    }
                    cropperImageStatus = false
                    transparentButtons()

                    cropImage()
                } else {
                    // Tab
                    $('#static-tab').removeClass('active');
                    $('#templates-tab').removeClass('active');
                    $('#classic-tab').addClass('active');
                    // Content
                    $('#classic').addClass('active');
                    $('#classic').addClass('show');
                    $('#static').removeClass('active');
                    $('#static').removeClass('show');
                    $('#templates').removeClass('active');
                    $('#templates').removeClass('show');
                }
            }
        });

        //      Ajax Call after change dynamic inputs
        function getQrCodeByField(ele) {

            var formName = $(ele).parents('form').attr('id');
            frontendValidation(formName)
            var fromId = '#' + formName;
            if ($(fromId).valid()) {
                if (dynamicContentField) {
                    firstImageAppend = true;
                    $('#save-qr-code-btn').prop('disabled', false);
                } else {
                    firstImageAppend = true;
                    makeQrCode();
                    $('#save-qr-code-btn').prop('disabled', false);
                }
            }
        }

        //      Display Campaign input field
        $(".add-campaigns").click(function () {
            $(".form-new-campaign").slideToggle();
        });

        //      Store campaign form
        $('#campaign-form').on("click", function (e) {
            var formData = new FormData(this);

            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.campaigns.store') }}",
                data: formData,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $("#add-campaign").append('<option value="' + response.data.id + '">' + response.data.name + '</option>');
                        $("#campaign-name").val('')
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        });

        //      Laravel Validation Message
        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li id=' + key + '>' + value + '</li>');
                removeErrorMessage(key)
            });
        }

        //      Remove laravel error message after sometime
        function removeErrorMessage(key) {
            var index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');
                $(".print-error-msg").css('display', 'none');
            }, 5000);
        }

        //      Get and active shape
        function getShapeName(selectedShapeName) {
            var shapeNameId = '#shapeName' + selectedShapeName;
            $('.body-shape').removeClass('active');
            $(shapeNameId).addClass('active');
            shapeName = selectedShapeName;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      Get and active frame
        function getFrameName(selectedFrameName) {
            var frameNameId = '#frameName' + selectedFrameName;
            $('.eye-frame').removeClass('active');
            $(frameNameId).addClass('active');
            frameName = selectedFrameName;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      Get and active eye
        function getEyeBallName(selectedEyeBallName) {
            var eyeShapeId = '#eyeShape' + selectedEyeBallName;
            $('.eye-shape').removeClass('active');
            $(eyeShapeId).addClass('active');
            eyeName = selectedEyeBallName;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      Upload Logo Image
        function addLogoImage(ele) {
            if (getExtension(ele)) {
                readURL(ele);
                starIcon();
                makeQrCode();
            }
        }

        //      Check Radio button on Color selection like single or gardient
        function foregroundColor(selectedColorType) {
            $('.radio').change(function () {
                $('.radio').not(this).prop('checked', false);
            });
            if (selectedColorType == 'single') {
                colorType = true;
                $('.hide-show').hide();

                $('#color-warning-2').hide();

                if ($(".color-warning").css('display') != 'none') {
                    $('#color-warning').show();
                } else {
                    $('#color-warning').hide();
                }
            }
            if (selectedColorType == 'gradient') {
                colorType = false;
                $('.hide-show').show();
            }

            //  make qr code true
            if (editCallBackFirst) {
                makeQrCode();
            }
            //Empty Star Show
            starIcon();
        }

        //      Display Eye show or hide
        function eyeColorStatus() {
            if (!eyeStatus) {
                $('.eye-hide-show').show();
                eyeStatus = true;
            } else {
                $('.eye-hide-show').hide();
                eyeStatus = false;

                $('#color-warning-3').hide();
                $('#color-warning-4').hide();

                if ($(".color-warning").css('display') != 'none') {
                    $('#color-warning').show();
                } else {
                    $('#color-warning').hide();
                }
            }
            if (editCallBackFirst) {
                makeQrCode();
            }
            //Empty Star Show
            starIcon();
        }

        function starIcon() {
            //Classic Template Star
            $('#classic-fa-star-o').show();
            $('#classic-fa-star').hide();
        }

        //      Pick up the color and store in variables
        function colorPicker(ele, id) {
            switch (id) {
                case 1:
                    colorOne = ele.value;
                    break;
                case 2:
                    colorTwo = ele.value;
                    break;
                case 3:
                    frameColor = ele.value;
                    break;
                case 4:
                    eyeBallColor = ele.value;
                    break;
                case 5:
                    bodyColor = ele.value;
                    break;
            }
            //Empty Star Show
            starIcon();

            makeQrCode();

            // brightness value can be 0-255, 0 is dark and 255 is light

            var brightness = getColorBrightness(ele.value);

            if (id == 1 || id == 2 || id == 3 || id == 4) {
                if (brightness <= 150) {
                    // dark color
                    $('#color-warning-' + id).hide();

                    if (!$('.color-warning').is(":visible")) {
                        $('#color-warning').hide();
                    }
                    $('#qr-code-height-change').removeClass('transparent-message')
                } else {
                    // light color
                    $('#color-warning-' + id).show();
                    $('#color-warning').show();
                    $('#qr-code-height-change').addClass('transparent-message')
                }
            } else if (id == 5) {
                if (brightness <= 200) {
                    // dark color
                    $('#color-warning-' + id).show();
                    $('#color-warning').show();
                    $('#qr-code-height-change').addClass('transparent-message')

                } else {
                    // light color
                    $('#color-warning-' + id).hide();
                    if (!$('.color-warning').is(":visible")) {
                        $('#color-warning').hide();
                        $('#qr-code-height-change').removeClass('transparent-message')
                    }
                }
            }
        }

        function getColorBrightness(color) {
            var c = color.substring(1);      // strip #
            var rgb = parseInt(c, 16);   // convert rrggbb to decimal
            var r = (rgb >> 16) & 0xff;  // extract red
            var g = (rgb >> 8) & 0xff;  // extract green
            var b = (rgb >> 0) & 0xff;  // extract blue

            var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
            return luma;
        }

        //      Pick up the color type etc varticle,radial...
        function colorSchema(ele) {
            colorStructure = ele.value;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      get and active logo
        function getLogoId(id) {
            //Add Active class
            var logoImageId = '#logoImage' + id;
            $('.logo-image').removeClass('active');
            $(logoImageId).addClass('active');
            //update image src
            $('#upload-logo-image').val(null);
            var firstImageId = '#' + id;
            if (id == 0) {
                $('.file-upload-content').css('display', 'none');
                $('.image-upload-wrap').css('display', 'block');
            } else {
                $(".file-upload-image").attr("src", $(firstImageId).attr('src'));
                //hide button and show image
                $('.file-upload-content').css('display', 'block');
                // $('.image-upload-wrap').css('display', 'none');
                $('#genQrCode').attr('disabled', false);
                $('.image-upload-wrap').css('display', 'none');
                $('#allowd_image').removeClass('d-block');
                $('#allowd_image').addClass('d-none');
            }
            temporaryLogoImage = '';
            logoId = id;
            //Empty Star Show
            starIcon();
            //  make qr code true
            if (editCallBackFirst) {
                makeQrCode();
            }
        }

        //      remove image and logo id
        function removeImageInput(ele) {
            $('#upload-logo-image').val(null);
            $('.logo-image').removeClass('active');
            $('.image-upload-wrap').css('display', 'block');
            $('#allowd_image').removeClass('d-block');
            $('#allowd_image').addClass('d-none');
            temporaryLogoImage = ''
            mainLogoImage = ''

            removeUpload()
            logoId = 0;

            makeQrCode()
        }

        //      Color values overlap
        function colorOverlap(index) {
            var singleColor = $('#colorOne').val();
            var gradientColor = $('#colorTwo').val();
            switch (index) {
                case 1:
                    $('#colorOne').val(gradientColor);
                    $('#colorTwo').val(singleColor);
                    colorOne = gradientColor;
                    colorTwo = singleColor;
                    break;
                case 2:
                    var eyeColor = $('#colorThree').val();
                    var colorFour = $('#colorFour').val();
                    $('#colorThree').val(colorFour);
                    $('#colorFour').val(eyeColor);
                    frameColor = colorFour;
                    eyeBallColor = eyeColor;
                    break;
                case 3:
                    var getFrameColor = colorType ? singleColor : gradientColor;
                    $('#colorThree').val(singleColor);
                    $('#colorFour').val(getFrameColor);
                    frameColor = singleColor;
                    eyeBallColor = getFrameColor;
                    break;
            }
            //Empty Star Show
            starIcon();

            makeQrCode()
        }

        function classicQrCode() {
            transparentImageStatus = false;
            starIcon();
            makeQrCode();

            if ($(".color-warning").css('display') != 'none') {
                $('#color-warning').show();
            }
        }

        //      Generate Qr Code with all the data
        function makeQrCode() {
            if (editCallBackFirst) {
                var data = '';
                // Loading on modal
                if ($('#design-qrcode').hasClass('show')) {
                    modalLoading = true;
                    data = {
                        "config": {
                            "body": shapeName,
                            "frame": frameName,
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
                            "colorType": colorType,
                            "eyeStatus": eyeStatus,
                            "gradientType": this.colorStructure,
                            "gradientOnEyes": false,
                            "logo": logoId
                        },
                        "size": 300,
                        "download": false,
                        "file": "svg"
                    };
                    // Copy config data in Config1
                    config1 = data;
                    var fileToUpload = $('#upload-logo-image').prop('files')[0];

                } else {
                    modalLoading = false
                    // Copy config2 data in data
                    data = config2;

                    fileToUpload = mainLogoImage;
                }
                if (transparentImageStatus) {
                    let formData = transparentData()
                    formData.append('x', useDesignCropperImageData.x);
                    formData.append('y', useDesignCropperImageData.y);
                    formData.append('size', useDesignCropperImageData.size);
                    console.log(useDesignCropperImageData, 'useDesignCropperImageData')

                    if (tabType == 'static') {
                        formData.append('transparentImage', transparentImageId);
                    } else {
                        formData.append('config', JSON.stringify(data));
                    }
                } else {
                    let myForm = document.getElementById('dynamic-content-type');
                    formData = new FormData(myForm);
                    formData.append('config', JSON.stringify(data));
                    formData.append('logo_image', fileToUpload);
                }

                formData.append('generate_short_link', generateShortLink);
                // formData.append('clone_logo_image', mainLogoImage);
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
                        if (!modalLoading) {
                            $('#loading').show();
                            $('#generated-qr-code').hide();
                        } else {
                            $('#design-loading').show();
                            $('#classic-qr-code').hide();
                        }
                    },
                    success: function (response) {

                        if (!modalLoading) {
                            $('#loading').hide();
                            $('#generated-qr-code').show();
                        } else {
                            $('#design-loading').hide();
                            $('#classic-qr-code').show();
                            temporaryLogoImage = response.logo_image
                        }

                        if (response.status == 1) {
                            if (firstImageAppend) {
                                nedLink = response.ned_link;
                                $('#ned-link').html(response.ned_link);
                                $('#ned-link-input').val(response.ned_link);
                            }
                            firstImageAppend = false;

                            // Loading on modal
                            if ($('#design-qrcode').hasClass('show')) {
                                modalLoading = true;
                                saved_image_id = response.image_id;
                                $('#classic-qr-code').empty();
                                $('#classic-qr-code').append(response.html);
                            } else {
                                modalLoading = false
                                saved_image_id = response.image_id;
                                main_image = response.image_id;
                                $('#generated-qr-code').empty();
                                $('#generated-qr-code').append(response.html);
                            }
                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
            // Check short linkc
            generateShortLink = 1;
        }

        //      Append qr code modal to page
        function useDesign() {
            $('#loading').css('display', 'none');
            $('#generated-qr-code').empty();
            $('#save-qr-code-btn').prop('disabled', false);
            $('#classic-qr-code').clone().appendTo('#generated-qr-code');
            $("div#generated-qr-code").find("div#classic-qr-code").contents().unwrap();

            if ($('#classic').hasClass('active')) {
                // Copy Config1 data into Config2
                config2 = config1;
                tabType = 'classic';
                transparentImageStatus = false
                mainLogoImage = temporaryLogoImage;
            } else if ($('#static').hasClass('active')) {
                tabType = 'static'
                transparentImageStatus = true
                mainLogoImage = '';
                useDesignCropperImageData = cropperImageData;
            }
            main_image = saved_image_id

            $('#design-qrcode').modal('hide');
        }

        //      Store QR Code in backend
        function saveQrCode() {
            formData.append('ned_link', nedLink);
            formData.append('transparent_image', transparentImageId);
            formData.append('cropper_image_data', JSON.stringify(cropperImageData));
            formData.append('transparent_image_status', transparentImageStatus);
            formData.append('logo_image', mainLogoImage);
            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.qr-codes.store') }}",
                data: formData,
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#loading').show();
                    $('#generated-qr-code').hide();
                    $('#save-qr-code-btn').prop('disabled', true)
                },
                success: function (response) {
                    if (response.status == 1 && response.template == 0) {
                        window.location.replace(response.url)
                    }
                    $('#templateImageAppend').empty()
                    $('#templateImageAppend').append(response.template_image);
                }
            });
        }

        /*whether image is Url or base64*/
        $('img.file-upload-image').on('load', function () {
            if ($("img.file-upload-image[src*='base64']").length == 1) {
                $('.logo-image').removeClass('active');
            }
        })
        /*End*/

        // Validate and Store Data in Formdata and call save function
        $('#save-qr-code-btn').on('click', function () {
            frontendValidation('save-qr-code-form');
            frontendValidation('dynamic-content-type');

            if ($('#save-qr-code-form').valid() && $('#dynamic-content-type').valid()) {
                var myForm = document.getElementById('dynamic-content-type');
                formData = new FormData(myForm);

                var data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
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
                        "colorType": colorType,
                        "eyeStatus": eyeStatus,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 300,
                    "download": false,
                    "file": "svg"
                };

                formData.append('config', JSON.stringify(data));
                var name = $('#general-name').val();
                var campaign_id = $('#add-campaign').val();
                formData.append('name', name);
                formData.append('campaign_id', campaign_id);
                formData.append('image_id', main_image);
                saveQrCode();
            }
        });

        function resetQrCode() {
            if (defaultCropperImageStatus == true) {
                $('#classic-tab').removeClass('active')
                $('#static-tab').addClass('active')
                $('#classic').removeClass('active')
                $('#classic').removeClass('show')
                $('#static').addClass('active')
                $('#static').addClass('show')
                $('#image_cropper').attr("src", '#');
                $('#image_cropper').attr("src", '{{asset('storage/users/'.auth()->user()->id.'/qr-codes/templates')}}' + '/' + defaultTransparentImageId);
                $('#templates-tab').removeClass('active')
                $('#templates').removeClass('active')
                $('#templates').removeClass('show')
                transparentImageId = defaultTransparentImageId;
                transparentImageStatus = defaultTransparentImageId ? true : false;
                cropperImageX = defaultCropperImageX;
                cropperImageY = defaultCropperImageY;
                cropperImageSize = defaultCropperImageSize;
                if (cropper != '') {
                    cropper.destroy()
                }
                cropImage()
            } else {
                $('#upload-logo-image').val('');
                if (defaultMainLogoImage) {
                    $(".file-upload-image").attr("src", '{{asset('storage/users/'.auth()->user()->id.'/qr-codes/logo-images/'.$generateQrCode->logo_image)}}');
                } else {
                    $(".file-upload-image").attr("src", '#');
                }
                transparentImageStatus = false
                //   set default value
                shapeName = defaultShapeName;
                frameName = defaultFrameName;
                eyeName = defaultEyeName;
                eyeStatus = defaultEyeStatus;
                colorOne = defaultColorOne;
                colorTwo = defaultColorTwo;
                frameColor = defaultFrameColor;
                eyeBallColor = defaultEyeBallColor;
                bodyColor = defaultBodyColor;
                colorStructure = defaultColorStructure;
                logoId = defaultLogoId;
                colorType = defaultColorType;
                foregroundColorCheck = defaultForegroundColorCheck;
                eyeStatusCheck = defaultEyeStatusCheck;
                temporaryLogoImage = defaultTemporaryLogoImage;
                mainLogoImage = defaultMainLogoImage;
                // Copy config1 to config2
                config1 = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
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
                        "colorType": colorType,
                        "eyeStatus": eyeStatus,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 300,
                    "download": false,
                    "file": "svg"
                };
                config2 = config1;

                if (defaultTemporaryLogoImage == '') {
                    getLogoId(logoId);
                }
                getShapeName(shapeName);
                getFrameName(frameName);
                getEyeBallName(eyeName);

                // checked color Type
                $("#single-color-type").prop("checked", true);
                $("#gradient-color-type").prop("checked", false);
                if (foregroundColorCheck) {
                    $("#single-color-type").prop("checked", false);
                    $("#gradient-color-type").prop("checked", false);
                    foregroundColorCheck == 'single' ? $("#single-color-type").prop("checked", true) : $("#gradient-color-type").prop("checked", true)
                    foregroundColor(foregroundColorCheck)
                }
                (foregroundColorCheck == 'gradient') ? $('.hide-show').show() : $('.hide-show').hide();

                $("#custom-eye-color").prop("checked", eyeStatusCheck);
                (eyeStatusCheck == true) ? $('.eye-hide-show').show() : $('.eye-hide-show').hide();

                $("#body-color").val(bodyColor);
                $("#colorOne").val(colorOne);
                $("#colorTwo").val(colorTwo);
                $("#colorThree").val(frameColor);
                $("#colorFour").val(eyeBallColor);
                $("#color-schema option[value='" + colorStructure + "']").prop("selected", true);

                $('#templates-tab').removeClass('active');
                $('#templates').removeClass('active show');
                $('#static-tab').removeClass('active');
                $('#static').removeClass('active show');
                $('#classic').addClass('active show');
                $('#classic-tab').addClass('active');
                $('#image_cropper').attr("src", '#');
                if (cropper != '') {
                    cropper.destroy();
                }
                $('.color-warning').hide();
                $('#color-warning').hide();
                cropperImageStatus = true
                transparentButtons()
            }
        }

        $('#classic-fa-star-o').on('click', function () {
            $('#classic-fa-star-o').hide();
            $('#classic-fa-star').show();
            storeTemplate()
        });

        function transparentData() {
            // $('#upload-transparent-image').trigger( 'click' )
            var myForm = document.getElementById('dynamic-content-type');
            // var myForm = new FormData()
            formData = new FormData(myForm);
            // formData.append('uniqueId', $('#uniqueId').val());

            var data = {
                "config": {
                    "body": 'square',
                    "frame": 'square',
                    "eyeBall": 'square',
                    "bodyColor": '#ffffff',
                    "bgColor": '#ffffff',
                    "eye1Color": '#000000',
                    "eye2Color": '#000000',
                    "eye3Color": '#000000',
                    "eyeBall1Color": '#000000',
                    "eyeBall2Color": '#000000',
                    "eyeBall3Color": '#000000',
                    "gradientColor1": '#000000',
                    "gradientColor2": '#000000',
                    "colorType": 'vertical',
                    "eyeStatus": eyeStatus,
                    "gradientType": 'vertical',
                    "gradientOnEyes": false,
                    "logo": 0
                },
                "size": 300,
                "download": false,
                "file": "svg"
            };
            formData.append('config', JSON.stringify(data));
            formData.append('transparentImage', $('#upload-transparent-image').prop('files')[0]);
            return formData;
        }

        function templateQrCode(ele) {
            $('#color-warning').hide();
        }

        function makeTransparentQrCode(ele) {
            $('#color-warning').hide();
            // transparentImageStatus = true;
            var getFormData = transparentData();
            // $('#upload-background-image').addClass('d-none')
            starIcon();

            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.qr.code.generator') }}",
                data: getFormData,
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#design-loading').show();
                    $('#classic-qr-code').hide();
                },
                complete: function () {
                    $('#design-loading').hide();
                    $('#classic-qr-code').show();
                },
                success: function (response) {
                    if (response.status == 1) {
                        $('#classic-qr-code').empty();
                        $('#classic-qr-code').append(response.html);
                        saved_image_id = response.image_id;

                        if (cropper != '') {
                            cropper.destroy()
                        }
                        cropImage()
                        // tabType = 'static'
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        }

        function uploadedTransparentImage(ele) {
            // Loading on modal
            let file = $('.transparent-image').val();
            let exten = file.split('.').pop();
            let ajaxCall = false;
            if (exten == 'png' || exten == 'jpeg' || exten == 'jpg') {

                $('#transparentImageTypeMessage').css('display', 'none');
                ajaxCall = true;
            } else {
                $('#transparentImageTypeMessage').css('display', 'block');
                ajaxCall = false;
            }
            if (ajaxCall) {
                $('#transparent-image-data').css('display', 'none')
                $('#upload-background-image').removeClass('d-inline')
                $('#upload-background-image').addClass('d-none')
                $('#transparent-loading').show();

                var formData = new FormData;
                formData.append('transparentImage', $('#transparentImage').prop('files')[0]);
                $.ajax({
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('frontend.user.qr-codes.transparent')}}',
                    data: formData,
                    beforeSend: function () {
                        $('#design-loading').show();
                        $('#classic-qr-code').hide();
                        $('#uploadedTransparentImage .cropper-container').hide()
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            if (cropper != '') {
                                cropper.destroy();
                            }
                            $('#image_cropper').attr("src", '#');
                            $('#image_cropper').attr("src", response.transparentImage);
                            transparentImageId = response.transparentImageId;
                            $('#transparent-image-data').css('display', 'none')
                            cropperImageStatus = false
                            transparentButtons()
                            cropImage()
                        }
                    }
                });
            }
        }

        function cropImage() {
            var image = document.getElementById('image_cropper');
            cropper = new Cropper(image, {
                aspectRatio: 1,
                // autoCrop: false,
                center: false,
                data: {
                    width: Number(cropperImageData.size),
                    height: Number(cropperImageData.size),
                    x: Number(cropperImageData.x),
                    y: Number(cropperImageData.y),
                },
                ready(event) {
                    serverCallAfterCrop(event, 'ready');
                },
                cropend(event) {
                    serverCallAfterCrop(event, 'cropend');
                },
            });
        }

        function serverCallAfterCrop(event, cropType) {
            $('#transparent-loading').show();

            var cropImageData = cropper.getData();
            if (cropperImageStatus && cropType == 'ready') {
                cropperImageData.x = cropImageData.x;
                cropperImageData.y = cropImageData.y;
                cropperImageData.size = cropImageData.width;
            } else {
                cropperImageData.x = cropImageData.x;
                cropperImageData.y = cropImageData.y;
                cropperImageData.size = cropImageData.width;
            }
            var getFormData = transparentData();
            getFormData.append('x', cropperImageData.x);
            getFormData.append('y', cropperImageData.y);
            getFormData.append('size', cropperImageData.size);
            getFormData.append('transparentImage', transparentImageId);
            console.log(useDesignCropperImageData,'useDesignCropperImageData')
            $.ajax({
                processData: false,
                contentType: false,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.qr.code.generator') }}",
                data: getFormData,
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#design-loading').show();
                    $('#classic-qr-code').hide();
                    $('#uploadedTransparentImage .cropper-container').hide()
                    $('#transparent-loading').show();
                },
                success: function (response) {
                    if (response.status == 1) {
                        firstImageAppend = false;
                        $('#classic-qr-code').empty();
                        $('#classic-qr-code').append(response.html);

                        $('#design-loading').hide();
                        $('#classic-qr-code').show();

                        saved_image_id = response.image_id;
                        $('#uploadedTransparentImage .cropper-container').show();
                        $('#transparent-loading').hide();

                        starIcon()
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        }

        function storeTemplate() {
            var myForm = document.getElementById('dynamic-content-type');
            formData = new FormData(myForm);

            var data = {
                "config": {
                    "body": shapeName,
                    "frame": frameName,
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
                    "colorType": colorType,
                    "eyeStatus": eyeStatus,
                    "gradientType": colorStructure,
                    "gradientOnEyes": false,
                    "logo": logoId
                },
                "size": 300,
                "download": false,
                "file": "svg"
            };

            formData.append('config', JSON.stringify(data));
            formData.append('uniqueId', '');
            formData.append('action', '');
            formData.append('template', 1);
            formData.append('image_id', saved_image_id);

            if ($('#classic').hasClass('active')) {
                transparentImageStatus = false
                mainLogoImage = temporaryLogoImage;
            } else if ($('#static').hasClass('active')) {
                transparentImageStatus = true
            }
            saveQrCode();
        }

        function deleteTemplate(id) {
            if (confirm('{{__('Are you sure you want to delete this record?')}}')) {
                var route = "{{ route('frontend.user.qr-codes.destroy',':id') }}";
                route = route.replace(':id', id);
                $.ajax({
                    type: "delete",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    data: {'id': id},
                    success: function (response) {
                        if (response.status == 1) {
                            let templateImageId = '#template-image-id-' + id;
                            let templateImageModelId = '#delete-model-' + id;
                            $(templateImageId).remove();
                            $(templateImageModelId).remove();
                        }
                    }
                });
            }
        }

        function templateConfigData(id) {
            $.ajax({
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.qr-codes.template.config.data') }}",
                data: {'id': id},
                success: function (response) {
                    if (response.status == 1) {
                        //Active and remove Tab
                        if (response.crop_status == 1) {
                            $('#transparent-image-data').css('display', 'none')
                            $('#classic-tab').removeClass('active')
                            $('#static-tab').addClass('active')
                            $('#classic').removeClass('active')
                            $('#classic').removeClass('show')
                            $('#static').addClass('active')
                            $('#static').addClass('show')
                            $('#image_cropper').attr("src", '#');
                            $('#image_cropper').attr("src", response.transparentImage);
                            transparentImageId = response.transparentImageId;
                            transparentImageStatus = response.transparentImageId ? true : false;
                            cropperImageX = response.transparentImageData.x;
                            cropperImageY = response.transparentImageData.y;
                            cropperImageSize = response.transparentImageData.size;
                            if (cropper != '') {
                                cropper.destroy()
                            }
                            cropperImageStatus = false;
                            transparentButtons()

                            cropImage()
                        } else {
                            $('#upload-logo-image').val('');
                            $('.file-upload-image').attr('src', '#')
                            transparentImageStatus = false
                            //Empty Data inside this id
                            $('#classic-qr-code').empty()
                            $('#template-qr-code').empty()
                            //Image show
                            $('#template-qr-code').append(response.image)
                            $('#classic-qr-code').append(response.image)

                            //Config Data Set
                            shapeName = response.data.body;
                            frameName = response.data.frame;
                            eyeName = response.data.eyeBall;
                            eyeStatus = response.data.eyeStatus;
                            colorOne = response.data.gradientColor1;
                            colorTwo = response.data.gradientColor2;
                            frameColor = response.data.eye1Color;
                            eyeBallColor = response.data.eyeBall1Color;
                            bodyColor = response.data.bodyColor;
                            colorStructure = response.data.gradientType;
                            logoId = response.data.logo;
                            colorType = response.data.colorType;
                            foregroundColorCheck = response.data.colorType == 1 ? 'single' : 'gradient';
                            eyeStatusCheck = response.data.eyeStatus;

                            if (response.logo_image != null) {
                                mainLogoImage = response.logo_image;
                                temporaryLogoImage = response.logo_image;
                                // How to replace three condition
                                $(".file-upload-image").attr("src", '{{asset('storage/users/'.auth()->user()->id.'/qr-codes/logo-images')}}' + '/' + response.logo_image);
                                //hide button and show image
                                $('.file-upload-content').css('display', 'block');
                                $('.image-upload-wrap').css('display', 'none');
                            } else {
                                mainLogoImage = '';
                                temporaryLogoImage = '';
                            }

                            // checked color Type
                            $("#single-color-type").prop("checked", true);
                            $("#gradient-color-type").prop("checked", false);
                            if (foregroundColorCheck) {
                                $("#single-color-type").prop("checked", false);
                                $("#gradient-color-type").prop("checked", false);
                                foregroundColorCheck == 'single' ? $("#single-color-type").prop("checked", true) : $("#gradient-color-type").prop("checked", true)
                                foregroundColor(foregroundColorCheck)
                            }
                            (foregroundColorCheck == 'gradient') ? $('.hide-show').show() : $('.hide-show').hide();

                            $("#custom-eye-color").prop("checked", eyeStatusCheck);

                            (eyeStatusCheck == true) ? $('.eye-hide-show').show() : $('.eye-hide-show').hide();

                            //Color input value set
                            $("#body-color").val(bodyColor);
                            $("#colorOne").val(colorOne);
                            $("#colorTwo").val(colorTwo);
                            $("#colorThree").val(frameColor);
                            $("#colorFour").val(eyeBallColor);
                            //Gradient type select
                            $("#color-schema option[value='" + colorStructure + "']").prop("selected", true);
                            //Shape, Frame and Eye select
                            getShapeName(shapeName);
                            getFrameName(frameName);
                            getEyeBallName(eyeName);
                            if (response.logo_image == null) {
                                getLogoId(logoId);
                            }

                            $('#static-tab').removeClass('active')
                            $('#classic-tab').addClass('active')
                            $('#classic').addClass('active')
                            $('#classic').addClass('show')
                            $('#static').removeClass('active')
                            $('#static').removeClass('show')
                        }

                        $('#templates-tab').removeClass('active')
                        $('#templates').removeClass('active')
                        $('#templates').removeClass('show')

                        starIcon()
                    }
                }
            });
        }

        function getExtension(val) {
            var file = $('.file-upload-input').val();
            var exten = file.split('.').pop();

            if (exten != 'png') {
                $('#genQrCode').attr('disabled', true);
                $('#allowd_image').removeClass('d-none');
                $('#allowd_image').addClass('d-block');
                return false;
            } else {
                $('#genQrCode').attr('disabled', false);
                $('#upload-logo-image').empty();
                $('#allowd_image').removeClass('d-block');
                $('#allowd_image').addClass('d-none');
                $('.image-upload-wrap').css('display', 'none')
                return true;
            }
        }

        $('#remove-image').on('click', function () {
            $('#genQrCode').attr('disabled', false);
            $('#upload-logo-image').empty();
            $('#allowd_image').removeClass('d-block');
            $('#allowd_image').addClass('d-none');
            $('.image-title').html(' ')
        });

        function transparentButtons() {

            if (cropperImageStatus) {
                $('#upload-background-image').removeClass('d-none')
                $('#upload-background-image').addClass('d-inline')
                $('#transparent-image-data').css('display', 'block')
                $('#image_cropper').css('display', 'none')
                $('#replace-transparent-image').removeClass('d-inline')
                $('#replace-transparent-image').addClass('d-none')
                $('#remove-transparent-image').removeClass('d-inline')
                $('#remove-transparent-image').addClass('d-none')
            } else {

                if (transparentImageId) {

                    $('#upload-background-image').removeClass('d-inline')
                    $('#upload-background-image').addClass('d-none')
                    $('#transparent-image-data').css('display', 'none')
                    $('#image_cropper').css('display', 'block')
                    $('#replace-transparent-image').removeClass('d-none')
                    $('#replace-transparent-image').addClass('d-inline')
                    $('#remove-transparent-image').removeClass('d-none')
                    $('#remove-transparent-image').addClass('d-inline')
                } else {

                    $('#upload-background-image').removeClass('d-none')
                    $('#upload-background-image').addClass('d-inline')
                    $('#transparent-image-data').css('display', 'block')
                    $('#image_cropper').css('display', 'none')
                    $('#replace-transparent-image').removeClass('d-inline')
                    $('#replace-transparent-image').addClass('d-none')
                    $('#remove-transparent-image').removeClass('d-inline')
                    $('#remove-transparent-image').addClass('d-none')
                }
            }
        }

        function removeTransparentImage(ele) {
            cropperImageStatus = true;
            transparentButtons();
            $('#image_cropper').attr('src', '#');
            $('#transparentImageTypeMessage').css('display', 'none');
            cropper.destroy();
            $('#upload-transparent-image').val(null);
            transparentImageId = null;
            makeTransparentQrCode();
        }
    </script>
@endsection

