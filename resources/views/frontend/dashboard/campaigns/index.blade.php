@extends('frontend.layouts.dashboard')

@section('title', __('Campaigns'))

@section('content')
    <div class="content-body">
        <div class="comon-title section-title pt-3 pb-3 text-center">
            <h2 class="welcome">{{__('Campaigns ')}}</h2>
        </div>
        @include('frontend.messages')

        <form action="{{ url('/campaigns') }}" method="get">
            <input type="hidden" name="limit" value="{{ $limit }}">

            <div class="row pt-4">
                <div class="col-sm-6 col-md-auto col-12 qr-create-button mb-2">
                    <a class="btn btn-orange" href="{{route('frontend.user.campaigns.create')}}">
                        <i class="fa fa-plus"></i> {{__('Create Campaign')}}
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
                            ↑ {{__('Updated')}}</option>
                        <option value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>
                            ↓ {{__('Updated')}}</option>
                        <option value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>
                            ↑ {{__('Created')}}</option>
                        <option value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>
                            ↓ {{__('Created')}}</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-auto col-12  mb-2">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid"
                               placeholder="{{ __('Search by Name') }}" type="text" name="text" value="{{ $text }}">
                    </div>
                </div>
                <div class="col-sm-auto col-md-auto col-12 ml-auto mb-2">
                    <div class="input-group qr--button">
                        <button class="btn btn-orange mr-sm-2 mb-sm-0 mb-2" type="submit">
                            <i class="fa fa-check"></i> {{__('Apply')}}
                        </button>
                        <a class="btn btn-primary" href="{{route('frontend.user.campaigns.index')}}">
                            <i class="fa fa-refresh"></i> {{__('Reset')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @if(!$campaigns->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="campaign-list list">
                        <div class="row">
                            @foreach($campaigns as $campaign)
                                <div class="col-md-6 col-12">
                                    <div class="list-item campaign-item NameBox itemsettings d-flex">
                                        <a class="inner"
                                           href="{{route('frontend.user.campaigns.edit',Hashids::encode($campaign->id))}}">
                                            <div class="icon list-col ml-2">
                                                <i class="fa fa-folder-o"></i>
                                            </div>
                                            <div class="info list-col">
                                                <div class="title"> {{$campaign->name}} </div>
                                            </div>
                                            <div class="qrcodes list-col">
                                                <span
                                                    class=""> {{ $campaign->qrCodes->count() }}  {{__('Codes')}} </span>
                                            </div>
                                            <div class="scans list-col">
                                                <span
                                                    class=""><strong>{{ $campaign->qrCodes->where('archive',0)->sum('scans') }}</strong> {{__('Scans')}}  </span>
                                            </div>
                                        </a>

                                        <!-- <div class="scans list-col d-none d-md-block"> -->
                                        <a type="button" class="btn bg-white" href="javascript:void(0)"
                                           onclick="deleteConfirmation('{{Hashids::encode($campaign->id)}}')"
                                           title="{{__('Delete')}}">
                                            <div class="scans list-col">
                                                <span class="fa fa-trash"></span>
                                            </div>
                                        </a>

                                    </div>
                                </div>

                                <!-- Modal Delete -->
                                <div class="modal fade" id="delete-model">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{__('Delete Campaign')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{__('Do you really want to delete this Campaign?')}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{__('Cancel')}}</button>
                                                <form id="delete-campaign-form" method="POST"
                                                      action="{{route('frontend.user.campaigns.destroy',Hashids::encode($campaign->id))}}"
                                                      accept-charset="UTF-8" style="display:inline">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    @csrf
                                                    <button class="btn btn-danger"
                                                            title="{{__('Delete')}}">{{__('Delete')}}</button>
                                                </form>
                                            </div>
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
                                <div class="col text-right">
                                    <strong>{{ $campaigns->firstItem() }}
                                        - {{ $campaigns->lastItem() }}</strong> {{__('of')}}
                                    <strong>{{ $campaigns->total() }}</strong>
                                    @include('frontend.dashboard.partials.paginator',['paginators'=> $campaigns])
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
                    <div class="alert alert-light persist-alert text-center mb-0">{{__('No Campaigns found')}}.</div>
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
                    window.location.href = "{{ url('/campaigns') }}" + '?limit=' + $(this).val();
                }
            });
        });

        function deleteConfirmation(id) {
            $('#delete-model').modal('show');
            let url = '{{route('frontend.user.campaigns.destroy',0)}}';
            $('#delete-campaign-form').attr('action', url.replace('0', id));
        }
    </script>
@endsection

