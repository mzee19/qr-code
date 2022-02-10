@extends('admin.layouts.app')

@section('title', 'Contact Us Queries')
@section('sub-title', $action.' Query')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/contact-us-queries')}}"><i class="fa fa-phone"></i> Contact Us Queries</a></li>
			<li>{{$action}}</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">{{$action}} Query</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="contact-us-queries-form" class="form-horizontal label-left"
							action="{{url('admin/contact-us-queries')}}" enctype="multipart/form-data" method="POST">
							@csrf

							<input type="hidden" name="action" value="{{$action}}" />
							<input name="id" type="hidden" value="{{ $query->id }}" />

							<div class="form-group">
								<label for="name" class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" value="{{ $query->name}}" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" value="{{ $query->email}}" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label for="phone" class="col-sm-3 control-label">Phone</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" value="{{ $query->phone}}" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label for="subject" class="col-sm-3 control-label">Subject</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" value="{{ $query->subject}}" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label for="message" class="col-sm-3 control-label">Message</label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="5" readonly="">{{ $query->message }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									@php $status = $query->status @endphp
									<label class="fancy-radio">
										<input name="status" value="1" type="radio"
											{{ ($status == 1) ? 'checked' : '' }}>
										<span><i></i>Completed</span>
									</label>
									<label class="fancy-radio">
										<input name="status" value="0" type="radio"
											{{ ($status == 0) ? 'checked' : '' }}>
										<span><i></i>Pending</span>
									</label>
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/contact-us-queries')}}">
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
        $('#contact-us-queries-form').validate({
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