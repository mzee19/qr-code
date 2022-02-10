<table class="qr-code-table table table-hover">
    <thead>
    <tr>
        <th scope="col">{{__('Date')}}</th>
        <th scope="col">{{__('Scans')}}</th>
        <th scope="col">{{__('Unique Users')}}</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$data['from']}} - {{$data['to']}}</td>
            <td>{{$data['scansList']->count()}}</td>
            <td>{{$data['scansList']->groupBy('ip')->count()}}</td>
        </tr>

    </tbody>
</table>
