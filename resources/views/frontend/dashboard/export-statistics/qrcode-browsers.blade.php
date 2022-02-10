<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{{__('Browser')}}</th>
            <th>{{__('Percent')}}</th>
            <th>{{__('Scans')}}</th>
        </tr>
        </thead>
        <tbody>
        @isset($data['browsers'])
        @foreach($data['browsers'] as $key => $value)
            <tr>
                <td>{{ $value->browser }}</td>
                <td>{{ number_format(($value->scans/$data['scansList']->count())*100, 2) }} %</td>
                <td>{{ $value->scans }}</td>
            </tr>
        @endforeach
        @endisset
        </tbody>
    </table>
</div>    

