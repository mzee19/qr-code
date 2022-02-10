@extends('frontend.layouts.page')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="qr-cms--page studio-head text-center">
                    <div class=row>
                        <div class="ml-auto mr-auto col-md-7 col-12">
                            @if(session()->get('flash_custom_message'))
                                <div class="alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    {!! session()->get('flash_custom_message') !!}
                                </div>
                            @endif
                        </div>
                    </div>

{{--                    <h6 class="get-more"><span>&nbsp;</span></h6>--}}
{{--                    <h3 class="studio-heading">{{$title}}</h3>--}}
                </div>
                <div class="cms-pages-text"><p>{!! $content !!}</p></div>

            </div>
        </div>
    </div>
@endsection
