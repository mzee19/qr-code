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
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{$action}} Qr Code</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal label-left">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input name="name" class="form-control"
                                               value="{{$qrCode->name}}" readonly="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-sm-3 control-label">Content Type</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control"
                                               value="{{ ucwords($qrCode->type)}}" readonly="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-sm-3 control-label">Created At</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"
                                               value="{{ $qrCode->created_at}}" readonly="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Type</label>
                                    <div class="col-sm-9">
                                        @if($qrCode->code_type == 1)
                                            <span class="label label-success">Dynamic</span>
                                        @else
                                            <span class="label label-danger">Static</span>
                                        @endif
                                    </div>
                                </div>
                                @if($qrCode->code_type == 1)
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Short Url</label>
                                        <div class="col-sm-9">
                                            {{isset($qrCode->ned_link) ? $qrCode->ned_link : $qrCode->short_url}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Scans</label>
                                        <div class="col-sm-9">
                                            {{$qrCode->scans ? $qrCode->scans : 0}}
                                        </div>
                                    </div>

                                @endif
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">QR Code</label>
                                    <div class="col-sm-9">
                                        <img src="{{checkImage(asset('storage/users/' . $qrCode->user_id.'/qr-codes/'.$qrCode->image),'placeholder.png',$qrCode->image)}}">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="{{url('admin/users/'.Hashids::encode($qrCode->user_id).'/qr-codes')}}">
                                        <button type="button" class="btn btn-primary btn-fullrounded">
                                            <span>Back</span>
                                        </button>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
