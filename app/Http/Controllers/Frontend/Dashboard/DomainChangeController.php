<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GenerateQrCode;
use App\Models\UserDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DomainChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['user'] = auth()->user();
        $data['action'] = 'listing';
//        dd(request()->all());

        if(request()->action != '1'){
            if (isset($data['user']->userDomain)) {
                if ($data['user']->userDomain->is_verified && $data['user']->userDomain->status) {
                    $domainName = $data['user']->userDomain->domain;
                } else {
                    $domainName = 'www.ned.link';
                }
            } else if(empty(request()->generate_qr_code_id)) {
                $domainName = 'www.ned.link';
            } else {
                $qrCode = GenerateQrCode::find(request()->generate_qr_code_id);

                $domainName = !empty($data['user']->userDomain) == true ? $data['user']->userDomain->domain : 'www.ned.link:8000';

                if(!empty($qrCode)){
                    $editDomainStatus = strpos($qrCode->ned_link,$domainName);
                    if ($editDomainStatus){
                        $domainName = $domainName;
                    } else{
                        $domainName = 'www.ned.link';
                    }
                }
            }
        } else{
            $domainName = 'qrcode.info/q';
        }

//        dd(request()->generate_qr_code_id);
        if (request()->generate_qr_code_id) {
            $qrCode = GenerateQrCode::find(request()->generate_qr_code_id);

            $domainName = !empty($data['user']->userDomain) == true ? $data['user']->userDomain->domain : 'www.ned.link';

            if(!empty($qrCode)){
                $editDomainStatus = strpos($qrCode->ned_link,$domainName);
                if ($editDomainStatus){
                    $domainName = $domainName;
                } else{
                    $domain = explode('/',$qrCode->ned_link);
                    if(empty($qrCode->ned_link)){
                        $domain = explode('/',$qrCode->short_url);
                        $domain[3] = 'qr-code';
                    }
                    $domainName = $domain[2];
                    if (isset($domain[4])){
                        $domainName .= '/'.$domain[3];
                    }
                }
            }
        }

        return response()->json([
            'status' => 1,
            'domain' => $domainName,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        $data['domainName'] = $request->domain;
        $data['action'] = 'Add';
        $validator = Validator::make($request->all(), [
            'domain' => 'required|max:30|regex:/^www\.[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](\.[a-zA-Z]{2,})+$/'
        ],[
            'domain.required' => __('The domain field is required.'),
            'domain.max' => __('The domain may not be greater than 30.'),
            'domain.regex' => __('The domain is invalid.'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->messages()
            ]);
        }

        $data['user'] = auth()->user();
        $response = getNedLinkDomainName($data);
        if (!$response['status']) {
            return response()->json([
                'status' => 0,
                'message' => [$response['message']]
            ]);
        }
        $model = new UserDomain([
            'user_id' => $data['user']->id,
            'ned_link_domain_id' => $response['domainId'],
            'domain' => $data['domainName'],
            'status' => false,
        ]);

        $model->save();

        Session::flash('flash_success_auto_remove', $response['message']);

        return response()->json([
            'status' => 1,
            'url' => route('frontend.user.setting')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy()
    {
        $data['domain'] = auth()->user()->userDomain;
        $data['action'] = 'DELETE';

        $response = getNedLinkDomainName($data);
        if (!$response['status']) {
            return response()->json([
                'status' => 0,
                'message' => [$response['message']]
            ]);
        }

        $data['domain']->delete();

        Session::flash('flash_success', $response['message']);

        return response()->json([
            'status' => 1,
            'url' => route('frontend.user.setting')
        ]);
    }

    public
    function changeUrl(Request $request)
    {
        if(empty($request->_id)){
            $qrCode = GenerateQrCode::where('unique_id',$request->customizeBackHalf)->first();
            $qrCodeId = empty($qrCode) ? '0' : $qrCode->id;
            $requestAll = $request->all();
            $requestAll['unique_id'] = $request->customizeBackHalf;
            $messages = [
                'unique_id.required' => __('The back half field is required.'),
                'unique_id.unique' => __('The back half must be a unique.'),
            ];
            // Validate the form data
            $validator = Validator::make($requestAll, [
                'unique_id' => 'required|unique:generate_qr_codes,unique_id,'.$qrCodeId,
            ], $messages);
            if($validator->fails()){
                $response['status'] = 0;
                $response['message'] = $validator->errors()->first();
            } else{
                $response['status'] = 1;
                $response['message'] = __('Link updated successfully');
            }
        } else{
            $data['customizeBackHalf'] = $request->customizeBackHalf;
            $data['_id'] = $request->_id;
            $data['from'] = 'QRC';
            $data['title'] = $request->title;
            $data['action'] = 'update-back-half';
            $data['status'] = isset($request->status) ? true : false;
            $response = getNedLinkDomainName($data);
        }

        if($response['status']){
            Session::flash('flash_info', $response['message']);
            return response()->json([
                'status' => 1,
                'message' => $response['message']
            ]);
        } else{
            return response()->json([
                'status' => 0,
                'message' => [$response['message']]
            ]);
        }
    }

    public function changeDomainStatus(Request $request){
        $data['domainStatus'] = $request->status;
        $data['user'] = auth()->user();
        $data['domain'] = auth()->user()->userDomain;
        $data['action'] = 'change-status';
        $response = getNedLinkDomainName($data);

        if (!$response['status']) {
            return response()->json([
                'status' => 0,
                'message' => [$response['message']]
            ]);
        }

        if($data['domainStatus'] == 'false'){
            $data['domainStatus'] = false;
        } else {
            $data['domainStatus'] = true;
        }

        $data['domain']->update(['status'=>(boolean)$data['domainStatus']]);

        Session::flash('flash_success', $response['message']);

        return response()->json([
            'status' => 1,
            'url' => route('frontend.user.setting')
        ]);
    }

}
