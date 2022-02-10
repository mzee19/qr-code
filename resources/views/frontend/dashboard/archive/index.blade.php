@extends('frontend.layouts.dashboard')

@section('title', __('Archive'))

@section('content')

<div class="content-body">
    <div class="comon-title section-title pt-3 pb-3 text-center">
        <h2 class="welcome">{{__('Archive ')}}</h2>
    </div>
	@include('frontend.messages')
	<form action="{{ url('/archive') }}" method="get">
        <input type="hidden" name="limit" value="{{ $limit }}">
		<div class="row pt-4">
            @if(getCount('generate_qr_codes' , array('archive' => 1,'user_id'=>\Illuminate\Support\Facades\Auth::id())) > 0)
			  <div class="col-lg-4 col-md-12 mb-2 archieve-btns d-flex alogn-items-center">
				<button class="btn btn-outline-secondary" title="{{__('Clean Archive')}}" type="button" data-toggle="modal" data-target="#clean-archive-modal"><i class="fa fa-trash"></i> {{__('Clean Archive')}}</button>
				<button class="btn btn-outline-secondary" title="{{__('Restore Archive')}}" type="button" data-toggle="modal" data-target="#restore-archive-modal"><i class="fa fa-ok"></i> {{__('Restore Archive')}}</button>
				<!-- Modal Clean -->
                <div class="modal fade" id="clean-archive-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('Clean Archive')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            {{__('Do you really want to delete all archived QR codes')}}?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                                <a href="{{route('frontend.user.archive.clean')}}" type="button" class="btn btn-danger">{{__('Clean')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal restorea ll -->
                <div class="modal fade" id="restore-archive-modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('Clean Archive')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            {{__('Do you really want to restore all archived QR codes')}}?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                                <a href="{{route('frontend.user.archive.restore.all')}}" type="button" class="btn btn-danger">{{__('Restore')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            @endif
			<div class="col-lg-4 col-md-6 col-12 mb-2">
	            <select class="form-control ng-valid ng-dirty ng-touched" name="sort">
	                <option value="name-asc" {{ $sort == "name-asc" ? "selected" : "" }}>↑ {{__('Name')}}</option>
	                <option value="name-desc" {{ $sort == "name-desc" ? "selected" : "" }}>↓ {{__('Name')}}</option>
	                <option value="scans-asc" {{ $sort == "scans-asc" ? "selected" : "" }}>↑ {{__('Scans')}}</option>
	                <option value="scans-desc" {{ $sort == "scans-desc" ? "selected" : "" }}>↓ {{__('Scans')}}</option>
	                <option value="updated_at-asc" {{ $sort == "updated_at-asc" ? "selected" : "" }}>↑ {{__('Updated')}}</option>
	                <option value="updated_at-desc" {{ $sort == "updated_at-desc" ? "selected" : "" }}>↓ {{__('Updated')}}</option>
	                <option value="created_at-asc" {{ $sort == "created_at-asc" ? "selected" : "" }}>↑ {{__('Created')}}</option>
	                <option value="created_at-desc" {{ $sort == "created_at-desc" ? "selected" : "" }}>↓ {{__('Created')}}</option>
	            </select>
	        </div>
            <div class="col-lg-4 col-md-6 col-12 mb-2 pl-lg-0">
	            <div class="input-group">
	                <span class="input-group-addon"><i class="fa fa-search"></i></span>
	                <input class="form-control ng-untouched ng-pristine ng-valid" placeholder="{{ __('Search by Name or Type') }}" type="text" name="text" value="{{ $text }}">
	            </div>
	        </div>
	        <div class="col-sm-auto col-md-auto col-12 ml-auto mb-2 qr--button">
	            <div class="input-group">
	                <button class="btn btn-orange mr-sm-2 mr-0" type="submit">
	                    <i class="fa fa-check"></i> {{__('Apply')}}
	                </button>
	                <a class="btn btn-primary mt-sm-0 mt-2" href="{{route('frontend.user.archive.index')}}">
	                    <i class="fa fa-refresh"></i> {{__('Reset')}}
	                </a>
	            </div>
	        </div>
		</div>
	</form>
	@if(!$qrCodes->isEmpty())
		<div class="row">
			<div class="col-12">
				<div class="list archieve-list">
					<div class="row">
						@foreach($qrCodes as $qrCode)
							<div class="col-lg-6 col-sm-12 col-12 mb-2">
								<div class="list-item archieve-list-item">
									<a class="inner" href="{{route('frontend.user.qr-codes.edit',Hashids::encode($qrCode->id))}}">
										<div class="thumb list-col">
											<img src="{{checkImage(asset('storage/users/'.$qrCode->user_id.'/qr-codes/' . $qrCode->image),'default.svg',$qrCode->image)}}" class="ng-lazyloaded">
										</div>
										<div class="info  list-col">
												<div class="scans">
													<span class="badge badge-secondary text-color">{{ $qrCode->code_type == 1 ? __('Dynamic') : __('Static') }}</span>
												</div>
												<div class="title mt-2">
													<i class="{{$qrCode->icon}}"></i>
	                                                {{$qrCode->name}}
												</div>
												<div class="fact">
													<span class="name">{{__('Type')}}:</span> {{ucwords($qrCode->type)}}
												</div>

                                                @if($qrCode->code_type == 1)
                                                    <div class="fact">
                                                        <span class="name">{{__('Short-URL')}}:</span>
                                                        <span data-toggle="tooltip" data-placement="top"
                                                              title="{{$qrCode->ned_link ? $qrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$qrCode->unique_id}}">
	                                                           {{$qrCode->ned_link ? $qrCode->ned_link : Request::getSchemeAndHttpHost().'/qr-code/'.$qrCode->unique_id}}
                                                        </span>
	                                                </div>
	                                            @endif
	                                            <div class="fact">
	                                                <span class="name">{{__('Created')}}:</span>
	                                                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($qrCode->created_at), "UTC")->tz(auth()->user()->timezone)->format('d/m/Y - H:i') }}
	                                            </div>
	                                            @if($qrCode->campaign)
	                                                <div class="campaign">
	                                                    <i class="fa fa-folder-o"></i> {{$qrCode->campaign->name}}
	                                                </div>
	                                            @endif

										</div>

									</a>
									<div class="list-col options">
										<div class="d-flex" style="column-gap: 20px;">
											<button class="btn btn-danger btn-sm" title="{{__('Delete QR Code')}}" type="button" data-toggle="modal" data-target="#delete-model-{{ $qrCode->id }}">
												<i class="fa fa-times"></i> {{__('Delete')}}
											</button>
											<button class="btn btn-success btn-sm" title="{{__('Restore QR Code')}}" type="button" data-toggle="modal" data-target="#restore-model-{{ $qrCode->id }}">
												<i class="fa fa-undo"></i> {{__('Restore')}}
											</button>
										</div>
									</div>
								</div>
							</div>
							<!-- Modal Delete -->
                            <div class="modal fade" id="delete-model-{{ $qrCode->id }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{__('Delete QR Code')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        {{__('Do you really want to delete this QR code')}}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                                            <form method="POST" action="{{route('frontend.user.qr-codes.destroy',Hashids::encode($qrCode->id))}}" accept-charset="UTF-8" style="display:inline">
			                                    <input type="hidden" name="_method" value="DELETE">
			                                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
			                                    <button class="btn btn-danger" title="{{__('Delete')}}">{{__('Delete')}}</button>
			                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<!-- Modal Restore -->
                            <div class="modal fade" id="restore-model-{{ $qrCode->id }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{__('Restore QR Code')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        {{__('Do you really want to restore this QR code')}}?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                                            <a href="{{route('frontend.user.qr-codes.restore',Hashids::encode($qrCode->id))}}" type="button" class="btn btn-primary">{{__('Restore')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
						@endforeach
					</div>

					<div class="list-footer pt-3">
                        <div class="row">
                            <div class="col-auto">
                                <select class="form-control" id="limit">
                                    @foreach($limits as $val)
                                        <option value="{{ $val }}" {{ $limit == $val ? "selected" : "" }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-auto col-12 ml-sm-auto text-sm-right text-center">
                                <strong>{{ $qrCodes->firstItem() }} - {{ $qrCodes->lastItem() }}</strong> {{__('of')}}
                                <strong>{{ $qrCodes->total() }}</strong>
                                @include('frontend.dashboard.partials.paginator',['paginators'=> $qrCodes])
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	@else
		<br>
		<div class="row">
            <div class="col-12">
                <div class="alert alert-light persist-alert text-center mb-0">{{__('No QR Codes in Archive')}}.</div>
            </div>
        </div>
	@endif
</div>

@endsection

@section('js')
<script>
    $(function(){
        $('#limit').on('change', function() {
            var search = location.search;

            if(search != '')
            {
                var url = new URL(window.location.href);
                url.searchParams.set("limit", $(this).val());
                window.location.href = url.href;
            }
            else
            {
                window.location.href = "{{ url('/archive') }}" + '?limit=' + $(this).val();
            }
        });
    });
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endsection
