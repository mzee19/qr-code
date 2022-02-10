<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\QRCodeGeneratorController;
use App\Models\Campaign;
use App\Models\GenerateQrCode;
use App\Models\Logo;
use App\Models\Shape;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Image;
use function Aws\boolean_value;
use function GuzzleHttp\Promise\all;

class BulkImportController extends Controller
{
    public $qr_code_generate;

    public function __construct(QRCodeGeneratorController $qr_code)
    {
        $this->qr_code_generate = $qr_code;
    }

    public function index()
    {
        if (empty(auth()->user()->bulk_import_limit)) {
            $flash_message = __('Package is not supported this feature');
            Session::flash('flash_info', $flash_message);
            return redirect()->back();
        }

        $parameters = request()->all();
        $parameters['unique_id'] = \Str::random(8,uniqid());
        $data['parameters'] = $parameters;
        $countQrCode = true;
        if ($data['parameters']['type'] == 'dynamic') {
            $countQrCode = getSubscriptionFeature('dynamic_qr_codes');
        }
        if ($data['parameters']['type'] == 'static') {
            $countQrCode = getSubscriptionFeature('static_qr_codes');
        }
        if (!$countQrCode) {
            Session::flash('flash_info', __('Please upgrade your package to get more packages'));
            return redirect()->back();
        }
        $data['campaigns'] = Campaign::Where('user_id', Auth::id())->get();
        $data['logos'] = Logo::where('status', 1)->get();
        $data['bodyShapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        $data['qrCodeFrames'] = Shape::where('type', 4)->where('status', 1)->get();
        $data['generateQrCode'] = new GenerateQrCode();
        if (checkFieldStatus(6)) {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();
        } else {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1, 'crop' => false])->get();
        }
        //        Admin QR Code
        $data['adminQrCodes'] = GenerateQrCode::where(['user_id' => null, 'status' => 1])->get();

        $data['action'] = "Add";
        $timezones = Timezone::all();
        return view('frontend.dashboard.bulk-import.index', $data, compact('timezones'));
    }

    public function selectContentType()
    {
        return view('frontend.dashboard.bulk-import.select_content_type');
    }

    public function qrCodeTypeIcon($type)
    {
        $icon = '';
        switch ($type) {
            case 'url':
                $icon = 'fa fa-link';
                break;
            case 'vcard':
                $icon = 'fa fa-address-card';
                break;
            case 'text':
                $icon = 'fa fa-file-text-o';
                break;
            case 'email':
                $icon = 'fa fa-envelope-o';
                break;
            case 'phone':
                $icon = 'fa fa-phone';
                break;
            case 'sms':
                $icon = 'fa fa-commenting';
                break;
            case 'app_store':
                $icon = 'fab fa-app-store-ios';
                break;
            case 'event':
                $icon = 'fa fa-calendar';
                break;
            case 'wifi':
                $icon = 'fa fa-wifi';
                break;
        }
        return $icon;
    }

    public function qrCodeTypeData($data)
    {
        switch ($data['type']) {
            case 'url':
                return $data['qrcodeUrl'];
            case 'vcard':
                $vCard = 'BEGIN:VCARD
VERSION:' . $data["vcardVersion"] . '
N:Gump;' . $data["qrcodeVcardFirstName"] . ';;Mr.,;
FN:' . $data["qrcodeVcardFirstName"] . '
ORG:' . $data["qrcodeVcardOrganization"] . '
TITLE:' . $data["qrcodeVcardTitle"] . '
PHOTO;VALUE=URI;TYPE=GIF:' . $data["qrcodeVcardUrl"] . '
TEL;TYPE=WORK,VOICE:' . $data["qrcodeVcardPhoneMobile"] . '
TEL;TYPE=HOME,VOICE:' . $data["qrcodeVcardPhoneMobile"] . '
ADR;TYPE=WORK,PREF:;;' . $data["qrcodeVcardStreet"] . ';' . $data["qrcodeVcardCity"] . ';' . $data["qrcodeVcardState"] . ';' . $data["qrcodeVcardZipcode"] . ';' . $data["qrcodeVcardCountry"] . '
LABEL;TYPE=WORK,PREF:' . $data["qrcodeVcardStreet"] . '\n' . $data["qrcodeVcardCity"] . '\, ' . $data["qrcodeVcardState"] . ' ' . $data["qrcodeVcardZipcode"] . '\n' . $data["qrcodeVcardCountry"] . '
EMAIL:' . $data["qrcodeVcardEmail"] . '
REV:2008-04-24T19:52:43Z
END:VCARD';
                return $vCard;
            case 'text':
                return $data['text'];
            case 'email':
                $email = 'mailto:' . $data['qrcodeEmail'] . '?subject=' . $data['qrcodeEmailSubject'] . '&body=' . $data['qrcodeEmailMessage'];
                return $email;
            case 'phone':
                $phone = 'tel:' . $data['qrcodePhone'];
                return $phone;
            case 'sms':
                $sms = 'sms:' . $data['qrcodeSmsPhone'] . '?body=' . $data['qrcodeSmsText'];
                return $sms;
            case 'wifi':
                $wifi = 'WIFI:S:' . $data['ssid'] . ';T:' . $data['encryption'] . ';P:' . $data['password'] . ';;';
                return $wifi;
            case 'event':
                $wifi = 'BEGIN:VEVENT↵VERSION:2.0↵SUMMARY:' . $data['summary'] . '↵LOCATION:↵DTSTART:' . $data['startDateTime'] . '↵DTEND:' . $data['endDateTime'] . '↵END:VEVENT↵';
                return $wifi;
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $arr = explode("\n", $request->bulk_text);
        $firstInputCount = count(explode(',',$arr[0]));

        // Bulk import limit check
        if (empty(auth()->user()->bulk_import_limit) || auth()->user()->bulk_import_limit < count($arr)) {
            $flash_message = __('Your package limit is ') . (!empty(auth()->user()->bulk_import_limit) ? auth()->user()->bulk_import_limit : 0) . __(' and uploaded record is ') . count($arr);
            Session::flash('flash_info', $flash_message);
            return response()->json([
                'status' => 0,
                'message' => $flash_message,
                'url' => url('bulk-import/index' . '?' . http_build_query(['content_type' => $request->type, 'type' => $request->qrCodeType])),
            ]);
        }

        unset($input['bulk_text']);
        $array1 = [];

        foreach ($arr as $key => $row) {
            $input = $request->all();
            $getContentTypeNames = $request->except('action','id','type','qrCodeType','uniqueId','dynamicUrl','config','name','campaign_id','bulk_text','image_id');
            foreach($getContentTypeNames as $contentTypeFieldName => $contentTypeFieldNameValue){
                $input[$contentTypeFieldName] = '';
            }
            $row_arr = explode(",", $row);
            foreach ($row_arr as $row_key => $row_arr_val) {
                foreach ($request->all() as $input_key => $val) {
                    if ((str_contains($input_key, 'qrcode') || $input_key == 'startDateTime' || $input_key == 'ssid' || $input_key == 'password' || $input_key == 'encryption' || $input_key == 'endDateTime' || $input_key == 'location' || $input_key == 'summary' || $input_key == 'qrcodeEventTimezone' || $input_key == 'qrcodeEventReminder' || $input_key == 'eventUrl')) {
                        if ($input_key == 'startDateTime' || $input_key == 'endDateTime') {
                            $date = $row_arr[intval($request->all()[$input_key])];
                            $dateFormatChange = str_replace('/', '-', $date);
                            $dateInTimestamp = strtotime($dateFormatChange);
                            $input[$input_key] = date('Y-m-d', $dateInTimestamp) . 'T' . date('H:i', $dateInTimestamp);
                        }elseif($input_key == 'encryption'){
                            $wirelessEncryption = ['WEP','WPA','nopass'];
                            if (in_array($row_arr[intval($request->all()[$input_key])],$wirelessEncryption)){
                                $input[$input_key] = $row_arr[intval($request->all()[$input_key])];
                            } else {
                                $input[$input_key] = 'nopass';
                            }
                        } elseif (strval($val) == strval($row_key)) {
                            $input[$input_key] = $row_arr[intval($request->all()[$input_key])];
                        }
                    } elseif ($input_key == 'text') {
                        if ($input[$input_key] == $row_key) {
                            $input[$input_key] = $row_arr[$request->text];
                        }
                    }
                    if ($request->name == null) {
                        $input['name'] = $key . ' ' . $input['type'];
                    } elseif ($request->name == $row_key) {
                        $input['name'] = $row_arr_val;
                    }
                }
            }
            unset($input['bulk_text']);
            unset($input['transparent_image']);
            unset($input['cropper_image_data']);
            unset($input['transparent_image_status']);
            $input['image_id'] = null;
            $req = new Request($input);

            if (isset($input['campaign_id'])) {
                $campaign_id = $input['campaign_id'];
            }

            $unique_id = \Str::random(8,uniqid());
            if ($input['qrCodeType'] == 'static') {
                $countQrCode = getSubscriptionFeature('static_qr_codes');

                if (!$countQrCode) {
                    Session::flash('flash_info', __('Please upgrade your package to get more QR Code'));
                    return response()->json([
                        'status' => 0,
                        'message' => __('Please upgrade your package to get more QR Code'),
                        'url' => url('bulk-import/index' . '?' . http_build_query(['content_type' => $request->type, 'type' => $request->qrCodeType])),
                    ]);
                }

                $dynamic_url = null;
                if($request->hasFile('logo_image')) {
                    $logoImage = $unique_id.'.'.$request->logo_image->getClientOriginalExtension();
                    Image::make($request->logo_image)->resize(200, 200)->save('storage/users/'.auth()->user()->id.'/qr-codes/logo-images'.'/'.$logoImage);
                    $input['logo_image'] = $logoImage;
                }

                if(!$request->hasFile('logo_image') && $request->logo_image && $request->logo_image != 'undefined') {
                    if(str_contains($request->logo_image,'.jpg')){
                        $getExt = '.jpg';
                    } elseif(str_contains($request->logo_image,'.jpeg')){
                        $getExt = '.jpeg';
                    } else {
                        $getExt = '.png';
                    }
                    $logoImage = $unique_id.$getExt;
                    $targetPath = 'users/'.auth()->user()->id.'/qr-codes/logo-images';
                    if(!file_exists($targetPath.'/' . $request->logo_image)){
                        Storage::disk('public')->copy('temp/' . $request->logo_image, $targetPath.'/'.$logoImage);
                    } else{
                        Storage::disk('public')->copy($targetPath.'/' . $request->logo_image, $targetPath.'/'.$logoImage);
                    }
                    $input['logo_image'] = $logoImage;
                }

                if($request->transparent_image && $request->transparent_image != 'null'){
                    $input['transparentImage'] = $request->transparent_image;
                    $input['size'] = json_decode($request->cropper_image_data,true)['size'];
                    $input['x'] = json_decode($request->cropper_image_data,true)['x'];
                    $input['y'] = json_decode($request->cropper_image_data,true)['y'];
                    $input['logo_image'] = null;

                }
                $req = new Request($input);
                $req = new Request($req->except('campaign_id', 'name', 'qrcodeName', 'image_id'));
                if ($firstInputCount == count($row_arr)){
                    $qr_code = $this->qr_code_generate->generateQrCode($req);
                }

            } else {
                $countQrCode = getSubscriptionFeature('dynamic_qr_codes');

                if (!$countQrCode) {
                    Session::flash('flash_info', __('Please upgrade your package to get more QR Code'));
                    return response()->json([
                        'status' => 0,
                        'message' => __('Please upgrade your package to get more QR Code'),
                        'url' => url('bulk-import/index' . '?' . http_build_query(['content_type' => $request->type, 'type' => $request->qrCodeType])),
                    ]);
                }

                $input['uniqueId'] = $unique_id;
                if($request->hasFile('logo_image')) {
                    $logoImage = $unique_id.'.'.$request->logo_image->getClientOriginalExtension();
                    Image::make($request->logo_image)->resize(200, 200)->save('storage/users/'.auth()->user()->id.'/qr-codes/logo-images'.'/'.$logoImage);
                    $input['logo_image'] = $logoImage;
                }

                if(!$request->hasFile('logo_image') && $request->logo_image && $request->logo_image != 'undefined') {
                    if(str_contains($request->logo_image,'.jpg')){
                        $getExt = '.jpg';
                    } elseif(str_contains($request->logo_image,'.jpeg')){
                        $getExt = '.jpeg';
                    } else {
                        $getExt = '.png';
                    }
                    $logoImage = $unique_id.$getExt;

                    $target_path = public_path('storage/users/' . auth()->user()->id . '/qr-codes/logo-images/' . $request->logo_image);
                    if (!file_exists($target_path)) {
                        $target_path = 'users/' . auth()->user()->id . '/qr-codes/logo-images/' . $request->logo_image;
                        Storage::disk('public')->copy('temp/' . $request->logo_image, $target_path);
                        $logoImage = $request->logo_image;
                    } else {
                        Storage::disk('public')->copy('temp/' . $request->logo_image, 'users/'.auth()->user()->id.'/qr-codes/logo-images/'.$logoImage);
                    }

                    $input['logo_image'] = $logoImage;
                }

                if($request->transparent_image && $request->transparent_image != 'null'){
                    $input['transparentImage'] = $request->transparent_image ;
                    $input['size'] = json_decode($request->cropper_image_data,true)['size'];
                    $input['x'] = json_decode($request->cropper_image_data,true)['x'];
                    $input['y'] = json_decode($request->cropper_image_data,true)['y'];
                    $input['logo_image'] = null;
                }

                $req = new Request($input);
                $req = new Request($req->except('name', 'campaign_id', 'dynamicUrl', 'action'));

                if ($firstInputCount == count($row_arr)){
                    $qr_code = $this->qr_code_generate->generateQrCode($req);
                }
            }
            $input['status'] = 1;

            $inputWithExceptData = $req->except('token', 'action', 'id', 'type', 'config', 'logo_image', 'name', 'image_id', 'campaign_id', 'uniqueId');
            $userData = $req->except('token', 'action', 'id', 'config', 'logo_image', 'name', 'image_id', 'campaign_id', 'qrCodeType', 'uniqueId');

            $configData = json_decode($req->config, true);
            if ($input['action'] == 'Add') {
                $validator = Validator::make($request->all(), [
                    'name' => ['required', 'string', 'max:100'],
                    'contentType' => ['required'],
                ]);

                $model = new GenerateQrCode();
                $flash_message = __('QR Code has been created successfully');
                $user = auth()->user();
                if (((is_numeric($user->dynamic_qr_codes) ? $user->dynamic_qr_codes > 0 : false) || strval($user->dynamic_qr_codes) != 'unlimited') && $request->qrCodeType == 'dynamic') {
                    $user->decrement('dynamic_qr_codes', 1);
                }

                if (((is_numeric($user->static_qr_codes) ? $user->static_qr_codes > 0 : false) || strval($user->static_qr_codes) != 'unlimited') && $request->qrCodeType == 'static') {
                    $user->decrement('static_qr_codes', 1);
                }
            }

//        Save Qr Code Image
            $imageUrl = null;
//        New Image Save

            if(!empty($qr_code)){
                if ($qr_code->original['image_id'] && $input['action'] == 'Add') {
                    $target_path = 'users/' . auth()->user()->id . '/qr-codes/' . $qr_code->original['image_id'];
                    Storage::disk('public')->move('temp/' . $qr_code->original['image_id'], $target_path);
                } else {
                    //        Update Image
                    if ($request->image_id) {
                        $target_path = 'users/' . auth()->user()->id . '/qr-codes/' . $request->image_id;
                        Storage::disk('public')->delete('users/' . auth()->user()->id . '/qr-codes/' . $model->image);
                        Storage::disk('public')->move('temp/' . $request->image_id, $target_path);
                    }
                }

                $qrCodeData = $this->qrCodeTypeData($userData);

                $icon = $this->qrCodeTypeIcon($request->type);


                $data = [
                    'user_id' => auth()->user()->id,
                    'name' => $input['name'],
                    'campaign_id' => $request->campaign_id ? $request->campaign_id : null,
                    'type' => $request->type,
                    'status' => 1,
                    'code_type' => $request->qrCodeType == 'dynamic' ? 1 : 2,
                    'unique_id' => $request->qrCodeType == 'dynamic' ? $unique_id : '',
                    'icon' => $icon,
                    'short_url' => $request->qrCodeType == 'dynamic' ? url('/q') . '/' . $unique_id : '',
                    'ned_link' => null,
                    'image' => $qr_code->original['image_id'],
                    'fields' => json_encode($inputWithExceptData),
                    'data' => $qrCodeData,
                    'config' => json_encode($configData['config']),
                    'size' => $configData['size'],
                    'file' => $configData['file'],
                ];
                if($request->hasFile('logo_image')) {
                    $data['logo_image'] = $logoImage;
                }

                if(!$request->hasFile('logo_image') && $request->logo_image && $request->logo_image != 'undefined') {
                    $data['logo_image'] = $logoImage;
                }

                if($request->transparent_image && $request->transparent_image != 'null'){
                    $cropperData = [
                        'x' =>  json_decode($request->cropper_image_data,true)['x'],
                        'y' =>  json_decode($request->cropper_image_data,true)['y'],
                        'size' =>  json_decode($request->cropper_image_data,true)['size'],
                    ];
                    $data['crop'] = true;
                    $data['crop_data'] = json_encode($cropperData,true);
                    $data['transparent_image_status'] = $request->transparent_image_status;
                    $data['transparent_background'] = null;
                    $data['logo_image'] = null;
                    if ($request->transparent_image != 'null') {
                        $target_path = public_path('storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->transparent_image);
                        if (!file_exists($target_path)) {
                            $target_path = 'users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->transparent_image;

                            Storage::disk('public')->copy('temp/' . $request->transparent_image, $target_path);
                        } else{
                            if (str_contains($request->transparent_image, '.png')) {
                                $transparentBackgroundName = uniqid() . '.png';
                            } elseif (str_contains($request->transparent_image, '.jpg')) {
                                $transparentBackgroundName = uniqid() . '.jpg';
                            } elseif (str_contains($request->transparent_image, '.jpeg')) {
                                $transparentBackgroundName = uniqid() . '.jpeg';
                            } else{
                                $transparentBackgroundName = uniqid() . '.png';
                            }
                            $path = 'users/' . auth()->user()->id . '/qr-codes/transparent-images/';
                            Storage::disk('public')->copy($path . $request->transparent_image, $path.$transparentBackgroundName);
                            $request->transparent_image = $transparentBackgroundName;
                        }
                        $data['transparent_background'] = $request->transparent_image;
                    }
                }

                $model->fill($data);
                $model->save();
            }
            $qr_code = '';
        }
        Session::flash('flash_success', $flash_message);
        return response()->json([
            'status' => 1,
            'message' => __('Qr Code has been generated'),
            'url' => route('frontend.user.qr-codes.index'),
        ]);
    }

    public function importCsv(Request $request)
    {
        if ($request->ignoreHead == 'true') {
            $startRowIndex = 1;
        } else {
            $startRowIndex = 0;
        }
        $separtor = $request->delimiter;
        if ($separtor == ',') {
            $delimiter = ',';
        } elseif ($separtor == ';') {
            $delimiter = ';';
        } elseif ($separtor == '|') {
            $delimiter = '|';
        } elseif ($separtor == '^') {
            $delimiter = '^';
        } else {
            return response()->json([
                'status' => 400,
                'result' => 'Invalid delimiter',
            ]);
        }
        $count = 0;
        $the_big_array = [];
        if (($h = fopen($request->file('file'), "r")) !== FALSE) {
            while (($data = fgetcsv($h, 0, $delimiter)) !== FALSE) {
                $count++;
                if ($count == $startRowIndex) {
                    continue;
                }
                $str_contcat = '';
                foreach ($data as $key => $val) {
                    if ($str_contcat != null) {
                        $str_contcat = $str_contcat . ',' . $val;
                    } else {
                        $str_contcat = $val;
                    }
                }
                if ($str_contcat != null) {
                    $the_big_array[] = $str_contcat;
                }
            }
            fclose($h);
        }
        return response()->json([
            'status' => 200,
            'result' => $the_big_array,
        ]);
    }

    public function importExcel(Request $request)
    {
        $reader = new Xlsx();
        if ($request->ignoreHead == 'true') {
            $rowIndex = 1;
        } else {
            $rowIndex = 0;
        }
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($request->file('file'));
        $worksheet = $spreadsheet->getActiveSheet();
        $excelData = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            if ($row->getRowIndex() != $rowIndex) {
                foreach ($cellIterator as $index => $cell) {
                    $excelData[$row->getRowIndex()] = isset($excelData[$row->getRowIndex()]) ? $excelData[$row->getRowIndex()] . ',' . $cell->getValue() : $cell->getValue();
                }
            }
        }
        return response()->json([
            'status' => 200,
            'result' => $excelData,
        ]);
    }
}
