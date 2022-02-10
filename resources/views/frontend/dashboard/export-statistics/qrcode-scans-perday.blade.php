<table class="qr-code-table table table-hover">
    <thead>
    <tr>
        <th scope="col">{{__('Date')}}</th>
        <th scope="col">{{__('Scans')}}</th>
        <th scope="col">{{__('Unique Users')}}</th>
    </tr>
    </thead>
    <tbody>
    @isset($data['scansPerDay'])
    @foreach($data['scansPerDay'] as $per_Day_scans)
        <tr>
            <td>{{$per_Day_scans['date']}}</td>
            <td>{{$per_Day_scans['scan']}}</td>
            <td>{{$per_Day_scans['unique_user']}}</td>
        </tr>
    @endforeach
    @endisset
    </tbody>
</table>
