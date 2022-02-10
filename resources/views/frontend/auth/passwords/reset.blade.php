@extends('frontend.layouts.page')

@section('title', 'Reset Password')

@section('content')
    <div class="login-form front-forms">
        <form id="reset-password" method="post" action="{{ route('auth.reset-password') }}">
            @csrf
            <h2 class="mb-4">{{__('Reset Password')}}</h2>
            <hr>

            @include('frontend.messages')

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text email-icon">
                                <i class="fa fa-envelope"></i>
                            </span>
                    </div>
                    <input type="email" class="form-control" name="email" value="{{$email}}" placeholder="{{ __('Email Address') }}" readonly="">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-lock"></i>
                            </span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('Password') }}" required="required">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-lock"></i>
                                <i class="fa fa-check"></i>
                            </span>
                    </div>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required="required">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg">{{__('Reset')}}</button>
            </div>
        </form>
    </div>
@endsection


@section('js')
<script>
    $(function(){
        $('#reset-password').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
            focusInvalid: false,

            rules: {
                password: {
                        passwordCheck:true,
                        required: true,
                        maxlength:8,
                        maxlength:30,

                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    },
            },

            messages: {
                password: {
                        passwordCheck: '{{__('Must contain at least one number and one uppercase and lowercase letter and at least 8 or more characters')}}',
                        required: '{{__('This field is required')}}',
                        minlength: '{{__('Minimum Length is 8')}}',
                        maxlength: '{{__('Maximum Length is 30')}}',

                    },
                    password_confirmation: {
                        required: '{{__('This field is required')}}',
                        equalTo : '{{__('Please enter the same value again')}}'
                    }
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
        $.validator.addMethod("passwordCheck", function(value) {
            return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)
        });
    });

</script>
    @endsection
