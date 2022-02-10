<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{env('APP_NAME')}} | @yield('title')</title>

        <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/favicon.png') }}"/>

        <!-- Bootstrap 4.5 CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}"  >
        <!-- FontAwesome File -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        {{--Select2 library for dropdown option--}}
        <link rel="stylesheet" href="{{ asset('admin-assets/vendor/select2/css/select2.min.css') }}">

        <!-- Custom Front pages Design -->
        <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}"  >
        <link rel="stylesheet" href="{{ asset('css/frontpages-style.css') }}"  >

        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.css" rel="stylesheet">
        @yield('before-style')
    </head>
    <body style="background: #e8eef2;" class="{{app()->getLocale()}}">
        <!------------------- Start Wrapper ---------------->
        <div class="wrapper">
            @include('frontend.sections.header')
            @yield('content')
            @include('frontend.sections.footer')
        </div>
        <!-------------------- End Wrapper ----------------->

        <!-- Bootstrap Scripts -->
        <script src="{{ asset('js/bootstrap_js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap_js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin-assets/js/jquery.validate.js') }}"></script>
        <script src="{{ asset('admin-assets/vendor/select2/js/select2.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.js"></script>
        <script>
            if(!$('.alert').hasClass('persist-alert'))
            {
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            }
        </script>
        @yield('js')
    </body>
</html>
