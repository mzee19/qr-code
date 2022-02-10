@extends('frontend.layouts.dashboard')

@section('title', $tabTitle.' '.__('Campaigns'))

@section('content')

<div  class="content-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="section-title">
{{--                <h3 class="sub-title">{{$tabTitle}} {{__('Campaigns')}}</h3>--}}
            </div>
        </div>

        <div class="col-12">
        @include('frontend.messages')

            <form id="campaign-form" action="{{route('frontend.user.campaigns.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="action" value="{{$action}}" />
                <input name="id" type="hidden" value="{{ $campaign->id }}" />
                <div class="setting-form forms-container cardbox cardbox-inner">
                    <div class="form-group">
                        <label>{{__('Name')}}<span class="text-danger"> *</span></label>
                        <input class="form-control" type="text" name="name" value="{{ ($action == 'Add') ? old('name') : $campaign->name}}" required maxlength="20">
                    </div>

                    <div class="text-right">
                        <a href="{{route('frontend.user.campaigns.index')}}" class="btn btn-danger  btn-fullrounded">
                            <span>{{__('Cancel')}}</span>
                        </a>
                        <button type="submit" class="btn btn-primary  btn-fullrounded">
                            <span>{{__('Save')}}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
	$(function(){
        $('#campaign-form').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
            focusInvalid: true,

            rules: {
                    name: {
                        required: true,
                    },
                },
            messages: {
                    name: {
                        required: '{{__('This field is required')}}'
                    },
                },

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
