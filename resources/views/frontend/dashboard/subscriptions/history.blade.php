@extends('frontend.layouts.dashboard')

@section('title', __('Subscriptions'))

@section('content')
    <div class="content-body">
        @include('frontend.messages')

        <form action="{{ url('/subscriptions') }}" method="get">
            <input type="hidden" name="limit" value="{{ $limit }}">

            <div class="row pt-4">
                <div class="col-sm-6 col-lg-auto col-12 mb-2">
                    <select class="form-control ng-valid ng-dirty ng-touched" name="sort">
                        <option value="updated_at-asc" {{ $sort == "updated_at-asc" ? "selected" : "" }}>↑ {{__('Updated')}}
                        </option>
                        <option value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>↓ {{__('Updated')}}
                        </option>
                        <option value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>↑ {{__('Created')}}
                        </option>
                        <option value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>↓ {{__('Created')}}
                        </option>
                        <option value="end_date-asc" {{ $sort == "end_date-asc" ? "selected" : "" }}>↑ {{__('End Date')}}
                        </option>
                        <option value="end_date-desc" {{ $sort == "end_date-desc" ? "selected" : "" }}>↓ {{__('End Date')}}
                        </option>
                        <option value="start_date-asc" {{ $sort == "start_date-asc" ? "selected" : "" }}>↑ {{__('Start Date')}}
                        </option>
                        <option value="start_date-desc" {{ $sort == "start_date-desc" ? "selected" : "" }}>↓ {{__('Start Date')}}
                        </option>
                        <option value="price-asc" {{ $sort == "price-asc" ? "selected" : "" }}>↑ {{__('Price')}}
                        </option>
                        <option value="price-desc" {{ $sort == "price-desc" ? "selected" : "" }}>↓ {{__('Price')}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-auto col-12 ">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid input-placeholder-font-size"
                               placeholder="{{__('Search by Package Name')}}" type="text" name="text" value="{{ $text }}">
                    </div>
                </div>
                <div class="col-sm-auto col-md-auto col-12 ml-sm-auto mb-2 button-size">
                    <div class="input-group qr--button">
                        <button class="btn btn-orange mt-2 mr-sm-2" type="submit">
                            <i class="fa fa-check"></i> {{__('Apply')}}
                        </button>
                        <a class="btn btn-primary mt-2" href="{{route('frontend.user.subscriptions')}}">
                            <i class="fa fa-refresh"></i> {{__('Reset')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
        <br><br>
        @if(!$subscriptions->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="campaign-list list">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">#</th>
                                        <th class="text-nowrap">{{__('Package')}}</th>
                                        <th class="text-nowrap">{{__('Type')}}</th>
                                        <th class="text-nowrap">{{__('Price')}}</th>
                                        <th class="text-nowrap">{{__('Start Date')}}</th>
                                        <th class="text-nowrap">{{__('End Date')}}</th>
                                        <th class="text-nowrap">{{__('Status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptions as $key => $value)
                                        <tr>
                                            <td class="text-nowrap">{{ $key+1 }}</td>
                                            <td class="text-nowrap">{{ $value->package->title }}</td>
                                            <td class="text-nowrap">
                                                @if($value->package_id == 1)
                                                    <span class="badge badge-secondary">{{__('Trial')}}</span>
                                                @elseif($value->package_id == 2)
                                                    <span class="badge badge-info">{{__('Free')}}</span>
                                                @else
                                                    <span class="badge badge-success">{{__('Paid')}}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">{{ !empty($value->price) ? config('constants.currency')['symbol'].$value->price : 0 }}</td>
                                            <td class="text-nowrap">{{ \Carbon\Carbon::createFromTimeStamp($value->start_date, "UTC")->tz(auth()->user()->timezone)->format('d M, Y') }}</td>
                                            <td class="text-nowrap">{{ empty($value->end_date) ? 'Lifetime' : \Carbon\Carbon::createFromTimeStamp($value->end_date, "UTC")->format('d M, Y')}}</td>
                                            <td class="text-nowrap">
                                                @if(auth()->user()->package_subscription_id == $value->id)
                                                    <span class="badge badge-success">{{__('Active')}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{__('In Active')}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="list-footer pt-3">
                            <div class="row">
                                <div class="col-auto">
                                    <select class="form-control" id="limit">
                                        @foreach($limits as $val)
                                            <option value="{{ $val }}" {{ $limit == $val ? "selected" : "" }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-auto col-12 ml-sm-auto text-sm-right text-center">
                                    <strong>{{ $subscriptions->firstItem() }} - {{ $subscriptions->lastItem() }}</strong> {{__('of')}}
                                    <strong>{{ $subscriptions->total() }}</strong>
                                    @include('frontend.dashboard.partials.paginator',['paginators'=> $subscriptions])
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
                    <div class="alert alert-light persist-alert text-center mb-0">{{__('No records found.')}}</div>
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
                    window.location.href = "{{ url('/subscriptions') }}" + '?limit=' + $(this).val();
                }
            });
        });
    </script>
@endsection
