@extends('frontend.layouts.dashboard')

@section('title', __('Dashboard'))

@section('content')
    <div class="content-body dashboard-custom">
        @include('frontend.messages')
        <div class="row">
            <div class="col">
                <div class="section-title pt-3 pb-3 text-center">
                    <h2 class="welcome">{{__('Welcome')}}</h2>
                </div>
            </div>
        </div>
        <div class="row pb-3 pt-3">
            <div class="col-sm-6 col-12">
                <div class="top-section-title">
                    <h3 class="sub-title">{{__('Latest QR Codes')}}:</h3>
                </div>
            </div>
            <div class="col-sm-6 col-12 text-center text-sm-right">
                <a class="btn btn-orange" href="{{route('frontend.user.qr-codes.select.content.type')}}"><i class="fa fa-plus"></i> {{__('Create QR Code')}}</a>
            </div>
        </div>
        @if(!$qrCodes->isEmpty())
            <div class="row qr-codes-lists">
                @foreach($qrCodes as $qrCode)
                    <div class="col-12 col-lg-6">
                        <div class="cardbox qrcode">
                            <div class="qrcode-container">
                                <a href="{{route('frontend.user.qr-codes.edit',Hashids::encode($qrCode->id))}}"
                                   class="custom-qrcode">
                                    <img
                                        src="{{checkImage(asset('storage/users/'.$qrCode->user_id.'/qr-codes/' . $qrCode->image),'default.svg',$qrCode->image)}}">
                                </a>

                                <div class="right-details cardbox-inner">
                                    <div class="pb-sm-3 d-flex justify-content-between">
                                        <div class="statistics-heading box_name maxname title"><i class="{{$qrCode->icon}}"></i> {{$qrCode->name}} </div>
                                        @if($qrCode->code_type == 1)
                                            <div class="scans">
    												<span>
{{--    													<strong>{{$qrCode->scans ? $qrCode->scans: 0}}</strong>--}}
                                                        {{__('Dynamic')}}
                                                    </span>
                                            </div>
                                        @else
                                            <div class="scans">
    												<span>
                                                    {{__('Static')}}
                                                    </span>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="fact text-handle ">
                                        <span class="name ">{{__('Created')}}: {{ \Carbon\Carbon::createFromTimeStamp(strtotime($qrCode->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}</span>
                                    </div>
                                    <div class="fact">
                                        <span class="name">{{__('Type')}}: {{ucwords($qrCode->type)}}</span>
                                    </div>
                                    @if($qrCode->code_type == 1)
                                        <div class="fact">
                                            <span class="name">{{__('Scans')}}: {{$qrCode->scans? $qrCode->scans: 0}}</span>
                                        </div>
                                    @endif
                                    @if($qrCode->campaign)
                                        <div class="campaign mb-sm-3">
                                            <i class="fa fa-folder-o"></i> {{$qrCode->campaign->name}}
                                        </div>
                                    @endif

                                    <div class="fixed-buttons-icon">
                                        @if(checkFieldStatus(8))
                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                    data-toggle="modal"
                                                    data-target="#download-model-{{ $qrCode->id }}"><i
                                                    class="fa fa-download"></i>
                                            </button>
                                        @endif
                                            @if(checkFieldStatus(9) && $qrCode->code_type == 1)
                                            <a class="btn btn-sm btn-secondary"
                                               href="{{route('frontend.user.qr-codes.statistics',Hashids::encode($qrCode->id))}}"><i
                                                    class="fa fa-bar-chart"></i></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Model Download -->
                    <div class="modal fade" id="download-model-{{ $qrCode->id }}">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{__('Download QR Code')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form
                                    action="{{ route('frontend.user.qr-codes.download',Hashids::encode($qrCode->id)) }}">
                                    @csrf
                                    @method('get')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <div class="input-group-prepend mb-3">
                                                                    <span class="input-group-text"
                                                                          id="inputGroupPrepend">{{__('File')}}</span>
                                                    </div>
                                                    <select class="custom-select custom-select-lg mb-3"
                                                            name="fileType">
                                                        <option value="png">PNG</option>
                                                        <option value="svg">SVG</option>
                                                        <option value="eps">EPS</option>
                                                        <option value="pdf">PDF</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <div class="range-slidecontainer">
                                                        <input type="range" step="25" min="100" max="2000" value="50%"
                                                               class="range-slider w-100 myRange"
                                                               name="size">
                                                        <div
                                                            class="d-flex justify-content-between values-labels">
                                                            <div class="low-quality">{{__('Small')}}</div>
                                                            <div class="ranges-value"><span
                                                                    class="height">1050</span> x <span
                                                                    class="width">1050</span> px</div>
                                                            <div class="high-quality">{{__('Big')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button
                                            type="submit" class="btn btn-primary">{{__('Download Image')}}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-light persist-alert text-center mb-0">{{__('No QR Codes found.')}}</div>
                </div>
            </div>
        @endif

        @if(checkFieldStatus(9))
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3 class="sub-title">{{__('Overall Scan Statistic')}}</h3>
                </div>
            </div>
            <div class="col-12">
                <div class="cardbox">
                    <div class="cardbox-head cardbox-inner">
                        <h5 class="title">{{__('Last 30 Days')}}</h5>
                    </div>
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                    <!-- <div class="cardbox-inner charts">
                        <div id="chartContainer" style="height: 500px; width: 100%;"></div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3 class="sub-title">{{__('Latest Scans')}}</h3>
                </div>
            </div>
            <div class="col-12">
                <div class="cardbox ">
                    <div class="table-responsive">
                        <table class="qr-code-table table table-hover">
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
                            @endphp
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
    </div>

@endsection

@section('js')
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function(){
            var labels = [
                <?php
                foreach ($labels as $label) {
                    echo '"' . $label . '",';
                }
                ?>
            ];
            var data = {{ $values }};

            var ctx = document.getElementById ('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
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
        CanvasJS.addColorSet("blueShades", ["#0645A4"]);

        var chart = new CanvasJS.Chart("chartContainer", {
            colorSet: "blueShades",
            animationEnabled: true,
            title: {
                text: '{{__('Total Scans')}}'
            },
            axisY: {
                title: '{{__('Scans')}}'
            },
            data: [{
                type: "column",
                showInLegend: true,
                legendText: '{{__('Date')}}',
                dataPoints: [
                    <?php
                    foreach ($dataPoints as $date => $scans) {
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
