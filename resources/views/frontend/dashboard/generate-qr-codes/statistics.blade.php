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
                <div class="row pt-4">
                    <div class="col-md-6 col-sm-7">
                        @if(getSubscriptionFeatureCount(9))
                            <div id="reportrange"
                                 style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-3 col-sm-auto mt-sm-0 col-12 mt-2 ">
                        @if(getSubscriptionFeatureCount(9))
                            <form action="{{route('frontend.user.qr-codes.statistics',Hashids::encode($qrCode->id))}}"
                                  method="get">
                                <input type="hidden" id="from" name="from" value="{{ $from }}">
                                <input type="hidden" id="to" name="to" value="{{ $to }}">
                                <button class="btn btn-orange w-100" type="submit">
                                    <i class="fa fa-check"></i> {{__('Apply')}}</button>
                            </form>
                        @endif

                    </div>
                    <!-- <div class="col-auto "></div> -->
                    <div class="col-lg-3 col-sm-auto mt-md-0 col-12 mt-sm-0 mt-2 text-sm-right">
                        <form id="export_from"
                              action="{{route('frontend.user.qr-codes.statistics',Hashids::encode($qrCode->id))}}"
                              method="get">
                            <input type="hidden" id="from" name="from" value="{{ $from }}">
                            <input type="hidden" id="to" name="to" value="{{ $to }}">
                            <input type="hidden" id="export" name="export" value="1">
                            @if(checkFieldStatus(10))
                                <a class="btn btn-outline-primary w-100"
                                   onclick="document.getElementById('export_from').submit();"
                                   href="javascript:void(0)">
                                    <i class="fa fa-file-excel-o"></i> {{__('Export')}}
                                </a>
                            @endif
                        </form>

                    </div>
                </div>
                <div class="row pt-4 static-cards">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="cardbox cardbox-tab active">
                            <div class="cardbox-inner">
                                <div class="title">{{__('Total Scans')}}</div>
                                <h3>{{ $scansList->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="cardbox cardbox-tab ">
                            <div class="cardbox-inner">
                                <div class="title">{{__('Unique Users')}}</div>
                                <h3>{{ $scansList->groupBy('ip')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="cardbox cardbox-tab ">
                            <div class="cardbox-inner">
                                <div class="title">{{__('Locations')}}/{{__('Languages')}}</div>
                                <h3>
                                    @if(!empty($firstScan))
                                        {{ number_format(($countryCount/$scansList->count())*100, 2) }}
                                        % {{ $firstScan->country.'/'.$firstScan->language }}
                                    @else
                                        -
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="cardbox cardbox-tab ">
                            <div class="cardbox-inner">
                                <div class="title">{{__('Devices')}}</div>
                                <h3>
                                    @if(!empty($firstScan))
                                        {{ number_format(($deviceCount/$scansList->count())*100, 2) }}
                                        % {{ $firstScan->device }}
                                    @else
                                        -
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

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
                                        <table id="statistics-table"
                                               class="latest-scan-table qr-code-table table table-hover">
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

                                            @foreach($scansList->whereIn('statistics_status',$checkStatisticsStatus)->take(5) as $row)
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
                                        @if($scansList->whereIn('statistics_status',$checkStatisticsStatus)->count() > 4)
                                            <div id="moreLoader" class="load-more"><a
                                                    href="{{route('frontend.user.qr-codes.all.statistics',Hashids::encode($row->qr_code_id))}}"
                                                    target="_blank" onclick="loadMoreStat()">Load More</a></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="cardbox">
                                    <div class="cardbox-head cardbox-inner">
                                        <h5 class="title">{{__('Scan Statistic')}}</h5>
                                    </div>
                                    <div class="cardbox-inner charts">
                                        <canvas id="scansChart"></canvas>
                                        <!-- <div id="scansChartContainer" style="height: 500px; width: 100%;"></div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="cardbox">
                                    <div class="cardbox-head cardbox-inner">
                                        <h5 class="title">{{__('Unique Users Statistic')}}</h5>
                                    </div>
                                    <div class="cardbox-inner charts">
                                        <canvas id="uniqueUsersChart"></canvas>
                                        <!-- <div id="uniqueUsersChartContainer" style="height: 500px; width: 100%;"></div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="cardbox">
                                    <div class="cardbox-head cardbox-inner">
                                        <h5 class="title">{{__('Locations')}}/{{__('Languages')}}</h5>
                                    </div>
                                    <div class="cardbox-inner">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="countries-tab" data-toggle="tab"
                                                   href="#countries" role="tab" aria-controls="countries"
                                                   aria-selected="true">{{__('Countries')}}</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="cities-tab" data-toggle="tab" href="#cities"
                                                   role="tab" aria-controls="cities"
                                                   aria-selected="false">{{__('Cities')}}</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="languages-tab" data-toggle="tab"
                                                   href="#languages"
                                                   role="tab" aria-controls="languages"
                                                   aria-selected="false">{{__('Languages')}}</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="countries" role="tabpanel"
                                                 aria-labelledby="countries-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Country')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($countries as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->country }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="cities" role="tabpanel"
                                                 aria-labelledby="cities-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('City')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($cities as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->city }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="languages" role="tabpanel"
                                                 aria-labelledby="languages-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Language')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($languages as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->language }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="cardbox">
                                    <div class="cardbox-head cardbox-inner">
                                        <h5 class="title">{{__('Devices')}}</h5>
                                    </div>
                                    <div class="cardbox-inner">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="devices-tab" data-toggle="tab"
                                                   href="#devices" role="tab" aria-controls="devices"
                                                   aria-selected="true">{{__('Devices')}}</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="platforms-tab" data-toggle="tab"
                                                   href="#platforms"
                                                   role="tab" aria-controls="platforms"
                                                   aria-selected="false">{{__('Platforms')}}</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="browsers-tab" data-toggle="tab" href="#browsers"
                                                   role="tab" aria-controls="browsers"
                                                   aria-selected="false">{{__('Browsers')}}</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="devices" role="tabpanel"
                                                 aria-labelledby="devices-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Device')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($devices as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->device }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="platforms" role="tabpanel"
                                                 aria-labelledby="platforms-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Platform')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($platforms as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->platform }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="browsers" role="tabpanel"
                                                 aria-labelledby="browsers-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('Browser')}}</th>
                                                            <th>{{__('Percent')}}</th>
                                                            <th>{{__('Scans')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($browsers as $key => $value)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td>{{ $value->browser }}</td>
                                                                <td>{{ number_format(($value->scans/$scanCount)*100, 2) }}
                                                                    %
                                                                </td>
                                                                <td>{{ $value->scans }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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

        function loadMoreStat() {
            $('#statistics-table').addClass('latest-more-record');
            $('#moreLoader').css('display', 'none');
        }
    </script>
@endsection

