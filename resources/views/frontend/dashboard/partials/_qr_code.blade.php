<div class="cardbox">
    <div class="qrcode-container">
        <div class="qrcode">
            <img class="qr-code-scan" src="/images/qr-code.svg">
            <div class="loading-screen">
                <div class="loader">

                </div>
            </div>
        </div>
    </div>
    <div class="cardbox-inner">
        <button class="btn btn-primary w-100" type="button" data-toggle="modal"
                data-target="#design-qrcode">
            <i class="fa fa-paint-brush"></i> @lang('Design QR Code')
        </button>
    </div>
</div>
<!--------------------- Model Popups ------------------------->
<!-- Change Setting Popup -->
<div class="modal fade" id="change-setting">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Edit Short URL')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                                    <span
                                        class="input-group-addon">{{Request::getSchemeAndHttpHost().'/qr-code/'}}</span>
                            <input class="form-control" type="text"
                                   value="{{$action == 'Add' ? $parameters['unique_id'] : $generateQrCode->unique_id}}" readonly="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Change URL</button> -->
                </div>
            </form>

        </div>
    </div>
</div>

<!-- design qr code -->
<div class="modal fade design-qrcode-model" id="design-qrcode">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body pb-0">
                <div class="tab-section">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="classic-tab" data-toggle="tab" href="#classic"
                               role="tab" aria-controls="classic" aria-selected="true">{{__('Classic')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="static-tab" data-toggle="tab" href="#static" role="tab"
                               aria-controls="static" aria-selected="false"> {{__('Transparent')}} </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="templates-tab" data-toggle="tab" href="#templates"
                               role="tab" aria-controls="templates" aria-selected="false"> <i
                                    class="fa fa-star"></i> {{__('Templates')}} </a>
                        </li>
                    </ul>
                    <!-- Body -->
                    <div class="tabs-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="classic" role="tabpanel"
                             aria-labelledby="classic-tab">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div id="accordion">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <div data-toggle="collapse" data-target="#collapseOne"
                                                         aria-expanded="true" aria-controls="collapseOne">
                                                        <div class="pane-header">
                                                            <div class="icon"><i class="fa fa-paint-brush"
                                                                                 aria-hidden="true"></i></div>
                                                            <h3 class="title">{{__('Set Color')}}</h3>
                                                            <div class="plus"><i class="fa fa-plus"
                                                                                 aria-hidden="true"></i></div>
                                                            <div class="minus"><i class="fa fa-minus"
                                                                                  aria-hidden="true"></i></div>
                                                        </div>
                                                    </div>
                                                </h5>
                                            </div>

                                            <div id="collapseOne" class="collapse show"
                                                 aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="pane-content">
                                                        <label>{{__('Foreground Color')}}</label>
                                                        <div>
                                                            <div class="form-check form-check-inline"
                                                                 id="single">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input radio"
                                                                           {{--                                                                           name="customColorMode"--}}
                                                                           type="radio" value="single"
                                                                           onclick="foregroundColor('single')"
                                                                        {{ ($action == 'Add') ? 'checked' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'checked': '')}}>
                                                                        {{__('Single Color')}}
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline"
                                                                 id="gradient">
                                                                <label class="form-check-label">
                                                                    <input
                                                                        class="form-check-input ng-valid ng-dirty ng-touched radio"
                                                                        {{--                                                                                                                                                name="customColorMode"--}}
                                                                        type="radio"
                                                                        {{ ($action == 'Add') ? 'gradient' : (json_decode($generateQrCode->config, true)['colorType'] == 0 ? 'checked': '')}}
                                                                        value="gradient"
                                                                        onclick="foregroundColor('gradient')">
                                                                        {{__('Gradient Color')}}
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline"
                                                                 onclick="eyeColorStatus()">
                                                                <label class="form-check-label">
                                                                    <input
                                                                        {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? 'checked' : '')}}
                                                                        class="form-check-input ng-untouched ng-pristine ng-valid"
                                                                        type="checkbox"> {{__('Custom Eye Colors')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-3">
                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorOne"
                                                                           {{--                                                                           name="color-picker-1"--}}
                                                                           value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}"
                                                                           onchange="colorPicker(this,1)">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-4 hide-show ">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                        style="font-size: 17px;"
                                                                           id="colorTwo"
                                                                           {{--                                                                           name="color-picker-2"--}}
                                                                           value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}"
                                                                           onchange="colorPicker(this,2)">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-4 hide-show">
                                                                <div class="input-group form-group">
                                                                                <span class="input-group-btn">
                                                                                    <button class="btn btn-secondary"
                                                                                            style="border-radius: 0px;"
                                                                                            type="button"
                                                                                            onclick="colorOverlap(1)">
                                                                                        <i class="fa fa-exchange"></i>
                                                                                    </button>
                                                                                </span>
                                                                    @php

                                                                        @endphp
                                                                    <select class="form-control"
                                                                            onchange="colorSchema(this)">
                                                                        <option
                                                                            value="vertical" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'vertical' ? 'selected' : '') }}>
                                                                            {{__('Vertical')}}
                                                                        </option>
                                                                        <option
                                                                            value="radial" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'radial' ? 'selected' : '') }}>
                                                                            {{__('Radial')}}
                                                                        </option>
                                                                        <option
                                                                            value="horizontal" {{ ($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['gradientType'] == 'horizontal' ? 'selected' : '') }}>
                                                                            {{__('Horizontal')}}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-3 eye-hide-show">
                                                            <div class="col-12 col-sm-6 col-md-6">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorThree"
                                                                           {{--                                                                           name="eye-frame-color-picker"--}}
                                                                           value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}"
                                                                           onchange="colorPicker(this,3)">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-6">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           id="colorFour"
                                                                           {{--                                                                           name="eye-ball-color-picker"--}}
                                                                           value="{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}"
                                                                           onchange="colorPicker(this,4)">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6 col-md-6">
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-btn image-upload-btn">
                                                                        <button class="btn btn-secondary"
                                                                                style="border-radius: 0px"
                                                                                type="button"
                                                                                onclick="colorOverlap(2)">
                                                                            <i class="fa fa-exchange"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="input-group-btn flex-button image-upload-btn">
                                                                        <button class="btn btn-secondary"
                                                                                type="button"
                                                                                onclick="colorOverlap(3)"
                                                                                style="border-radius: 0px"> {{__('Copy Foreground')}}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-3">
                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                <div class="form-group input-group">
                                                                    <input class="form-control" type="color"
                                                                           value="{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}"

                                                                           {{--                                                                           name="bg-color-picker"--}}
                                                                           onchange="colorPicker(this,5)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo">
                                                <h5 class="mb-0">
                                                    <div class="collapsed" data-toggle="collapse"
                                                         data-target="#collapseTwo" aria-expanded="false"
                                                         aria-controls="collapseTwo">
                                                        <div class="pane-header">
                                                            <div class="icon"><i class="fa fa-picture-o"
                                                                                 aria-hidden="true"></i></div>
                                                            <h3 class="title">{{__('Add Logo Image')}}</h3>
                                                            <div class="plus"><i class="fa fa-plus"
                                                                                 aria-hidden="true"></i></div>
                                                            <div class="minus"><i class="fa fa-minus"
                                                                                  aria-hidden="true"></i></div>
                                                        </div>
                                                    </div>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="custom-file-upload">
                                                        <button class="file-upload-btn" type="button"
                                                                onclick="$('.file-upload-input').trigger( 'click' );">
                                                                {{__('Add Image')}}
                                                        </button>

                                                        <div class="image-upload-wrap">
                                                            <input class="file-upload-input" type="file"
                                                                   id="upload-logo-image"
                                                                   onchange="addLogoImage(this)"
                                                                   name="logo_image"
                                                                   style="pointer-events: none;"
                                                                   accept="image/png">
                                                                   {{__('Upload')}}
                                                        </div>
                                                        <div class="file-upload-content">
                                                            <img class="file-upload-image" src="#"
                                                                 alt="your image">
                                                            <div class="image-title-wrap">
                                                                <button type="button"
                                                                        onclick="removeImageInput()"
                                                                        class="remove-image">{{__('Remove')}} <span
                                                                        class="image-title">{{__('Uploaded Image')}}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group presets clearfix pt-3">
                                                        @foreach($logos as $index => $logo)
                                                            <div
                                                                {{--                                                                        class="item {{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['logo'] == $logo->id ? 'active' : '') }} logo-image" id="logoImage{{$logo->id}}"--}}
                                                                class="item logo-image"
                                                                id="logoImage{{$logo->id}}"
                                                                onclick="getLogoId({{$logo->id}})">
                                                                <img id="{{$logo->id}}"
                                                                     src="{{ asset('storage/logos/'.$logo->image) }}">
                                                            </div>
                                                        @endforeach
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree">
                                                <h5 class="mb-0">
                                                    <div class="collapsed" data-toggle="collapse"
                                                         data-target="#collapseThree" aria-expanded="false"
                                                         aria-controls="collapseThree">
                                                        <div class="pane-header">
                                                            <div class="icon"><i class="fa fa-qrcode"
                                                                                 aria-hidden="true"></i></div>
                                                            <h3 class="title">{{__('Customize Shape')}}</h3>
                                                            <div class="plus"><i class="fa fa-plus"
                                                                                 aria-hidden="true"></i></div>
                                                            <div class="minus"><i class="fa fa-minus"
                                                                                  aria-hidden="true"></i></div>
                                                        </div>
                                                    </div>
                                                </h5>
                                            </div>
                                            <div id="collapseThree" class="collapse"
                                                 aria-labelledby="headingThree" data-parent="#accordion">
                                                <div class="card-body">
                                                    <label><b>{{__('Body Shape')}}</b></label>
                                                    <div class="form-group presets clearfix">
                                                        @foreach($bodyShapes as $index => $bodyShape)
                                                            <div
                                                                class="item body-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['body'] == $bodyShape->name ? 'active' : '') }}"
                                                                id="shapeName{{$bodyShape->name}}"
                                                                onclick="getShapeName('{{$bodyShape->name}}')">
                                                                <img
                                                                    src="{{ asset('storage/shapes/'.$bodyShape->image) }}">
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    <label><b>{{__('Eye Frame Shape')}}</b></label>
                                                    <div class="form-group presets clearfix">
                                                        @foreach($eyeFrames as $index => $eyeFrame)
                                                            <div
                                                                class="item eye-frame {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['frame'] == $eyeFrame->name ? 'active' : '') }}"
                                                                id="frameName{{$eyeFrame->name}}"
                                                                onclick="getFrameName('{{$eyeFrame->name}}')">
                                                                <img
                                                                    src="{{ asset('storage/shapes/'.$eyeFrame->image) }}">
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    <label><b>{{__('Eye Ball Shape')}}</b></label>
                                                    <div class="form-group presets clearfix">
                                                        @foreach($eyeBallShapes as $index => $eyeBallShape)
                                                            <div
                                                                class="item eye-shape {{($action == 'Add') ? ($index == 0 ? 'active' : '') : (json_decode($generateQrCode->config, true)['eyeBall'] == $eyeBallShape->name ? 'active' : '') }}"
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
                                <div class="col-sm-4">
                                    <div class="fixed-right-qrbar">
                                        <div class="cardbox">
                                            <div class="qrcode-container">
                                                <div class="qrcode" id="classic-qr-code">
                                                    <img class="qr-code-scan"
                                                         src="{{checkImage(asset('storage/users/'.$generateQrCode->user_id.'/qr-codes/' . $generateQrCode->image),'default.svg',$generateQrCode->image)}}">
                                                </div>
                                                <div class="fa-5x mt-4" id="design-loading"
                                                     style="display: none">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                            </div>
                                            <div class="cardbox-inner">
                                                <div class="row">
                                                    <div class="col">
                                                        <button class="btn btn-outline-secondary btn-sm"
                                                                type="button">
                                                            <i class="fa fa-undo"></i> {{__('Reset')}}
                                                        </button>
                                                    </div>
                                                    <div class="col text-right">
                                                        <i class="fa fa-star-o add-template"></i>
                                                        <i class="fa fa-star added-template" hidden=""></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="static" role="tabpanel" aria-labelledby="static-tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="transparent-qr">
                                        <div class="cardbox-inner line">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="cropper-area">
                                                        <div class="loading-screen active">
                                                            <div class="loader">

                                                            </div>
                                                        </div>
                                                        <img hidden="" id="cropImage" src="">
                                                        <div class="cropper-empty">
                                                            <div class="text-center">
                                                                <div>
                                                                    <p>{{__('Place your transparent QR Code on any background')}}</p>
                                                                    <p>- {{__('OR')}} -</p>
                                                                    <button
                                                                        onclick="$('.file-upload-input').trigger( 'click' )"
                                                                        class="btn btn-primary" type="button">
                                                                        {{__('Upload Background Image')}}
                                                                    </button>
                                                                </div>
                                                                <div class="loading-screen active" hidden="">
                                                                    <div class="loader"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!---->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="fixed-right-qrbar">
                                        <div class="cardbox">
                                            <div class="qrcode-container">
                                                <div class="qrcode">
                                                    <img class="qr-code-scan"
                                                         src="{{asset('images/qr-code.svg')}}">
                                                    <div class="loading-screen">
                                                        <div class="loader">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cardbox-inner">
                                                <div class="row">
                                                    <div class="col">
                                                        <button class="btn btn-outline-secondary btn-sm"
                                                                type="button">
                                                            <i class="fa fa-undo"></i> {{__('Reset')}}
                                                        </button>
                                                    </div>
                                                    <div class="col text-right">
                                                        <i class="fa fa-star-o add-template"></i>
                                                        <i class="fa fa-star added-template" hidden=""></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="templates" role="tabpanel"
                             aria-labelledby="templates-tab">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="template">
                                                <div class="qrcode-container">
                                                    <div class="qrcode">
                                                        <img src="{{asset('images/qr-code.svg')}}"
                                                             class=" ng-lazyloaded">
                                                    </div>
                                                </div>
                                                <div class="options">
                                                    <div class="row">
                                                        <div class="col type">
                                                            <!---->
                                                            <span>{{__('Classic')}}</span>
                                                            <!---->
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fa fa-trash delete"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="fixed-right-qrbar">
                                        <div class="cardbox">
                                            <div class="qrcode-container">
                                                <div class="qrcode">
                                                    <img class="qr-code-scan"
                                                         src="{{asset('images/qr-code.svg')}}">
                                                    <div class="loading-screen">
                                                        <div class="loader">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cardbox-inner">
                                                <div class="row">
                                                    <div class="col">
                                                        <button class="btn btn-outline-secondary btn-sm"
                                                                type="button">
                                                            <i class="fa fa-undo"></i> {{__('Reset')}}
                                                        </button>
                                                    </div>
                                                    <div class="col text-right">
                                                        <i class="fa fa-star-o add-template"></i>
                                                        <i class="fa fa-star added-template" hidden=""></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-orange" onclick="cloneQrCode()">{{__('Use Design')}}</button>
                </div>
            </div>

        </div>
    </div>
</div>
@section('js')
    <script>
        let shapeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['body']}}';
        let frameName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['frame']}}';
        let eyeName = '{{ ($action == 'Add') ? 'square' : json_decode($generateQrCode->config, true)['eyeBall']}}';
        let eyeStatus = '{{ ($action == 'Add') ? false : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true : false)}}';
        let colorOne = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor1']}}';
        let colorTwo = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['gradientColor2']}}';
        let frameColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eye1Color']}}';
        let eyeBallColor = '{{ ($action == 'Add') ? '#000000' : json_decode($generateQrCode->config, true)['eyeBall1Color']}}';
        let bodyColor = '{{ ($action == 'Add') ? '#ffffff' : json_decode($generateQrCode->config, true)['bodyColor']}}';
        let colorStructure = '{{ ($action == 'Add') ? 'vertical' : json_decode($generateQrCode->config, true)['gradientType']}}';
        {{--let logoId = '{{ ($action == 'Add') ? 0 : json_decode($generateQrCode->config, true)['logo']}}';--}}
        let logoId = 0;
        let colorType = '{{($action == 'Add') ? true : json_decode($generateQrCode->config, true)['colorType']}}';
        let formData = '';
        let saved_image_id = '';
        let firstImageAppend = true;
        let ajaxCallBlock = true;
        let modalLoading = false;
        let dynamicContentField = '{{($parameters['type'] == 'dynamic') ? true : false}}';
        let dynamicContentFieldAction = '{{($action == 'Add') ? true : false}}';

        $(document).ready(function () {
            $('.hide-show').hide();
            $('.eye-hide-show').hide();
            ajaxCallBlock = '{{($action == 'Add') ? true : false}}';
            let foregroundColorCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['colorType'] == 1 ? 'single': 'gradient')}}';
            let eyeStatusCheck = '{{($action == 'Add') ? '' : (json_decode($generateQrCode->config, true)['eyeStatus'] ? true: false)}}';
            if (foregroundColorCheck) {
                foregroundColor(foregroundColorCheck)
            }
            if (eyeStatusCheck) {
                eyeStatus = false
                eyeColorStatus()
            }
//          Check the dynamic Field
            if (dynamicContentField && dynamicContentFieldAction) {
                makeQrCode();
            }
        });

        //      Check Frontend Validation
        function frontendValidation(formName) {
            let formId = '#' + formName;
            $(formId).validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
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
                        let controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1)
                            controls.append(error);
                        else
                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
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
            })
        }

        //      Add Campaign
        $('#campaign-btn').on('click', function () {
            frontendValidation('campaign-form');
            $('#campaign-form').valid();
        });

        //      Validation data and open Modal
        $('#custom-qr-btn').on('click', function () {
            frontendValidation('dynamic-content-type')
            if ($('#dynamic-content-type').valid()) {
                $('#design-qrcode').modal('show');
            }
        });

        //      Ajax Call after change dynamic inputs
        function getQrCodeByField(ele) {
            let formName = $(ele).parents('form').attr('id');
            let fromId = '#' + formName;
            if ($(fromId).valid()) {
                if (dynamicContentField) {
                    firstImageAppend = true;
                    $('#save-qr-code-btn').prop('disabled', false);
                } else {
                    firstImageAppend = true;
                    makeQrCode();
                    $('#save-qr-code-btn').prop('disabled', false);
                }
            }
        }

        //      Display Campaign input field
        $(".add-campaigns").click(function () {
            $(".form-new-campaign").slideToggle();
        });

        //      Store campaign form
        $('#campaign-form').on("click", function (e) {
            let formData = new FormData(this);

            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.campaigns.store') }}",
                data: formData,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $("#add-campaign").append('<option value="' + response.data.id + '">' + response.data.name + '</option>');
                        $("#campaign-name").val('')
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        });

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

        //      Get and active shape
        function getShapeName(selectedShapeName) {
            let shapeNameId = '#shapeName' + selectedShapeName;
            $('.body-shape').removeClass('active');
            $(shapeNameId).addClass('active');
            shapeName = selectedShapeName;
            makeQrCode()
        }

        //      Get and active frame
        function getFrameName(selectedFrameName) {
            let frameNameId = '#frameName' + selectedFrameName;
            $('.eye-frame').removeClass('active');
            $(frameNameId).addClass('active');
            frameName = selectedFrameName;
            makeQrCode()
        }

        //      Get and active eye
        function getEyeBallName(selectedEyeBallName) {
            let eyeShapeId = '#eyeShape' + selectedEyeBallName;
            $('.eye-shape').removeClass('active');
            $(eyeShapeId).addClass('active');
            eyeName = selectedEyeBallName;
            makeQrCode()
        }

        //      Upload Logo Image
        function addLogoImage(ele) {
            readURL(ele);
            makeQrCode();
        }

        //      Check Radio button on Color selection like single or gardient
        function foregroundColor(selectedColorType) {
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
//          Check the dynamic Field
            if (dynamicContentField && dynamicContentFieldAction) {
                makeQrCode();
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
            if (ajaxCallBlock) {
                makeQrCode();
            }
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
            makeQrCode()
        }

        //      Pick up the color type etc varticle,radial...
        function colorSchema(ele) {
            colorStructure = ele.value;
            makeQrCode()
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
            makeQrCode()
        }

        //      remove image and logo id
        function removeImageInput(ele) {
            $('#upload-logo-image').val(null);
            removeUpload()
            logoId = 0;
            makeQrCode()
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
                    let frameColor = $('#colorFour').val();
                    $('#colorThree').val(frameColor);
                    $('#colorFour').val(eyeColor);
                    frameColor = frameColor;
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
            makeQrCode()
        }

        //      Generate Qr Code with all the data
        function makeQrCode() {
            let myForm = document.getElementById('dynamic-content-type');
            formData = new FormData(myForm);
//          Loading on modal
            if ($('#design-qrcode').is(":visible")) {
                modalLoading = true
            } else {
                modalLoading = false
            }

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
            let fileToUpload = $('#upload-logo-image').prop('files')[0];
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
                    if (!modalLoading) {
                        $('#loading').show();
                        $('#generated-qr-code').hide();
                    } else {
                        $('#design-loading').show();
                        $('#classic-qr-code').hide();
                    }
                },
                complete: function () {
                    if (!modalLoading) {
                        $('#loading').hide();
                        $('#generated-qr-code').show();
                    } else {
                        $('#design-loading').hide();
                        $('#classic-qr-code').show();
                    }
                },
                success: function (response) {
                    if (response.status == 1) {
                        if (firstImageAppend) {
                            $('#generated-qr-code').empty();
                            $('#generated-qr-code').append(response.html);
                        }
                        firstImageAppend = false;
                        $('#classic-qr-code').empty();
                        $('#classic-qr-code').append(response.html);
                        saved_image_id = response.image_id;
                    } else {
                        printErrorMsg(response.message);
                    }
                }
            });
        }

        //      Append qr code modal to page
        function cloneQrCode() {
            $('#generated-qr-code').empty();
            $('#save-qr-code-btn').prop('disabled', false);
            $('#classic-qr-code').clone().appendTo('#generated-qr-code');
            $("div#generated-qr-code").find("div#classic-qr-code").contents().unwrap();
            $('#design-qrcode').modal('hide');
        }

        //      Store QR Code in backend
        function saveQrCode() {
            formData.append('image_id', saved_image_id);
            $.ajax({
                processData: false,
                contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.qr-codes.store') }}",
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

        /*whether image is Url or base64*/
        $('img.file-upload-image').on('load', function () {
            if ($("img.file-upload-image[src*='base64']").length == 1) {
                $('.logo-image').removeClass('active');
            }
        })
        /*End*/

        //      Validate and Store Data in Formdata and call save function
        $('#save-qr-code-btn').on('click', function () {
            frontendValidation('save-qr-code-form');
            frontendValidation('dynamic-content-type');

            if ($('#save-qr-code-form').valid() && $('#dynamic-content-type').valid()) {
                let myForm = document.getElementById('dynamic-content-type');
                formData = new FormData(myForm);

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
                let name = $('#general-name').val();
                let campaign_id = $('#add-campaign').val();
                formData.append('name', name);
                formData.append('campaign_id', campaign_id);
                saveQrCode();
            }
        });
    </script>
@endsection
