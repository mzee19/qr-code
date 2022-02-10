<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{__('Document')}}</title>
</head>
<body onload="redirectPage()">
@switch($generateQrCode->type)
    @case('text')
    {{$generateQrCode->data}}
    @break
    @case('email')
    <a id="open-email" href="{{$generateQrCode->data}}"></a>
    @break
    @case('app_store')
    {{$generateQrCode->data}}
    @break
@endswitch

<script src="{{asset('js/bootstrap_js/jquery-3.3.1.min.js')}}"></script>

<script>
    var redirectType = '{{($generateQrCode->type == 'text' || $generateQrCode->type ==  'email') ? false: true}}';
    $("document").ready(function () {
        $("#open-email")[0].click();
    });

    @switch($generateQrCode->type)
        @case('text')

        @break
        @case('email')

        @break
        @case('app_store')
        {{$generateQrCode->data}}
        @break
        @default
        if (redirectType) {
            function redirectPage() {
            window.location.href = '{{$generateQrCode->data}}';
            }
          }
        @break
    @endswitch

</script>

</body>
</html>
