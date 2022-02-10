@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('sub-title', 'Overview & Statistics')

@section('content')
    <div class="main-content">
        <div class="content-heading clearfix">

        </div>
        <div class="container-fluid">
            @include('admin.messages')
            <div class="row">
                <div class="col-md-12">
                    <!-- OVERVIEW -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Overview</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row margin-bottom-30">
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-user"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $users }}</span>
                                            <span class="title">Users</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i
                                                class="fa fa-qrcode"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $qrcode_templates }}</span>
                                            <span class="title">QR Code Templates</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-list"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $packages }}</span>
                                            <span class="title">Packages</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-credit-card"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $payments }}</span>
                                            <span class="title">Received Payment (€) </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom-30">
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-question"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $faqs }}</span>
                                            <span class="title">FAQs</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-envelope"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $email_templates }}</span>
                                            <span class="title">Email Templates</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i
                                                class="fa fa-file-text-o"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $cms_pages }}</span>
                                            <span class="title">CMS Pages</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <!-- fa fa-th-large -->
                                        <span class="icon-wrapper custom-bg-blue"><i
                                                class="fa fa-user-secret"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $roles }}</span>
                                            <span class="title">Roles</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row margin-bottom-30">
                                <div class="col-md-3 col-xs-6">
                                    <div class="widget-metric_6 animate">
                                        <span class="icon-wrapper custom-bg-blue"><i class="fa fa-user"></i></span>
                                        <div class="right">
                                            <span class="value">{{ $admins }}</span>
                                            <span class="title">Admin Users</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OVERVIEW -->
                </div>
            </div>
            @if(have_right(15))
                @if(count($deleted_users) > 0)
                <!-- persist-alert class is added in the below div to separate it from flash messages alert -->
                    <div class="alert alert-danger persist-alert" role="alert">
                        <center>
                            Following users will be deleted on specific deletion datetime
                        </center>
                    </div>
                @endif
                    @if(have_right(72))
                    <div class="row">
                        <div class="col-md-12">
                            <!-- DATATABLE -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Users Listing</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="users-deleted-datatable" class="table table-hover " style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Deletion DateTime</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($deleted_users as $user)
                                            <tr>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{\Carbon\Carbon::createFromTimeStamp(strtotime($user->deleted_at), "UTC")->addDays(settingValue('user_deletion_days'))->tz(session('timezone'))->format('d M, Y h:i:s a')}}
                                                </td>
                                                <td>
                                                    <span class="label label-danger">Deleted</span>
                                                </td>
                                                <td>
										<span class="actions">
											@if(have_right(7))
                                                <a class="btn btn-primary" title="Edit" target="_blank"
                                                   href="{{url('admin/users/' . Hashids::encode($user->id) . '/edit')}}"><i
                                                        class="fa fa-pencil-square-o"></i></a>
                                            @endif
                                            @if(have_right(8))
                                                <form method="POST"
                                                      action="{{url('admin/users/'.Hashids::encode($user->id)) }}"
                                                      accept-charset="UTF-8" style="display:inline">
												<input type="hidden" name="_method" value="DELETE">
												<input type="hidden" name="page" value="dashboard">
												<input name="_token" type="hidden" value="{{csrf_token()}}">
												<button class="btn btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this record?');">
													<i class="fa fa-trash"></i>
												</button>
											</form>
                                            @endif
										</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END DATATABLE -->
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            if ($("#users-deleted-datatable").length) {
                $('#users-deleted-datatable').dataTable(
                    {
                        pageLength: 50,
                        scrollX: true,
                        responsive: true,
                        //dom: 'Bfrtip',
                        lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                        language: {"processing": showOverlayLoader()},
                        drawCallback: function () {
                            hideOverlayLoader();
                        },
                    }).on('length.dt', function () {
                    showOverlayLoader();
                }).on('page.dt', function () {
                    showOverlayLoader();
                }).on('order.dt', function () {
                    showOverlayLoader();
                }).on('search.dt', function () {
                    showOverlayLoader();
                });
            }

            if ($("#vouchers-datatable").length) {
                $('#vouchers-datatable').dataTable(
                    {
                        searching: false,
                        // pageLength: 50,
                        scrollX: true,
                        bPaginate: false,
                        bInfo: false,
                        processing: false,
                        language: {"processing": showOverlayLoader()},
                        drawCallback: function () {
                            hideOverlayLoader();
                        },
                        responsive: true,
                        dom: 'Bfrtip',
                        // lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                        serverSide: true,
                        ajax: {
                            url: '/admin/dashboard/orders'
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            {data: 'id', name: 'id'},
                            {data: 'user_name', name: 'user_name'},
                            {data: 'user_email', name: 'user_email'},
                            {data: 'product', name: 'product'},
                            {data: 'main_project', name: 'main_project'},
                            {data: 'secondary_projects', name: 'secondary_projects'},
                            {data: 'discount_percentage', name: 'discount_percentage'},
                            {data: 'quantity', name: 'quantity'},
                            {data: 'per_unit_price', name: 'per_unit_price'},
                            {data: 'amount', name: 'amount'},
                            {data: 'vat_percentage', name: 'vat_percentage'},
                            {data: 'vat_amount', name: 'vat_amount'},
                            {data: 'total_amount', name: 'total_amount'},
                            {data: 'created_at', name: 'created_at'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    }).on('length.dt', function () {
                    showOverlayLoader();
                }).on('page.dt', function () {
                    showOverlayLoader();
                }).on('order.dt', function () {
                    showOverlayLoader();
                }).on('search.dt', function () {
                    showOverlayLoader();
                });
            }
        });
    </script>
@endsection
