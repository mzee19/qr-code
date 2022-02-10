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

        <title>{{env('APP_NAME')}}</title>

        <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/favicon.png') }}"/>

        <!-- Bootstrap 4.5 CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
        <!-- FontAwesome File -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Custom Front pages Design -->
        <link rel="stylesheet" href="{{ asset('css/frontpages-style.css') }}">
    </head>
    <body style="background: #e8eef2;" class="{{app()->getLocale()}}">
        <!------------------- Start Wrapper ---------------->
        <div class="wrapper">

        @yield('content')
        </div>
        <!-------------------- End Wrapper ----------------->
        <!-- Bootstrap Scripts -->
        <script src="{{ asset('js/bootstrap_js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap_js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin-assets/js/jquery.validate.js') }}"></script>

        <script type="text/javascript" src="{{ asset('js/custom-js.js') }}"></script>


        <script type="text/javascript">
            function readImageURL(input,id,types,error_type_id,media_type)
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
                        error_message = 'The '+media_type+' must be a file of type: '+media_type+'/' + error_message;
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

            // Range Slider
            $("#my-range").on('input', function () {
                $("#qr-code-width").text(this.value);
                $("#qr-code-height").text(this.value);
            });
            if(!$('.alert').hasClass('persist-alert'))
            {
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            }
        </script>
        @yield('after-js')
    </body>
</html>
