@foreach ($userTemplates as $userTemplate)
<div class="col-md-3 col-sm-4 col-6" id="template-image-id-{{Hashids::encode($userTemplate->id)}}">
    <div class="template">
        <div class="qrcode-container">
            <div class="qrcode" onclick="templateConfigData(' {{Hashids::encode($userTemplate->id)}} ')">
                <img
                    src="{{checkImage(asset('storage/users/' . $userTemplate->user_id . '/qr-codes/templates/' . $userTemplate->image), 'default.svg', $userTemplate->image)}}"
                    class=" ng-lazyloaded">
            </div>
        </div>
        <div class="options">
            <div class="row">
                <div class="col type">
                    <span>{{$userTemplate->crop == true ? __('Transparent') : __('Classic')}}</span>
                </div>
                <div class="col-auto">
                    <a href="javascript:void(0)"
                       onclick="deleteTemplate('{{Hashids::encode($userTemplate->id)}}') ">
                        <i class="fa fa-trash delete"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
