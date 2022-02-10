@extends('frontend.layouts.dashboard')

@section('title', __('Setting'))

@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title">
                    <h3 class="sub-title">{{__('General')}}</h3>
                </div>
            </div>
            <div class="col-12">
                @include('admin.messages')
                <form id="update-setting-form" action="{{route('frontend.user.update.setting')}}" method="post"
                      enctype="multipart/form-data">
                    <div class="setting-form forms-container cardbox cardbox-inner fieldsetting">
                        @csrf
                        <div class="form-group">
                            <label>{{__('Name')}}<span class="text-danger"> *</span></label>
                            <input class="form-control" type="text" maxlength="100" name="name" value="{{$user->name}}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label>{{__('Email')}}<span class="text-danger"> *</span></label>
                            <input class="form-control" type="email" name="email" value="{{$user->email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">{{__('Profile Image')}}</label>
                            <input type="file" onchange="getExtension(this)" name="profile_image" id="profile_image"
                                   class="form-control pt-2 file-upload-input"
                                   accept="image/.png,.png,.jpg,.jpeg,.svg">
                            <span id="profile_image_file_error"></span>

                            <span
                                class="d-none"
                                id="profileimagetype"
                                style="color: #FC4014">{{__('The image must be a file of type: image/jpg, png, jpeg, svg+xml')}}</span>
                            <br>
                            <span
                                class="label label-info">{{__('Note')}}:</span> {{__('Recommended image resolution is 100x100 pixels')}}
                            .
                            <br><br>
                            <div style="width: 100px; height: auto">
                                <img style="width: 100px"
                                     src="{{checkImage(asset('storage/users/'.$user->id.'/' . $user->profile_image),'avatar.png',$user->profile_image)}}"
                                     class="img-responsive file-upload-image" alt="Avatar" id="profile_image">
                            </div>
                        </div>

                        <div class="form-group country-series setting-seachbar ">
                            <label for="country" class="control-label">{{__('Country')}}<span
                                    class="text-danger"> *</span></label>
                            <div class="row">
                                <div class="col-12">
                                    <select class="form-control" name="country_id" id="country" required>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}"
                                                {{$country->id == $user->country_id  ? 'selected' : ''}}>
                                                {{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="timezone" class="control-label">{{__('Timezone')}}<span
                                    class="text-danger"> *</span></label>
                            <div class="row">
                                <div class="col-12">
                                    <select class="form-control" name="timezone" id="timezone" required>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{$timezone->name}}"
                                                {{$timezone->name == $user->timezone  ? 'selected' : ''}}>
                                                {{$timezone->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">{{__('Password')}}</label>
                            <span class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                            <input type="password" name="password" id="password" minlength="8" maxlength="30"
                                   class="password form-control" value="{{$user->original_password}}">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="control-label">{{__('Confirm Password')}}</label>
                            <span class="fa fa-fw fa-eye password-field-icon toggle-password"></span>
                            <input type="password" name="confirm_password" id="confirm_password"
                                   class="password form-control" value="{{$user->original_password}}">
                        </div>

                        <div class="text-right">
                            <a href="{{route('frontend.user.account')}}" class="btn btn-danger btn-fullrounded">
                                <span>{{__('Cancel')}}</span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-fullrounded">
                                <span>{{__('Save')}}</span>
                            </button>
                        </div>

{{--                        @if(checkFieldStatus(14))--}}
{{--                        <div class="text-left">--}}
{{--                            <button type="button" class="btn btn-primary" data-toggle="modal"--}}
{{--                                    data-target="#white-label-short-url-modal">--}}
{{--                                {{__('WhiteLabel Short URL')}}--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        @endif--}}

                        <br><br>

                        <p><a href="https://en.wikipedia.org/wiki/Multi-factor_authentication"
                              target="_blank">{{__('Two-factor Authentication (2FA)')}}</a> {{__('adds additional account security if your password is compromised or stolen. With 2FA, access to your account requires a password and a second form of verification')}}
                            .</p>
                        <p>{{__('QR Code supports 2FA by using one-time passwords generated with the')}} <a
                                href="https://en.wikipedia.org/wiki/Time-based_One-time_Password_algorithm"
                                target="_blank">{{__('TOTP algorithm')}}</a></p>
                        <p>{{__('You can use any mobile application employing TOTP')}}.</p>
                        <p>{{__('We recommend the following apps')}}</p>
                        <p>{{__('Android, iOS, and Blackberry')}}—<a
                                href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&amp;hl=en"
                                target="_blank">{{__('Google Authenticator')}}</a></p>

                        @if($user->otp_auth_status)
                            <a href="{{url('otp-auth/disable-two-factor-authentication')}}">
                                <button type="button" class="btn btn-primary btn-fullrounded">{{__('Disable')}}</button>
                            </a>
                        @else
                            <a href="{{url('otp-auth/setup-two-factor-authentication')}}">
                                <button type="button"
                                        class="btn btn-primary btn-fullrounded">{{__('Configure')}}</button>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
{{--    <div class="modal fade" id="white-label-short-url-modal" tabindex="-1" role="dialog"--}}
{{--         aria-labelledby="white-label-short-url-modal" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel">{{__('WhiteLabel')}}</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="print-error-msg"></div>--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="addDomain">{{__('Add Domain')}}</label>--}}
{{--                        <a data-toggle="tooltip" data-placement="auto" title="www.ned.link" class="circle-des">--}}
{{--                            <i class="fa fa-info-circle"></i></a>--}}
{{--                        <input type="text" name="domain" class="form-control" id="addDomain" maxlength="30"--}}
{{--                               placeholder="{{__('Add Domain')}}" value="{{$user->userDomain->domain ?? ''}}" required>--}}
{{--                    </div>--}}
{{--                    @if(isset($user->userDomain->domain))--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="addDomain">{{__('Change status')}}</label>--}}
{{--                            <a data-toggle="tooltip" data-placement="auto" title="www.ned.link" class="circle-des">--}}
{{--                                <i class="fa fa-info-circle"></i></a>--}}
{{--                            <input type="checkbox" name="domain-status" class="form-control" id="domain-status"--}}
{{--                                   {{$user->userDomain->status == true ? 'checked' : ''}}--}}
{{--                                   onclick="changeDomainStatus()">--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>--}}
{{--                    @if(!isset($user->userDomain->domain))--}}
{{--                        <button type="button" class="btn btn-primary" onclick="saveDomain(this)">{{__('Save')}}</button>--}}
{{--                    @else--}}
{{--                        <button type="button" class="btn btn-danger" onclick="deleteDomain()">{{__('Delete')}}</button>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection

@section('js')
    <script>
        $(function () {
            $('#update-setting-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,


                rules: {
                    name: {
                        required: true,
                        maxlength: 100,
                    },
                    country_id: {
                        required: true,
                    },
                    timezone: {
                        required: true,
                    },
                    password: {
                        passwordCheck: true,
                        required: true,
                        maxlength: 30,
                    },
                    confirm_password: {
                        equalTo: "#password",
                    },
                },

                messages: {
                    name: {
                        required: '{{__('This field is required')}}',
                        maxlength: '{{__('Maximum Length is 100')}}',
                    },
                    country_id: {
                        required: '{{__('This field is required')}}'
                    },
                    timezone: {
                        required: '{{__('This field is required')}}'
                    },
                    password: {
                        passwordCheck: '{{__('Must contain at least one number and one uppercase and lowercase letter and at least 8 or more characters')}}',
                        required: '{{__('This field is required')}}',
                        minlength: '{{__('Minimum Length is 8')}}',
                        maxlength: '{{__('Maximum Length is 30')}}',

                    },
                    confirm_password: {
                        equalTo: '{{__('Please enter the same value again')}}'
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
            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)
            });
        });

        $("#profile_image").change(function () {
            var fileExtension = ['jpg', 'png', 'jpeg', 'svg+xml'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                $('#profileimagetype').removeClass('d-none');
            } else {
                $('#profileimagetype').addClass('d-none');
            }
            $("#profile_image_file_error").html("");
            var file_size = $('#profile_image')[0].files[0].size;
            if (file_size > 1048576) {
                $("#profile_image_file_error").html("<p style='color:#FF0000'>{{__('The profile image may not be greater than 8192 kilobytes.')}}</p>");
                return false;
            }
            return true;
        });

        $('#timezone, #country').select2(
            {
                placeholder: 'Select a Timezone',
                allowClear: true
            });

        $(".toggle-password").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(this).siblings('input');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        function getExtension(val) {
            // var file = $('.file-upload-input').val();
            // var exten = file.split('.').pop();
            readURL(val);
        }

        function saveDomain(ele) {
            var data = {
                'domain': $('#addDomain').val()
            }
            $.ajax({
                // processData: false,
                // contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.store.domain') }}",
                data: data,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $('#white-label-short-url-modal').modal('hide');
                        window.location.reload()
                    } else {
                        printErrorMsg(response.message)
                    }
                    console.log(response)
                }
            });
        }

        function changeDomainStatus(ele) {
            var data = {
                'status': $('#domain-status').is(":checked"),
            }
            console.log(data)
            $.ajax({
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('frontend.user.status.domain')}}',
                data: data,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $('#white-label-short-url-modal').modal('hide');
                        window.location.reload()
                    } else {
                        printErrorMsg(response.message)
                    }
                }
            });
        }


        function deleteDomain(ele) {

            var confirmText = "Are you sure you want to delete this domain?";
            var data = {
                'domain': $('#addDomain').val()
            }
            if (confirm(confirmText)) {
                $.ajax({
                    // processData: false,
                    // contentType: false,
                    type: "get",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('frontend.user.delete.domain') }}",
                    data: data,
                    enctype: 'multipart/form-data',
                    success: function (response) {
                        if (response.status == 1) {
                            $('#white-label-short-url-modal').modal('hide');
                            window.location.reload()
                        } else {
                            printErrorMsg(response.message)
                        }
                        console.log(response)
                    }
                });
            }
        }

        function printErrorMsg(msg) {
            $(".print-error-msg").css('display', 'block');
            $(".print-error-msg").addClass('alert-danger');
            $.each(msg, function (key, value) {
                $(".print-error-msg").append('<li id=' + key + '>' + value + '</li>');
                removeErrorMessage(key)
            });
        }

        //      Remove laravel error message after sometime
        function removeErrorMessage(key) {
            var index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');
                $(".print-error-msg").removeClass('alert-danger');
                $(index).remove();
            }, 5000);
        }
    </script>


@endsection
