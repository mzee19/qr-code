@extends('frontend.layouts.page')
@section('before-style')
    {{-- Start Google Captcha --}}
    {!! htmlScriptTagJsApi(['lang'=> session()->get('locale')]) !!}
    {{-- End Google Captcha --}}
    <script>
        function gCaptchaCallback(response) {
            // read HTTP status
            if(response != ''){
                window.registerButton = true
                let registerButton = document.getElementById('signupButton')
                var termsCheck = document.getElementById('termsCheck');
                if (termsCheck.checked){
                    registerButton.disabled = false
                }else{
                    registerButton.disabled = true
                }
            }
        };
    </script>
@endsection
@section('title', 'Register')

@section('content')
    <div class="login-banner">
        <div class="signup-form front-forms">
            <form id="signup-form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <h2>{{__('Sign Up')}}</h2>
                <p>{{__('Start Your Free Trail!')}}</p>
                <hr>
                @include('frontend.messages')
                <input type="hidden" class="form-control" name="lang" value="en">
                <input type="hidden" class="form-control" name="time_zone" value="" id="time_zone">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-user"></span>
                                </span>
                        </div>
                        <input type="text" class="form-control" name="name" maxlength="100" placeholder="{{ __('Full Name') }}"
                            value="{{old('name')}}" required="required">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                                <span class="input-group-text email-icon">
                                    <i class="fa fa-envelope"></i>
                                </span>
                        </div>
                        <input type="email" class="form-control" name="email" placeholder="{{ __('Email Address') }}"
                            value="{{old('email')}}" required="required">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group text-left country-select-field">
                        <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fa fa-flag"></span>
                                </span>
                        </div>
                        <select class="form-control" name="country_id" id="country" required="required">
                            <option value="">
                            </option>
                            @foreach ($countries as $country)
                                <option value="{{$country->id}}">
                                    {{$country->name}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-lock"></i>
                                </span>
                        </div>
                        <input type="password" id="password" class="form-control" name="password" placeholder="{{ __('Password') }}"
                            minlength="8" maxlength="30" required="required">
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
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="{{ __('Confirm Password') }}" required="required">
                    </div>
                </div>
                {{-- Start Google Captcha --}}
                <div class="form-group  d-flex justify-content-center captcha-holder">
                {!! htmlFormSnippet() !!}
                </div>
                {{-- End Google Captcha --}}
                <div class="form-group">
                    <label class="form-check-label">
                        <input type="checkbox" onclick="signUpEnable(this)" id="termsCheck">
                        {{__('By signing up you agree to our')}} <a href="{{url('/pages/terms-and-conditions')}}" target="_blank" class="#">{{__('terms and conditions')}}</a> {{__('and our')}} <a href="{{url('pages/privacy-policy')}}" class="#" target="_blank"> {{__('privacy policy')}}</a> {{__('and you start your free 14-day trial.')}}
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" id="signupButton" class="btn btn-primary btn-lg" disabled="disabled">{{__('Sign Up')}}
                    </button>
                </div>
                <div class="text-center"><span class="color-white"> {{__('Already have an account?')}}</span> <a
                        href="{{route('login')}}" style="color: orange"> {{__('Login here')}}</a></div>

            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            $('#time_zone').val(timezone)
        });

        $(function () {
            $('#country').select2(
                {
                    placeholder: '{{__('Select a Country')}}',
                    allowClear: true
                });


            $('#signup-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

                rules: {
                    name: {
                        required: true,
                        maxlength:100,
                    },
                    email: {
                        email: true,
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    password: {
                        passwordCheck:true,
                        required: true,
                        minlength:8,
                        maxlength:30,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    },
                },

                messages: {
                    name: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('Maximum Length is 100')}}',
                    },
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}'
                    },
                    country_id: {
                        required: '{{__('This field is required')}}'
                    },
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

            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)
            });
            $.validator.addMethod("emailCheck", function (value) {
                return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
            });
        });

        function signUpEnable(ele) {
            if ($("input[type=checkbox]").is(
                ":checked") && window.registerButton) {
                $('#signupButton').attr('disabled', false);
            } else {
                $('#signupButton').attr('disabled', true);
            }
        }

    </script>

@endsection
