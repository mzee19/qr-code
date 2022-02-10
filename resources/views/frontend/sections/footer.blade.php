<footer class="footer">
    <div class="footer-content text-center">
        <h2 class="footer-title">{{__('I’d Like to Know New')}} {{__('Features')}}</h2>
        <form action="{{ url('/subscriber') }}" method="post" class="subscriber-form">
            @csrf
            <div class="form-group">
                <input class="footer-input" type="email" name="email" placeholder="{{ __('Enter your email') }}..."
                       required="">
                <input class="submit" type="submit" value="{{ __('Subscribe') }}">
            </div>
        </form>
        <div class="social-icons">
            @if(!empty(settingValue('facebook')))
                <a href="{{ settingValue('facebook') }}" target="_blank">
                    <i class="fa fa-facebook-f"></i>
                </a>
            @endif
            @if(!empty(settingValue('twitter')))
                <a href="{{ settingValue('twitter') }}" target="_blank">
                    <i class="fa fa-twitter"></i>
                </a>
            @endif
            @if(!empty(settingValue('youtube')))
                <a href="{{ settingValue('youtube') }}" target="_blank">
                    <i class="fa fa-youtube-play" aria-hidden="true"></i>
                </a>
            @endif
            @if(!empty(settingValue('linkedin')))
                <a href="{{ settingValue('linkedin') }}" target="_blank">
                    <i class="fa fa-linkedin" aria-hidden="true"></i>
                </a>
            @endif
            @if(!empty(settingValue('pinterest')))
                <a href="{{ settingValue('pinterest') }}" target="_blank">
                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                </a>
            @endif

        </div>
        <div class="footer-nav">
            <nav class="navbar navbar-expand-lg">
                <div class="collapse navbar-collapse footer-nav" id="navbarTogglerDemo02">
                    <ul class="navbar-nav">
                        @foreach(cmsPages() as $page)
                            @if($page->slug == 'support' || $page->slug == 'license-agreement')
                            @else
                                <li class="nav-item active">
                                    <a class="nav-link"
                                       href="{{ url('/pages/'.$page->slug) }}">{{ translation($page->id,5,App::getLocale(),'title' ,$page->title)}}</a>
                                </li>
                            @endif
                        @endforeach
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ route('frontend.contact') }}">{{__('Contact Us')}}</a>
                            </li>
                    </ul>

                </div>
            </nav>
            <div class="right-img">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-7 text-md-left text-center">
                            <span
                                class="footer-logo-text">{{__('Copyright')}} © {{settingValue('company_name')}}. {{__('All rights reserved.')}}</span>
                        </div>
                        <div class="col-md-5 text-md-right text-center">
                            <span class="footer-logo-text">{{__('Powered by')}} ArhamSoft (Pvt) Ltd.</span>
                            <a href="https://www.arhamsoft.com/" target="_blank"><img
                                    src="{{asset('images/ar-logo.svg')}}" class="img-fluid" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>



@section('js')
    <script>
        $(function () {
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
