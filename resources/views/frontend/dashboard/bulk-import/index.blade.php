@extends('frontend.layouts.dashboard')

@section('title', __('Bulk Import'))

@section('content')

    <div class="content-body">
        <form id="save-bulk-qr-code-form" class="dynamic-content-type-form" method="post" enctype="multipart/form-data"  onsubmit="return false;">
            <div class="row">
                <div class="col-md-12">
                    <div class="fixed--left--qrbar">
                        <div class="row">
                            <div class="col-12">
                                <div>@include('frontend.messages')</div>
                                <div class="section-title">
                                    <h3 class="sub-title">{{__('General')}}</h3>
                                </div>
                                <div class="cardbox">
                                    <div class="cardbox-inner cardbox-head">
                                        <div class="title text-left  text-capitalize">
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

                                            {{--                                            <i class="fa fa-link"></i> @isset($parameters['content_type']) {{$parameters['content_type']}} @endisset--}}
                                            {{--                                            <span class="badge badge-secondary text-capitalize">@isset($parameters['type']){{$parameters['type']}}@endisset</span>--}}
                                        </div>
                                        <div class="options">
                                            <a href="{{route('frontend.user.bulk-import.select.content.type')}}"
                                               class="btn btn-outline-secondary btn-sm" type="button"><i
                                                    class="fa fa-pencil"></i> {{__('Edit Type')}} </a>
                                        </div>
                                    </div>
                                    <div class="cardbox">
                                        <div class="cardbox-inner">
                                            <form id="save-qr-code-form" onsubmit="return false;">
                                                {{--                                            <div class="form-group">--}}
                                                {{--                                                <label><b>Name </b></label>--}}
                                                {{--                                                <span> A short title that identifies your QR code and helps you find it again</span>--}}
                                                {{--                                                <input class="form-control" type="text" name="name" id="general-name"--}}
                                                {{--                                                       placeholder="e.g. vCard John Doe" required--}}
                                                {{--                                                       value="{{ ($action == 'Add') ? old('name') : $generateQrCode->name}}">--}}
                                                {{--                                            </div>--}}
                                                @if(checkFieldStatus(4))

                                                    <div class="form-group">
                                                        <label><b>{{__('Campaign')}}</b><span class="text-danger"> *</span></label>
                                                        <select-campaign>

                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-folder-o"></i>
                                                                </div>
                                                                <select id="add-campaign" required class="form-control"
                                                                        name="campaignId"
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
                                                <form id="campaign-form" onsubmit="return false;">
                                                    <div class="subform form-new-campaign pt-3" style="display: none;">
                                                        <label>{{__('New Campaign')}}<span class="text-danger"> *</span></label>
                                                        <div class="input-group">
                                                            <input type="hidden" value="Add" name="action">
                                                            <input class="form-control" id="campaign-name" name="name"
                                                                   placeholder="e.g. QR {{__('Codes')}} 2018"
                                                                   type="text" required maxlength="20">
                                                            <div class="input-group-append">
                                                                <a href="javascript:void(0)" class="btn btn-success" type="button"
                                                                        id="campaign-btn" onclick="addCampaign(this)">
                                                                    {{__('Create')}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="section-title pt-2">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="sub-title">{{__('Import Data')}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cardbox section">
                                    <div class="tab-section edit--compaigns tabs-content bulk-import-tabs">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="Textfield-tab" data-toggle="tab"
                                                   href="#Textfield" role="tab" aria-controls="Textfield"
                                                   aria-selected="true">{{__('Textfield')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="Excel-tab" data-toggle="tab" href="#Excel"
                                                   role="tab"
                                                   aria-controls="Excel" aria-selected="false">Excel</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="CSV-tab" data-toggle="tab" href="#CSV"
                                                   role="tab"
                                                   aria-controls="CSV" aria-selected="false">CSV</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="Textfield" role="tabpanel"
                                                 aria-labelledby="Textfield-tab">
                                                <div class="cardbox-inner">
                                                    <div class="form-group">
                                                        <label><b>{{__('Data')}}</b> <span class="shortinfo">{{__('Every row is a new entry and its fields are divided by comma')}}.</span></label>
                                                        <textarea class="form-control" required id="bulkText"
                                                                  formcontrolname="list"
                                                                  placeholder="{{__('See example for data format below')}}..."
                                                                  rows="10"
                                                                  style="height: 120px;"></textarea>
                                                    </div>
                                                    <div>{{__('Example Data')}}:</div>
                                                    <code> Joe,Connor,CEO,+491239581<br> Lucie,Woo,CTO,+498923759<br>
                                                        Hugh,Gates,Co-Founder,+89218248 </code>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="Excel" role="tabpanel"
                                                 aria-labelledby="Excel-tab">
                                                <form id="excelFileImportFform" enctype="multipart/form-data"
                                                      method="POST">
                                                    @csrf
                                                    <div class="cardbox-inner">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" value="1"
                                                                       id="ignoreExcelHead" name="ignoreExcelHead"
                                                                       type="checkbox"> {{__('Ignore heading row for import')}}
                                                            </label>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            {{--                                                        <button class="btn btn-primary" type="button"--}}
                                                            {{--                                                                onclick="$('.file-upload-input').trigger( 'click' )"--}}
                                                            {{--                                                                accept=".exl">Upload Excel--}}
                                                            {{--                                                        </button>--}}
                                                            <input accept=".xlsx,.xlsm,.xls" ng2fileselect=""
                                                                   name="excelFile" class="excelFile" id="excelFile"
                                                                   type="file">
                                                            <br>
                                                            <span id="excel_file_error"></span>

                                                            <span
                                                                class="d-none"
                                                                id="excelfiletype"
                                                                style="color: #FC4014">{{__('Please upload a file with .xlsx, .xlsm, .xls only.')}}</span>

                                                        </div>
                                                        <div class="alert alert-light mb-0 persist-alert">
                                                            {{__('Excel file should have one sheet without references and functions,otherwise convert to CSV')}}
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="Excel" role="tabpanel"
                                                 aria-labelledby="Excel-tab">
                                                <form id="excelFileImportFform" enctype="multipart/form-data"
                                                      method="POST">
                                                    @csrf
                                                    <div class="cardbox-inner">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" value="1"
                                                                       id="ignoreExcelHead" name="ignoreExcelHead"
                                                                       type="checkbox"> {{__('Ignore heading row for import')}}
                                                            </label>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            {{--                                                        <button class="btn btn-primary" type="button"--}}
                                                            {{--                                                                onclick="$('.file-upload-input').trigger( 'click' )"--}}
                                                            {{--                                                                accept=".exl">Upload Excel--}}
                                                            {{--                                                        </button>--}}
                                                            <input ng2fileselect="" name="excelFile" id="excelFile"
                                                                   type="file"
                                                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                        </div>
                                                        <div class="alert alert-light mb-0 persist-alert">
                                                            {{__('Excel file should have one sheet without references and functions,otherwise convert to CSV')}}
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="CSV" role="tabpanel"
                                                 aria-labelledby="CSV-tab">
                                                <div class="cardbox-inner">
                                                    <form action="#" id="csvFileImportFform" method="POST"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" id="csvHeadIgnore"
                                                                       name="csvHeadIgnore"
                                                                       type="checkbox"> {{__('Ignore heading row for import')}}
                                                            </label>
                                                        </div>

                                                        <div class="col-md-6 pl-0 mt-3">
                                                            <div class="input-group form-group">
                                                                <span
                                                                    class="input-group-addon"> {{__('Delimiter')}} </span>
                                                                <select class="form-control" name="delimiter"
                                                                        id="delimiter">
                                                                    <option value=",">,</option>
                                                                    <option value=";">;</option>
                                                                    <option value="|">|</option>
                                                                    <option value="^">^</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            {{--                                                        <button class="btn btn-primary" type="button"--}}
                                                            {{--                                                                onclick="$('.file-upload-input').trigger( 'click' )"--}}
                                                            {{--                                                                accept=".exl">Upload CSV--}}
                                                            {{--                                                        </button>--}}
                                                            <input ng2fileselect="" accept=".csv" id="csvFile"
                                                                   name="csvFile" class="csvFile" type="file">
                                                            <br>
                                                            <span id="csv_file_error"></span>
                                                            <span
                                                                class="d-none"
                                                                id="csvfiletype"
                                                                style="color: #FC4014">{{__('Please upload a file with .csv extension.')}}</span>

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="section-title">
                                <h3 class="sub-title">{{__('Assign Fields')}}</h3>
                            </div>
                            <div class="cardbox py-2">
                                @isset($parameters)
                                    <div class="cardbox-inner">
                                        <form id="dynamic-content-type" class="dynamic-content-type-form" method="post"
                                              enctype="multipart/form-data"  onsubmit="return false;">
                                            <div class="row">
                                                <input type="hidden" value="{{$action}}" name="action"
                                                       id="contentType">
                                                <input type="hidden" value="{{$generateQrCode->id}}" name="id"
                                                       id="generateQrCodeId">
                                                <input type="hidden" value="{{$parameters['content_type']}}" name="type"
                                                       id="type">
                                                <input type="hidden" value="{{$parameters['type']}}" name="qrCodeType"
                                                       id="qrCodeType">
                                                <input type="hidden"
                                                       value="{{url('/q/'.$parameters['unique_id'])}}"
                                                       name="uniqueId"
                                                       id="uniqueId">
                                                @if($parameters['type'] == 'dynamic')
                                                    <input type="hidden" name="dynamicUrl"
                                                           value=" {{url('/q/').($action == 'Add' ? $parameters['unique_id'] : $generateQrCode->unique_id)}}">
                                                @endif
                                                @switch($parameters['content_type'])
                                                    @case('url')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="qrcodeName">{{__('Name')}}
                                                            </label>
                                                            <select id="general-name" name="name"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('auto')}} -</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="url"><span
                                                                    class="shortinfo">{{__('Url')}}<span
                                                                        class="text-danger"> *</span></span>
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)" data-type="url"
                                                                    id="url" required class="form-control assignedVal"
                                                                    name="qrcodeUrl"
                                                            >
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @break
                                                    @case('vcard')
                                                    <input name="vcardVersion"
                                                           value="3.0" type="hidden">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="name">{{__('Name')}}
                                                            </label>
                                                            <select id="general-name" name="name"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('auto')}} -</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardFirstName">{{__('First Name')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardFirstName"
                                                                    name="qrcodeVcardFirstName"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label for="qrcodeVcardLastName">{{__('Last Name')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardLastName" name="qrcodeVcardLastName"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeVcardOrganization">{{__('Organization')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardOrganization"
                                                                    name="qrcodeVcardOrganization"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6  col-12" style="display: none">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeVcardTitle">{{__('Position (Work)')}} </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardTitle" name="qrcodeVcardTitle"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeVcardPhoneWork">{{__('Phone (Work)')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardPhoneWork"
                                                                    name="qrcodeVcardPhoneWork"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12" style="display: none">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardPhonePrivate">{{__('Phone (Private)')}}
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardPhonePrivate"
                                                                    name="qrcodeVcardPhonePrivate"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardPhoneMobile">{{__('Phone (Mobile)')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardPhoneMobile"
                                                                    name="qrcodeVcardPhoneMobile"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardFaxWork">{{__('Fax (Work)')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardFaxWork" name="qrcodeVcardFaxWork"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6  col-12" style="display: none">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardFaxPrivate">{{__('Fax (Private)')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardFaxPrivate"
                                                                    name="qrcodeVcardFaxPrivate"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardEmail">{{__('Email')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardEmail" name="qrcodeVcardEmail"
                                                                    data-type="email"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardUrl">{{__('Website')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardUrl" name="qrcodeVcardUrl"
                                                                    data-type="url"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardStreet">{{__('Street')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardStreet" name="qrcodeVcardStreet"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardZipcode">{{__('Zipcode')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardZipcode" name="qrcodeVcardZipcode"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardCity">{{__('City')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardCity" name="qrcodeVcardCity"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6  col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardState">{{__('State')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardState" name="qrcodeVcardState"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12">
                                                        <div class="form-group"><label
                                                                for="qrcodeVcardCountry">{{__('Country')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    id="qrcodeVcardCountry" name="qrcodeVcardCountry"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @break
                                                    @case('text')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="name">{{__('Name')}}
                                                            </label>
                                                            <select id="general-name" name="name"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('auto')}} -</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="">
                                                <div class="form-group"><label
                                                        for="qrcodeText">{{__('Your Text')}}<span
                                                            class="text-danger"> *</span></label>
                                                    <select onchange="getQrCodeByField(this)" id="qrcodeText"
                                                            name="text" required
                                                            class="form-control assignedVal">
                                                        <option value="">- {{__('no column assigned')}} -</option>
                                                    </select>
                                                </div>
                                                @break

                                                @case('email')
                                                <div class="col-12">

                                                    <div class="form-group">
                                                        <label for="qrcodeName">{{__('Name')}}
                                                        </label>
                                                        <select onchange="getQrCodeByField(this)" id="general-name"
                                                                name="qrcodeName" class="form-control assignedVal">
                                                            <option value="">- {{__('auto')}} -</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="qrcodeEmail">{{__('Your Email')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                id="qrcodeEmail"
                                                                data-type="email"
                                                                name="qrcodeEmail">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="qrcodeEmailSubject">{{__('Subject')}}</label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal"
                                                                id="qrcodeEmailSubject" name="qrcodeEmailSubject">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="qrcodeEmailMessage">{{__('Message')}}</label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" id=""
                                                                name="qrcodeEmailMessage">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @break

                                                @case('phone')
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">{{__('Name')}}
                                                        </label>
                                                        <select id="general-name" name="name"
                                                                class="form-control assignedVal">
                                                            <option value="">- {{__('auto')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="qrcodePhone">{{__('Phone Number')}}<span
                                                                class="text-danger"> *</span>
                                                        </label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                id="qrcodePhone"
                                                                name="qrcodePhone">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @break
                                                @case('sms')
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">{{__('Name')}}
                                                        </label>
                                                        <select onchange="getQrCodeByField(this)" id="general-name"
                                                                name="name" class="form-control assignedVal">
                                                            <option value="">- {{__('auto')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="qrcodeSmsPhone">{{__('Phone Number')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                id="qrcodeSmsPhone"
                                                                name="qrcodeSmsPhone">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                    <div class="">
                                                        <div class="form-group">
                                                            <label for="qrcodeSmsText">{{__('Message')}}</label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal" id="qrcodeSmsText"
                                                                    name="qrcodeSmsText">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @break

                                                    @case('app-store')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="name">{{__('Name')}}
                                                            </label>
                                                            <select id="general-name" name="name"
                                                                    class="form-control assignedVal">
                                                                <option value="">- {{__('auto')}} -</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="qrcodeAppStoreUrl">{{__('Default URL')}} <span
                                                                    class="shortinfo">{{__('Fallback URL for devices without available app store')}}.</span>
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal" required
                                                                    id="qrcodeAppStoreUrl" name="qrcodeAppStoreUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeAppStoreAppStoreUrl">{{__('iOS App Store')}}
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal"
                                                                    id="qrcodeAppStoreAppStoreUrl"
                                                                    name="qrcodeAppStoreAppStoreUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeAppStoreGooglePlayStoreUrl">{{__('Google Play Store')}}
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal"
                                                                    id="qrcodeAppStoreGooglePlayStoreUrl"
                                                                    name="qrcodeAppStoreGooglePlayStoreUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeAppStoreWindowStoreUrl">{{__('Window Store')}}
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal"
                                                                    id="qrcodeAppStoreWindowStoreUrl"
                                                                    name="qrcodeAppStoreWindowStoreUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeAppStoreBlackberryUrl">{{__('Blackberry')}}
                                                            </label>
                                                            <select onchange="getQrCodeByField(this)"
                                                                    class="form-control assignedVal"
                                                                    id="qrcodeAppStoreBlackberryUrl"
                                                                    name="qrcodeAppStoreBlackberryUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                                @case('event')
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">{{__('Name')}}
                                                        </label>
                                                        <select id="general-name" name="name"
                                                                class="form-control assignedVal">
                                                            <option value="">- {{__('auto')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>{{__('Title')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                name="summary" id="summary">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label for="startDateTime">{{__('Start Date')}}<span
                                                                class="text-danger"> *</span> {{__('e.g.')}} (29/01/2021
                                                            20:00)</label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                id="startDateTime"
                                                                data-type="date"
                                                                name="startDateTime">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label for="endDateTime">{{__('End Date')}}<span
                                                                class="text-danger"> *</span> {{__('e.g.')}} (30/01/2021
                                                            20:00)</label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" required
                                                                id="endDateTime"
                                                                data-type="date"
                                                                name="endDateTime">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="eventTimezone">{{__('Timezone')}}</label>
                                                        <!-- Button trigger modal -->
                                                        <a class="fa fa-info-circle fa-4" style="cursor: pointer" aria-hidden="true"
                                                           data-toggle="modal" data-target="#timezoneInfoModal"></a>
                                                        <select class="form-control assignedVal"
                                                                id="qrcodeEventTimezone"
                                                                name="qrcodeEventTimezone">
                                                            <option value="">- {{__('no column assigned')}}-
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="location">{{__('Location')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal"
                                                                id="location" name="location">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @if($parameters['type'] == 'dynamic')
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="qrcodeEventDescription">{{__('Description')}}<span
                                                                    class="text-danger"> *</span></label>
                                                            <select class="form-control assignedVal"
                                                                    id="qrcodeEventDescription"
                                                                    name="qrcodeEventDescription">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="eventUrl">{{__('Url')}}</label>
                                                            <select class="form-control assignedVal" id="eventUrl"
                                                                    name="eventUrl">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        {{--                                                        onchange="getQrCodeByField(this)"--}}
                                                        <div class="form-group">
                                                            <label for="eventReminder">{{__('Reminder')}}
                                                                ({{__('Minutes')}} 0,5,15,30,60,720,1440,10080)</label>
                                                            <select class="form-control assignedVal" id="reminder"
                                                                    name="qrcodeEventReminder">
                                                                <option value="">- {{__('no column assigned')}}-
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                                @break
                                                @case('wifi')
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="name">{{__('Name')}}
                                                        </label>
                                                        <select id="general-name" name="name"
                                                                class="form-control assignedVal">
                                                            <option value="">- {{__('auto')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="ssid">{{__('Wirless SSID')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)" required
                                                                class="form-control assignedVal" id="ssid"
                                                                name="ssid">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="password">{{__('Password')}}<span
                                                                class="text-danger"> *</span></label>
                                                        <select onchange="getQrCodeByField(this)" required
                                                                class="form-control assignedVal" id="password"
                                                                name="password">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="encryption">{{__('Encryption')}}<span
                                                                class="text-danger"> * </span>(nopass/WEP/WPA)</label>
                                                        <select onchange="getQrCodeByField(this)"
                                                                class="form-control assignedVal" id="encryption"
                                                                required
                                                                name="encryption">
                                                            <option value="">- {{__('no column assigned')}} -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @break

                                                @default
                                                <div class="cardbox-inner">
                                                    <div class="text-center">
                                                        {{__('No Import Data Set')}}
                                                    </div>
                                                </div>
                                                @endswitch
                                            </div>

                                        </form>

                                    </div>
                                @else
                                    <div class="cardbox-inner">
                                        <div class="text-center">
                                            {{__('No Import Data Set')}}
                                        </div>
                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>
            <!-- Fixed QR code Right sidebar -->
                    <div class="fixed-right-qrbar  ">
                        <div class="col-12">
                            <button class="btn btn-orange w-100 mb-3" type="button"
                                    id="save-qr-code-btn" {{ ($action == 'Add') ? 'disabled' : ''}} >
                                <i class="fa fa-plus"></i> {{ ($action == 'Add') ? __('Create') : "Save"}}
                            </button>
                        </div>
                        <div class="cardbox">
                            <div class="qrcode-container">
                                <div class="qrcode dashboard-generated-qr-code img-contaner img-contane-color" id="generated-qr-code">
                                    <img class="qr-code-scan"
                                        src="{{checkImage(asset('storage/users/'.$generateQrCode->user_id.'/qr-codes/' . $generateQrCode->image),'default.svg',$generateQrCode->image)}}">
                                </div>
                                <div class="fa-5x mt-4" id="loading" style="display: none">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                            </div>
                            @if(checkFieldStatus(5))
                                <div class="cardbox-inner">
                                    <button id="custom-qr-btn" class="btn btn-primary w-100" type="button">
                                        <i class="fa fa-paint-brush"></i> {{__('Design QR Code')}}
                                    </button>
                                    <p class="alerttxt bottom-text text-center mt-3">
                                        <span>* {{__('Complex design or data may cause delay in scanning. Try keeping it simple')}}</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                 </div>
            </div>
        </form>
     <!-- End -->
    <!--------------------- Model Popups ------------------------->

    <!-- design qr code -->
    <div class="modal fade manager design-qrcode-model" id="design-qrcode">
        <div class="modal-dialog modal-lg" role="document">
            <div id="qr-code-height-change" class="qr-designer visible">
                <div class="qr-designer">
                    <div class="qr-designer-inner">
                        <div class="modal-content">
                            <div class="modal-body pb-0">
                                <div class="tab-section">
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

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               id="templates-tab"
                                               onclick="templateQrCode()"
                                               data-toggle="tab"
                                               href="#templates"
                                               role="tab"
                                               aria-controls="templates"
                                               aria-selected="false"> {{__('Templates')}}
                                            </a>
                                        </li>
                                        @if(checkFieldStatus(7))
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                   id="save-design-tab"
                                                   onclick="saveDesignQrCode()"
                                                   data-toggle="tab"
                                                   href="#save-designs"
                                                   role="tab"
                                                   aria-controls="save-designs"
                                                   aria-selected="false"> <i
                                                        class="fa fa-star"></i> {{__('Saved Designs')}}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    <!-- Body -->
                                    <div class="content-body" style="padding:0">
                                        <div class="tabs-content row"
                                             id="myTabContent">
                                            <div
                                                class="tab-pane fade show active col-sm-12"
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
                                                                                        class="input-group image-upload-btn form-group">
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
                                                                                    src="{{($action == 'Add') ? '#' : ($generateQrCode->logo_image ? asset('storage/users/'.auth()->user()->id.'/qr-codes/logo-images/'.$generateQrCode->logo_image) : '#')}}"
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
                                                            <div class="card">
                                                                <div
                                                                    class="card-header"
                                                                    id="headingTwo">
                                                                    <h5 class="mb-0">
                                                                        <div
                                                                            class="collapsed"
                                                                            data-toggle="collapse"
                                                                            data-target="#collapseFour"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapseFour">
                                                                            <div
                                                                                class="pane-header">
                                                                                <div
                                                                                    class="icon">
                                                                                    <i
                                                                                        class="fa fa-picture-o"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <h3 class="title">{{__('Choose Frame Design')}}</h3>
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
                                                                    id="collapseFour"
                                                                    class="collapse"
                                                                    aria-labelledby="headingTwo"
                                                                    data-parent="#accordion">
                                                                    <div
                                                                        class="card-body customized-2nd-tab-body clearfix">
                                                                        <label><b>{{__('Frame Style')}}</b></label>
                                                                        <div
                                                                            class="form-group presets clearfix">
                                                                            <div
                                                                                class="item qr-code-frame {{($action == 'Add') ? 'active': ((isset(json_decode($generateQrCode->config, true)['qrCodeFrameId']) && json_decode($generateQrCode->config, true)['qrCodeFrameId'] != '0') ?'' : 'active')}}"
                                                                                id="qrCodeFrameId0"
                                                                                onclick="qrCodeFrameName('0')">
                                                                                <div class="image-tile__none"></div>
                                                                            </div>
                                                                            @foreach($qrCodeFrames as $index => $qrCodeFrame)
                                                                                <div
                                                                                    class="item qr-code-frame {{($action == 'Add') ? '': ((isset(json_decode($generateQrCode->config, true)['qrCodeFrameId']) && json_decode($generateQrCode->config, true)['qrCodeFrameId'] == $qrCodeFrame->id) ? 'active' : '')}}"
                                                                                    id="qrCodeFrameId{{$qrCodeFrame->id}}"
                                                                                    onclick="qrCodeFrameName('{{$qrCodeFrame->id}}')">
                                                                                    {{--                                                                                {{json_decode($generateQrCode->config, true)['qrCodeFrameId'] == $qrCodeFrame->id}}--}}
                                                                                    <img
                                                                                        src="{{ asset('storage/shapes/'.$qrCodeFrame->image) }}">
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
                                                                                class="fa-5x mt-4"
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

                                                                                        <button
                                                                                            id="upload-background-image"
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
                                                 id="save-designs" role="tabpanel"
                                                 aria-labelledby="save-design-tab">
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
                                            <div class="tab-pane fade col-sm-12"
                                                 id="templates" role="tabpanel"
                                                 aria-labelledby="templates-tab">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div
                                                            class="row designsetting qrdesigner-iner">
                                                            @foreach($adminQrCodes as $index => $adminQrCode)
                                                                <div
                                                                    class="col-md-3 col-sm-4 col-6"
                                                                    id="template-image-id-{{Hashids::encode($adminQrCode->id)}}">
                                                                    <div
                                                                        class="template">
                                                                        <div
                                                                            class="qrcode-container">
                                                                            <div
                                                                                class="qrcode"
                                                                                onclick="templateConfigData('{{Hashids::encode($adminQrCode->id)}}')">
                                                                                <img
                                                                                    src="{{checkImage(asset('storage/admin-qr-codes/' . $adminQrCode->image), 'default.svg', $adminQrCode->image)}}"
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
                                                                                    <span>{{$adminQrCode->name}}</span>
                                                                                    <!---->
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
                                                                class="qrcode mt-3 img-contaner img-contane-color"
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
                                                                <p class="alerttxt bottom-text text-center  mt-3 mb-3">
                                                                    <span>* Complex design or data may cause delay in scanning. Try keeping it simple</span>
                                                                </p>
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
                                                            onclick="cloneQrCode()">{{__('Use Design')}}
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
    </div>
    {{--   Error message alert  --}}
    <div class="modal fade" id="errorMessageModal" tabindex="-1" role="dialog" aria-labelledby="customErrorMessageModal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customErrorMessageModal">{{__('Invalid Url')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="errorMessageDiv" style="color: #e13b15">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- TimeZone modal -->
    <div class="modal fade bulkimport home-qr-template-model show"
         id="timezoneInfoModal" tabindex="-1" role="dialog"
         aria-labelledby="timezoneInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="timezoneInfoModalLabel"> {{__('Time Zone list')}}</h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div
                        class="d-flex campaign-list list new-time-zone-model p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                            <!-- <thead>
                                                                                        <tr>
                                                                                            <th colspan="2"
                                                                                                class="w-50 text-center">
                                                                                                {{__('Time Zone list')}}
                                </th>
                            </tr>
                        </thead> -->
                                <tbody>
                                @foreach($timezones->chunk(2) as $timezone)
                                    <tr>

                                        @foreach($timezone as $timeName)
                                            <td class="w-50" style="word-break: break-word;">
                                                {{$timeName->name}}
                                            </td>
                                        @endforeach
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        $("#excelFile").change(function () {
            var fileExtension = ['xlsx', 'xlsm', 'xls'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                $('#excelfiletype').removeClass('d-none');
            } else {
                $('#excelfiletype').addClass('d-none');
            }

            $("#excel_file_error").html("");
            var file_size = $('#excelFile')[0].files[0].size;
            if (file_size > 1048576) {
                $("#excel_file_error").html("<p style='color:#FF0000'>{{__('File size is too big. Max. 1 MB.')}}</p>");
                return false;
            }
            return true;
        });


        $("#csvFile").change(function () {

            var fileExtension = ['csv'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                $('#csvfiletype').removeClass('d-none');
            } else {
                $('#csvfiletype').addClass('d-none');
            }

            $("#csv_file_error").html("");
            var file_size = $('#csvFile')[0].files[0].size;
            if (file_size > 1048576) {
                $("#csv_file_error").html("<p style='color:#FF0000'>{{__('File size is too big. Max. 1 MB.')}}</p>");
                return false;
            }
            return true;
        });

        let shapeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['body']}}';
        let frameName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['frame']}}';
        var qrCodeFrameId = '0';
        let eyeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['eyeBall']}}';
        let eyeStatus = '{{ ($action == 'Add') ? false : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true : false)}}';
        let colorOne = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}';
        let colorTwo = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}';
        let frameColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}';
        let eyeBallColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}';
        let bodyColor = '{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}';
        let colorStructure = '{{ ($action == 'Add') ? 'vertical' : json_decode($generateQrCode->config, true)['gradientType']}}';
        var generateShortLink = 0;
        var transparentImageId = null;
        var transparentImageStatus = false;
        var cropper = '';
        var cropperImageX = 0;
        var cropperImageY = 0;
        var cropperImageSize = 500;
        var cropperImageData = {x: cropperImageX, y: cropperImageY, size: cropperImageSize};
        var cropperImageStatus = false;
        var temporaryLogoImage = '';
        var mainLogoImage = '';
        var tabType = 'classic'

            {{--let logoId = '{{ ($action == 'Add') ? 0 : json_decode($generateQrCode->config, true)['logo']}}';--}}
        let logoId = 0;
        let colorType = '{{($action == 'Add') ? true : json_decode($generateQrCode->config, true)['colorType']}}';
        let formData = '';
        let saved_image_id = '';
        let main_image = '';
        let firstImageAppend = true;
        let ajaxCallBlock = true;
        let modalLoading = false;
        let dynamicContentField = '{{($parameters['type'] == 'dynamic') ? true : false}}';
        let dynamicContentFieldAction = '{{($action == 'Add') ? true : false}}';

        //Reset Value
        var defaultShapeName = 'square';
        var defaultFrameName = 'square';
        var defaultEyeName = 'square';
        var defaultEyeStatus = false;
        var defaultColorOne = '#000000';
        var defaultColorTwo = '#000000';
        var defaultFrameColor = '#000000';
        var defaultEyeBallColor = '#000000';
        var defaultBodyColor = '#ffffff';
        var defaultColorStructure = 'vertical';
        var defaultLogoId = 0;
        var defaultColorType = true;
        var defaultForegroundColorCheck = '';
        var defaultEyeStatusCheck = '';

        $(document).ready(function () {
            $('.hide-show').hide();
            $('.eye-hide-show').hide();
            $('#classic-fa-star').hide();
            ajaxCallBlock = '{{($action == 'Add') ? true : false}}';
            let foregroundColorCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'single': 'gradient')}}';
            let eyeStatusCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true: false)}}';
            if (foregroundColorCheck) {
                foregroundColor(foregroundColorCheck)
            }
            if (eyeStatusCheck) {
                eyeStatus = false
                eyeColorStatus()
            }
//          Check the dynamic Field
            if (dynamicContentField && dynamicContentFieldAction) {
                makeQrCode();
            }
        });


        //      Check Frontend Validation
        function frontendValidation(formName) {
            let formId = '#' + formName;
            $(formId).validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    text: {
                        required: true,
                    },
                    campaignId: {
                        required: true,
                    },
                    qrcodeText: {
                        required: true,
                    },
                    qrcodeEmail: {
                        required: true,
                        // email: true,
                    },
                    qrcodePhone: {
                        required: true,
                    },
                    qrcodeSmsPhone: {
                        required: true,
                    },
                    // qrcodeSmsText: {
                    //     required : false,
                    //     maxlength: true,
                    // },
                    summary: {
                        required: true,
                    },
                    location: {
                        required: true,
                    },
                    // qrcodeEventTimezone: {
                    //     required: true,
                    // },
                    // qrcodeEventReminder: {
                    //     required: true,
                    // },
                    qrcodeEventDescription: {
                        required: true,
                    },
                    startDateTime: {
                        required: true,
                        date: true,
                    },
                    endDateTime: {
                        required: true,
                        date: true,
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
                    campaignId: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeUrl: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeText: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeEmail: {
                        required: '{{__('This field is required')}}',
                        {{--email: '{{__('Please enter a valid email address')}}',--}}
                    },
                    qrcodePhone: {
                        required: '{{__('This field is required')}}'
                    },
                    qrcodeSmsPhone: {
                        required: '{{__('This field is required')}}'
                    },
                    {{--qrcodeSmsText: {--}}
                        {{--    maxlength: '{{__('Must be less than 400')}}'--}}
                        {{--},--}}
                    summary: {
                        required: '{{__('This field is required')}}'
                    },
                    location: {
                        required: '{{__('This field is required')}}'
                    },
                    {{--qrcodeEventTimezone: {--}}
                    {{--    required: '{{__('This field is required')}}'--}}
                    {{--},--}}
                    {{--qrcodeEventReminder: {--}}
                    {{--    required: '{{__('This field is required')}}'--}}
                    {{--},--}}
                    qrcodeEventDescription: {
                        required: '{{__('This field is required')}}',
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
                        let controls = element.closest('div[class*="col-"]');
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

        //      Add Campaign
        function addCampaign(e) {
            frontendValidation('campaign-form');
            if ($('#campaign-form').valid()) {
                var formData = {
                    name: $('#campaign-name').val(),
                    action: 'Add'
                };

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('frontend.user.campaigns.store') }}",
                    data: formData,
                    // enctype: 'multipart/form-data',
                    success: function (response) {
                        if (response.status == 1) {
                            // Remove selected attribute and also hide campaign form
                            $('#add-campaign option:selected').removeAttr('selected')
                            $('.form-new-campaign').css('display', 'none')

                            $("#add-campaign").append('<option selected value="' + response.data.id + '">' + response.data.name + '</option>');
                            $("#campaign-name").val('')
                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
        }

        //      Validation data and open Modal
        $('#custom-qr-btn').on('click', function () {
            frontendValidation('dynamic-content-type');
            frontendValidation('save-bulk-qr-code-form');

            if ($('#dynamic-content-type').valid() && $('#save-bulk-qr-code-form').valid()) {
                $('#design-qrcode').modal('show');
            }
        });

        function isUrlValid(col_index) {
            let msg = '';
            let bulkTextVal = $('#bulkText').val();
            let bulkTextRow = bulkTextVal.split('\n');
            bulkTextRow = bulkTextRow.filter(item => item);
            $.each(bulkTextRow, function (key, value) {
                let bulkTextRowArray1 = value.split(',');
                let bulkTextRowArray = bulkTextRowArray1[col_index];
                if (url != undefined) {
                    if (/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(bulkTextRowArray)) {
                        return true;
                    } else {
                        msg = msg + '\n' + '{{__('Invalid URL type at row ')}}' + key + '<br>';
                    }
                }
            });
            if (msg != '') {
                $('#errorMessageDiv').html(msg);
                $('#id').empty()
                $('#dynamic-content-type').find('select').empty()
                $('.assignedVal').append($('<option>', {
                    value: '',
                    text: '{{__('no column selected')}}'
                }));
                $('#general-name').find('select').empty()
                $('#general-name').html($('<option>', {
                    value: '',
                    text: '{{__('auto')}}'
                }));
                let bulkTextVal = $('#bulkText').val();
                let bulkTextRow = bulkTextVal.split('\n');
                let bulkTextRowArray = bulkTextRow[0].split(',');
                $.each(bulkTextRowArray, function (key, value) {
                    $('.assignedVal').append($('<option>', {
                        value: key,
                        text: value + (' (Column ' + key + ')'),
                    }));
                });
                $("#errorMessageModal").modal('toggle');
                msg = ''
            }
        }

        function isEmailValid(col_index) {
            let msg = '';
            let bulkTextVal = $('#bulkText').val();
            let bulkTextRow = bulkTextVal.split('\n');
            bulkTextRow = bulkTextRow.filter(item => item);
            $.each(bulkTextRow, function (key, value) {
                let bulkTextRowArray1 = value.split(',');
                let bulkTextRowArray = bulkTextRowArray1[col_index];
                // if (email != undefined) {
                if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(bulkTextRowArray)) {
                    return true;
                } else {
                    msg = msg + '\n' + '{{__('Invalid email type at row ')}}' + key + '<br>';
                }
                // }
            });
            if (msg != '') {
                $('#errorMessageDiv').html(msg);
                $('#customErrorMessageModal').html('{{__('Invalid Email')}}')
                $('#id').empty()
                $('#dynamic-content-type').find('select').empty()
                $('.assignedVal').append($('<option>', {
                    value: '',
                    text: '{{__('no column selected')}}'
                }));
                $('#general-name').find('select').empty()
                $('#general-name').html($('<option>', {
                    value: '',
                    text: '{{__('auto')}}'
                }));
                let bulkTextVal = $('#bulkText').val();
                let bulkTextRow = bulkTextVal.split('\n');
                let bulkTextRowArray = bulkTextRow[0].split(',');
                $.each(bulkTextRowArray, function (key, value) {
                    $('.assignedVal').append($('<option>', {
                        value: key,
                        text: value + (' (Column ' + key + ')'),
                    }));
                });
                $("#errorMessageModal").modal('toggle');
                msg = ''
            }
        }

        function isDateValid(col_index) {
            let msg = '';
            let bulkTextVal = $('#bulkText').val();
            let bulkTextRow = bulkTextVal.split('\n');
            bulkTextRow = bulkTextRow.filter(item => item);
            $.each(bulkTextRow, function (key, value) {
                let bulkTextRowArray1 = value.split(',');
                let bulkTextRowArray = bulkTextRowArray1[col_index];
                if (/^([1-9]|([012][0-9])|(3[01]))\/([0]{0,1}[1-9]|1[012])\/([1-2][0-9][0-9][0-9]) [0-2][0-9]:[0-9][0-9]$/.test(bulkTextRowArray)) {
                    return true;
                } else {
                    msg = msg + '\n' + '{{__('Invalid Date type at row')}} ' + key + '<br>';
                }
            });
            if (msg != '') {
                $('#errorMessageDiv').html(msg);
                $('#customErrorMessageModal').text('{{__('Invalid Date')}}')
                $('#id').empty()
                $('#dynamic-content-type').find('select').empty()
                $('.assignedVal').append($('<option>', {
                    value: '',
                    text: '{{__('no column selected')}}'
                }));
                $('#general-name').find('select').empty()
                $('#general-name').html($('<option>', {
                    value: '',
                    text: '{{__('auto')}}'
                }));
                let bulkTextVal = $('#bulkText').val();
                let bulkTextRow = bulkTextVal.split('\n');
                let bulkTextRowArray = bulkTextRow[0].split(',');
                $.each(bulkTextRowArray, function (key, value) {
                    $('.assignedVal').append($('<option>', {
                        value: key,
                        text: value + (' (Column ' + key + ')'),
                    }));
                });
                $("#errorMessageModal").modal('toggle');
                msg = ''
            }
        }


        //      Ajax Call after change dynamic inputs
        function getQrCodeByField(ele) {
            let id = '#' + ele.id;
            let col_index = $(id).val();
            let data_type = $(id).data('type');
            if (data_type == 'url') {
                isUrlValid(col_index);
            } else if (data_type == 'email') {
                isEmailValid(col_index);
            } else if (data_type == 'date') {
                isDateValid(col_index)
            }
            let formName = $(ele).parents('form').attr('id');
            let fromId = '#' + formName;

            frontendValidation(formName);

            if ($(fromId).valid()) {
                firstImageAppend = true;
                $('#save-qr-code-btn').prop('disabled', false);
            }
        }

        //      Display Campaign input field
        $(".add-campaigns").click(function () {
            $(".form-new-campaign").slideToggle();
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
            let index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');
                $(".print-error-msg").css('display', 'none');
            }, 5000);
        }

        //      Get and active shape
        function getShapeName(selectedShapeName) {
            let shapeNameId = '#shapeName' + selectedShapeName;
            $('.body-shape').removeClass('active');
            $(shapeNameId).addClass('active');
            shapeName = selectedShapeName;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      Get and active frame
        function getFrameName(selectedFrameName) {
            let frameNameId = '#frameName' + selectedFrameName;
            $('.eye-frame').removeClass('active');
            $(frameNameId).addClass('active');
            frameName = selectedFrameName;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        //      Get and active eye
        function getEyeBallName(selectedEyeBallName) {
            let eyeShapeId = '#eyeShape' + selectedEyeBallName;
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
                mainLogoImage = '';
                temporaryLogoImage = '';
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
                $('.hide-show').show()
            }
            // Empty Star Show
            starIcon();
            // Check the dynamic Field

            // if (dynamicContentField && dynamicContentFieldAction) {
            makeQrCode();
            // }
        }

        function qrCodeFrameName(selectedQrCodeFrameId) {
            var shapeNameId = '#qrCodeFrameId' + selectedQrCodeFrameId;
            $('.qr-code-frame').removeClass('active');
            $(shapeNameId).addClass('active');
            qrCodeFrameId = selectedQrCodeFrameId;
            //Empty Star Show
            starIcon();
            makeQrCode()
        }

        // Display Eye show or hide
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
            if (ajaxCallBlock) {
                makeQrCode();
            }
            // Empty Star Show
            starIcon();
        }

        function starIcon() {
            // Classic Template Star
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

        // Pick up the color type etc varticle,radial...
        function colorSchema(ele) {
            colorStructure = ele.value;
            // Empty Star Show
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
            makeQrCode();
        }

        // remove image and logo id
        function removeImageInput(ele) {
            $('#upload-logo-image').val(null);
            $('.logo-image').removeClass('active');
            $('.image-upload-wrap').css('display', 'block');
            $('#allowd_image').removeClass('d-block');
            $('#allowd_image').addClass('d-none');

            temporaryLogoImage = '';

            removeUpload()
            logoId = 0;
            makeQrCode()
        }

        // Color values overlap
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
            // Empty Star Show
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

        function makeTransparentQrCode(ele) {
            $('#color-warning').hide();
            // transparentImageStatus = true;
            var getFormData = transparentData();
            // $('#upload-background-image').addClass('d-none')
            let contentType = '{{$parameters['content_type']}}';
            let type = '{{$parameters['type']}}';
            if (contentType == 'event' && type == 'static') {
                getFormData.set('startDateTime', '2021-01-01T20:34')
                getFormData.set('endDateTime', '2021-01-01T20:34')
            }
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
                    if (!modalLoading) {
                        $('#loading').show();
                        $('#generated-qr-code').hide();
                    } else {
                        $('#design-loading').show();
                        $('#classic-qr-code').hide();
                    }
                },
                complete: function () {
                    if (!modalLoading) {
                        $('#loading').hide();
                        $('#generated-qr-code').show();
                    } else {
                        $('#design-loading').hide();
                        $('#classic-qr-code').show();
                    }
                },
                success: function (response) {
                    if (response.status == 1) {
                        if (firstImageAppend) {
                            // $('#generated-qr-code').empty();
                            // $('#generated-qr-code').append(response.html);
                        }
                        firstImageAppend = false;
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

        // Generate Qr Code with all the data
        function makeQrCode() {
            // Loading on modal
            if ($('#design-qrcode').hasClass('show')) {
                modalLoading = true
            } else {
                modalLoading = false
            }

            var data = {
                "config": {
                    "body": shapeName,
                    "frame": frameName,
                    "qrCodeFrameId": qrCodeFrameId,
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
            if (transparentImageStatus) {
                if ($('#design-qrcode').hasClass('show')) {
                    modalLoading = true
                } else {
                    modalLoading = false
                }

                let formData = transparentData()
                formData.append('x', cropperImageData.x);
                formData.append('y', cropperImageData.y);
                formData.append('size', cropperImageData.size);
                if (tabType == 'static') {
                    formData.append('transparentImage', transparentImageId);
                } else {
                    formData.append('config', JSON.stringify(data));
                }
            } else {
                let myForm = document.getElementById('dynamic-content-type');
                formData = new FormData(myForm);
                formData.append('config', JSON.stringify(data));
                var fileToUpload = $('#upload-logo-image').prop('files')[0];
                if (mainLogoImage != '') {
                    fileToUpload = temporaryLogoImage
                }

                formData.append('logo_image', fileToUpload);
                formData.append('eyeStatus', eyeStatus);
            }
            // Loading on modal
            if ($('#design-qrcode').hasClass('show')) {
                modalLoading = true;
            } else {
                modalLoading = false
            }
            let contentType = '{{$parameters['content_type']}}';
            let type = '{{$parameters['type']}}';
            if (contentType == 'event' && type == 'static') {
                formData.set('startDateTime', '2021-01-01T20:34')
                formData.set('endDateTime', '2021-01-01T20:34')
            }

            formData.append('generate_short_link', generateShortLink);
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
                    }

                    if (response.status == 1) {
                        if (firstImageAppend) {
                            // $('#generated-qr-code').empty();
                            // $('#generated-qr-code').append(response.html);
                            nedLink = response.ned_link;
                            $('#ned-link').html(response.ned_link);
                            $('#ned-link-input').val(response.ned_link);
                        }
                        temporaryLogoImage = response.logo_image
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
                    "qrCodeFrameId": qrCodeFrameId,
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

            // var fileToUpload = $('#upload-transparent-image').prop('files')[0];
            formData.append('transparentImage', $('#upload-transparent-image').prop('files')[0]);

            if ($('#design-qrcode').hasClass('show')) {
                modalLoading = true
            } else {
                modalLoading = false
            }

            return formData;
        }

        // Append qr code modal to page
        function cloneQrCode() {
            $('#loading').css('display', 'none');
            $('#generated-qr-code').empty();
            $('#save-qr-code-btn').prop('disabled', false);
            $('#classic-qr-code').clone().appendTo('#generated-qr-code');
            $("div#generated-qr-code").find("div#classic-qr-code").contents().unwrap();
            $('#design-qrcode').modal('hide');
            $('#generated-qr-code img').addClass('qr-code-scan')


            if ($('#classic').hasClass('active')) {
                tabType = 'classic';
                transparentImageStatus = false
                mainLogoImage = temporaryLogoImage;
                transparentImageId = ''
            } else if ($('#static').hasClass('active')) {
                tabType = 'static'
                transparentImageStatus = true
                mainLogoImage = '';
            }

            main_image = saved_image_id

        }

        // Store QR Code in backend
        function saveQrCode() {
            formData.append('transparent_image', transparentImageId);
            formData.append('cropper_image_data', JSON.stringify(cropperImageData));
            formData.append('transparent_image_status', transparentImageStatus);

            // if(mainLogoImage == ''){
            //     mainLogoImage = $('#upload-logo-image').prop('files')[0];
            // }
            formData.append('logo_image', mainLogoImage);
            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.bulk-import.store') }}",
                data: formData,
                enctype: 'multipart/form-data',
                beforeSend: function () {
                    $('#save-qr-code-btn').prop('disabled', true);
                    $('#loading').show();
                    $('#generated-qr-code').hide();
                },
                complete: function () {
                    $('#save-qr-code-btn').prop('disabled', false);
                },
                success: function (response) {
                    if (response.status == 1) {
                        window.location.replace(response.url);
                    } else if (response.status == 0) {
                        window.location.replace(response.url);
                    }
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
            frontendValidation('save-bulk-qr-code-form');
            frontendValidation('dynamic-content-type');
            if ($('#save-bulk-qr-code-form').valid() && $('#dynamic-content-type').valid()) {
                let myForm = document.getElementById('dynamic-content-type');
                formData = new FormData(myForm);
                var data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
                        "qrCodeFrameId": qrCodeFrameId,
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

                if (transparentImageId != '') {
                    data = {
                        "config": {
                            "body": 'square',
                            "frame": 'square',
                            "qrCodeFrameId": qrCodeFrameId,
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
                }

                formData.append('config', JSON.stringify(data));
                let name = $('#general-name').val();
                let campaign_id = $('#add-campaign').val();
                let bulk_text = $('#bulkText').val();
                formData.append('name', name);
                formData.append('campaign_id', campaign_id);
                formData.append('bulk_text', bulk_text);
                formData.append('image_id', main_image);

                saveQrCode();
            }
        });

        function resetQrCode() {
            transparentImageStatus = false
            cropperImageStatus = true

            $('#upload-logo-image').val(null);
            $('#upload-transparent-image').val(null);
            $('.file-upload-image').attr('src', '#')
            // mainLogoImage = ''
            temporaryLogoImage = ''
            // set default value
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

            getShapeName(shapeName);
            getFrameName(frameName);
            getEyeBallName(eyeName);
            getLogoId(logoId)

            $('#templates-tab').removeClass('active')
            $('#templates').removeClass('active show')
            $('#static-tab').removeClass('active')
            $('#static').removeClass('active show')
            $('#classic').addClass('active show')
            $('#classic-tab').addClass('active')
            $('#image_cropper').attr("src", '#');

            $('.color-warning').hide();
            $('#color-warning').hide();

            transparentButtons()
        }

        $('#classic-fa-star-o').on('click', function () {
            $('#classic-fa-star-o').hide();
            $('#classic-fa-star').show();
            storeTemplate()
        });

        function storeTemplate() {
            var myForm = document.getElementById('dynamic-content-type');
            formData = new FormData(myForm);

            var data = {
                "config": {
                    "body": shapeName,
                    "frame": frameName,
                    "qrCodeFrameId": qrCodeFrameId,
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
            formData.append('transparent_image', transparentImageId);
            formData.append('cropper_image_data', JSON.stringify(cropperImageData));
            formData.append('transparent_image_status', transparentImageStatus);

            if (mainLogoImage == '') {
                mainLogoImage = $('#upload-logo-image').prop('files')[0];
            }
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
                success: function (response) {
                    if (response.status == 1 && response.template == 0) {
                        window.location.replace(response.url)
                    }
                    $('#templateImageAppend').empty()
                    $('#templateImageAppend').append(response.template_image);
                }
            });
        }

        function deleteTemplate(id) {
            if (confirm("Are you sure you want to delete this record?")) {
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

                        // remove active class on template
                        $('#templates-tab').removeClass('active')
                        $('#templates').removeClass('active')
                        $('#templates').removeClass('show')
                        // remove active class on save design
                        $('#save-design-tab').removeClass('active')
                        $('#save-designs').removeClass('active')
                        $('#save-designs').removeClass('active')

                        starIcon()
                    }
                }
            });
        }

        // Pick columns from bulk textarea
        $('#bulkText').change(function () {
            readBulk();
        });
        // End
        // Read bulk text
        function readBulk() {
            $('#dynamic-content-type').find('.assignedVal').empty()
            // $(this).closest('form').find("select").empty();
            $('.assignedVal').append($('<option>', {
                value: '',
                text: '{{__('no column assigned')}}',
            }));
            $('#general-name').empty();
            $('#general-name').append($('<option>', {
                value: '',
                text: '{{__('auto')}}',
            }));
            $('#save-qr-code-btn').attr('disabled', false);
            let bulkTextVal = $('#bulkText').val();
            let bulkTextRow = bulkTextVal.split('\n');
            let bulkTextRowArray = bulkTextRow[0].split(',');
            $.each(bulkTextRowArray, function (key, value) {
                $('.assignedVal').append($('<option>', {
                    value: key,
                    text: value + (' (Column ' + key + ')'),
                }));
            });
        }


        // End read bulk text
        // pick columns from csv files
        $('#csvFile').on('change', function () {
            $("#bulkText").empty();
            let route = '{{route('frontend.user.bulk-import.csv')}}';
            let myForm = document.getElementById('csvFileImportFform');
            var formData = new FormData(myForm)
            var fd = new FormData();
            var files = $('#csvFile')[0].files;
            fd.append('file', files[0]);
            var file_size = $('#csvFile')[0].files[0].size;
            validateSize(file_size, 'csvFile')
            var bool = validateSize(file_size, 'csvFile')
            var ignoreCsvHead = $('#csvHeadIgnore').val();
            var checkboxValue = $('#csvHeadIgnore').prop('checked');
            if (bool) {
                fd.append('delimiter', $('#delimiter').val());
                fd.append('ignoreHead', checkboxValue);
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status == 200) {
                            $.each(response.result, function (key, value) {
                                $("textarea#bulkText").append(response.result[key] + "\n");
                            });
                            readBulk();
                        } else {
                            msg = response.result;
                            $("#errorMessageModal").body(response.result);
                            $("#errorMessageModal").modal('toggle');
                            msg = ''
                        }
                    },
                });
            }
        })

        // Excel file import
        $('#excelFile').on('change', function () {
            $("#bulkText").empty();
            let route = '{{route('frontend.user.bulk-import.excel')}}';
            let myForm = document.getElementById('excelFileImportFform');
            var formData = new FormData(myForm)
            var fd = new FormData();
            var files = $('#excelFile')[0].files;
            var ignore = $('#ignoreExcelHead').val();
            var checkboxValue = $('#ignoreExcelHead').prop('checked');
            var file_size = $('#excelFile')[0].files[0].size;
            var bool = validateSize(file_size, 'excelFile')
            if (bool) {
                fd.append('file', files[0]);
                fd.append('ignoreHead', checkboxValue);
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status == 200) {
                            $.each(response.result, function (key, value) {
                                $("textarea#bulkText").append(response.result[key] + "\n");
                            });
                            readBulk();
                        } else {
                            msg = response.result;
                            // $("#errorMessageModal").body(response.result);
                            $("#errorMessageModal").modal('toggle');
                            msg = ' '
                        }
                    },
                });
            }
        })

        function uploadedTransparentImage(ele) {
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
                    width: Number(cropperImageSize),
                    height: Number(cropperImageSize),
                    x: Number(cropperImageX),
                    y: Number(cropperImageY),
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
            let contentType = '{{$parameters['content_type']}}';
            let type = '{{$parameters['type']}}';
            if (contentType == 'event' && type == 'static') {
                formData.set('startDateTime', '2021-01-01T20:34')
                formData.set('endDateTime', '2021-01-01T20:34')
            }
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
                    if (!modalLoading) {
                        $('#loading').show();
                        $('#generated-qr-code').hide();
                    } else {
                        $('#design-loading').show();
                        $('#classic-qr-code').hide();
                    }
                    $('#uploadedTransparentImage .cropper-container').hide()
                },
                success: function (response) {
                    if (response.status == 1) {
                        // if (firstImageAppend) {
                        //     $('#generated-qr-code').empty();
                        //     $('#generated-qr-code').append(response.html);
                        // }
                        firstImageAppend = false;
                        $('#classic-qr-code').empty();
                        $('#classic-qr-code').append(response.html);

                        $('#design-loading').hide();
                        $('#classic-qr-code').show();

                        saved_image_id = response.image_id;
                        $('#uploadedTransparentImage .cropper-container').show()
                        $('#transparent-loading').hide();

                        starIcon()
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        }

        // File size
        function validateSize(file_size, id) {
            if (file_size > 1000000) {
                $("#" + id).after(error_html);
                return false;
            } else {
                return true;
            }
        }

        function saveDesignQrCode(ele) {
            $('#color-warning').hide();
        }

        function templateQrCode(ele) {
            $('#color-warning').hide();
        }

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

