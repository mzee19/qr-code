@extends('frontend.layouts.dashboard')

@section('title', $tabTitle.' '.__('Campaigns'))
@section('content')
    <!-- <style>
    canvas{
    width:947px !important;
    height:600px !important;
}
</style> -->
    <div class="content-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="tab-section">
                    <div class="d-flex justify-content-between" style="border-bottom: 1px solid #ddd;">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @isset($statistics) @if($statistics==1) '' @else active  @endif @endisset "
                                   id="edit-tab" data-toggle="tab" href="#edit" role="tab"
                                   aria-controls="edit" aria-selected="true">{{__('Edit')}}</a>
                            </li>
                            @if(checkFieldStatus(9))
                                <li class="nav-item">
                                    <a class="nav-link @isset($statistics) @if($statistics==1) active @endif @endisset"
                                       id="static-tab" data-toggle="tab" href="#static" role="tab"
                                       aria-controls="static" aria-selected="false">{{__('Statistics')}}</a>
                                </li>
                            @endif
                        </ul>
                        <div class="buttons right-side float-right">
                            <archive-item class="ng-star-inserted">
                                <button title="{{__('Archive QR Code')}}" type="button"
                                        class="btn btn-icon btn-danger btn-sm"
                                        data-toggle="modal" data-target="#all-trash-com"><i
                                        class="fa fa-trash-o"></i> {{__('Delete')}}
                                </button>
                            </archive-item>
                        {{--                            @if(checkFieldStatus(8))--}}
                        {{--                                <button class="btn btn-outline-primary ng-star-inserted" type="button"><i--}}
                        {{--                                        class="fa fa-download"></i> {{__('Download')}}--}}
                        {{--                                </button>--}}
                        {{--                        @endif--}}
                        <!-- <button class="btn btn-success ng-star-inserted" type="submit">Save </button> -->
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="edit--compaigns tabs-content campaigns-view-tabs mt-0 compaign-box" id="myTabContent">
                        <div
                            class="tab-pane fade @isset($statistics) @if($statistics==1) ' ' @else show active  @endif @endisset"
                            id="edit" role="tabpanel" aria-labelledby="edit-tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="section-title">
                                        {{--                                        <h3 class="sub-title">{{__('General')}}</h3>--}}
                                    </div>
                                </div>
                                <div class="col-12">
                                    @include('frontend.messages')
                                    <form id="campaign-form" action="{{route('frontend.user.campaigns.store')}}"
                                          method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="action" value="{{$action}}"/>
                                        <input name="id" type="hidden" value="{{ $campaign->id }}"/>
                                        <div class="setting-form forms-container cardbox cardbox-inner">
                                            <div class="form-group">
                                                <label>{{__('Name')}}<span class="text-danger"> *</span></label>
                                                <input class="form-control" type="text" name="name"
                                                       value="{{ ($action == 'Add') ? old('name') : $campaign->name}}"
                                                       required maxlength="20">
                                            </div>

                                            <div class="text-right">
                                                <a href="{{route('frontend.user.campaigns.index')}}">
                                                    <button type="button" class="btn btn-danger btn-fullrounded">
                                                        <span>{{__('Cancel')}}</span></button>
                                                </a>
                                                <button type="submit" class="btn btn-primary  btn-fullrounded">
                                                    <span>{{__('Save')}}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <form action="{{ url('/campaigns/'.Hashids::encode($campaign->id).'/edit') }}" method="get">
                                <input type="hidden" name="limit" value="{{ $limit }}">
                                <div class="row pt-4">
                                    <div class="col-sm-6 col-md-auto col-12 qr-create-button mb-2">
                                        <a class="btn btn-primary w-100"
                                           href="{{route('frontend.user.qr-codes.select.content.type','campaign_id='.Hashids::encode($campaign->id))}}">
                                            <i class="fa fa-plus"></i> {{__('Create QR Code')}}
                                        </a>
                                    </div>
                                    <div class="col-sm-6 col-md-auto col-12 mb-2">
                                        <select class="form-control ng-valid ng-dirty ng-touched" name="sort">
                                            <option value="name-asc" {{ $sort == "name-asc" ? "selected" : "" }}>↑
                                                {{__('Name')}}
                                            </option>
                                            <option value="name-desc" {{ $sort == "name-desc" ? "selected" : "" }}>↓
                                                {{__('Name')}}
                                            </option>
                                            <option value="scans-asc" {{ $sort == "scans-asc" ? "selected" : "" }}>↑
                                                {{__('Scans')}}
                                            </option>
                                            <option value="scans-desc" {{ $sort == "scans-desc" ? "selected" : "" }}>↓
                                                {{__('Scans')}}
                                            </option>
                                            <option
                                                value="updated_at-asc" {{ $sort == "updated_at-asc" ? "selected" : "" }}>
                                                ↑ {{__('Updated')}}
                                            </option>
                                            <option
                                                value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>
                                                ↓ {{__('Updated')}}
                                            </option>
                                            <option
                                                value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>
                                                ↑ {{__('Created')}}
                                            </option>
                                            <option
                                                value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>
                                                ↓ {{__('Created')}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-auto col-12 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input class="form-control ng-untouched ng-pristine ng-valid"
                                                   placeholder="{{__('Search by Name or Type')}}" type="text"
                                                   name="text"
                                                   value="{{ $text }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-auto col-md-auto col-12 ml-auto mb-2 button-size-compaign">
                                        <div class="input-group">
                                            <button class="btn btn-orange" type="submit">
                                                <i class="fa fa-check"></i> {{__('Apply')}}
                                            </button>
                                            &nbsp;&nbsp;
                                            <a class="btn btn-primary"
                                               href="{{ url('/campaigns/'.Hashids::encode($campaign->id).'/edit') }}">
                                                <i class="fa fa-refresh"></i> {{__('Reset')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @if(!$qrCodes->isEmpty())
                                @php
                                    $packageStatisticStatusValue = getSubscriptionFeatureCount(9);
                                    switch ($packageStatisticStatusValue){
                                         case 'basic':
                                             $checkStatisticsStatus = [0,2];
                                             break;
                                         case 'advanced':
                                             $checkStatisticsStatus = [0,1,2];
                                             break;
                                         default:
                                             $checkStatisticsStatus = [0];
                                    }
                                    $scanCount = $scansList->whereIn('statistics_status',$checkStatisticsStatus)->count()
                                @endphp
                                <div class="row">
                                    <div class="col-12">
                                        <div class="list">
                                            @foreach($qrCodes as $qrCode)
                                                <div class="list-item">
                                                    <a class="inner"
                                                       href="{{route('frontend.user.qr-codes.edit',Hashids::encode($qrCode->id))}}">
                                                        <div class="thumb list-col">
                                                            <img
                                                                src="{{checkImage(asset('storage/users/'.$qrCode->user_id.'/qr-codes/' . $qrCode->image),'default.svg',$qrCode->image)}}"
                                                                class="ng-lazyloaded">
                                                        </div>
                                                        <div class="info list-col card-details ">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="title">
                                                                    <i class="{{$qrCode->icon}}"></i>
                                                                    {{$qrCode->name}}
                                                                </div>
                                                                @if($qrCode->code_type == 1)
                                                                    <div class="scans">
                                                            <span>
                                                                <strong>{{$qrCode->scans? $qrCode->scans: 0}}</strong>
                                                                {{__('Scans')}}
                                                            </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="fact">
                                                                <span
                                                                    class="name">{{__('Type')}}:</span> {{ucwords($qrCode->type)}}
                                                            </div>

                                                            @if($qrCode->code_type == 1)
                                                                <div class="fact">
                                                                    <span class="name">{{__('Short-URL')}}:</span>
                                                                    <span class="text-handle" data-toggle="tooltip"
                                                                          data-placement="top"
                                                                          title="{{$qrCode->ned_link ? $qrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$qrCode->unique_id}}">
                                                                    {{$qrCode->ned_link ? $qrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$qrCode->unique_id}}
                                                                    </span>
                                                                <!-- {{$qrCode->ned_link ? $qrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$qrCode->unique_id}} -->
                                                                </div>
                                                            @endif

                                                            <div class="fact">
                                                                <span class="name">{{__('Created')}}:</span>
                                                                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($qrCode->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}
                                                            </div>

                                                            @if($qrCode->campaign)
                                                                <div class="campaign">
                                                                    <i class="fa fa-folder-o"></i> {{$qrCode->campaign->name}}
                                                                </div>
                                                            @endif
                                                        </div>

                                                    </a>
                                                    <div class="list-col options ">
                                                        @if(checkFieldStatus(8))

                                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                                    data-toggle="modal"
                                                                    data-target="#download-model-{{ $qrCode->id }}"><i
                                                                    class="fa fa-download"></i>
                                                                {{__('Download')}}
                                                            </button>
                                                        @endif
                                                        <br>
                                                        @if($qrCode->code_type == 1 && checkFieldStatus(9))
                                                            <a class="btn btn-sm btn-outline-secondary"
                                                               href="{{route('frontend.user.qr-codes.statistics',Hashids::encode($qrCode->id))}}">
                                                                <i class="fa fa-bar-chart"></i> {{__('Statistics')}}
                                                            </a>
                                                        @endif
                                                        <br>
                                                        <a class="btn btn-sm btn-icon"
                                                           title="{{__('Duplicate QR Code')}}"
                                                           href="{{route('frontend.user.qr-codes.clone',Hashids::encode($qrCode->id))}}">
                                                            <i class="fa fa-files-o"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-icon"
                                                                title="{{__('Archive QR Code')}}" type="button"
                                                                data-toggle="modal"
                                                                data-target="#trash-model-{{ $qrCode->id }}"><i
                                                                class="fa fa-trash-o"></i></button>
                                                    </div>
                                                </div>
                                                <!-- Model Trash -->
                                                <div class="modal fade" id="trash-model-{{ $qrCode->id }}">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{__('Archive QR Code')}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{__('Do you really want to archive this QR code')}}?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger"
                                                                        data-dismiss="modal"> {{__('Cancel')}}
                                                                </button>
                                                                <a href="{{route('frontend.user.qr-codes.archive',Hashids::encode($qrCode->id))}}"
                                                                   type="button"
                                                                   class="btn btn-primary"> {{__('Archive')}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Model Download -->
                                                <div class="modal fade" id="download-model-{{ $qrCode->id }}">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"> {{__('Download QR Code')}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form
                                                                action="{{ route('frontend.user.qr-codes.download',Hashids::encode($qrCode->id)) }}">
                                                                @csrf
                                                                @method('get')
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend mb-3">
                                                                    <span class="input-group-text"
                                                                          id="inputGroupPrepend"> {{__('File')}}</span>
                                                                                </div>
                                                                                <select
                                                                                    class="custom-select custom-select-lg mb-3"
                                                                                    name="fileType">
                                                                                    <option value="png">PNG</option>
                                                                                    <option value="svg">SVG</option>
                                                                                    <option value="eps">EPS</option>
                                                                                    <option value="pdf">PDF</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-12">
                                                                                <div class="range-slidecontainer">
                                                                                    <input type="range" step="25"
                                                                                           min="100"
                                                                                           max="2000" value="50%"
                                                                                           class="range-slider w-100 myRange"
                                                                                           name="size">
                                                                                    <div
                                                                                        class="d-flex justify-content-between values-labels">
                                                                                        <div
                                                                                            class="low-quality">{{__('Small')}}
                                                                                        </div>
                                                                                        <div class="ranges-value"><span
                                                                                                class="height">1050</span>
                                                                                            x <span
                                                                                                class="width">1050</span>
                                                                                            px
                                                                                        </div>
                                                                                        <div
                                                                                            class="high-quality">{{__('Big')}}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-primary">{{__('Download Image')}}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="list-footer pt-3">
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <select class="form-control" id="limit">
                                                            @foreach($limits as $val)
                                                                <option
                                                                    value="{{ $val }}" {{ $limit == $val ? "selected" : "" }}>{{ $val }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="col-sm-auto col-12 ml-sm-auto text-sm-right text-center">
                                                        <strong>{{ $qrCodes->firstItem() }}
                                                            - {{ $qrCodes->lastItem() }}</strong> {{__('of')}}
                                                        <strong>{{ $qrCodes->total() }}</strong>
                                                        @include('frontend.dashboard.partials.paginator',['paginators'=> $qrCodes])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <br>
                                <div class="row">
                                    <div class="col-12">
                                        <div
                                            class="alert alert-light persist-alert text-center mb-0">{{__('No QR Codes in current Campaign')}}
                                            .
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div
                            class="tab-pane fade @isset($statistics) @if($statistics==1) show active  @else ''  @endif @endisset"
                            id="static" role="tabpanel" aria-labelledby="static-tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row pt-4">
                                        <div class="col-md-6 col-sm-7 mb-2 ">
                                            @if(getSubscriptionFeatureCount(9))
                                                <div id="reportrange"
                                                     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-3 col-sm-auto mt-sm-0 col-6 mb-2">
                                            @if(getSubscriptionFeatureCount(9))

                                                <form
                                                    action="{{route('frontend.user.campaigns.edit',Hashids::encode($campaign->id))}}"
                                                    method="get">
                                                    <input type="hidden" id="from" name="from" value="{{ $from }}">
                                                    <input type="hidden" id="to" name="to" value="{{ $to }}">
                                                    <button class="btn btn-orange" type="submit">
                                                        <i class="fa fa-check"></i> {{__('Apply')}}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <!-- <div class="col-auto "></div> -->
                                        <div class="col-md-3 col-sm-auto mt-md-0 col-6 mt-sm-0 mb-2 text-right">
                                            @if(checkFieldStatus(10))
                                                <form id="export_from"
                                                      action="{{route('frontend.user.campaigns.edit',Hashids::encode($campaign->id))}}"
                                                      method="get">
                                                    <input type="hidden" id="from" name="from" value="{{ $from }}">
                                                    <input type="hidden" id="to" name="to" value="{{ $to }}">
                                                    <input type="hidden" id="export" name="export" value="1">
                                                    <input type="hidden" id="campaign" name="campaing" value="1">
                                                    <a class="btn btn-outline-primary"
                                                       onclick="document.getElementById('export_from').submit();"
                                                       href="javascript:{}">
                                                        <i class="fa fa-file-excel-o"></i> {{__('Export')}}
                                                    </a>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row pt-4 parentBox2">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="cardbox cardbox-tab active">
                                                <div class="cardbox-inner">
                                                    <div class="title">{{__('Total Scans')}}</div>
                                                    <h3>{{ $scansList->count() }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="cardbox cardbox-tab ">
                                                <div class="cardbox-inner">
                                                    <div class="title">{{__('Unique Users')}}</div>
                                                    <h3>{{ $scansList->groupBy('ip')->count() }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="cardbox cardbox-tab ">
                                                <div class="cardbox-inner">
                                                    <div class="title">{{__('Locations')}}/{{__('Languages')}}</div>
                                                    <h3>
                                                        @if(!empty($firstScan))
                                                            {{ number_format(($countryCount/$scansList->count())*100, 2) }}
                                                            % {{ $firstScan->country .'/'.$firstScan->language}}
                                                        @else
                                                            -
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="cardbox cardbox-tab ">
                                                <div class="cardbox-inner">
                                                    <div class="title">{{__('Devices')}}</div>
                                                    <h3>
                                                        @if(!empty($firstScan))
                                                            {{ number_format(($deviceCount/$scansList->count())*100, 2) }}
                                                            % {{ $firstScan->device }}
                                                        @else
                                                            -
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$scansList->isEmpty())
                                        @if(getSubscriptionFeatureCount(9))

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="cardbox">
                                                        <div class="cardbox-head cardbox-inner">
                                                            <h5 class="title">{{__('Scan Statistic')}}</h5>
                                                        </div>
                                                        <div class="cardbox-inner charts">
                                                            <canvas id="scansChart"></canvas>
                                                            <!-- <div id="scansChartContainer" style="height: 500px; width: 100%;"></div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="cardbox">
                                                        <div class="cardbox-head cardbox-inner">
                                                            <h5 class="title">{{__('Unique Users Statistic')}}</h5>
                                                        </div>
                                                        <div class="cardbox-inner charts">
                                                            <canvas id="uniqueUsersChart"></canvas>
                                                            <!-- <div id="uniqueUsersChartContainer" style="height: 500px; width: 100%;"></div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="cardbox">
                                                        <div class="cardbox-head cardbox-inner">
                                                            <h5 class="title">{{__('Locations')}}
                                                                /{{__('Languages')}}</h5>
                                                        </div>
                                                        <div class="cardbox-inner">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active" id="countries-tab"
                                                                       data-toggle="tab" href="#countries" role="tab"
                                                                       aria-controls="countries"
                                                                       aria-selected="true">{{__('Countries')}}</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="cities-tab"
                                                                       data-toggle="tab"
                                                                       href="#cities" role="tab" aria-controls="cities"
                                                                       aria-selected="false">{{__('Cities')}}</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="languages-tab"
                                                                       data-toggle="tab"
                                                                       href="#languages" role="tab"
                                                                       aria-controls="languages"
                                                                       aria-selected="false">{{__('Languages')}}</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="countries"
                                                                     role="tabpanel" aria-labelledby="countries-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('Country')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($countries as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->country }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane fade" id="cities" role="tabpanel"
                                                                     aria-labelledby="cities-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('City')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($cities as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->city }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane fade" id="languages"
                                                                     role="tabpanel"
                                                                     aria-labelledby="languages-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('Language')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($languages as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->language }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
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

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="cardbox">
                                                        <div class="cardbox-head cardbox-inner">
                                                            <h5 class="title">Devices</h5>
                                                        </div>
                                                        <div class="cardbox-inner">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active" id="devices-tab"
                                                                       data-toggle="tab" href="#devices" role="tab"
                                                                       aria-controls="devices"
                                                                       aria-selected="true">{{__('Devices')}}</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="platforms-tab"
                                                                       data-toggle="tab"
                                                                       href="#platforms" role="tab"
                                                                       aria-controls="platforms"
                                                                       aria-selected="false">{{__('Platforms')}}</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="browsers-tab"
                                                                       data-toggle="tab"
                                                                       href="#browsers" role="tab"
                                                                       aria-controls="browsers"
                                                                       aria-selected="false">{{__('Browsers')}}</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="devices"
                                                                     role="tabpanel" aria-labelledby="devices-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('Device')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($devices as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->device }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane fade" id="platforms"
                                                                     role="tabpanel"
                                                                     aria-labelledby="platforms-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('Platform')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($platforms as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->platform }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="tab-pane fade" id="browsers" role="tabpanel"
                                                                     aria-labelledby="browsers-tab">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>{{__('Browser')}}</th>
                                                                            <th>{{__('Percent')}}</th>
                                                                            <th>{{__('Scans')}}</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($browsers as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key+1 }}</td>
                                                                                <td>{{ $value->browser }}</td>
                                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                                    %
                                                                                </td>
                                                                                <td>{{ $value->scans }}</td>
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

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="section-title">
                                                        <h3 class="sub-title">{{__('Latest Scans')}}</h3>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="cardbox">
                                                        <div class="table-responsive">
                                                            <table class=" qr-code-table table table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col">{{__('QR Code')}}</th>
                                                                    <th scope="col">{{__('Time')}}</th>
                                                                    <th scope="col">{{__('IP')}}</th>
                                                                    <th scope="col">{{__('City')}}</th>
                                                                    <th scope="col">{{__('Country')}}</th>
                                                                    <th scope="col">{{__('Browser')}}</th>
                                                                    <th scope="col">{{__('Platform')}}</th>
                                                                    <th scope="col">{{__('Device')}}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($scansList->whereIn('statistics_status',$checkStatisticsStatus) as $row)
                                                                    <tr>
                                                                        <td>
                                                                            <a href="{{route('frontend.user.qr-codes.edit',Hashids::encode($row->qr_code_id))}}">{{ $row->qrCode->name }}</a>
                                                                        </td>
                                                                        <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}</td>
                                                                        <td>{{ $row->ip }}</td>
                                                                        <td>{{ $row->city }}</td>
                                                                        <td>{{ $row->country }}</td>
                                                                        <td>{{ $row->browser }}</td>
                                                                        <td>{{ $row->platform }}</td>
                                                                        <td>{{ $row->device }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="cardbox">
                                            <div class="cardbox-inner">
                                                <div
                                                    class="alert alert-light persist-alert text-center mb-0"> {{__('No tracking statistics for current time range')}}
                                                    .
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{--        {{dd($campaign->id)}}--}}
        <!-- All trash compaigns and qrcode -->
            <div class="modal fade" id="all-trash-com">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('Archive QR Codes')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{__('Do you really want to delete this campaign and archive all related QR codes')}}?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Cancel')}}</button>
                            <a href="{{route('frontend.user.campaign.destroy',[Hashids::encode($campaign->id)])}}"
                               type="button" class="btn btn-primary">{{__('Archive')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('#campaign-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    name: {
                        required: true,
                    },
                },

                messages: {
                    name: {
                        required: '{{__('This field is required')}}',
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
                        error.insertAfter(element);
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
            });

            $('#limit').on('change', function () {
                var search = location.search;

                if (search != '') {
                    var url = new URL(window.location.href);
                    url.searchParams.set("limit", $(this).val());
                    window.location.href = url.href;
                } else {
                    window.location.href = "{{ url('/campaigns/'.Hashids::encode($campaign->id).'/edit') }}" + '?limit=' + $(this).val();
                }
            });
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            var scanLabels = [
                <?php
                foreach ($scanLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $scanValues }};

            var ctx = document.getElementById('scansChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: scanLabels,
                    datasets: [{
                        label: '{{__('Total Scans')}}',
                        data: data,
                        backgroundColor: "#0645a4"
                    }]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'minute',
                                displayFormats: {
                                    'minute': 'YYYY-MM-DD',
                                    'hour': 'YYYY-MM-DD'
                                }
                            },
                            ticks: {
                                source: 'data'
                            }
                        }]
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            var uniqueLabels = [
                <?php
                foreach ($uniqueLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $uniqueValues }};

            var ctx = document.getElementById('uniqueUsersChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: uniqueLabels,
                    datasets: [{
                        label: '{{__('Unique Users')}}',
                        data: data,
                        backgroundColor: "#0645a4"
                    }]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'minute',
                                displayFormats: {
                                    'minute': 'YYYY-MM-DD',
                                    'hour': 'YYYY-MM-DD'
                                }
                            },
                            ticks: {
                                source: 'data'
                            }
                        }]
                    }
                }
            });
        });
    </script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script>
        CanvasJS.addColorSet("blueShades", ["#0645A4"]);

        var scansChart = new CanvasJS.Chart("scansChartContainer", {
            responsive: true,
            colorSet: "blueShades",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "{{__('Total Scans')}}"
            },
            axisY: {
                title: "{{__('Scans')}}"
            },

            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: "{{__('Date')}}",
                dataPoints: [
                    <?php
                    foreach ($scansDataPoints as $date => $scans) {
                        echo '{ x:new Date("' . $date . '"), y: ' . $scans . '},';
                    }
                    ?>
                ],
                xValueFormatString: "MMM DD, YYYY",
            }]
        });
        scansChart.render();

        var uniqueUsersChart = new CanvasJS.Chart("uniqueUsersChartContainer", {
            colorSet: "blueShades",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: '{{__('Unique Users')}}'
            },
            axisY: {
                title: '{{__('Users')}}'
            },
            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: '{{__('Date')}}',
                dataPoints: [
                    <?php
                    foreach ($uniqueUsersDataPoints as $date => $users) {
                        echo '{ x:new Date("' . $date . '"), y: ' . $users . '},';
                    }
                    ?>
                ],
                xValueFormatString: "MMM DD, YYYY",
            }]
        });
        uniqueUsersChart.render();
    </script>


    <script type="text/javascript">
        $(document).ready(function () {
            $("#reportrange").click(function () {
                $(".daterangepicker .ranges li:last-child").text('{{__('Custom Range')}}');
            });
        });

        $(function () {

            var start = moment('{{$from}}');
            var end = moment('{{$to}}');

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#from').val(start.format('YYYY-MM-D'));
                $('#to').val(end.format('YYYY-MM-D'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    '{{__('Today')}}': [moment(), moment()],
                    '{{__('Yesterday')}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '{{__('Last 7 Days')}}': [moment().subtract(6, 'days'), moment()],
                    '{{__('Last 30 Days')}}': [moment().subtract(30, 'days'), moment()],
                    '{{__('This Month')}}': [moment().startOf('month'), moment().endOf('month')],
                    '{{__('Last Month')}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
