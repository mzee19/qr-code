@extends('admin.layouts.app')

@section('title', 'Qr Codes')
@section('sub-title', $action.' Qr Code')
@section('content')
    <div class="main-content">
        <div class="content-heading clearfix">

            <ul class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{{url('admin/users')}}"><i class="fa fa-user"></i>Users</a></li>
                <li><a href="{{url('admin/users/'.Hashids::encode($qrCode->user_id).'/qr-codes')}}"><i
                            class="fa fa-qrcode"></i>Qr Codes</a></li>
                <li>{{$action}}</li>
            </ul>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        {{--                        <div class="panel-heading">--}}
                        {{--                            <h3 class="panel-title">{{$action}} Qr Code</h3>--}}
                        {{--                        </div>--}}
                        <div class="panel-body">
{{--                            <div class="row pt-4">--}}
{{--                                <div class="col-md-3 ">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <span class="input-group-addon" id="basic-addon1">--}}
{{--                                            <i class="fa fa-calendar"></i>--}}
{{--                                        </span>--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option value="today">Today</option>--}}
{{--                                            <option value="week">Last 7 Days</option>--}}
{{--                                            <option value="month">Last 30 Days</option>--}}
{{--                                            <option value="3-months">Last 90 Days</option>--}}
{{--                                            <option value="custom">Custom</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-offset-6 col-md-3 text-right">--}}
{{--                                    <a class="btn btn-outline-primary" href="create-campaign.html">--}}
{{--                                        <i class="fa fa-file-excel-o"></i> Export--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="row margin-bottom-30 mt-4">
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <div class="right">
                                            <span class="title">Total Scans</span>
                                            <span class="value">{{ $scansList->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <div class="right">
                                            <span class="title">Unique Users</span>
                                            <span class="value">{{ $scansList->groupBy('ip')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <div class="right">
                                            <span class="title">Locations/Languages</span>
                                            <span class="value">
                                                @if(!empty($firstScan))
                                                    {{ number_format(($countryCount/$scansList->count())*100, 2) }}
                                                    % {{ $firstScan->country }}
                                                @else
                                                    -
                                                @endif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <div class="right">
                                            <span class="title">Devices</span>
                                            <span class="value">
                                                @if(!empty($firstScan))
                                                    {{ number_format(($deviceCount/$scansList->count())*100, 2) }}
                                                    % {{ $firstScan->device }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!$scansList->isEmpty())

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="text-primary">Locations/Languages</h5>
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#countries" aria-controls="countries"
                                                                              role="tab" data-toggle="tab">Countries</a></li>
                                    <li role="presentation"><a href="#cities" aria-controls="cities" role="tab"
                                                               data-toggle="tab">Cities</a></li>
                                    <li role="presentation"><a href="#language" aria-controls="languages" role="tab"
                                                               data-toggle="tab">Languages</a></li>

                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane active" id="countries">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Country</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($countries as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->country }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }}
                                                        %
                                                    </td>
                                                    <td>{{ $value->scans }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="cities">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>City</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cities as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->city }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }}
                                                        %
                                                    </td>
                                                    <td>{{ $value->scans }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="language">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Language</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($languages as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->language }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }}
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="text-primary">Devices</h5>
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#devices" aria-controls="devices"
                                                                              role="tab" data-toggle="tab">Devices</a></li>
                                    <li role="presentation"><a href="#platforms" aria-controls="platforms" role="tab"
                                                               data-toggle="tab">Platforms</a></li>
                                    <li role="presentation"><a href="#browsers" aria-controls="browsers" role="tab"
                                                               data-toggle="tab">Browsers</a></li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div role="tabpanel" class="tab-pane active" id="devices">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Device</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($devices as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->device }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }} %</td>
                                                    <td>{{ $value->scans }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="platforms">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Platform</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($platforms as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->platform }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }} %</td>
                                                    <td>{{ $value->scans }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="browsers">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Browser</th>
                                                <th>Percent</th>
                                                <th>Scans</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($browsers as $key => $value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->browser }}</td>
                                                    <td>{{ number_format(($value->scans/$scansList->count())*100, 2) }} %</td>
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="text-primary">Scan Statistic</h5>
                            </div>
                            <div class="panel-body">
                                <canvas id="scansChart"></canvas>
                                <!-- <div id="scansChartContainer" style="height: 500px; width: 100%;"></div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h5 class="text-primary">Unique Users Statistic</h5>
                            </div>
                            <div class="panel-body">
                                <canvas id="uniqueUsersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="text-primary">Latest Scans</h3>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="qr-code-table table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">QR Code</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Browser</th>
                                        <th scope="col">Platform</th>
                                        <th scope="col">Device</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($scansList as $row)
                                        <tr>
                                            <td>{{ $row->qrCode->name }}</td>
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

            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="alert alert-light persist-alert text-center mb-0"> No tracking statistics
                                    for current time range.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function(){
            var scanLabels = [
                <?php
                foreach ($scanLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $scanValues }};

            var ctx = document.getElementById ('scansChart').getContext('2d');
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
                                beginAtZero:true
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
        $(document).ready(function(){
            var uniqueLabels = [
                <?php
                foreach ($uniqueLabels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $uniqueValues }};

            var ctx = document.getElementById ('uniqueUsersChart').getContext('2d');
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
                                beginAtZero:true
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
        CanvasJS.addColorSet("blueShades",["#0645A4"]);

        var chart = new CanvasJS.Chart("scansChartContainer", {
            colorSet: "blueShades",
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "Total Scans"
            },
            axisY: {
                title: "Scans"
            },
            data: [{
                type: "column",
                showInLegend: true,
                legendMarkerColor: "grey",
                legendText: "Date",
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
        chart.render();
    </script>
@endsection
