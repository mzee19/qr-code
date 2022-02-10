@extends('frontend.layouts.dashboard')

@section('title', __('Support'))


@section('content')
    <div class="content-body">
        <div class="comon-title section-title pt-3 pb-3 text-center">
            <h2 class="welcome">{{__('Support')}}</h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2 class="title"><strong>{{__('Frequently Asked Questions')}}</strong></h2>
                </div>
                <div class="customer-support">
                    <div class="cardbox-faq">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="key-features-accordion" id="keyaccordionExample">
                                    <div class="row">
                                        <!-- Key Feture col 1 -->
                                        @foreach($faqs as $index => $faq)
                                            <div class="col-md-6">
                                                <div class="card" id="faqIndex{{$index+1}}"
                                                     style="display: {{$index < 6 ? 'block': 'none'}}">
                                                    <div class="card-header" id="keyheading{{$index+1}}">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link " type="button"
                                                                    data-toggle="collapse"
                                                                    data-target="#keycollapse{{$index}}"
                                                                    aria-expanded="{{$index == 0 ? 'true' : 'false'}}"
                                                                    aria-controls="keycollapse{{$index}}">
                                                                {{translation($faq->id,3,App::getLocale(),'question',$faq->question)}}
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="keycollapse{{$index}}" class="collapse "
                                                         aria-labelledby="keyheading{{$index+1}}"
                                                         data-parent="#keyaccordionExample" style="">
                                                        <div class="card-body">
                                                            {!! translation($faq->id,3,App::getLocale(),'answer',$faq->answer)!!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($faqs->count() > 6)
                                        <div class="d-flex justify-content-center loadmore-option">
                                            <a onclick="loadMore(isLoadMore)" class="btn btn-link"
                                               href="javascript:void(0)"
                                               id="load-more">{{__('Load More')}}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-title text-center mt-3">
                    <h2 class="title"><strong>{{__('Found no Answer')}}</strong></h2>
                    <span>{{__('Write us at')}}
                         <a href="mailto:'{{settingValue('contact_email')}}'"><strong>{{settingValue('contact_email')}}</strong</a></span>
                </div>

            </div>

        </div>

    </div>

@endsection



@section('js')
    <script>
        var isLoadMore = 1;

        function loadMore(loadMoreStatus) {
            if(loadMoreStatus == 1){
                let countFaq = '{{$faqs->count()}}';
                for (i = 7; i <= countFaq; i++) {
                    $('#faqIndex' + i).css('display', 'block');
                }

                this.isLoadMore = 2;
                $('#load-more').parent().removeClass('loadmore-option');
                $('#load-more').parent().addClass('lessmore-option');
                $('#load-more').html('{{__('Load Less')}}')

            } else{
                let countFaq = '{{$faqs->count()}}';
                for (i = 7; i <= countFaq; i++) {
                    $('#faqIndex' + i).css('display', 'none');
                }
                this.isLoadMore = 1;
                $('#load-more').parent().removeClass('lessmore-option');
                $('#load-more').parent().addClass('loadmore-option');
                $('#load-more').html('{{__('Load More')}}')
            }
        }
    </script>
@endsection
