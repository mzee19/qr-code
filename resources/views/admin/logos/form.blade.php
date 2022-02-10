@extends('admin.layouts.app')

@section('title', 'Logos')
@section('sub-title', $action.' Logo')
@section('content')
<div class="main-content">
    <div class="content-heading clearfix">

        <ul class="breadcrumb">
            <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="{{url('admin/logo-images')}}"><i class="fa fa-picture-o"></i> Logos</a></li>
            <li>{{$action}}</li>
        </ul>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{$action}} Logo</h3>
                    </div>
                    <div class="panel-body">
                        @include('admin.messages')
                        <form id="logos-form" class="form-horizontal label-left" action="{{url('admin/logos')}}"
                            enctype="multipart/form-data" method="POST">
                            @csrf

                            <input type="hidden" name="action" value="{{$action}}" />
                            <input name="id" type="hidden" value="{{ $model->id }}" />

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Title*</label>
                                <div class="col-sm-9">
                                    <input type="text" name="title" maxlength="100" class="form-control" required=""
                                        value="{{ ($action == 'Add') ? old('title') : $model->title}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image" class="col-sm-3 control-label">Upload Image</label>
                                <div class="col-sm-9">
                                    <input type="file" name="image" id="image" class="form-control"
                                        onchange="readURL(this,'image',['jpeg','jpg','png'],'image-error','image')">
                                    <span id="image-error" style="display:none;color:#f36363;"></span>
                                    <br>
                                    <span class="label label-info">Note:</span> Recommended image resolution is 40x40
                                    pixels.
                                    <br><br>
                                    <div style="width: 100px; height: auto">
                                        <img src="{{checkImage(asset('storage/logos/' . $model->image),'placeholder.png',$model->image)}}"
                                            class="img-responsive" alt="" id="image">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-9">
                                    @php $status = ($action == 'Add') ? old('status') : $model->status @endphp
                                    <label class="fancy-radio">
                                        <input name="status" value="1" type="radio" {{ ($status == 1) ? 'checked' : '' }}>
                                        <span><i></i>Active</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="status" value="0" type="radio" {{ ($status == 0) ? 'checked' : '' }}>
                                        <span><i></i>Disable</span>
                                    </label>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="{{url('admin/logos')}}">
                                    <button type="button" class="btn cancel btn-fullrounded">
                                        <span>Cancel</span>
                                    </button>
                                </a>

                                <button type="submit" class="btn btn-primary btn-fullrounded">
                                    <span>Save</span>
                                </button>
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
    $(function(){
        $('#logos-form').validate({
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
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
                }, 500);
            },
            submitHandler: function (form,validator) {
                if($(validator.errorList).length == 0)
                {
                    document.getElementById("page-overlay").style.display = "block";
                    return true;
                }
            }
        });
    });

</script>
@endsection
