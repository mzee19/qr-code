<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QR Code | @yield('title')</title>

    <link rel="shortcut icon" type="image/jpg" href="{{asset('images/favicon.png')}}"/>

    <!-- Bootstrap 4.5 CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}"  >
    <!-- FontAwesome File -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom Front pages Design -->
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{--Crop image--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/dashboard-custom-style.css')}}">
</head>


<body class="{{app()->getLocale()}}">
    <div id="page-overlay">
        <div class="page-overlay-loader"></div>
    </div>
    <div class="wrapper d-flex align-items-stretch">
        @include('frontend.sections.dashboard_sidebar')

        <div id="content" class="custom-margin">
            @include('frontend.sections.dashboard_header')
            @yield('content')

        </div>
    </div>
    <!-- Bootstrap Scripts -->
    <script src="{{asset('js/bootstrap_js/jquery-3.3.1.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="{{asset('js/bootstrap_js/bootstrap.min.js')}}"></script>

    <script src="{{ asset('admin-assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.js') }}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('js/custom-js.js') }}"></script>


    {{--Crop image--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.js"></script>

    <script>
        var scrollTopDifference = 110;

        if(!$('.alert').hasClass('persist-alert'))
        {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        }

        // if(!$('.alert-info').hasClass('persist-alert'))
        // {
        //     setTimeout(function() {
        //         $('.alert').fadeOut('slow');
        //     }, 5000);
        // }

        // Range Slider
        $(".myRange").on('input', function () {
            $(".width").text(this.value);
            $(".height").text(this.value);
        });

        function readFileURL(input,id,types,error_type_id,media_type)
        {
            if (input.files && input.files[0])
            {
                var mimeType = input.files[0]['type'];
                var extention = mimeType.split('/');
                var file_type = extention[0];
                extention = extention[1];

                if(jQuery.inArray(extention, types) == -1)
                {
                    $('input[name="'+id+'"]').val('');
                    var error_message = types.join(", ");
//                    error_message = 'The '+media_type+' must be a file of type: '+media_type+'/' + error_message;
                    error_message = '{{__('The image must be a file of type:')}} '+media_type+'/' + error_message;
                    $('#'+error_type_id).css('display','block');
                    $('#'+error_type_id).html(error_message);
                }
                else
                {
                    var size = input.files[0]['size'];
                    size = size / 1024;
                    var max_size = (file_type == "image") ? {{ config('constants.file_size') }} : {{ config('constants.video_file_size') }};

                    if(size > max_size)
                    {
                        max_size = max_size / 1024;
                        $('input[name="'+id+'"]').val('');
                        $('#'+error_type_id).css('display','block');
                        $('#'+error_type_id).html('The '+file_type+' size must be '+max_size+'MB or less.');
                    }
                    else
                    {
                        $('#'+error_type_id).css('display','none');
                        if(file_type == 'image')
                        {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#'+id).attr('src', e.target.result);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }
                }
            }
        }

        function showOverlayLoader()
        {
            document.getElementById("page-overlay").style.display = "block";
        }

        function hideOverlayLoader()
        {
            document.getElementById("page-overlay").style.display = "none";
        }

        setTimeout(function () {
            var packageUpdateStatus = '{{request()->subscription_update_status}}';
            // Only call when package upgrade or downgrade
            if(packageUpdateStatus){
                $.ajax({
                    processData: false,
                    contentType: false,
                    type: "get",
                    url: "{{ route('frontend.user.updated.user.package.detail') }}",
                    success: function (response) {
                        console.log(response)
                        $('#user-package-name').html(response.package)
                        $('#user-package-expiry').html(response.planExpiry)
                        $('#auth-user-dynamic-qr-code').html(response.dynamic_qr_codes)
                    }
                });
            }
        })

    </script>

    @yield('js')
</body>
</html>
