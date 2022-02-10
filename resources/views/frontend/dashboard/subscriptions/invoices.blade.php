@extends('frontend.layouts.dashboard')

@section('title', __('Invoices'))

@section('content')
    <div class="content-body">
        @include('frontend.messages')

        <form action="{{ url('/invoices') }}" method="get">
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
                <div class="col-sm-6 col-lg-auto col-12 mb-2">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid input-placeholder-font-size"
                               placeholder="{{__('Search by Package Name')}}" type="text" name="text" value="{{ $text }}">
                    </div>
                </div>
                <div class="col-sm-6 col-lg-auto col-12">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid"
                               placeholder="{{__('Search by Amount')}}" type="text" name="amount" value="{{ $amount }}">
                    </div>
                </div>
                <div class="col-sm-auto col-md-auto col-12 ml-sm-auto mb-2 button-size">
                     <div class="input-group qr--button">
                        <button class="btn btn-orange mt-2 mr-sm-2" type="submit">
                            <i class="fa fa-check"></i> {{__('Apply')}}
                        </button>
                        <a class="btn btn-primary mt-2" href="{{route('frontend.user.invoices')}}">
                            <i class="fa fa-refresh"></i> {{__('Reset')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
        <br><br>
        @if(!$payments->isEmpty())
        <div class="row">
                <div class="col-12">
                    <div class="campaign-list list">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">#</th>
                                        <th class="text-nowrap">{{__('Package')}}</th>
                                        <th class="text-nowrap">{{__('Amount')}}</th>
                                        <th class="text-nowrap">{{__('VAT')}} %</th>
                                        <th class="text-nowrap">{{__('VAT Amount')}}</th>
                                        <th class="text-nowrap">{{__('Reseller')}}</th>
                                        <th class="text-nowrap">{{__('Voucher')}}</th>
                                        <th class="text-nowrap">{{__('Discount')}} %</th>
                                        <th class="text-nowrap">{{__('Discount Amount')}}</th>
                                        <th class="text-nowrap">{{__('Total Amount')}}</th>
                                        <th class="text-nowrap">{{__('Payment Source')}}</th>
                                        <th class="text-nowrap">{{__('Payment Date')}}</th>
                                        <th class="text-nowrap">{{__('Status')}}</th>
                                        <th class="text-nowrap">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $key => $value)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $value->item }}</td>
                                            <td>{{ !empty($value->amount) ? config('constants.currency')['symbol'].$value->amount : 0 }}</td>
                                            <td>{{ $value->vat_percentage }}</td>
                                            <td>{{ !empty($value->vat_amount) ? config('constants.currency')['symbol'].$value->vat_amount : 0 }}</td>
                                            <td>{{ $value->reseller }}</td>
                                            <td>{{ $value->voucher }}</td>
                                            <td>{{ $value->discount_percentage }}</td>
                                            <td>{{ !empty($value->discount_amount) ? config('constants.currency')['symbol'].$value->discount_amount : 0 }}</td>
                                            <td>{{ !empty($value->total_amount) ? config('constants.currency')['symbol'].$value->total_amount : 0 }}</td>
                                            <td>
                                                @if($value->payment_method == config('constants.payment_methods')['MOLLIE'])
                                                    Mollie
                                                @elseif($value->payment_method == config('constants.payment_methods')['ADMIN'])
                                                    Admin
                                                @elseif($value->payment_method == config('constants.payment_methods')['VOUCHER_PROMOTION'])
                                                    Voucher Promotion
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::createFromTimeStamp($value->timestamp, "UTC")->tz(auth()->user()->timezone)->format('d M, Y') }}</td>
                                            <td class="text-nowrap">
                                                @switch($value->status)
                                                    @case(1)
                                                    {{__('Active')}}
                                                    @break
                                                    @case(2)
                                                    {{__('Open')}}
                                                    @break
                                                    @case(3)
                                                    {{__('Pending')}}
                                                    @break
                                                    @case(4)
                                                    {{__('Failed')}}
                                                    @break
                                                    @case(5)
                                                    {{__('Expired')}}
                                                    @break
                                                    @case(6)
                                                    {{__('Cancel')}}
                                                    @break
                                                    @case(7)
                                                    {{__('Refund')}}
                                                    @break
                                                    @case(7)
                                                    {{__('Chargeback')}}
                                                    @break
                                                    @default
                                                    {{__('--')}}
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{route('frontend.download.invoice',Hashids::encode($value->id))}}?lang={{ App::getLocale() }}"><button title="Download Invoice" class="btn btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i></button></a>
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
                                    <strong>{{ $payments->firstItem() }} - {{ $payments->lastItem() }}</strong> {{__('of')}}
                                    <strong>{{ $payments->total() }}</strong>
                                    @include('frontend.dashboard.partials.paginator',['paginators'=> $payments])
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
                    window.location.href = "{{ url('/invoices') }}" + '?limit=' + $(this).val();
                }
            });
        });
    </script>
@endsection
