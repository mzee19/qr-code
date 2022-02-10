<section class="qr-studio-section" >
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="studio-head">
                    <h6 class="get-more"><span>{{__('Generate high quality QR codes with')}}</span></h6>
                    <h3 class="studio-heading">{{__('QR CODE')}}</h3>
                </div>
            </div>
            @foreach($features as $index => $feature)
                <div class="col-lg-4 col-sm-6">
                    <div class="studio-box studio-box-{{$index+1}}">
                        <div class="studio-img">
                            <img height="100"
                                 src="{{checkImage(asset('storage/features/' . $feature->image),'placeholder.png',$feature->image)}}"
                                 alt="Quality">
                        </div>
                        <div class="studio-content">
                            <h6>{{translation($feature->id,6,App::getLocale(),'name',$feature->name)}}</h6>
                            <span>{{translation($feature->id,6,App::getLocale(),'description',$feature->description)}}</span>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="custom-qr-code" id="how-it-work">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="mobile-image-box">
                        <img src="{{asset('images/mobile-image.png')}}" alt="Quality">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-section">
                        <div class="studio-head pb-4">
                            <h6 class="get-more"><span>{{__('How it works?')}}</span></h6>
                            <h3 class="studio-heading">{{__('CUSTOM QR CODES WITH LOGO')}}</h3>
                        </div>
                        <div class="qr-codes-features">
                            <div class="qr-feature">
                                <h5 class="heading-content">{{__('Set QR Code Content')}}</h5>
                                <p>{{__('Begin with choosing the content type to be at the top of your QR code, this content can be URL, Email, VCards, etc. Once you have selected the type, you will notice the form will update its fields accordingly.')}}</p>
                            </div>
                            <div class="qr-feature">
                                <h5 class="heading-design">{{__('Customize Design')}}</h5>
                                <p>{{__('To make your QR code look unique, you need to set a custom design for it. The colors, corners and the body of the QR code can be customized, and that'."'".'s not it, you can also add a logo to your QR codes.')}}</p>
                            </div>
                            <div class="qr-feature">
                                <h5 class="heading-generate">{{__('Generate QR Code')}}</h5>
                                <p>{{__('Set the pixel resolution of your QR code with the slider. Once done, click the "Create QR Code" button to see your QR Code preview. Check the QR Code once by scanning it to ensure it is working fine.')}}</p>
                            </div>
                            <div class="qr-feature">
                                <h5 class="heading-download-img">{{__('Download')}}</h5>
                                <p>{{__('You’re all set to go! Now you may download the image files for your QR code as PNG, SVG, PDF or EPS vector graphics.')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                     <div class="float-right mt-sm-5 mt-3 d-flex">
                        <a href="javascript:void(0)" onclick="topFunction()" class="get-started button mr-2">
                            {{__('Create Your QR Code')}}
                        </a>

                        <a href="{{url('register')}}" type="button" class="get-started button">{{__('Explore More With QR Code')}}</a>
                    </div>
                </div>
            </div>
       </div>
</section>
