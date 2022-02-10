@extends('frontend.layouts.dashboard')

@section('title', __('Statistics').' - ('.$qrCode->name.')')

@section('content')
    @php
        $packageStatisticStatusValue = getSubscriptionFeatureCount(9);
        switch ($packageStatisticStatusValue){
             case 'basic':
                 $checkStatisticsStatus = [0,2];
                 break;
             case 'advanced':
                 $checkStatisticsStatus = [0,1,2];
                 break;
             default:
                 $checkStatisticsStatus = [0];
        }
        $scanCount = $scansList->whereIn('statistics_status',$checkStatisticsStatus)->count()
    @endphp
    <div class="content-body ">
        <div class="row">
            <div class="col-sm-12 tab-section">

                @if(!$scansList->isEmpty() )
                    @if(getSubscriptionFeatureCount(9))
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="section-title">
                                    <h3 class="sub-title">{{__('Latest Scans')}}</h3>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="cardbox">
                                    <div class="table-responsive">
                                        <table
                                            class="latest-scan-table qr-code-table table table-hover latest-more-record">
                                            <thead>
                                            <tr>
                                                <th scope="col">{{__('QR Code')}}</th>
                                                <th scope="col">{{__('Time')}}</th>
                                                <th scope="col">{{__('IP')}}</th>
                                                <th scope="col">{{__('City')}}</th>
                                                <th scope="col">{{__('Country')}}</th>
                                                <th scope="col">{{__('Browser')}}</th>
                                                <th scope="col">{{__('Platform')}}</th>
                                                <th scope="col">{{__('Device')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($scansList->whereIn('statistics_status',$checkStatisticsStatus) as $row)
                                                <tr>
                                                    <td>
                                                        <a href="{{route('frontend.user.qr-codes.edit',Hashids::encode($row->qr_code_id))}}">{{ $row->qrCode->name }}</a>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}</td>
                                                    <td>{{ $row->ip }}</td>
                                                    <td>{{ $row->city }}</td>
                                                    <td>{{ $row->country }}</td>
                                                    <td>{{ $row->browser }}</td>
                                                    <td>{{ $row->platform }}</td>
                                                    <td>{{ $row->device }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif
                @else
                    <div class="cardbox">
                        <div class="cardbox-inner">
                            <div
                                class="alert alert-light persist-alert text-center mb-0">  {{__('No tracking statistics for current timerange')}}
                                .
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            var scanLabels = [
                <?php
                foreach ($scanLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $scanValues }};

            var ctx = document.getElementById('scansChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: scanLabels,
                    datasets: [{
                        label: '{{__('Total Scans')}}',
                        data: data,
                        backgroundColor: "#0645a4"
                    }]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'minute',
                                displayFormats: {
                                    'minute': 'YYYY-MM-DD',
                                    'hour': 'YYYY-MM-DD'
                                }
                            },
                            ticks: {
                                source: 'data'
                            }
                        }]
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            var uniqueLabels = [
                <?php
                foreach ($uniqueLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $uniqueValues }};

            var ctx = document.getElementById('uniqueUsersChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: uniqueLabels,
                    datasets: [{
                        label: '{{__('Unique Users')}}',
                        data: data,
                        backgroundColor: "#0645a4"
                    }]
                },

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'minute',
                                displayFormats: {
                                    'minute': 'YYYY-MM-DD',
                                    'hour': 'YYYY-MM-DD'
                                }
                            },
                            ticks: {
                                source: 'data'
                            }
                        }]
                    }
                }
            });
        });
    </script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script>

        $(document).ready(function () {
            $("#reportrange").click(function () {
                $(".daterangepicker .ranges li:last-child").text('{{__('Custom Range')}}');
            });
        });

        CanvasJS.addColorSet("blueShades", ["#0645A4"]);

        var scansChart = new CanvasJS.Chart("scansChartContainer", {
            colorSet: "blueShades",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: '{{__('Total Scans')}}'
            },
            axisY: {
                title: '{{__('Scans')}}'
            },

            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: '{{__('Date')}}',
                dataPoints: [
                    <?php
                    foreach ($scansDataPoints as $date => $scans) {
                        echo '{ x:new Date("' . $date . '"), y: ' . $scans . '},';
                    }
                    ?>
                ],
                xValueFormatString: "MMM DD, YYYY",
            }]
        });
        scansChart.render();

        var uniqueUsersChart = new CanvasJS.Chart("uniqueUsersChartContainer", {
            colorSet: "blueShades",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: '{{__('Unique Users')}}'
            },
            axisY: {
                title: '{{__('Users')}}'
            },

            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: '{{__('Date')}}',

                dataPoints: [
                    <?php
                    foreach ($uniqueUsersDataPoints as $date => $users) {
                        echo '{ x:new Date("' . $date . '"), y: ' . $users . '},';
                    }
                    ?>
                ],
                xValueFormatString: "MMM DD, YYYY",
            }]
        });
        uniqueUsersChart.render();
    </script>

    <script type="text/javascript">
        $(function () {

            var start = moment('{{$from}}');
            var end = moment('{{$to}}');

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#from').val(start.format('YYYY-MM-D'));
                $('#to').val(end.format('YYYY-MM-D'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    '{{__('Today')}}': [moment(), moment()],
                    '{{__('Yesterday')}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '{{__('Last 7 Days')}}': [moment().subtract(6, 'days'), moment()],
                    '{{__('Last 30 Days')}}': [moment().subtract(30, 'days'), moment()],
                    '{{__('This Month')}}': [moment().startOf('month'), moment().endOf('month')],
                    '{{__('Last Month')}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }

            }, cb);

            cb(start, end);
        });
    </script>
@endsection

