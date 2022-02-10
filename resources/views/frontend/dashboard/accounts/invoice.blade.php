@extends('frontend.layouts.dashboard')

@section('title', __('Invoices'))


@section('content')
    <div class="content-body">
        @include('frontend.messages')

        <form action="{{ url('/invoices') }}" method="get">
            {{--            <input type="hidden" name="limit" value="{{ $limit }}">--}}

            <div class="row pt-4">
                <div class="col">
                    {{--                    <a class="btn btn-orange" href="{{route('frontend.user.qr-codes.select.content.type')}}">--}}
                    {{--                        <i class="fa fa-plus"></i> Create QR Code--}}
                    {{--                    </a>--}}
                </div>
                <div class="col-auto">
                    <select class="form-control ng-valid ng-dirty ng-touched" name="sort">
                        <option value="updated_at-asc" {{ $sort == "updated_at-asc" ? "selected" : "" }}>↑ {{__('Updated')}}
                        </option>
                        <option value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>↓ {{__('Updated')}}
                        </option>
                        <option value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>↑ {{__('Created')}}
                        </option>
                        <option value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>↓ {{__('Created')}}
                        </option>
                    </select>
                </div>
                <div class="col-3">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control ng-untouched ng-pristine ng-valid"
                               placeholder="{{ __('Search by Package Name')}}" type="text" name="text" value="{{ $text }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="input-group">
                        <button class="btn btn-orange" type="submit">
                            <i class="fa fa-check"></i> {{__('Apply')}}
                        </button>
                        &nbsp;&nbsp;
                        <a class="btn btn-primary" href="{{route('frontend.user.invoice')}}">
                            <i class="fa fa-refresh"></i> {{__('Reset')}}
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @if(!$payments->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="list qr-codes-lists">
                        <div class="row">
                            @foreach($payments as $invoice)
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="list-item">
                                        <a class="inner"
                                           href="javascript:void(0)">
                                            <div class="info list-col">
                                                <div class="fact">
                                                    <span class="name">{{__('Package')}}:</span>
                                                    <strong>{{$invoice->subscription->package->title}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Amount')}}:</span>
                                                    <strong>{{$invoice->amount}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('VAT')}} %:</span>
                                                    <strong>{{$invoice->vat_percentage}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('VAT Amount')}}:</span>
                                                    <strong>{{$invoice->vat_amount}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Reseller')}}:</span> <strong>N/A</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Voucher')}}:</span> <strong>N/A</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Discount')}} %:</span> <strong>N/A</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Discount Amount')}}:</span> <strong>0</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Paid Amount')}}:</span>
                                                    <strong>{{$invoice->total_amount}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Payment Source')}}:</span> <strong>{{__('Mollie')}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">{{__('Payment Date')}}:</span>
                                                    <strong>{{date_format($invoice->created_at,'Y-m-d')}}</strong>
                                                </div>
                                                <div class="fact">
                                                    <span class="name">__('Status'):</span> <strong>
                                                        @if($invoice->status == 1 )
                                                        {{__('Paid')}}
                                                        @elseif($invoice->status == 2 )
                                                        {{__('Open')}}
                                                        @elseif($invoice->status == 3 )
                                                        {{__('Pending')}}
                                                        @elseif($invoice->status == 4 )
                                                        {{__('Failed')}}
                                                        @elseif($invoice->status == 5 )
                                                        {{__('Expired')}}
                                                        @elseif($invoice->status == 6 )
                                                        {{__('Cancel')}}
                                                        @elseif($invoice->status == 7 )
                                                        {{__('Refund')}}
                                                        @else
                                                        {{__('ChargeBack')}}
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="list-col options">
                                            <div class="d-flex qr--list--gap">
                                                <a href="{{route('frontend.user.download.invoice',Hashids::encode($invoice->id))}}" class="btn btn-sm btn-outline-primary"
                                                        ><i
                                                        class="fa fa-download"></i>
                                                </a>
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
                                    <strong>{{ $payments->firstItem() }} - {{ $payments->lastItem() }}</strong> __('of')
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
                    <div class="alert alert-light persist-alert text-center mb-0">{{__('There are no invoices yet')}}.</div>
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
