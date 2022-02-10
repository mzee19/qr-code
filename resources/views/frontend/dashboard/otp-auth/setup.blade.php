@extends('frontend.layouts.dashboard')

@section('title', __('Setup Google 2FA'))

@section('content')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            @include('admin.messages')
            <div class="section-title text-center">
                <h2 class="title"><strong>{{__('Configure Google Authenticator')}}</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p>{{__('Configure your 2FA by scanning the QR CODE below.')}}</p>
            <center>
                <p> {!! $otp_auth_qr_image !!}</p>
            </center>
            <p>{{__('You must configure your Google Authenticator app before continuing. You will be unable to login otherwise.')}}</p>

            <form id="otp-setup-form" action="{{url('/otp-auth/enable-two-factor-authentication')}}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control"  placeholder="{{ __('One Time Password') }}" name="one_time_password" required>
                </div>

                <div class="text-right">
                    <a href="{{route('frontend.user.setting')}}" class="btn btn-danger btn-fullrounded"><span>{{__('Cancel')}}</span></a>
                    <button type="submit" class="btn btn-primary btn-fullrounded">
                        <span>{{__('Enable 2FA')}}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#otp-setup-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                
                rules: {
                    one_time_password: {
                        required:true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6,                  
                        },
                },

                messages: {
                    one_time_password: {
                        required: '{{__('This field is required')}}',
                        digits: '{{__('The one time password must be a number.')}}',
                        minlength: '{{__('The one time password must be 6 digits.')}}',
                        maxlength: '{{__('The one time password must be 6 digits.')}}',

                    },
                },


                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');
                    $(e).remove();
                },
            });
        });

 </script>


@endsection

