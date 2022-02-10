<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>{{ __('Invoice') }}</title>
</head>

<body
    style="margin:0; font-size: 18px;line-height: 18px; background:#fff;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
    <div
        style="width: 1000px; margin:0 auto; background:#fff; color:#000;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
        <table style="width: 1000px;margin: 0px 0 50px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
            <tr style="vertical-align:top; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                <td
                    style="width: 500px; text-align: left;vertical-align:middle; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                    <img src="{{ public_path('images/brand-logo.png') }}" alt="" width="300px">
                </td>
                <td
                    style="width: 500px; text-align: right;vertical-align:middle; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                    <img src="{{ public_path('images/pdf-logo2.png') }}" alt="" width="300px">
                </td>
            </tr>
            <tr style="vertical-align:top; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                <td colspan="2"
                    style="vertical-align:top;height: 1200px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                    <table style="width: 1000px;">
                        <tr
                            style="vertical-align: middle; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                            <td
                                style="width: 500px;padding-top: 80px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <table
                                    style="width: 500px;  border-collapse: collapse;font-size: 18px;line-height: 18px;">
                                    <tr>
                                        <td>
                                            <span
                                                style="color: #9f9f9f; font-size: 14px; padding-bottom: 10px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{settingValue('company_name')}} <span
                                                    style="font-family: Segoe, 'Segoe UI', 'sans-serif';">•</span>
                                                {{settingValue('company_street')}} <span
                                                    style="font-family: Segoe, 'Segoe UI', 'sans-serif';">•</span>
                                                {{settingValue('company_zip_code')}}
                                                {{settingValue('company_city')}}
                                            </span>
                                            <p
                                                style="color: #000; font-size: 14px; margin:0;padding-top: 25px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $user->name }}<br>
                                                {{ $user->street }}<br>
                                                {{ $user->postcode }} {{ $user->city }}
                                                {{ $user->country->name }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td
                                style="width: 500px;padding-top: 80px;text-align: right;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <table
                                    style="width: 498px;border-collapse: collapse;border: 1px solid #9f9f9f;font-size: 14px;line-height: 18px; padding: 4px 8px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    <thead>
                                        <tr>
                                            <th
                                                style="text-align: left;font-weight: 600; padding-bottom: 5px;font-size: 16px;padding: 8px 0px 0px 10px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Invoice') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                style="padding: 8px 0 0px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                QrCode/{{\Carbon\Carbon::now('UTC')->tz($user->timezone)->format('Y')}}/{{ $payment->id }}</td>
                                            <td
                                                style="color: #9f9f9f;text-align: right;padding: 8px 10px 0px 0px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{__('Date') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;padding: 0px 0 8px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                &nbsp;</td>
                                            <td
                                                style="text-align: right;border-bottom: 1px solid #9f9f9f;padding: 0px 10px 8px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{\Carbon\Carbon::now('UTC')->tz($user->timezone)->format('d/m/Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="color: #9f9f9f;padding: 8px 0 0px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Reference') }}:</td>
                                            <td
                                                style="color: #9f9f9f;text-align: right;padding: 8px 10px 0px 0px; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Source Platform') }}:</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;padding: 0px 0 8px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                QrCode/{{\Carbon\Carbon::now('UTC')->tz($user->timezone)->format('Y')}}/{{ $payment->id }}</td>
                                            <td
                                                style="text-align: right;border-bottom: 1px solid #9f9f9f;padding: 0px 10px 8px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{env('APP_NAME')}}</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="color: #9f9f9f;border-bottom: 1px solid #9f9f9f;padding: 8px 0 8px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $user->name }}</td>
                                            <td
                                                style="color: #9f9f9f;border-bottom: 1px solid #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="color: #9f9f9f;padding: 8px 0 0px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{settingValue('company_name')}}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;padding: 0px 0 8px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{settingValue('company_registration_number')}}</td>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="color: #9f9f9f;padding: 8px 0 0px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Date From') }}</td>
                                            <td
                                                style="color: #9f9f9f;text-align: right;padding: 8px 10px 0px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Date To') }}</td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;padding: 0px 0 8px 10px;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ \Carbon\Carbon::createFromTimeStamp($item['start_date'], "UTC")->tz($user->timezone)->format('d/m/Y') }}
                                            </td>
                                            <td
                                                style="text-align: right;border-bottom: 1px solid #9f9f9f;padding: 0px 10px 8px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ (!empty($item['end_date'])) ?  \Carbon\Carbon::createFromTimeStamp($item['end_date'], "UTC")->tz($user->timezone)->format('d/m/Y') : __('Lifetime') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="padding-top: 100px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <table
                                    style="width: 1000px;border-collapse: collapse;font-size: 13px;line-height: 18px;margin-bottom: 50px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    <thead>
                                        <tr
                                            style="border-bottom: 1px solid #9f9f9f; border-top: 1px solid #9f9f9f; padding: 12px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                            <th
                                                style="font-weight: 500;text-align: left;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                Pos</th>
                                            <th
                                                style="font-weight: 600;text-align: left;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Package') }}
                                            </th>
                                            <th
                                                style="font-weight: 600;text-align: left;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Description') }}
                                            </th>
                                            <th style="font-weight: 600;text-align: right;padding: 4px 0;font-family: {{$global_font_family}},
                                            'Segoe UI', 'sans-serif';">
                                                {{ __('Type') }}
                                            </th>
                                            <th
                                                style="font-weight: 600;text-align: right;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $item['type'] == 1 ? __('Monthly Price') : __('Annually Price')}}
                                            </th>
                                            <th
                                                style="font-weight: 600;text-align: right;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Discount') }} %</th>
                                            <th
                                                style="font-weight: 600;text-align: right;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Taxes') }}</th>
                                            <th
                                                style="font-weight: 600;text-align: right;padding: 4px 0;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Total Price') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                style="text-align: left;width: 50px;  vertical-align: top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                1
                                            </td>
                                            <td
                                                style="text-align: left;width: 100px;vertical-align: top;white-space: nowrap;font-family: {{$payment_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $item['name']}}
                                            </td>
                                            <td
                                                style="text-align: left;width: 300px;vertical-align: top;white-space: nowrap;font-family: {{$payment_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {!! $item['description'] !!}
                                            </td>
                                            <td style="text-align: right;width: 90px;white-space: nowrap;vertical-align: top;font-family: {{$global_font_family}},
                                            'Segoe UI', 'sans-serif';">
                                                {{ $item['type'] == 1 ? __('Monthly') : __('Annually') }}
                                            </td>
                                            <td
                                                style="text-align: right;width: 120px;white-space: nowrap;vertical-align: top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ config('constants.currency')['symbol'].(isset($package_price) ? $package_price : $item['amount']) }}
                                            </td>
                                            <td
                                                style="text-align: right;width: 120px;vertical-align: top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $item['discount_percentage'] }}%
                                            </td>
                                            <td
                                                style="text-align: right;width: 100px;vertical-align: top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ $item['vat_percentage'] }}%
                                                {{strtoupper($payment->vat_country_code)}}t
                                            </td>
                                            <td
                                                style="text-align: right;width: 120px;vertical-align: top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ config('constants.currency')['symbol'].$item['amount'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="width: 500px; text-align: left;vertical-align:middle;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                            </td>
                            <td
                                style="width: 500px; text-align: right;vertical-align:middle;padding-top: 40px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <table
                                    style="border-collapse: collapse;width: 500px;float: right;font-size: 14px;line-height: 18px; padding: 4px 8px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    <tbody>
                                        <tr>
                                            <td
                                                style="padding: 10px 0 10px 0px;border-bottom: 1px solid #9f9f9f;border-top: 1px solid #9f9f9f;text-align: left;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Net') }}</td>
                                            <td
                                                style="color: #000;text-align: right;padding: 10px 0px 10px 0px;border-top: 1px solid #9f9f9f;border-bottom: 1px solid #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
{{--                                                {{ config('constants.currency')['symbol'].(isset($package_price) ? $package_price : $item['amount']) }}--}}
                                                {{ config('constants.currency')['symbol'].$item['amount'] }}
                                            </td>
                                        </tr>
                                        @if(!empty($item['discount_amount']))
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #9f9f9f;padding: 10px 0 10px 0px;text-align: left; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Discount Amount') }}
                                                {{ $item['discount_percentage'] }}%
                                            </td>
                                            <td
                                                style="text-align: right;border-bottom: 1px solid #9f9f9f;padding: 10px 0px 10px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ config('constants.currency')['symbol'].$item['discount_amount'] }}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td
                                                style="border-bottom: 1px solid #000;padding: 10px 0 10px 0px;text-align: left; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{strtoupper($payment->vat_country_code)}}t {{settingValue('vat')}}% /
                                                {{ $item['vat_percentage'] }}%</td>
                                            <td
                                                style="text-align: right;border-bottom: 1px solid #000;padding: 10px 0px 10px 0px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ config('constants.currency')['symbol'].number_format($item['vat_amount'], 2, '.', '') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="padding: 10px 0 0px 0px;font-weight: 600;text-align: left; font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ __('Total') }}
                                            </td>
                                            <td
                                                style="text-align: right;padding: 4px 0px 0px 0px;font-weight: 600;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                {{ config('constants.currency')['symbol'].$item['total_amount'] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="width: 500px;padding-top: 40px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <table
                                    style="width: 500px;margin-bottom:20px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    <tbody>
                                        @if(!empty($payment->txn_id))
                                        <tr>
                                            <td colspan="2" style="white-space: nowrap;">
                                                <span
                                                    style="text-align: left;white-space: nowrap;margin-bottom: 10px;font-size: 14px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                    {{ __('Please use the following communication for your payment') }}:
                                                    <span
                                                        style="white-space: nowrap;font-weight: 600;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">QrCode/{{\Carbon\Carbon::now('UTC')->tz($user->timezone)->format('Y')}}/{{ $payment->id }}</span></span>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td
                                                style="padding-top: 20px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                <span
                                                    style="text-align: left;white-space: nowrap;font-size: 14px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">{{ __('Payment Terms') }}:
                                                    {{ $item['type'] == 1 ?  (isset($repetition) ? __('Monthly for').' '.$repetition.' '.__('Month(s)') : __('Monthly')) : (isset($repetition) ? __('Annually for').' '.$repetition.' '.__('Year(s)') : __('Annually'))}}</span>
                                            </td>
                                        </tr>
                                        @if(!empty($item['reseller']))
                                        <tr>
                                            <td>
                                                <span
                                                    style="text-align: left;white-space: nowrap;margin-bottom: 10px;font-size: 14px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                    {{ __('Reseller') }}: <span
                                                        style="white-space: nowrap;font-weight: 600;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">{{ $item['reseller'] }}</span></span>
                                            </td>
                                        </tr>
                                        @endif
                                        @if(!empty($item['voucher']))
                                        <tr>
                                            <td>
                                                <span
                                                    style="text-align: left;white-space: nowrap;margin-bottom: 10px;font-size: 14px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                                    {{ __('Voucher') }}: <span
                                                        style="white-space: nowrap;font-weight: 600;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">{{ $item['voucher'] }}</span></span>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table
                        style="width: 1000px;border-collapse: collapse;font-size: 14px;line-height: 18px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                        <tr>
                            <td width="450px"
                                style="border-bottom: 1px solid #9f9f9f; padding-bottom:5px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <p
                                    style="margin: 0; padding-bottom: 8px;color: #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    {{ __('Invoice') }}
                                    QrCode/{{\Carbon\Carbon::now('UTC')->tz($user->timezone)->format('Y')}}/{{ $payment->id }}</p>
                            </td>
                            <td width="300px"
                                style="border-bottom: 1px solid #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                            </td>
                            <td width="250px"
                                style="border-bottom: 1px solid #9f9f9f;text-align:right;padding-bottom:5px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <p
                                    style="margin: 0; padding-bottom: 8px;color: #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    {{ __('Page') }}: 1 / 1</p>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="padding-top:10px;vertical-align:top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <p style="color: #9f9f9f;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                    {{settingValue('company_name')}}<br>
                                    {{settingValue('company_street')}}<br>
                                    {{settingValue('company_zip_code')}} {{settingValue('company_city')}}<br>
                                    {{ __('Email') }}: <span
                                        style="color:#00bcd4; padding-left: 15px;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">{{settingValue('contact_email')}}</span><br>
                                    {{ __('Platform Website') }}:
                                    <span style="font-size: 13">{{ env('APP_URL') }}</span><br>
                                    {{ __('Company Website') }}: <span
                                        style="font-size: 13">{{settingValue('website')}}</span>

                                </p>
                            </td>
                            <td
                                style="padding-top:10px;vertical-align:top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <p style="color: #9f9f9f;">{{settingValue('commercial_register_address')}}<br>
                                    {{ __('VAT-ID') }}: {{settingValue('vat_id')}}<br>
                                    {{ __('TAX-ID') }}: {{settingValue('tax_id')}}
                                </p>
                            </td>
                            <td
                                style="padding-top:10px; vertical-align:top;font-family: {{$global_font_family}}, 'Segoe UI', 'sans-serif';">
                                <p style="color: #9f9f9f;">{{settingValue('bank_name')}}<br>
                                    {{ __('IBAN') }}: {{settingValue('iban')}}<br>
                                    {{ __('BIC') }}: {{settingValue('code')}}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
