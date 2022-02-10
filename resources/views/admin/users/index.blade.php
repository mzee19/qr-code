@extends('admin.layouts.app')
@section('title', 'Users')
@section('sub-title', 'Listing')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Users</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Users Listing</h3>
				@if(have_right(5))
				<div class="right">
					<a href="{{url('admin/users/create')}}" class="pull-right">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Add</span>
						</button>
					</a>
				</div>
				@endif
			</div>
			<div class="panel-body">
				<table id="users-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<!-- <th>Id</th> -->
							<th>Name</th>
							<th>Username</th>
							<th>Email</th>
                            <th>Platform</th>
							<th>Approval Status</th>
							<th>Status</th>
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
		var language_code = "{{ $language_code }}";
		var package_id = "{{ $package_id }}";
    	var url = "{{ url('admin/users') }}";

    	if(language_code != '')
    	{
    		url += '?language_code='+language_code;
    	}
		else if(package_id != '')
    	{
    		url += '?package_id='+package_id;
    	}

		$('#users-datatable').dataTable(
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
				{data: 'username', name: 'username'},
				{data: 'email', name: 'email'},
				{data: 'platform', name: 'platform'},
				{data: 'is_approved', name: 'is_approved'},
				{data: 'status', name: 'status'},
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
