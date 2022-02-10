@extends('frontend.layouts.dashboard')

@section('title', __('Account'))

@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <div class="content-body">
        <div class="comon-title section-title pt-3 pb-3 text-center">
            <h2 class="welcome">{{__('Account')}}</h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="list setting-module-list">
                    @include('admin.messages')

                    <div class="row">
                        <div class="col-sm-6 col-md-4 mb-2">
                            <div class="list-item settings-item">
                                <a class="inner" href="{{route('frontend.user.setting')}}">
                                    <div class="icon list-col">
                                        <i class="fa fa-cog"></i>
                                    </div>
                                    <div class="info">
                                        <div class="title">{{__('General Settings')}}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class=" col-sm-6 col-md-4  mb-2">
                            <div class="list-item settings-item">
                                <a class="inner" href="{{route('frontend.user.upgrade.package')}}">
                                    <div class="icon list-col">
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="info">
                                        <div class="title">{{__('Subscription')}}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class=" col-sm-6 col-md-4  mb-2">
                            <div class="list-item settings-item">
                                <a class="inner" href="{{route('frontend.user.subscriptions')}}">
                                    <div class="icon list-col">
                                        <i class="fa fa-history"></i>
                                    </div>
                                    <div class="info">
                                        <div class="title">{{__('Subscriptions History')}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 mb-2">
                            <div class="list-item settings-item">
                                <a class="inner" href="{{route('frontend.user.invoices')}}">
                                    <div class="icon list-col">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <div class="info">
                                        <div class="title">{{__('Invoices')}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @if(checkFieldStatus(14))
                            <div class="col-sm-6 col-md-4 mb-2">
                                <div class="list-item settings-item">
                                    <a href="javascript:void(0)" class="inner" data-toggle="modal"
                                       data-target="#white-label-short-url-modal">
                                        <div class="icon list-col">
                                            <i class="fa fa-link" aria-hidden="true"></i>
                                        </div>
                                        <div class="info">
                                            <div class="title">{{__('WhiteLabel Short URL')}}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <br><br>
                <!-- <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="list-item settings-item">
                                <a class="inner" href="{{route('frontend.user.invoices')}}">
                                    <div class="icon list-col">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <div class="info">
                                        <div class="title"> __('Invoices')
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    {{--    Modal--}}
    <div class="modal fade white-label-modal " id="white-label-short-url-modal" tabindex="-1" role="dialog"
         aria-labelledby="white-label-short-url-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('WhiteLabel Short URL')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="domain-instruction">
                        <ul>
                            <li>
                                <small><i>{{__('Domain name must be less than 30 characters including the dot.')}}</i></small>
                            </li>
                            <li>
                                <small><i>{{__('Set your DNS A Record to make your Custom Domain point to 138.201.78.234')}}</i></small>
                            </li>
                            <li><small><i>{{__('Learn more about')}}<a target="_blank"
                                                                       href="https://help.one.com/hc/en-us/articles/360000799298-How-do-I-create-an-A-record">{{' '.__('DNS Record Setup')}}</a></i></small>
                            </li>
                        </ul>
                    </div>
                    <div class="print-error-msg"></div>
                    <div class="form-group dlt-btn-des">
                        <label for="addDomain">{{__('Domain')}}</label>
                        @if(isset(auth()->user()->userDomain->domain))
                            @if(auth()->user()->userDomain->is_verified)
                                <i class="fa fa-check tick-success" aria-hidden="true"></i>
                            @endif
                            <a href="javascript:void(0)" type="button" class="btn" data-toggle="modal"
                               data-target="#delete-domain-modal"
                            ><span class="fa fa-trash dlt-danger"></span></a>
                        @endif
                        <input type="text" name="domain" class="form-control" id="addDomain" maxlength="30"
                               placeholder="{{__('Add Domain')}}" value="{{auth()->user()->userDomain->domain ?? ''}}"
                               required {{isset(auth()->user()->userDomain->domain) ?'readonly': ''}}>
                    </div>
                    @if(isset(auth()->user()->userDomain->domain))
                        <div class="form-group d-flex justify-content-between">
                            <div>
                                <label for="addDomain">{{__('Set domain as default')}}</label>
                            </div>
                            <div>
                                <label class="switch">
                                    <input name="domain-status" class="form-control" id="domain-status" type="checkbox"
                                           {{auth()->user()->userDomain->status == true ? 'checked' : ''}} onclick="changeDomainStatus()">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    @endif

                </div>
                @if(!isset(auth()->user()->userDomain->domain))
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="button" class="btn btn-primary" onclick="saveDomain(this)">{{__('Save')}}</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade savedomain-modal" id="save-domain-modal" tabindex="-1" role="dialog"
         aria-labelledby="save-domain-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body pb-0">
                    <h4 class="text-center">{{__('It will be activated and available after verification.')}}</h4>
                    <p class="text-center"><strong>{{__('Note')}}
                            : </strong><small><i>{{__('Domain verification may take upto 24 - 48 hours. So you`ll be able to use it once it is verified.')}}</i></small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">{{__('OK')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade delete-domain-modal" id="delete-domain-modal" tabindex="-1" role="dialog"
         aria-labelledby="delete-domain-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Delete Domain')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('Do you really want to delete this domain')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>

                    <a class="btn btn-primary" href="javascript:void(0)" onclick="deleteDomain()">{{__('Delete')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var domainRefreshStatus = false;
        $(document).ready(function () {
            addDomainStatus();
        })

        function saveDomain(ele) {
            var data = {
                'domain': $('#addDomain').val()
            }
            $.ajax({
                // processData: false,
                // contentType: false,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.store.domain') }}",
                data: data,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $('#white-label-short-url-modal').modal('hide');
                        // $('#save-domain-modal').modal('show');
                        // $('#save-domain-modal').modal('show');
                        domainRefreshStatus = true
                    } else {
                        printErrorMsg(response.message)
                    }
                    console.log(response)
                }
            });
        }

        function changeDomainStatus(ele) {
            var data = {
                'status': $('#domain-status').is(":checked"),
            }
            console.log(data)
            $.ajax({
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('frontend.user.status.domain')}}',
                data: data,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $('#white-label-short-url-modal').modal('hide');

                        window.location.reload()
                    } else {
                        printErrorMsg(response.message)
                    }
                }
            });
        }


        function deleteDomain(ele) {

            var data = {
                'domain': $('#addDomain').val()
            }
            // if (confirm(confirmText)) {
            $.ajax({
                // processData: false,
                // contentType: false,
                type: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('frontend.user.delete.domain') }}",
                data: data,
                enctype: 'multipart/form-data',
                success: function (response) {
                    if (response.status == 1) {
                        $('#delete-domain-modal').modal('hide');
                        $('#white-label-short-url-modal').modal('hide');
                        window.location.reload()
                    } else {
                        printErrorMsg(response.message)
                    }
                    console.log(response)
                }
            });
        }

        function printErrorMsg(msg) {
            $(".print-error-msg").css('display', 'block');
            $(".print-error-msg").addClass('alert-danger');
            $.each(msg, function (key, value) {
                $(".print-error-msg").append('<span id=' + key + '>' + value + '</span>');
                removeErrorMessage(key)
            });
        }

        //      Remove laravel error message after sometime
        function removeErrorMessage(key) {
            var index = '#' + key;
            setTimeout(function () {
                $(index).fadeOut('slow');
                $(".print-error-msg").removeClass('alert-danger');
                $(index).remove();
            }, 5000);
        }

        function addDomainStatus() {
            setInterval(function () {
                if (domainRefreshStatus && !$('#save-domain-modal').hasClass('show')) {
                    window.location.reload()
                }
            }, 2000)

        }
    </script>


@endsection

