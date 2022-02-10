@extends('frontend.layouts.page')

@section('title', 'Forgot Password')

@section('content')
    <div class="login-banner">
        <div class="login-form front-forms">
            <form id="forgot-password" method="post" action="{{ route('auth.send-reset-link-email') }}">
                @csrf
                <h2 class="mb-4">{{__('Recover Password')}}</h2>
                <hr>
                @include('frontend.messages')

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                                <span class="input-group-text email-icon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                        </div>
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}" required="required">
                    </div>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg">{{__('Recover')}}</button>
                </div>
            </form>
        </div>
    </div>    
@endsection


@section('js')
    <script>
        $(function(){
            $('#forgot-password').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

                rules: {
                    email: {
                        email:true,
                        required: true,
                    },
                },

                messages: {
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
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
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
                },
                invalidHandler: function (form) {
                }
            });
            $('.subscriber-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    email: {
                        email: true,
                        required: true,
                    },
                },

                messages: {
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
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
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
                },
                invalidHandler: function (form) {
                }
            });
        });

        if(!$('.alert').hasClass('persist-alert'))
        {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        }
    </script>

    @endsection
