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
        <span class="name">{{__('Status')}}:</span> <strong>
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
