<section class="effrctive-faq" id="faqs">
    <div class="effrctive-faq-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="studio-head pb-4">
                        <h6 class="get-more"><span>{{__('Help')}}</span></h6>
                        <h3 class="studio-heading">{{__('FREQUENTLY ASKED QUESTIONS')}}</h3>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="key-features-accordion" id="keyaccordionExample">
                        <div class="row">
                            <!-- Key Feature col 1 -->
                            @foreach($faqs as $index => $faq)
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card" id="faqIndex{{$index+1}}"
                                         style="display: {{$index < 6 ? 'block': 'none'}}">
                                        <div class="card-header" id="keyheading{{$index+1}}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link"
                                                        type="button" data-toggle="collapse"
                                                        data-target="#keycollapse{{$index}}"
                                                        aria-expanded="{{$index == 0 ? 'true' : 'false'}}"
                                                        aria-controls="keycollapse{{$index}}">
                                                    {{translation($faq->id,3,App::getLocale(),'question',$faq->question)}}
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="keycollapse{{$index}}"
                                             class="collapse"
                                             aria-labelledby="keyheading{{$index+1}}"
                                             data-parent="#keyaccordionExample">
                                            <div class="card-body">
                                                {!!  translation($faq->id,3,App::getLocale(),'answer',$faq->answer)!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($faqs->count() > 6)
                            <div class="d-flex justify-content-center loadmore-option">
                                <a onclick="loadMore(isLoadMore)" class="btn btn-link" href="javascript:void(0)"
                                   id="load-more">
                                   <!-- <span><i class="fa fa-arrow-down"></i></span> -->
                                    {{__('Load More')}}</a>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center align-items-center flex-column pt-5">
                            <h6>{{__('Not enough to answer your query?')}}</h6>
                            <div class="d-flex feature-buttons">
                            <!-- <a href="{{route('frontend.contact')}}"><strong>{{__('Contact Us')}}</strong></a> -->
                                <a href="{{route('frontend.contact')}}"type="button" class="button-dark-blue">{{__('Contact Us')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
