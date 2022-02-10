@extends('admin.layouts.app')

@section('title', 'Logos')
@section('sub-title', 'Listing')
@section('content')
    <div class="main-content">
        <div class="content-heading clearfix">
            <ul class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
                <li>Logos</li>
            </ul>
        </div>
        <div class="container-fluid">
        @include('admin.messages')
        <!-- DATATABLE -->
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Logos Listing</h3>
                    @if(have_right(21))
                        <div class="right">
                            <a href="{{url('admin/logos/create')}}" class="pull-right">
                                <button type="button" title="Add" class="btn btn-primary btn-lg btn-fullrounded">
                                    <span>Add</span>
                                </button>
                            </a>
                        </div>
                    @endif

                </div>
                <div class="panel-body">
                    <table id="logos-datatable" class="table table-hover " style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
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
        $(function () {
            $('#logos-datatable').dataTable(
                {
                    pageLength: 50,
                    scrollX: true,
                    processing: false,
                    language: {"processing": showOverlayLoader()},
                    drawCallback: function () {
                        hideOverlayLoader();
                    },
                    responsive: true,
                    // dom: 'Bfrtip',
                    lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
                    serverSide: true,
                    ajax: "{{ route('admin.logos.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'image', name: 'image', orderable: false, searchable: false},
                        {data: 'title', name: 'title'},
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
        });
    </script>
@endsection
