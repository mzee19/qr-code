@extends('admin.layouts.app')

@section('title', 'Users')
@section('sub-title', 'Subscriptions')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/users')}}"><i class="fa fa-user"></i>Users</a></li>
			<li>Subscriptions</li>
		</ul>
	</div>
	<div class="container-fluid">

		@include('admin.messages')
		{{-- @if(have_right(87)) --}}
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Filter</h3>
			</div>
			<div class="panel-body">
				<form id="subscription-filter-form" class="form-inline filter-form-des" method="GET">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8">
							<div class="form-group">
								<input type="text" name="search" id="search" class="form-control"
									placeholder="Search by Package">
							</div>
						</div>
						<div class="col-auto ml-auto">
							<a href="{{url('admin/users/subscriptions/'.$id)}}">
								<button type="button" class="btn cancel btn-fullrounded">
									<span>Reset</span>
								</button>
							</a>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<button type="submit" class="btn btn-primary btn-fullrounded btn-apply">
								<span>Apply</span>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		{{-- @endif --}}

		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Subscriptions</h3>
				<div class="right">
					<span class="label label-default" style="font-size: 90%;">{{$user->name.' - '.$user->email}}</span>
				</div>
			</div>
			<div class="panel-body">
				<table id="subscriptions-datatable" class="table  table-hover " style="width:100%">
					<thead>
						<tr>
							<th>Id</th>
							<th>Package</th>
							<th>Type</th>
							<th>Price</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END DATATABLE -->
	</div>
</div>
@endsection

@section('js')
<script>
	$(function()
    {
		$('#subscriptions-datatable').dataTable(
		{
			pageLength: 50,
			scrollX: true,
			processing: false,
			language: { "processing": showOverlayLoader()},
			drawCallback : function( ) {
		        hideOverlayLoader();
		    },
			responsive: true,
			// dom: 'Bfrtip',
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
			searching: false,
			// ajax: "{{url('admin/users/subscriptions/'.$id)}}",
			ajax: {
				url: 'admin/users/subscriptions/'.$id,
				data: function (d) {
					d.search = $('#search').val();
				}
			},
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'package_title', name: 'package_title'},
				{data: 'type', name: 'type'},
				{data: 'price', name: 'price'},
				{data: 'start_date', name: 'start_date'},
				{data: 'end_date', name: 'end_date'},
				{data: 'status', name: 'status'},
			]
		}).on( 'length.dt', function () {
			showOverlayLoader();
		}).on('page.dt', function () {
	        showOverlayLoader();
	    }).on( 'order.dt', function () {
		    showOverlayLoader();
		}).on( 'search.dt', function () {
    		showOverlayLoader();
		});

		$('#subscription-filter-form').on('submit', function (e) {
			e.preventDefault();
			$('#subscriptions-datatable').DataTable().draw();
		});
	});
</script>
@endsection