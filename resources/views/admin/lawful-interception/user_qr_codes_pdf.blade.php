@extends('admin.lawful-interception.template')

@section('title', 'User Qr Codes Pdf')
@section('content')

<table style="width: 100%; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;">
    <thead style="border: solid #c7c7c7; border-width: 1px 1px 0;">
        <tr>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                ID
            </th>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                Name
            </th>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                Content Type
            </th>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                Type
            </th>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                Created At
            </th>
            <th
                style="text-align:left;padding:8px 10px;font-weight:bold;color:#fff;background:#2345a4;font-weight:normal;font-family: arial, sans-serif;">
                QR Code
            </th>
        </tr>
    </thead>
    <tbody style="border: solid #c7c7c7; border-width: 1px 1px 1px 1px;">
        @foreach ($qr_codes as $key => $record)
        <tr>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 1px;vertical-align:top;font-family: arial, sans-serif;">
                {{ $key+1 }}
            </td>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 1px;vertical-align:top;font-family: arial, sans-serif;">
                {{ $record->name }}
            </td>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 0;vertical-align:top;font-family: arial, sans-serif;">
                @if($record->code_type == 1)
                    Dynamic
                @else
                    Static
                @endif
            </td>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 0;font-family: arial, sans-serif;">
                {{ ucwords($record->type) }}
            </td>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 0;font-family: arial, sans-serif;">
                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($record->created_at))->format('d M, Y') }}
            </td>
            <td
                style="font-size:14px;text-align:left;padding:8px 10px;border:solid #c7c7c7;border-width:0 1px 1px 0;font-family: arial, sans-serif;">
                <a class="btn btn-primary" target="_blank" href="{{ asset('storage/users/' . $record->user_id.'/qr-codes/'.$record->image) }}">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection