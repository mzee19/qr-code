
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{{__('Device')}}</th>
            <th>{{__('Percent')}}</th>
            <th>{{__('Scans')}}</th>
        </tr>
        </thead>
        <tbody>
        @isset($data['devices'])
        @foreach($data['devices'] as $key => $value)
            <tr>
                <td>{{ $value->device }}</td>
                <td>{{ number_format(($value->scans/$data['scansList']->count())*100, 2) }} %</td>
                <td>{{ $value->scans }}</td>
            </tr>
        @endforeach
        @endisset
        </tbody>
    </table>
</div>    

