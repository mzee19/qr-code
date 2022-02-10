
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>{{__('Country')}}</th>
        <th>{{__('Percent')}}</th>
        <th>{{__('Scans')}}</th>
    </tr>
    </thead>
    <tbody>
    @isset($data['countries'])
    @foreach($data['countries'] as $key => $value)
        <tr>
            <td>{{ $value->country }}</td>
            <td>{{ number_format(($value->scans/$data['scansList']->count())*100, 2) }} %</td>
            <td>{{ $value->scans }}</td>
        </tr>
    @endforeach
    @endisset
    </tbody>
</table>

