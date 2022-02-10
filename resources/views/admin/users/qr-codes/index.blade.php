@extends('admin.layouts.app')
@section('title', 'Qr Codes')
@section('sub-title', 'Qr Codes Listing')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/users')}}"><i class="fa fa-home"></i> Users</a></li>
			<li>QR Codes</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">QR Codes Listing</h3>

			</div>
			<div class="panel-body">
				<table id="qrcodes-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Content Type</th>
							<th>Type</th>
							<th>Created At</th>
							<th>Image</th>
							<th>Actions</th>
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

    	var url = "{{ route('admin.users.qr.codes',$id) }}";

		$('#qrcodes-datatable').dataTable(
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
			ajax: url,
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				// {data: 'id', name: 'id'},
				{data: 'name', name: 'name'},
				{data: 'type', name: 'type'},
				{data: 'code_type', name: 'code_type'},
				// {data: 'package_title', name: 'package_title'},
				{data: 'created_at', name: 'created_at'},
				{data: 'image', name: 'image'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
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
	});
</script>
@endsection
