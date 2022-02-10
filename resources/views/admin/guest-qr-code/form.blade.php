@extends('admin.layouts.app')

@section('title', 'Guest Qr Code')
@section('sub-title', $action.' Guest Qr Code')
@section('content')
    <div class="main-content">
        <div class="content-heading clearfix">

            <ul class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{{url('admin/guest-qr-code')}}"><i class="fa fa-question"></i> Guest Qr Code</a></li>
                <li>{{$action}}</li>
            </ul>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{$action}} Guest Qr Code</h3>
                        </div>
                        <div class="panel-body">
                            <form id="faqs-form" class="form-horizontal label-left">
                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Ip Address</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly
                                               value="{{$guestQrCode->ip_address}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-9">
                                        <div style="width: 100px; height: auto">
                                            <img
                                                src="{{checkImage(asset('storage/temp/' . $guestQrCode->image),'placeholder.png',$guestQrCode->image)}}"
                                                class="img-responsive" alt="" id="image">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Type</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly
                                               value="{{$guestQrCode->type}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Browser</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly
                                               value="{{$guestQrCode->browser}}">
                                    </div>
                                </div>
                                {{--                            <div class="form-group">--}}
                                {{--                                <label for="question" class="col-sm-3 control-label">City</label>--}}
                                {{--                                <div class="col-sm-9">--}}
                                {{--                                    <input class="form-control" readonly--}}
                                {{--                                           value="{{$guestQrCode->city}}">--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly
                                               value="{{$guestQrCode->country}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">Platform</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" readonly
                                               value="{{$guestQrCode->platform}}">
                                    </div>
                                </div>
                                {{--                            <div class="form-group">--}}
                                {{--                                <label for="question" class="col-sm-3 control-label">Device</label>--}}
                                {{--                                <div class="col-sm-9">--}}
                                {{--                                    <input class="form-control" readonly--}}
                                {{--                                           value="{{$guestQrCode->device}}">--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}

                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">QR Code Config Data</label>
                                    <div class="col-sm-9">
                                        @php
                                            $configData = json_decode($guestQrCode->config);
                                        @endphp
                                        @foreach($configData as $index => $config)
                                            @if($index == 'config')
                                                @foreach($config as $key =>$data)
                                                    <small>{{$key}}</small>
                                                    <strong>{!! $data !!}</strong>
                                                @endforeach()
                                            @else
                                            <small>{{$index}}</small>
                                                <strong>{{ $config }}</strong>

                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="question" class="col-sm-3 control-label">QR Code Data</label>
                                    <div class="col-sm-9">
                                        <p>{!! $guestQrCode->data !!}</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#faqs-form').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: true,

                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');
                    $(e).remove();
                },
                errorPlacement: function (error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1)
                            controls.append(error);
                        else
                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element);
                },
                invalidHandler: function (form, validator) {
                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
                    }, 500);
                },
                submitHandler: function (form, validator) {
                    if ($(validator.errorList).length == 0) {
                        document.getElementById("page-overlay").style.display = "block";
                        return true;
                    }
                }
            });


            // summernote editor
            $('.summernote').summernote(
                {
                    height: 300,
                    focus: true,
                    onpaste: function () {
                        alert('You have pasted something to the editor');
                    },
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['insert', ['link', 'picture', 'video']],
                        ['para', ['ol', 'ul', 'paragraph']],
                        ['table', ['table']],
                        ['view', ['undo', 'redo', 'fullscreen', 'codeview']]
                    ]
                });
        });


    </script>
@endsection
