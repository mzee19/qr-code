@extends('frontend.layouts.page')
@section('before-style')
    {{-- Start Google Captcha --}}
    {!! htmlScriptTagJsApi(['lang'=> session()->get('locale')]) !!}
    {{-- End Google Captcha --}}

    <script>
        function gCaptchaCallback(response) {
            // read HTTP status
            if(response != ''){
                let loginButton = document.getElementById('login')
                loginButton.disabled = false
            }
        };
    </script>

@endsection
@section('title', 'Login')

@section('content')
    <div class="login-banner">
        <div class="login-form front-forms">
            <form id="login-form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <h2 class="mb-4">{{__('Log In')}}
                </h2>
                <hr>

                @include('frontend.messages')

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text email-icon">
                            <i class="fa fa-envelope"></i>
                        </span>
                        </div>

                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                               placeholder="{{ __('Email Address') }}" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </span>
                        </div>
                        <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}"
                               required="required">
                    </div>
                </div>
                {{-- Start Google Captcha --}}
                <div class="form-group  d-flex justify-content-center captcha-holder">
                {!! htmlFormSnippet() !!}
                </div>
              
                {{-- End Google Captcha --}}

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg" id="login" disabled>{{__('Login')}}</button>
                </div>
                <div class="forget-password"><a
                        href="{{ route('auth.forgot-password') }}">{{__('Forgot Password?')}}</a>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(function () {
            $('#login-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    email: {
                        email: true,
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                },

                messages: {
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}'
                    },
                    password: {
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


    </script>

@endsection
