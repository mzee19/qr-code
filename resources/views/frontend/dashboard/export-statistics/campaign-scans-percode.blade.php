<table class="qr-code-table table-responsive table table-hover">
    <thead>
    <tr>
        <th scope="col">{{__('Id')}}</th>
        <th scope="col">{{__('Name')}}</th>
        <th scope="col">{{__('Scans')}}</th>
        <th scope="col">{{__('Unique Users')}}</th>
    </tr>
    </thead>
    <tbody>
    @isset($data['per_code_scans'])
    @foreach($data['per_code_scans'] as $scans)
        <tr>
            <td>{{$scans['id']}}</td>
            <td>{{$scans['name']}}</td>
            <td>{{$scans['scans_per_day']}}</td>
            <td>{{$scans['unique']}}</td>
        </tr>
    @endforeach
    @endisset
    </tbody>
</table>
