@extends('frontend.layouts.dashboard')

@section('title', __('QR Codes'))


@section('content')
    <div class="content-body">
        <div class="comon-title section-title pt-3 pb-3 text-center">
            <h2 class="welcome">{{__('QR Codes')}}</h2>
        </div>
        @include('frontend.messages')
        <form action="{{ url('/qr-codes') }}" method="get">
            <input type="hidden" name="limit" value="{{ $limit }}">

            <div class="row pt-4">
                <div class="col-sm-6 col-md-auto col-12 qr-create-button mb-2 qrcode-pg-btn ">
                    <a class="btn btn-orange" href="{{route('frontend.user.qr-codes.select.content.type')}}">
                        <i class="fa fa-plus"></i> {{__('Create QR Code')}}
                    </a>
                </div>
                <div class="col-sm-6 col-md-auto col-12 mb-2">
                    <select class="form-control ng-valid ng-dirty ng-touched" name="sort">
                        <option value="name-asc" {{ $sort == "name-asc" ? "selected" : "" }}>↑ {{__('Name')}}</option>
                        <option value="name-desc" {{ $sort == "name-desc" ? "selected" : "" }}>↓ {{__('Name')}}</option>
                        <option value="scans-asc" {{ $sort == "scans-asc" ? "selected" : "" }}>
                            ↑ {{__('Scans')}}</option>
                        <option value="scans-desc" {{ $sort == "scans-desc" ? "selected" : "" }}>
                            ↓ {{__('Scans')}}</option>
                        <option value="updated_at-asc" {{ $sort == "updated_at-asc" ? "selected" : "" }}>
                            ↑ {{__('Updated')}}
                        </option>
                        <option value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>
                            ↓ {{__('Updated')}}
                        </option>
                        <option value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>
                            ↑ {{__('Created')}}
                        </option>
                        <option value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>
                            ↓ {{__('Created')}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-auto col-12  mb-2">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid"
                               placeholder="{{__('Search by Name or Type')}}" type="text" name="text"
                               value="{{ $text }}">
                    </div>
                </div>
                <div class="col-sm-auto col-md-auto col-12 ml-auto mb-2">
                    <div class="input-group qr--button justify-content-end">
                        <button class="btn btn-orange mr-lg-2 mr-md-2 mr-sm-2 mr-0 mb-sm-0 mb-2" type="submit">
                            <i class="fa fa-check"></i> {{__('Apply')}}
                        </button>
                        <a class="btn btn-primary" href="{{route('frontend.user.qr-codes.index')}}">
                            <i class="fa fa-refresh"></i> {{__('Reset')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @if(!$qrCodes->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="list qr-codes-lists">
                        <div class="row">
                            @foreach($qrCodes as $qrCode)
                                <div class="col-lg-6 col-sm-12 col-12 mb-2">
                                    <div class="list-item">
                                        <a class="inner"
                                           href="{{route('frontend.user.qr-codes.edit',Hashids::encode($qrCode->id))}}">
                                            <div class="thumb list-col">
                                                <img
                                                    src="{{checkImage(asset('storage/users/'.$qrCode->user_id.'/qr-codes/' . $qrCode->image),'default.svg',$qrCode->image)}}"
                                                    class="ng-lazyloaded">
                                            </div>
                                            <div class="info list-col-uper">
                                                @if($qrCode->code_type == 1)
                                                    <div class="scans">
    												<span>
{{--    													<strong>{{$qrCode->scans ? $qrCode->scans: 0}}</strong>--}}
                                                        {{__('Dynamic')}}
                                                    </span>
                                                    </div>
                                                @else
                                                    <div class="scans">
    												<span>
                                                    {{__('Static')}}
                                                    </span>
                                                    </div>
                                                @endif
                                                <div class="box_name maxname title">
                                                    <i class="{{$qrCode->icon}}"></i>
                                                    {{$qrCode->name}}
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Type')}}:</span> {{ucwords($qrCode->type)}}
                                                </div>
                                                @if($qrCode->code_type == 1)
                                                    <div class="fact">
                                                        <span class="name">{{__('Short-URL')}}:</span>
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              title="{{$qrCode->ned_link ? $qrCode->ned_link : $qrCode->short_url}}">
                                                        {{$qrCode->ned_link ? $qrCode->ned_link : $qrCode->short_url}}
                                                        </span>
                                                    </div>

                                                @endif
                                                <div class="fact">
                                                    <span class="name">{{__('Created')}}:</span>
                                                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($qrCode->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}
                                                </div>
                                                @if($qrCode->code_type == 1)
                                                    <div class="fact">
                                                        <span class="name">{{__('Scans')}}:</span>
                                                        <span>
                                                        {{$qrCode->scans? $qrCode->scans: 0}}
                                                        </span>
                                                        {{--                                                    <div class="statistics scans--items"><span--}}
                                                        {{--                                                            class="ml-2 text-nowrap"><strong>{{$qrCode->scans? $qrCode->scans: 0}}</strong> {{__('Scans')}}</span>--}}
                                                        {{--                                                    </div>--}}
                                                    </div>
                                                @endif
                                                @if($qrCode->campaign)
                                                    <div class="campaign">
                                                        <i class="fa fa-folder-o"></i> {{$qrCode->campaign->name}}
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                        <div class="list-col options">
                                            <div class="d-flex justify-content-between qr--list--gap ">

                                                @if(checkFieldStatus(8))
                                                    <div class="col">
                                                        <button class="btn btn-sm btn-outline-primary" type="button"
                                                                data-toggle="modal"
                                                                data-target="#download-model-{{ $qrCode->id }}"><i
                                                                class="fa fa-download"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                                @if(checkFieldStatus(9) && $qrCode->code_type == 1)
                                                    <div class="col">
                                                        <a class="btn btn-sm btn-outline-secondary"
                                                           href="{{route('frontend.user.qr-codes.statistics',Hashids::encode($qrCode->id))}}">
                                                            <i class="fa fa-bar-chart"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <a class="btn btn-sm btn-icon" title="{{__('Duplicate QR Code')}}"
                                               href="{{route('frontend.user.qr-codes.clone',Hashids::encode($qrCode->id))}}">
                                                <i class="fa fa-files-o"></i>
                                            </a>
                                            <button class="btn btn-sm btn-icon" title="{{__('Archive QR Code')}}"
                                                    type="button"
                                                    data-toggle="modal" data-target="#trash-model-{{ $qrCode->id }}"><i
                                                    class="fa fa-trash-o"></i></button>
                                        </div>
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
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                    {{__('Cancel')}}
                                                </button>
                                                <a href="{{route('frontend.user.qr-codes.archive',Hashids::encode($qrCode->id))}}"
                                                   type="button" class="btn btn-primary">{{__('Archive')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Model Download -->
                                <div class="modal fade" id="download-model-{{ $qrCode->id }}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{__('Download QR Code')}}</h5>
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
                                                                          id="inputGroupPrepend">{{__('File')}}</span>
                                                                </div>
                                                                <select class="custom-select custom-select-lg mb-3"
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
                                                                    <input type="range" step="25" min="100" max="2000"
                                                                           value="50%"
                                                                           class="range-slider w-100 myRange"
                                                                           name="size">
                                                                    <div
                                                                        class="d-flex justify-content-between values-labels">
                                                                        <div class="low-quality">{{__('Small')}}</div>
                                                                        <div class="ranges-value"><span
                                                                                class="height">1050</span> x <span
                                                                                class="width">1050</span> px
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

                            @endforeach
                        </div>
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
                                <div class="col-sm-auto col-12 ml-sm-auto text-sm-right text-center">
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
                    <div class="alert alert-light persist-alert text-center mb-0">{{__('No QR Codes found.')}}.</div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>

        $(function () {
            $('#limit').on('change', function () {
                var search = location.search;

                if (search != '') {
                    var url = new URL(window.location.href);
                    url.searchParams.set("limit", $(this).val());
                    window.location.href = url.href;
                } else {
                    window.location.href = "{{ url('/qr-codes') }}" + '?limit=' + $(this).val();
                }
            });
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
