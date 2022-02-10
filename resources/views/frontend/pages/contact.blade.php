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
@section('title', __('Contact Us'))

@section('content')
    <div class="main-content">
        <section class="contact-form-info">
            <div class="container">
                <div class="contact-title-des text-center">
                    <h2>{{__('GET IN TOUCH')}}</h2><p>{{__('Contact Us & You’ll Hear Back TODAY.')}}</p>
                    <p>{{__('You are welcome to send us enquiries or comments by completing this form and clicking the ’Submit’ button.')}}</p>
                </div>
                <div class="cont-box-wrap">
                    <div class="row cont-inner-wrap align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="login-form front-forms">
                                <form id="contact-us-form" method="POST" action="{{ url('/contact-us') }}">
                                    {{ csrf_field() }}

                                    <h2 class="mb-4">{{__('Contact Us')}}</h2>
                                    <hr>

                                    @include('frontend.messages')

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text email-icon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                            </div>

                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                                placeholder="{{ __('Name') }}" required="required">
                                        </div>
                                    </div>

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
                                                <span class="input-group-text email-icon">
                                                    <i class="fa fa-phone"></i>
                                                </span>
                                            </div>

                                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                                placeholder="{{ __('Phone Number') }}" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text email-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </span>
                                            </div>

                                            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}"
                                                placeholder="{{ __('Subject') }}" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text email-icon">
                                                    <i class="fa fa-file"></i>
                                                </span>
                                            </div>

                                            <textarea class="form-control" name="message" placeholder="{{ __('Write a message') }}" required="required">{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group  d-flex justify-content-center captcha-holder">
                                        {!! htmlFormSnippet() !!}
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg">{{__('Submit')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="contact-info">
                                <li>
                                    <span class="fa fa-map-marker"></span><div class="info-holder"><h3>{{__('Location')}}</h3><p>{{settingValue('office_address')}}</p></div>
                                </li>
                                <li>
                                    <span class="fa fa-phone"></span><div class="info-holder"><h3>{{__('Contact Us')}}</h3><p>{{settingValue('contact_number')}}</p></div>
                                </li>
                                <li>
                                    <span class="fa fa-envelope"></span><div class="info-holder"><h3>{{__('Email')}}</h3><p>{{settingValue('contact_email')}}</p></div>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#contact-us-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

                rules: {
                    name: {
                        required: true,
                        maxlength: 250,
                    },
                    email: {
                        email: true,
                        required: true,
                        maxlength: 100,
                    },
                    phone: {
                        required: true,
                        maxlength: 50,
                    },
                    subject: {
                        required: true,
                        maxlength: 250,
                    },
                    message: {
                        required: true,
                    },
                },

                messages: {
                    name: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('The name may not be greater than 250 characters.')}}',
                    },
                    email: {
                        email: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('The email may not be greater than 100 characters.')}}',
                    },
                    phone: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('The phone may not be greater than 50 characters.')}}',
                    },
                    subject: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('The subject may not be greater than 50 characters.')}}',
                    },
                    message: {
                        required: '{{__('This field is required')}}',
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
        });
    </script>
@endsection
