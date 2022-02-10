@extends('admin.layouts.app')

@section('title', 'QR Code Templates')
@section('sub-title', $action.' QR Code Template')
@section('content')
    <style>
        .d-flex {
            display: flex;
        }
    </style>
    <div class="main-content">
        <div class="content-heading clearfix">

            <ul class="breadcrumb">
                <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="{{url('admin/qr-code-templates')}}"><i class="fa fa-qrcode"></i> QR Code Templates</a></li>
                <li> {{$action}}</li>
            </ul>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{$action}} Qr Code Template</h3>
                        </div>
                        <div class="panel-body">
                            @include('admin.messages')
                            <form id="qr-code-form" class="form-horizontal label-left "
                                  action="{{url('admin/qr-code-templates')}}"
                                  enctype="multipart/form-data" method="POST">
                                @csrf

                                <input type="hidden" name="action" value="{{$action}}"/>
                                <input type="hidden" name="contentType" value="1"/>
                                <input type="hidden" name="type" value="url"/>
                                <input name="id" type="hidden" value="{{ $model->id }}"/>

                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name*</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" maxlength="20" class="form-control" required=""
                                               value="{{ ($action == 'Add') ? old('name') : $model->name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">URL*</label>
                                    <div class="col-sm-9">
                                        <input type="url" name="qrcodeUrl" maxlength="250" class="form-control"
                                               required=""
                                               value="{{ ($action == 'Add') ? old('url') : $model->data}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-9">
                                        @php $status = ($action == 'Add') ? old('status') : $model->status @endphp
                                        <label class="fancy-radio">
                                            <input name="status" value="1" type="radio"
                                                {{ ($status == 1) ? 'checked' : '' }}>
                                            <span><i></i>Active</span>
                                        </label>
                                        <label class="fancy-radio">
                                            <input name="status" value="0" type="radio"
                                                {{ ($status == 0) ? 'checked' : '' }}>
                                            <span><i></i>Disable</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="outerBox">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Customize
                                                Design</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse">
                                        <div class="panel-body Box-iner">
                                            <div class="row">
                                                <h4>Body Shape</h4>
                                                <div class="col-md-12">
                                                <div class="form-group presets clearfix ">
                                                        @foreach($shapes as $index => $shape)
                                                            <div
                                                                class="item body-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($model->config, true)['body'] == $shape->name ? 'active' : '') }}"
                                                                id="shapeName{{$shape->name}}"
                                                                onclick="getShapeName('{{$shape->name}}')">
                                                                <img
                                                                    src="{{ asset('storage/shapes/'.$shape->image) }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h4>Eye Frame Shape</h4>
                                                <div class="col-md-12">
                                                    <div class="form-group presets clearfix ">
                                                        @foreach($eyeFrames as $index => $eyeFrame)
                                                            <div
                                                                class="item eye-frame {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($model->config, true)['frame'] == $eyeFrame->name ? 'active' : '') }}"
                                                                id="frameName{{$eyeFrame->name}}"
                                                                onclick="getFrameName('{{$eyeFrame->name}}')">
                                                                <img
                                                                    src="{{ asset('storage/shapes/'.$eyeFrame->image) }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h4>Eye Ball Shape</h4>
                                                <div class="col-md-12">
                                                    <div class="form-group presets clearfix ">
                                                        @foreach($eyeBallShapes as $index => $eyeBallShape)
                                                            <div
                                                                class="item eye-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($model->config, true)['eyeBall'] == $eyeBallShape->name ? 'active' : '') }}"
                                                                id="eyeShape{{$eyeBallShape->name}}"
                                                                onclick="getEyeBallName('{{$eyeBallShape->name}}')">
                                                                <img
                                                                    src="{{ asset('storage/shapes/'.$eyeBallShape->image) }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="outerBox">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Set
                                                Color</a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body Box-iner">
                                            <label>Foreground Color</label>
                                            <div>
                                                <div class="form-check form-check-inline" id="single">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input radio"
                                                            {{--                                                                           name="customColorMode"--}}
                                                            type="radio" value="single"
                                                            onclick="foregroundColor('single')" checked>
                                                        Single Color
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline" id="gradient">
                                                    <label class="form-check-label">
                                                        <input
                                                            class="form-check-input ng-valid ng-dirty ng-touched radio"
                                                            {{--                                                                                                                                                name="customColorMode"--}}
                                                            type="radio"
                                                            value="gradient"
                                                            onclick="foregroundColor('gradient')"> Gradient
                                                        Color
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline"
                                                   >
                                                    <label class="form-check-label">
                                                        <input id="custom-eye-color"
                                                            class="form-check-input ng-untouched ng-pristine ng-valid"
                                                            type="checkbox"  onclick="eyeColorStatus()"> Custom Eye Colors
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex pt-3">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input class="form-control" type="color"
                                                            id="colorOne"
                                                            {{--                                                                           name="color-picker-1"--}}
                                                            value="{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['gradientColor1']}}"
                                                            onchange="colorPicker(this,1)">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4 hide-show">
                                                    <div class="form-group ">
                                                        <input class="form-control" type="color"
                                                            id="colorTwo"
                                                            {{--                                                                           name="color-picker-2"--}}
                                                            value="{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['gradientColor2']}}"
                                                            onchange="colorPicker(this,2)">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3 hide-show">
                                                    <div class="input-group form-group" style="display: -webkit-box;">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-secondary"
                                                                    style="border-radius: 0px;"
                                                                    onclick="colorOverlap(1)">
                                                                <i class="fa fa-exchange"></i>
                                                            </button>
                                                        </span>
                                                        @php
                                                         $gradientType = ($action == 'Add') ? 'vertical' : json_decode($model->config, true)['gradientType'];
                                                        @endphp
                                                        <select class="form-control"
                                                                onchange="colorSchema(this)">
                                                            <option value="vertical" {{$gradientType == 'vertical' ? 'selected' : ''}}>Vertical</option>
                                                            <option value="radial" {{$gradientType == 'radial' ? 'selected' : ''}}>Radial</option>
                                                            <option value="horizontal" {{$gradientType == 'horizontal' ? 'selected' : ''}}>Horizontal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="custom-colorpicker d-flex pt-3 eye-hide-show">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input class="" type="color"
                                                            id="colorThree"
                                                            {{--                                                                           name="eye-frame-color-picker"--}}
                                                            value="{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['eye1Color']}}"
                                                            onchange="colorPicker(this,3)">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input class="" type="color"
                                                            id="colorFour"
                                                            {{--                                                                           name="eye-ball-color-picker"--}}
                                                            value="{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['eyeBall1Color']}}"
                                                            onchange="colorPicker(this,4)">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="input-group form-group">
                                                        <div class="input-group-btn image-upload-btn">
                                                            <button class="btn btn-secondary"
                                                                    style="border-radius: 0px"
                                                                    type="button" onclick="colorOverlap(2)">
                                                                <i class="fa fa-exchange"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-group-btn flex-button image-upload-btn">
                                                            <button class="btn btn-secondary" type="button"
                                                                    onclick="colorOverlap(3)"
                                                                    style="border-radius: 0px"> Copy
                                                                Foreground
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex pt-3">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group input-group">
                                                        <input class="form-control" type="color"
                                                            value="{{ ($action == 'Add') ? '#ffffff' : json_decode($model->config, true)['bodyColor']}}"
                                                            onchange="colorPicker(this,5)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="outerBox">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Add
                                                Logo Image</a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse">
                                        <div class="panel-body Box-iner">
                                            <div class="row">
                                                <h4>Add Logo</h4>
                                                <div class="col-md-12">
                                                    <div class="form-group presets clearfix d-flex">
                                                        @foreach($logos as $index => $logo)
                                                            <div
                                                                class="item logo-image {{($action == 'Add') ? '' : (json_decode($model->config, true)['logo'] == $logo->id ? 'active' : '') }}"
                                                                id="logoImage{{$logo->id}}"
                                                                onclick="getLogoId('{{$logo->id}}')">
                                                                <img
                                                                    src="{{ asset('storage/logos/'.$logo->image) }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <br>
                                    <div class="text-right">
                                        <a href="{{url('admin/qr-code-templates')}}">
                                            <button type="button" class="btn cancel btn-fullrounded">
                                                <span>Cancel</span>
                                            </button>
                                        </a>
                                        <button type="button" class="btn btn-primary btn-fullrounded" id="savebtn"
                                                onclick="saveQrCode(this)" {{($action == 'Add') ? 'disabled' : ''}}>
                                            <span>Save</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-sm-9">
                                <div style="height: auto" id="generated-qr-code">
                                    <img
                                        src="{{checkImage(asset('storage/admin-qr-codes/' . $model->image),'placeholder.png',$model->image)}}"
                                        class="img-responsive" alt="" id="image">
                                </div>
                                <div class="fa-5x mt-4" id="loading" style="display: none">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <br>
                                <button type="button" class="btn btn-success btn-fullrounded"
                                        onclick="makeQrCode(this)">Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
    <script>
        let shapeName = '{{ ($action == 'Add') ? 'square' : json_decode($model->config, true)['body']}}';
        let frameName = '{{ ($action == 'Add') ? 'square' : json_decode($model->config, true)['frame']}}';
        let eyeName = '{{ ($action == 'Add') ? 'square' : json_decode($model->config, true)['eyeBall']}}';
        let eyeStatus = '{{ ($action == 'Add') ? false : (json_decode($model->config, true)['eyeStatus'] ? true : false)}}';
        let colorOne = '{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['gradientColor1']}}';
        let colorTwo = '{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['gradientColor2']}}';
        var frameColor = '{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['eye1Color']}}';
        var eyeBallColor = '{{ ($action == 'Add') ? '#000000' : json_decode($model->config, true)['eyeBall1Color']}}';
        let bodyColor = '{{ ($action == 'Add') ? '#ffffff' : json_decode($model->config, true)['bodyColor']}}';
        let colorStructure = '{{ ($action == 'Add') ? 'vertical' : json_decode($model->config, true)['gradientType']}}';
        let imageId = '';
            {{--let logoId = '{{ ($action == 'Add') ? 0 : json_decode($model->config, true)['logo']}}';--}}
        let logoId = '{{ ($action == 'Add') ? 'vertical' : json_decode($model->config, true)['logo']}}';
        let colorType = '{{($action == 'Add') ? true : json_decode($model->config, true)['colorType']}}';
        let formData = '';

        $(document).ready(function () {
            $('.hide-show').hide();
            $('.eye-hide-show').hide();

            let foregroundColorCheck = '{{($action == 'Add') ? 'single' : (json_decode($model->config, true)['colorType'] == 1 ? 'single': 'gradient')}}';
            let eyeStatusCheck = '{{($action == 'Add') ? '' : (json_decode($model->config, true)['eyeStatus'] ? true: false)}}';
            if (foregroundColorCheck) {
                foregroundColor(foregroundColorCheck)
            }
            if (eyeStatusCheck) {
                eyeStatus = false
                eyeColorStatus()
            }
        });
        $(function () {
            $('#qr-code-form').validate({
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
        });

        //      Get and active shape
        function getShapeName(selectedShapeName) {
            let shapeNameId = '#shapeName' + selectedShapeName;
            $('.body-shape').removeClass('active');
            $(shapeNameId).addClass('active');
            shapeName = selectedShapeName;
        }

        //      Get and active frame
        function getFrameName(selectedFrameName) {
            let frameNameId = '#frameName' + selectedFrameName;
            $('.eye-frame').removeClass('active');
            $(frameNameId).addClass('active');
            frameName = selectedFrameName;
        }

        //      Get and active eye
        function getEyeBallName(selectedEyeBallName) {
            let eyeShapeId = '#eyeShape' + selectedEyeBallName;
            $('.eye-shape').removeClass('active');
            $(eyeShapeId).addClass('active');
            eyeName = selectedEyeBallName;
        }

        //      Upload Logo Image
        function addLogoImage(ele) {
            readURL(ele);
        }

        //      Check Radio button on Color selection like single or gardient
        function foregroundColor(selectedColorType) {
            $("input[value='gradient']").prop('checked',false)
            $("input[value='single']").prop('checked',false)
            $("input[value='"+selectedColorType+"']").prop('checked',true)
            $('.radio').change(function () {
                $('.radio').not(this).prop('checked', false);
            });
            if (selectedColorType == 'single') {
                colorType = true;
                $('.hide-show').hide()
            }
            if (selectedColorType == 'gradient') {
                colorType = false;
                $('.hide-show').show()

            }
        }

        //      Display Eye show or hide
        function eyeColorStatus() {

            if (!eyeStatus) {
                $('.eye-hide-show').show();
                eyeStatus = true;
            } else {
                $('.eye-hide-show').hide();
                eyeStatus = false;
            }
            $("#custom-eye-color").prop("checked", eyeStatus);

        }

        //      Pick up the color and store in variables
        function colorPicker(ele, id) {
            switch (id) {
                case 1:
                    colorOne = ele.value;
                    break;
                case 2:
                    colorTwo = ele.value;
                    break;
                case 3:
                    frameColor = ele.value;
                    break;
                case 4:
                    eyeBallColor = ele.value;
                    break;
                case 5:
                    bodyColor = ele.value;
                    break;
            }
        }

        //      Pick up the color type etc varticle,radial...
        function colorSchema(ele) {
            colorStructure = ele.value;
        }

        //      get and active logo
        function getLogoId(id) {
            //Add Active class
            let logoImageId = '#logoImage' + id;
            $('.logo-image').removeClass('active');
            $(logoImageId).addClass('active');
            //update image src
            $('#upload-logo-image').val(null);
            let firstImageId = '#' + id;
            $(".file-upload-image").attr("src", $(firstImageId).attr('src'));
            //hide button and show image
            $('.file-upload-content').css('display', 'block');
            $('.image-upload-wrap').css('display', 'none');
            logoId = id;

            makeQrCode(this)
        }


        //      Color values overlap
        function colorOverlap(index) {
            let singleColor = $('#colorOne').val();
            let gradientColor = $('#colorTwo').val();
            switch (index) {
                case 1:
                    $('#colorOne').val(gradientColor);
                    $('#colorTwo').val(singleColor);
                    colorOne = gradientColor;
                    colorTwo = singleColor;
                    break;
                case 2:
                    let eyeColor = $('#colorThree').val();
                    let frameColorValue = $('#colorFour').val();
                    $('#colorThree').val(frameColorValue);
                    $('#colorFour').val(eyeColor);
                    frameColor = frameColorValue;
                    eyeBallColor = eyeColor;
                    break;
                case 3:
                    let getFrameColor = colorType ? singleColor : gradientColor;
                    $('#colorThree').val(singleColor);
                    $('#colorFour').val(getFrameColor);
                    frameColor = singleColor;
                    eyeBallColor = getFrameColor;
                    break;
            }
        }

        //      Generate QR Code
        function makeQrCode(ele) {

            if ($('#qr-code-form').valid()) {
                formData = new FormData(document.getElementById("qr-code-form"));
                let data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
                        "eyeBall": eyeName,
                        "bodyColor": bodyColor,
                        "bgColor": bodyColor,
                        "eye1Color": eyeStatus ? frameColor : '#000000',
                        "eye2Color": eyeStatus ? frameColor : '#000000',
                        "eye3Color": eyeStatus ? frameColor : '#000000',
                        "eyeBall1Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall2Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall3Color": eyeStatus ? eyeBallColor : '#000000',
                        "gradientColor1": colorOne,
                        "gradientColor2": colorType ? colorOne : colorTwo,
                        "colorType": colorType,
                        "eyeStatus": eyeStatus,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 300,
                    "download": false,
                    "file": "svg"
                };
                formData.append('config', JSON.stringify(data));
                formData.append('eyeStatus', eyeStatus);

                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('frontend.qr.code.generator') }}",
                    data: formData,
                    enctype: 'multipart/form-data',
                    beforeSend: function () {
                        $('#loading').show();
                        $('#generated-qr-code').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                        $('#generated-qr-code').show();
                        $('#savebtn').prop('disabled', false);
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            $('#generated-qr-code').empty();
                            $('#generated-qr-code').append(response.html);
                            imageId = response.image_id;
                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
        }
        function generateQRCode(ele) {
            //Get Active Form Id
            let formName = $('#selectedType').val();
            //End

            //Start show the selected content Type
            $(selectedCollapse).addClass('show')
            // End

            // Page reload make Qr Code for Current URl and Validate form Data
            if (ele == true || $('form[name="' + formName + '"]').valid()) {
                let formData = new FormData($('form[name="' + formName + '"]')[0]);
                // Page Reload
                if (ele == true) {
                    formData.append('qrcodeUrl', window.location.href)
                }
                //Config data
                let data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
                        "eyeBall": eyeName,
                        "bodyColor": bodyColor,
                        "bgColor": bodyColor,
                        "eye1Color": eyeStatus ? frameColor : '#000000',
                        "eye2Color": eyeStatus ? frameColor : '#000000',
                        "eye3Color": eyeStatus ? frameColor : '#000000',
                        "eyeBall1Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall2Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall3Color": eyeStatus ? eyeBallColor : '#000000',
                        "gradientColor1": colorOne,
                        "gradientColor2": colorType ? colorOne : colorTwo,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 300,
                    "download": false,
                    "file": "svg"
                };
                // Get uploaded Image
                var fileToUpload = $('#upload-logo-image').prop('files')[0];

                formData.append('config', JSON.stringify(data));
                formData.append('logo_image', fileToUpload);
                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('frontend.qr.code.generator') }}",
                    data: formData,
                    enctype: 'multipart/form-data',
                    beforeSend: function () {
                        $('#qrCodeImageAppend').hide();
                        $('#loading').show();
                    },
                    complete: function () {
                        $('#loading').hide();
                        $('#qrCodeImageAppend').show();
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            $('#qrCodeImageAppend').empty();
                            $('#qrCodeImageAppend').append(response.html);
                            imageName = response.image_id;
                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
        }

        //      Laravel Validation Message
        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li id=' + key + '>' + value + '</li>');
                removeErrorMessage(key)
            });
        }

        //      Remove laravel error message after sometime
        function removeErrorMessage(key) {
            let index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');
                $(".print-error-msg").css('display', 'none');
            }, 5000);
        }

        //      Save QR Code
        function saveQrCode(ele) {
            if ($('#qr-code-form').valid()) {
                formData = new FormData(document.getElementById("qr-code-form"));

                let data = {
                    "config": {
                        "body": shapeName,
                        "frame": frameName,
                        "eyeBall": eyeName,
                        "bodyColor": bodyColor,
                        "bgColor": bodyColor,
                        "eye1Color": eyeStatus ? frameColor : '#000000',
                        "eye2Color": eyeStatus ? frameColor : '#000000',
                        "eye3Color": eyeStatus ? frameColor : '#000000',
                        "eyeBall1Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall2Color": eyeStatus ? eyeBallColor : '#000000',
                        "eyeBall3Color": eyeStatus ? eyeBallColor : '#000000',
                        "gradientColor1": colorOne,
                        "gradientColor2": colorType ? colorOne : colorTwo,
                        "colorType": colorType,
                        "eyeStatus": eyeStatus,
                        "gradientType": colorStructure,
                        "gradientOnEyes": false,
                        "logo": logoId
                    },
                    "size": 300,
                    "download": false,
                    "file": "svg"
                };
                formData.append('config', JSON.stringify(data));
                formData.append('image_id', imageId);

                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.qr-code-templates.store') }}",
                    data: formData,
                    enctype: 'multipart/form-data',
                    success: function (response) {
                        if (response.status == 1) {
                            window.location.replace(response.url)
                        } else {
                            printErrorMsg(response.message);
                        }
                    }
                });
            }
        }
    </script>
@endsection
