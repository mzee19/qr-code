<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Frontend\QRCodeGeneratorController;
use App\Models\EmailTemplate;
use App\Models\PackageSubscription;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\IcalendarGenerator\Components\Calendar;
use JeroenDesloovere\VCard\VCard;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\GenerateQrCode;
use App\Models\Logo;
use App\Models\Shape;
use App\Models\Scan;
use Hashids;
use Imagick;
use ImagickPixel;
use Session;
use Browser;
use Spatie\IcalendarGenerator\Components\Event;
use View;
use File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScansExport;
use function GuzzleHttp\Psr7\str;
use function Sodium\increment;

class GenerateQrCodeController extends Controller
{

    protected $qrCodeGenerate;


    public function __construct(QRCodeGeneratorController $qrCodeGenerate)
    {
        $this->qrCodeGenerate = $qrCodeGenerate;
    }

    public function renderTemplateImages()
    {
//        $data['userTemplates'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();

        if (checkFieldStatus(6)) {
            $data['userTemplates'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();
        } else {
            $data['userTemplates'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1, 'crop' => false])->get();
        }

        $images = View::make('frontend.sections.dashboard_qr_code_template', $data)->render();
        return $images;
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
N:' . $data["qrcodeVcardLastName"] . ';' . $data["qrcodeVcardFirstName"] . ';;;
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
                $data['qrcodeEmailSubject'] = str_replace(' ', '%20', $data['qrcodeEmailSubject']);
                $data['qrcodeEmailMessage'] = str_replace(' ', '%20', $data['qrcodeEmailMessage']);
                $email = 'mailto:' . $data['qrcodeEmail'] . '?subject=' . $data['qrcodeEmailSubject'] . '&body=' . $data['qrcodeEmailMessage'];
                return $email;
            case 'phone':
                $phone = 'tel:' . $data['qrcodePhone'];
                return $phone;
            case 'sms':
                $sms = 'SMS:' . $data['qrcodeSmsPhone'] . '?body=' . $data['qrcodeSmsText'];
                return $sms;
            case 'wifi':
                $wifi = 'WIFI:S:' . $data['ssid'] . ';T:' . $data['encryption'] . ';P:' . $data['password'] . ';;';
                return $wifi;
            case 'event':
                $wifi = 'BEGIN:VEVENT↵VERSION:2.0↵SUMMARY:' . $data['summary'] . '↵SUMMARY:↵LOCATION:' . $data['location'] . '↵LOCATION:↵DTSTART:' . $data['startDateTime'] . '↵DTEND:' . $data['endDateTime'] . '↵END:VEVENT↵';
                return $wifi;
        }
    }

    public function selectContentType(Request $request)
    {
        $data['campaign_id'] = '';

        if (isset($request->campaign_id)) {
            $data['campaign_id'] = "campaign_id=$request->campaign_id";
        }
        return view('frontend.dashboard.generate-qr-codes.select_content_type', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';

        $sortArr = explode('-', $sort);
        $db_record = GenerateQrCode::where(['user_id' => auth()->user()->id, 'archive' => 0, 'template' => 0])->orderBy($sortArr[0], $sortArr[1]);

        if ($request->has('text') && !empty($request->text)) {
            $db_record = $db_record->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->text . '%')
                    ->orWhere('type', 'like', '%' . $request->text . '%');
            });
        }

        $data['qrCodes'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['limits'] = [15, 25, 50, 75, 100];
        return view('frontend.dashboard.generate-qr-codes.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parameters = request()->all();
        $parameters['unique_id'] = \Str::random(8,uniqid());
        $data['parameters'] = $parameters;

        $data['campaigns'] = Campaign::where('user_id', auth()->user()->id)->get();
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
        $data['timezones'] = Timezone::all();
        $data['action'] = "Add";
        $data['tabTitle'] = __('Add');

        // Campaign selection check
        $data['campaign_id'] = '';

        if (isset($request->campaign_id)) {
            $campaign = Hashids::decode($request->campaign_id);
            $data['campaign_id_without_hash'] = 0;
            if (count($campaign) > 0) {
                $data['campaign_id_without_hash'] = $campaign[0];
            }
        }
//        Admin QR Code
        $data['adminQrCodes'] = GenerateQrCode::where(['user_id' => null, 'status' => 1])->get();

        $countQrCode = true;
        if ($data['parameters']['type'] == 'dynamic') {
            $countQrCode = getSubscriptionFeature('dynamic_qr_codes');
        }
        if ($data['parameters']['type'] == 'static') {
            $countQrCode = getSubscriptionFeature('static_qr_codes');
        }
        if (!$countQrCode) {
            Session::flash('flash_info', __('Please upgrade your package to get more features'));
            return redirect()->back();
        }

        return view('frontend.dashboard.generate-qr-codes.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['status'] = 1;

        $inputWithExceptData = $request->except('token', 'action', 'id', 'type', 'config', 'logo_image', 'name', 'image_id', 'campaign_id', 'uniqueId', 'template');
        $userData = $request->except('token', 'action', 'id', 'config', 'logo_image', 'name', 'image_id', 'campaign_id', 'qrCodeType', 'uniqueId', 'template');
        $configData = json_decode($request->config, true);

        if ($input['action'] == 'Add' || $request->has('template')) {

            $model = new GenerateQrCode();
            $flash_message = __('QR Code has been created successfully');
            if (!$request->has('template')) {
                $user = auth()->user();
                if (is_numeric($user->dynamic_qr_codes) && $request->qrCodeType == 'dynamic') {
                    $user->decrement('dynamic_qr_codes', 1);
                }

                if (is_numeric($user->static_qr_codes) && $request->qrCodeType == 'static') {
                    $user->decrement('static_qr_codes', 1);
                }
            }
        } else {
            $model = GenerateQrCode::findOrFail($input['id']);
            $flash_message = __('QR Code has been updated successfully');
        }

        $uniqueId = $request->uniqueId;
        $imageId['image_id'] = $request->image_id;
        $shortUrl = $request->ned_link;

        // Save Qr Code Image
        $imageUrl = null;
        // New Image Save
        if ($input['action'] == 'Add') {
//                Transparent Check Continue ......
            $qrCodeRequestData = $request->all();
            $cropData = json_decode($qrCodeRequestData['cropper_image_data'], true);
            $qrCodeRequestData['ned_link_domain_url'] = '';
            $qrCodeRequestData['transparentImage'] = $qrCodeRequestData['transparent_image'];
            $qrCodeRequestData['size'] = $cropData['size'];
            $qrCodeRequestData['x'] = $cropData['x'];
            $qrCodeRequestData['y'] = $cropData['y'];
            $qrCodeRequestedData = new Request($qrCodeRequestData);
            $qrCodeResponse = $this->qrCodeGenerate->generateQrCode($qrCodeRequestedData);
            $responseInArray = json_decode($qrCodeResponse->content(), true);
            if ($responseInArray['status']) {
                $imageId['image_id'] = $responseInArray['image_id'];
                $shortUrl = $responseInArray['ned_link'];
                $request->request->add([
                    'ned_link' => $responseInArray['ned_link'],
                    'back_half_id' => $responseInArray['back_half_id'],
                    'back_half' => $responseInArray['back_half'],
                ]);
            }

            $target_path = 'users/' . auth()->user()->id . '/qr-codes/' . $imageId['image_id'];
            Storage::disk('public')->move('temp/' . $imageId['image_id'], $target_path);
        } else {
            // Image save for User Template
            if ($request->template) {
                $target_path = 'users/' . auth()->user()->id . '/qr-codes/templates';
                $imageName = uniqid() . '.svg';
                if (\File::exists(public_path() . '/storage/users/' . auth()->user()->id . '/qr-codes/templates/' . $imageId['image_id']) && !empty($imageId['image_id'])) {
                    Storage::disk('public')->copy(public_path() . '/storage/users/' . auth()->user()->id . '/qr-codes/templates/' . $imageId['image_id'], $target_path . '/' . $imageName);
                    $imageId['image_id'] = $imageName;
                } else if (\File::exists(public_path() . '/storage/users/' . auth()->user()->id . '/qr-codes/' . $imageId['image_id'])) {
                    Storage::disk('public')->copy('users/' . auth()->user()->id . '/qr-codes/' . $imageId['image_id'], $target_path . '/' . $imageName);
                    $imageId['image_id'] = $imageName;
                } else {
                    Storage::disk('public')->copy('temp/' . $imageId['image_id'], $target_path . '/' . $imageName);
                    $imageId['image_id'] = $imageName;
                }
            } // Update Image
            else if ($imageId['image_id']) {
                $shortUrl = $request->ned_link;
                $target_path = 'users/' . auth()->user()->id . '/qr-codes/' . $imageId['image_id'];
                if ($request->image_id != $model->image) {
                    Storage::disk('public')->delete('users/' . auth()->user()->id . '/qr-codes/' . $model->image);
                }
                // Edit Qr code without changing
                if (file_exists(public_path('storage/') . $target_path)) {
                    $imageId['image_id'] = uniqid() . '.svg';
                    $target_path = 'users/' . auth()->user()->id . '/qr-codes/' . $imageId['image_id'];
                }
                if (\File::exists(public_path('/storage/users/' . auth()->user()->id . '/qr-codes/' . $request->image_id))) {
                    Storage::disk('public')->copy('users/' . auth()->user()->id . '/qr-codes/' . $request->image_id, $target_path);
                } else {
                    Storage::disk('public')->copy('temp/' . $request->image_id, $target_path);
                }
            }
        }

        $qrCodeData = $this->qrCodeTypeData($userData);

        $icon = $this->qrCodeTypeIcon($request->type);

        $data = [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'campaign_id' => isset($request->campaign_id) ? $request->campaign_id : null,
            'ned_link' => $shortUrl,
            'ned_link_back_half_id' => $request->back_half_id,
            'ned_link_back_half' => $request->back_half,
            'type' => $request->type,
            'status' => 1,
            'code_type' => $request->qrCodeType == 'dynamic' ? 1 : 2,
            'unique_id' => $request->qrCodeType == 'dynamic' ? $uniqueId : '',
            'icon' => $icon,
            'short_url' => $request->qrCodeType == 'dynamic' ? ($input['action'] == 'Add' ? url('/q/'. $input['uniqueId']) : str_replace($model->unique_id,$uniqueId,$model->short_url)) : '',
            'image' => $input['action'] == 'Add' ? $imageId['image_id'] : ($request->image_id ? $imageId['image_id'] : $model->image),
            'fields' => json_encode($inputWithExceptData),
            'data' => $qrCodeData,
            'config' => json_encode($configData['config']),
            'size' => $configData['size'],
            'file' => $configData['file'],
            'download' => $configData['download'],
            'template' => isset($request->template),
            'crop' => false,
            'crop_data' => null,
            'transparent_background' => null
        ];
        if ($request->template) {
            // 3 use for template code_type
            $data['code_type'] = 3;
        }

        $data['logo_image'] = '';

        if ($request->logo_image) {
            if (!$request->hasFile('logo_image') && $request->logo_image != 'undefined') {
                $logoImageName = str_replace('temp/', '', $request->logo_image);

                $target_path = public_path('storage/users/' . auth()->user()->id . '/qr-codes/logo-images/' . $logoImageName);
                if (!file_exists($target_path)) {
                    $target_path = 'users/' . auth()->user()->id . '/qr-codes/logo-images/' . $logoImageName;
                    Storage::disk('public')->copy('temp/' . $logoImageName, $target_path);
                } else {
                    if (str_contains($request->logo_image, '.png')) {
                        $newLogoImageName = uniqid() . '.png';
                    } elseif (str_contains($request->logo_image, '.jpg')) {
                        $newLogoImageName = uniqid() . '.jpg';
                    } elseif (str_contains($request->logo_image, '.jpeg')) {
                        $newLogoImageName = uniqid() . '.jpeg';
                    } else {
                        $newLogoImageName = uniqid() . '.png';
                    }
                    $path = 'users/' . auth()->user()->id . '/qr-codes/logo-images/';
                    Storage::disk('public')->copy($path . $logoImageName, $path . $newLogoImageName);
                    $logoImageName = $newLogoImageName;
                }
            } else if ($request->logo_image == 'undefined') {
                $logoImageName = null;
            } else {
                $logoImageUrl = Storage::disk('public')->put('users/' . auth()->user()->id . '/qr-codes/logo-Images', $request->logo_image);
                $logoImageName = str_replace('users/' . auth()->user()->id . '/qr-codes/logo-Images/', '', $logoImageUrl);
            }
            $data['logo_image'] = $logoImageName;
        }

        if ($request->transparent_image_status == 'true' || $request->transparent_image_status == '1') {
            $data['crop'] = true;
            $data['crop_data'] = $input['cropper_image_data'];
            $data['transparent_background'] = null;

            if ($request->transparent_image != 'null') {
                $target_path = public_path('storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->transparent_image);
                if (!file_exists($target_path)) {
                    $target_path = 'users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->transparent_image;

                    Storage::disk('public')->copy('temp/' . $request->transparent_image, $target_path);
                }

                $data['transparent_background'] = $request->transparent_image;
            } // Update Image
            $data['logo_image'] = null;

        }

        $model->fill($data);
        $model->save();

        if (!$model->template) {
            Session::flash('flash_success', $flash_message);
        }

        $templateImages = $this->renderTemplateImages();

        return response()->json([
            'status' => 1,
            'message' => __('Qr Code has beed generated'),
            'url' => route('frontend.user.qr-codes.index'),
            'template' => isset($request->template),
            'template_image' => $templateImages
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Hashids::decode($id)[0];
        $data['generateQrCode'] = GenerateQrCode::findOrFail($id);
        if ($data['generateQrCode']->crop == true && !checkFieldStatus(6)) {
            Session::flash('flash_info', __('Please upgrade your package to get more features'));
            return redirect()->back();
        }
        $type = json_decode($data['generateQrCode']->fields, true);
        $parameters['type'] = $type['qrCodeType'];
        $parameters['content_type'] = $data['generateQrCode']->type;
        $data['parameters'] = $parameters;
        $data['campaigns'] = Campaign::where('user_id', auth()->user()->id)->get();
        $data['logos'] = Logo::where('status', 1)->get();
        $data['bodyShapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        $data['qrCodeFrames'] = Shape::where('type', 4)->where('status', 1)->get();

        if (checkFieldStatus(6)) {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();
        } else {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1, 'crop' => false])->get();
        }
        //        Admin QR Code
        $data['adminQrCodes'] = GenerateQrCode::where(['user_id' => null, 'status' => 1])->get();

        $data['timezones'] = Timezone::all();
        $data['action'] = "Edit";
        $data['tabTitle'] = __('Edit');
        return view('frontend.dashboard.generate-qr-codes.form', $data);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            $id = Hashids::decode(request()->id)[0];

            GenerateQrCode::destroy($id);
//            Session::flash('flash_success', __('QR Code has been deleted successfully'));
            return response()->json([
                'status' => 1,
                'message' => __('QR Code has been deleted successfully'),
            ]);
        }

        $id = Hashids::decode($id)[0];
        deleteQrCodeOnNedLink($id);

        GenerateQrCode::destroy($id);
        Session::flash('flash_success', __('QR Code has been deleted successfully'));
        return redirect()->back();
    }

    /**
     * Archive the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $id = Hashids::decode($id)[0];
        GenerateQrCode::where('id', $id)->update(['archive' => 1]);

        Session::flash('flash_success', __('QR Code has been archived successfully'));
        return redirect()->back();
    }

    /**
     * Clone the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function clone($id)
    {
        $id = Hashids::decode($id)[0];
        $model = GenerateQrCode::find($id);
        $uniqueId = uniqid();
        $config_array['config'] = (array)json_decode($model->toArray()['config']);
        $config_array['size'] = $model->size;

        // Copy Logo image with unique name
        if ($model->logo_image) {
            if (str_contains($model->logo_image, '.png')) {
                $logoImageName = uniqid() . '.png';
            } elseif (str_contains($model->logo_image, '.jpg')) {
                $logoImageName = uniqid() . '.jpg';
            } elseif (str_contains($model->logo_image, '.jpeg')) {
                $logoImageName = uniqid() . '.jpeg';
            } else {
                $logoImageName = uniqid() . '.png';
            }

            $path = 'users/' . auth()->user()->id . '/qr-codes/logo-images/';
            Storage::disk('public')->copy($path . $model->logo_image, $path . $logoImageName);
        }

        // Make new Transparent Image Name
        if ($model->transparent_background && $model->transparent_background != 'null') {
            if (str_contains($model->transparent_background, '.png')) {
                $transparentBackgroundName = uniqid() . '.png';
            } elseif (str_contains($model->transparent_background, '.jpg')) {
                $transparentBackgroundName = uniqid() . '.jpg';
            } elseif (str_contains($model->transparent_background, '.jpeg')) {
                $transparentBackgroundName = uniqid() . '.jpeg';
            } else {
                $transparentBackgroundName = uniqid() . '.png';
            }
            $imageUrl = public_path() . ('/storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $model->transparent_background);
            $svg = file_get_contents($imageUrl);
            file_put_contents(public_path() . '/storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $transparentBackgroundName, $svg);

            $config_array['config'] = [
                "body" => "square",
                "frame" => "square",
                "eyeBall" => "square",
                "bodyColor" => "#ffffff",
                "bgColor" => "#ffffff",
                "eye1Color" => "#000000",
                "eye2Color" => "#000000",
                "eye3Color" => "#000000",
                "eyeBall1Color" => "#000000",
                "eyeBall2Color" => "#000000",
                "eyeBall3Color" => "#000000",
                "gradientColor1" => "#000000",
                "gradientColor2" => "#000000",
                "colorType" => false,
                "eyeStatus" => "",
                "gradientType" => "vertical",
                "gradientOnEyes" => false,
                "logo" => "0"];

            $dataArray['replicate_transparent_background_name'] = $transparentBackgroundName;
            $dataArray['size'] = json_decode($model->crop_data, true)['size'];
            $dataArray['y'] = json_decode($model->crop_data, true)['y'];
            $dataArray['x'] = json_decode($model->crop_data, true)['x'];
            $dataArray['config'] = json_encode($config_array);
        }

        if ($model->code_type == 1) {
            $countQrCode = getSubscriptionFeature('dynamic_qr_codes');

            if (!$countQrCode) {
                Session::flash('flash_info', __('Please upgrade your package to get more features'));
                return redirect()->back();
            }
            $dataArray['url'] = url('/qr-code', $uniqueId);
            $dataArray['type'] = $model->type;
            $dataArray['status'] = 1;
            $dataArray['qrCodeType'] = 'Dynamic';
            $dataArray['uniqueId'] = $uniqueId;
            $dataArray['config'] = json_encode($config_array);
            $dataArray['name'] = $model->name;

            $isDynamicQrCodeNum = is_numeric(auth()->user()->dynamic_qr_codes) ? true : false;
            if ($isDynamicQrCodeNum) {
                auth()->user()->decrement('dynamic_qr_codes');
            }

            if (isset($model->ned_link)) {
                $dataArray['action'] = 'Add';
            }
        } else {
            $countQrCode = getSubscriptionFeature('static_qr_codes');

            if (!$countQrCode) {
                Session::flash('flash_info', __('Please upgrade your package to get more features'));
                return redirect()->back();
            }

            $newModel = $model->replicate();
            $newModel->scans = 0;
            $filename = uniqid() . '.svg';
            $svgContent = file_get_contents(public_path('storage/users/' . $model->user_id . '/qr-codes/' . $model->image));
            file_put_contents(public_path('storage/users/' . $model->user_id . '/qr-codes/' . $filename), $svgContent);

            $newModel->image = $filename;

            if ($newModel->code_type == 1) {
                $newModel->unique_id = uniqid();
                $newModel->short_url = url('/qr-code/' . $newModel->unique_id);
            }

            $isStaticQrCodeNum = is_numeric(auth()->user()->static_qr_codes) ? true : false;
            if ($isStaticQrCodeNum) {
                auth()->user()->decrement('static_qr_codes');
            }

            if ($model->logo_image) {
                $newModel->logo_image = $logoImageName;
            }

            if ($model->transparent_background) {
                $newModel->transparent_background = $transparentBackgroundName;
            }

            $newModel->save();

            Session::flash('flash_success', __('QR Code has been cloned successfully'));
            return redirect()->back();
        }
        $generate_clone = new QRCodeGeneratorController();
        $request = new \Illuminate\Http\Request();

        if ($model->logo_image) {
            $dataArray['clone_logo_image'] = $model->logo_image;
        }
        $dataArray['cloneNedLinkResponse'] = true;
        $collection_request = $request->replace($dataArray);
        $qrcode_object = $generate_clone->generateQrCode($collection_request);
        $newModel = $model->replicate();
        $newModel->scans = 0;
        $imageUrl = Storage::url('temp/' . json_decode($qrcode_object->getContent())->image_id);

        $filename = json_decode($qrcode_object->getContent())->image_id;
        $target_path = public_path($imageUrl);
        $svg = file_get_contents($target_path);
//        $svgContent = file_get_contents(public_path('storage/users/' . $model->user_id . '/qr-codes/' . $imageUrl));
        file_put_contents(public_path('/storage/users/' . $model->user_id . '/qr-codes/' . $filename), $svg);

        if ($newModel->code_type == 1) {
            $newModel->unique_id = $uniqueId;
            $newModel->image = $filename;
            $newModel->short_url = url('/qr-code/' . $uniqueId);
            // Check old ned_link record
            if (isset($newModel->ned_link)) {
                $newModel->ned_link = json_decode($qrcode_object->getContent(), true)['ned_link'];
                $newModel->ned_link_back_half_id = json_decode($qrcode_object->getContent(), true)['back_half_id'];
                $newModel->ned_link_back_half = json_decode($qrcode_object->getContent(), true)['back_half'];
            }
        }

        if ($model->transparent_background) {
            $newModel->transparent_background = $transparentBackgroundName;
        }

        if ($model->logo_image) {
            $newModel->logo_image = $logoImageName;
        }

        $newModel->save();

        Session::flash('flash_success', __('QR Code has been cloned successfully'));
        return redirect()->back();
    }

    public function oldQrCodeData($id){
        $data['generateQrCode'] = GenerateQrCode::where(['unique_id' => $id, 'archive' => 0, 'template' => 0])->first();
        if (empty($data['generateQrCode']) || !isset($data['generateQrCode']->user->qr_code_scans)) {
            abort(404, 'Not Found');
        }
        return redirect('q/'.$id);
    }

    public function getQrCodeData($id)
    {
        $data['generateQrCode'] = GenerateQrCode::where(['unique_id' => $id, 'archive' => 0, 'template' => 0])->first();
        if (empty($data['generateQrCode']) || !isset($data['generateQrCode']->user->qr_code_scans)) {
            abort(404, 'Not Found');
        }

        if (is_numeric($data['generateQrCode']->user->qr_code_scans) ? ((int)$data['generateQrCode']->user->qr_code_scans > 0) : $data['generateQrCode']->user->qr_code_scans == 'unlimited') {
            if (is_numeric($data['generateQrCode']->user->qr_code_scans)) {
                $data['generateQrCode']->user->decrement('qr_code_scans');
            }

            // set IP address and API access key
            $ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '221.120.216.102' : $_SERVER['REMOTE_ADDR'];
//            $access_key = 'ec4274066fafbae34caf95e8036a2584';
//            $access_key = 'e7bdc2567821d42e6e0ab4def8f34439';
            $access_key = 'bbe00aed1fee93f2c01031c95f08adcb';
            if(\App::environment() !== 'local'){
                $access_key = 'KAohk4snSmCrpqn';
            }

            // Initialize CURL:
            $ch = curl_init('https://pro.ip-api.com/json/' . $ip . '?key=' . $access_key . '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response:
            $api_result = json_decode($json, true);

            if (!empty($api_result) && array_key_exists('country', $api_result) && !empty($api_result['country'])) {
                $device = 'Desktop';
                if (Browser::isMobile())
                    $device = 'Phone';
                else if (Browser::isTablet())
                    $device = 'Tablet';

                $packageSubscription = isset($data['generateQrCode']->user->subscription) ? $data['generateQrCode']->user->subscription : 'None';
                $features = json_decode($packageSubscription->features, true);
                switch ($features[9]) {
                    case 'basic':
                        $statisticsStatus = 1;
                        break;
                    case 'Basic':
                        $statisticsStatus = 1;
                        break;
                    case 'advanced':
                        $statisticsStatus = 2;
                        break;
                    case 'Advanced':
                        $statisticsStatus = 2;
                        break;
                    default:
                        $statisticsStatus = 0;
                }
                Scan::create([
                    'user_id' => $data['generateQrCode']->user_id,
                    'qr_code_id' => $data['generateQrCode']->id,
                    'city' => $api_result['city'],
                    'country' => $api_result['country'],
                    'location' => $api_result['lat'] . ',' . $api_result['lon'],
//                    'language' => $api_result['location']['languages'][0]['code'],
                    'language' => 'en',
                    'ip' => $ip,
                    'browser' => Browser::browserFamily(),
                    'platform' => Browser::platformFamily(),
                    'device' => $device,
                    'status' => 1,
                    'statistics_status' => $statisticsStatus,
                ]);
            }

            $incrementScan = $data['generateQrCode']->scans + 1;
            $data['generateQrCode']->update(['scans' => $incrementScan]);
            // Vcard Download
            if ($data['generateQrCode']->type == 'vcard') {
                $vcard = new VCard();
                $data = json_decode($data['generateQrCode']->fields, true);
                // define variables
                $additional = '';
                $prefix = '';
                $suffix = '';

                // add personal data
                $vcard->addName($data['qrcodeVcardLastName'], $data['qrcodeVcardFirstName'], $additional, $prefix, $suffix);

                // add work data
                $vcard->addCompany($data['qrcodeVcardOrganization']);
                $vcard->addJobtitle($data['qrcodeVcardTitle']);
                $vcard->addRole('Data Protection Officer');
                $vcard->addEmail($data['qrcodeVcardEmail']);
                $vcard->addPhoneNumber($data['qrcodeVcardPhoneWork'], 'PREF;WORK');
                $vcard->addPhoneNumber($data['qrcodeVcardPhonePrivate'], 'WORK');
                $vcard->addPhoneNumber($data['qrcodeVcardPhoneMobile'], 'WORK');
                $vcard->addAddress(null, null, $data['qrcodeVcardStreet'], $data['qrcodeVcardCity'], null, $data['qrcodeVcardZipcode'], $data['qrcodeVcardCountry']);
                $vcard->addLabel('street, worktown, workpostcode Country');
                $vcard->addURL($data['qrcodeVcardUrl']);
                // $vcard->addPhoto(__DIR__ . '/landscape.jpeg');
                $filename = $data['qrcodeVcardFirstName'] . '-' . $data['qrcodeVcardLastName'] . '.vcf';

                return $vcard->download();
//                header('Content-Type: text/vcard');
//                header('Content-Disposition: inline; filename= "' . $filename . '"');
//                return \Response::make(
//                    $vcard->getOutput(),
//                    200,
//                    $vcard->getHeaders(true)
//                );
            } // Calendar Event Download File
            else if ($data['generateQrCode']->type == 'event') {
                $data['event'] = json_decode($data['generateQrCode']->fields, true);
                // Start Date
                $startDateTime = date($data['event']['startDateTime']);
                $timestamp = strtotime($startDateTime);
                $time = $timestamp - 18000;
                $startDateTime = date("Y-m-d H:i:s", $time);

                // End Date
                $endDateTime = date($data['event']['endDateTime']);
                $timestamp = strtotime($endDateTime);
                $time = $timestamp - 18000;
                $endDateTime = date("Y-m-d H:i:s", $time);

                // Timezone check
                $timezone = Timezone::where('name', $data['event']['qrcodeEventTimezone'])->first();
                if (!empty($timezone)) {
                    $data['event']['qrcodeEventTimezone'] = $timezone->name;
                } else {
                    $data['event']['qrcodeEventTimezone'] = 'Pacific/Midway';
                }

                // integer check in reminder
                if (!is_numeric($data['event']['qrcodeEventReminder'])) {
                    $data['event']['qrcodeEventReminder'] = '0';
                }

                $calendar = Calendar::create($data['generateQrCode']->name)
                    ->event([
                        Event::create()
                            ->name($data['event']['summary'])
                            ->description($data['event']['qrcodeEventDescription'])
                            ->address($data['event']['location'])
                            ->startsAt(new \DateTime($startDateTime), new \DateTimeZone($data['event']['qrcodeEventTimezone']))
                            ->endsAt(new \DateTime($endDateTime), new \DateTimeZone($data['event']['qrcodeEventTimezone']))
                            ->alertMinutesAfter($data['event']['qrcodeEventReminder'], $data['event']['summary'] . ' Reminder!')
                    ])
                    ->description(isset($data['event']['qrcodeEventDescription']) ? $data['event']['qrcodeEventDescription'] : '');
                return response($calendar->get(), 200, [
                    'Content-Type' => 'text/calendar',
                    'Content-Disposition' => 'attachment; filename="' . $data['generateQrCode']->name . '.ics"',
                    'charset' => 'utf-8',
                ]);
            }
        } else {
            Session::flash('flash_info', __('QR code scan limit is restricted by end user.'));
            return redirect()->route('frontend.home');
        }

        if ($data['generateQrCode']->type == 'sms') {
            if (Browser::isMac() && Browser::isMobile()) {
                $data['generateQrCode']->data = 'sms:' . json_decode($data['generateQrCode']->fields, true)['qrcodeSmsPhone'];
            }
        }

        return view('frontend.dashboard.generate-qr-codes.data_open_app', $data);
    }

    /**
     * Statistics of the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function statistics(Request $request, $id)
    {
        if (!checkFieldStatus(9)) {
            Session::flash('flash_info', __('Please upgrade your package to get more features'));
            return redirect()->back();
        }

        $id = Hashids::decode($id)[0];
        $data['qrCode'] = GenerateQrCode::find($id);

        if ($request->has('from') && $request->has('to')) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $data['from'] = $request->from;
            $data['to'] = $request->to;
        } else {
            $from = date('Y-m-d', strtotime("-7 days")) . ' 00:00:00';
            $to = date('Y-m-d') . ' 23:59:59';

            $data['from'] = date('Y-m-d', strtotime("-7 days"));
            $data['to'] = date('Y-m-d');
        }

        $checkStatisticsStatus = $this->getPackageStatisticsValueCheck();

        $data['scansList'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'desc')->get();
        $data['firstScan'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->first();

        $data['scanLabels'] = array();
        $data['scanValues'] = json_encode(array());

        $data['uniqueLabels'] = array();
        $data['uniqueValues'] = json_encode(array());

        $data['scansDataPoints'] = [];
        $data['uniqueUsersDataPoints'] = [];

        if (!empty($data['firstScan'])) {
            $data['countryCount'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->where('country', $data['firstScan']->country)->count();
            $data['deviceCount'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->where('device', $data['firstScan']->device)->count();

            /*
            ** Countries
            ** Cities
            ** Languages
            */

            $data['countries'] = $this->getScansCountGroupBy($id, 'country', $from, $to);
            $data['cities'] = $this->getScansCountGroupBy($id, 'city', $from, $to);
            $data['languages'] = $this->getScansCountGroupBy($id, 'language', $from, $to);

            /*
            ** Devices
            ** Platforms
            ** Browsers
            */

            $data['devices'] = $this->getScansCountGroupBy($id, 'device', $from, $to);
            $data['platforms'] = $this->getScansCountGroupBy($id, 'platform', $from, $to);
            $data['browsers'] = $this->getScansCountGroupBy($id, 'browser', $from, $to);

            // $period = new \DatePeriod(
            //     new \DateTime(date('Y-m-d', strtotime("-30 days"))),
            //     new \DateInterval('P1D'),
            //     new \DateTime(date('Y-m-d', strtotime("+1 days")))
            // );

            $period = new \DatePeriod(
                new \DateTime($from),
                new \DateInterval('P1D'),
                new \DateTime($to)
            );

            /*
            ** Scans Graph
            */

            $scans = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as scans'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $scanLabels = array();
            $scanValues = array();

            foreach ($period as $key => $value) {
                $scanLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $scanValues[] = $scans[$index]['scans'];
                } else {
                    $scanValues[] = 0;
                }
            }

            $data['scanLabels'] = $scanLabels;
            $data['scanValues'] = json_encode($scanValues);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $scansDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $scansDataPoints[$date] = $scans[$index]['scans'];
                } else {
                    $scansDataPoints[$date] = 0;
                }
            }

            $data['scansDataPoints'] = $scansDataPoints;

            /*
            ** End CanvasJs
            */

            /*
            ** Unique Users Graph
            */

            $unique_users = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(DISTINCT ip) as unique_count'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $uniqueLabels = array();
            $uniqueValues = array();

            foreach ($period as $key => $value) {
                $uniqueLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if ($index !== false) {
                    $uniqueValues[] = $unique_users[$index]['unique_count'];
                } else {
                    $uniqueValues[] = 0;
                }
            }

            $data['uniqueLabels'] = $uniqueLabels;
            $data['uniqueValues'] = json_encode($uniqueValues);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $uniqueUsersDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if ($index !== false) {
                    $uniqueUsersDataPoints[$date] = $unique_users[$index]['unique_count'];
                } else {
                    $uniqueUsersDataPoints[$date] = 0;
                }
            }

            $data['uniqueUsersDataPoints'] = $uniqueUsersDataPoints;

            /*
            ** End CanvasJs
            */
        }
        if ($request->has('export')) {
            $data['from'] = Carbon::parse($data['from'])->format('m/d/Y');
            $data['to'] = Carbon::parse($data['to'])->format('m/d/Y');
            $data['scansPerDay'] = [];
            foreach ($data['uniqueUsersDataPoints'] as $date => $unique_user) {
                $arr['date'] = Carbon::parse($date)->format('m/d/Y');
                $arr['unique_user'] = $unique_user;
                $arr['scan'] = $data['scansDataPoints'][$date];
                $data['scansPerDay'][] = $arr;
            }
            $from = Carbon::parse($data['from'])->toDate()->format('Ymd');
            $to = Carbon::parse($data['to'])->toDate()->format('Ymd');
            $sheet_name = $from . '-' . $to . '_qrcode_statistics_' . time() . '.xlsx';
            return Excel::download(new ScansExport($data), $sheet_name);
        } else {
            return view('frontend.dashboard.generate-qr-codes.statistics', $data);
        }
    }

    /**
     * Statistics of the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function allStatistics(Request $request, $id)
    {
        if (!checkFieldStatus(9)) {
            Session::flash('flash_info', __('Please upgrade your package to get more features'));
            return redirect()->back();
        }

        $id = Hashids::decode($id)[0];
        $data['qrCode'] = GenerateQrCode::find($id);

        if ($request->has('from') && $request->has('to')) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $data['from'] = $request->from;
            $data['to'] = $request->to;
        } else {
            $from = date('Y-m-d', strtotime("-7 days")) . ' 00:00:00';
            $to = date('Y-m-d') . ' 23:59:59';

            $data['from'] = date('Y-m-d', strtotime("-7 days"));
            $data['to'] = date('Y-m-d');
        }

        $checkStatisticsStatus = $this->getPackageStatisticsValueCheck();

        $data['scansList'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'desc')->get();
        $data['firstScan'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->first();

        $data['scanLabels'] = array();
        $data['scanValues'] = json_encode(array());

        $data['uniqueLabels'] = array();
        $data['uniqueValues'] = json_encode(array());

        $data['scansDataPoints'] = [];
        $data['uniqueUsersDataPoints'] = [];

        if (!empty($data['firstScan'])) {
            $data['countryCount'] = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->where('country', $data['firstScan']->country)->count();
            $data['deviceCount'] = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->where('device', $data['firstScan']->device)->count();

            /*
            ** Countries
            ** Cities
            ** Languages
            */

            $data['countries'] = $this->getScansCountGroupBy($id, 'country', $from, $to);
            $data['cities'] = $this->getScansCountGroupBy($id, 'city', $from, $to);
            $data['languages'] = $this->getScansCountGroupBy($id, 'language', $from, $to);

            /*
            ** Devices
            ** Platforms
            ** Browsers
            */

            $data['devices'] = $this->getScansCountGroupBy($id, 'device', $from, $to);
            $data['platforms'] = $this->getScansCountGroupBy($id, 'platform', $from, $to);
            $data['browsers'] = $this->getScansCountGroupBy($id, 'browser', $from, $to);

            // $period = new \DatePeriod(
            //     new \DateTime(date('Y-m-d', strtotime("-30 days"))),
            //     new \DateInterval('P1D'),
            //     new \DateTime(date('Y-m-d', strtotime("+1 days")))
            // );

            $period = new \DatePeriod(
                new \DateTime($from),
                new \DateInterval('P1D'),
                new \DateTime($to)
            );

            /*
            ** Scans Graph
            */

            $scans = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as scans'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $scanLabels = array();
            $scanValues = array();

            foreach ($period as $key => $value) {
                $scanLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $scanValues[] = $scans[$index]['scans'];
                } else {
                    $scanValues[] = 0;
                }
            }

            $data['scanLabels'] = $scanLabels;
            $data['scanValues'] = json_encode($scanValues);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $scansDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $scansDataPoints[$date] = $scans[$index]['scans'];
                } else {
                    $scansDataPoints[$date] = 0;
                }
            }

            $data['scansDataPoints'] = $scansDataPoints;

            /*
            ** End CanvasJs
            */

            /*
            ** Unique Users Graph
            */

            $unique_users = Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(DISTINCT ip) as unique_count'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $uniqueLabels = array();
            $uniqueValues = array();

            foreach ($period as $key => $value) {
                $uniqueLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if ($index !== false) {
                    $uniqueValues[] = $unique_users[$index]['unique_count'];
                } else {
                    $uniqueValues[] = 0;
                }
            }

            $data['uniqueLabels'] = $uniqueLabels;
            $data['uniqueValues'] = json_encode($uniqueValues);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $uniqueUsersDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if ($index !== false) {
                    $uniqueUsersDataPoints[$date] = $unique_users[$index]['unique_count'];
                } else {
                    $uniqueUsersDataPoints[$date] = 0;
                }
            }

            $data['uniqueUsersDataPoints'] = $uniqueUsersDataPoints;

            /*
            ** End CanvasJs
            */
        }
        if ($request->has('export')) {
            $data['from'] = Carbon::parse($data['from'])->format('m/d/Y');
            $data['to'] = Carbon::parse($data['to'])->format('m/d/Y');
            $data['scansPerDay'] = [];
            foreach ($data['uniqueUsersDataPoints'] as $date => $unique_user) {
                $arr['date'] = Carbon::parse($date)->format('m/d/Y');
                $arr['unique_user'] = $unique_user;
                $arr['scan'] = $data['scansDataPoints'][$date];
                $data['scansPerDay'][] = $arr;
            }
            $from = Carbon::parse($data['from'])->toDate()->format('Ymd');
            $to = Carbon::parse($data['to'])->toDate()->format('Ymd');
            $sheet_name = $from . '-' . $to . '_qrcode_statistics_' . time() . '.xlsx';
            return Excel::download(new ScansExport($data), $sheet_name);
        } else {
            return view('frontend.dashboard.generate-qr-codes.latest_scan_record', $data);
        }
    }


    /**
     * Statistics of the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function templateConfigData(Request $request)
    {
        $id = Hashids::decode($request->id)[0];
        $qrCode = GenerateQrCode::find($id);
        $configData = json_decode($qrCode->config, true);
        $image = '<img src="' . checkImage(asset('storage/users/' . $qrCode->user_id . '/qr-codes/' . $qrCode->image), 'default.svg', $qrCode->image) . '">';
        $transparentImage = asset('storage/users/' . $qrCode->user_id . '/qr-codes/transparent-images/' . $qrCode->transparent_background);
        return response()->json([
            'status' => 1,
            'data' => $configData,
            'image' => $image,
            'crop_status' => $qrCode->crop,
            'transparentImage' => $transparentImage,
            'transparentImageId' => $qrCode->transparent_background,
            'transparentImageData' => json_decode($qrCode->crop_data, true),
            'logo_image' => $qrCode->logo_image,
        ]);
    }

    public function getScansCountGroupBy($id, $field, $fromDate, $toDate)
    {
        $checkStatisticsStatus = $this->getPackageStatisticsValueCheck();

        return Scan::where('qr_code_id', $id)->whereIn('statistics_status', $checkStatisticsStatus)->whereBetween('created_at', [$fromDate, $toDate])->select($field, \DB::raw('count(*) as scans, created_at'))->groupBy($field)->orderBy('created_at', 'asc')->get();
//        return Scan::where('qr_code_id', $id)->select($field, 'created_at')->orderBy('created_at', 'asc')->get();
    }

    /**
     * Archive the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        $id = Hashids::decode($id)[0];
        $qrCode = GenerateQrCode::where('id', $id)->first();
        // Remove Space bt words
        $qrCode->name = str_replace(' ', '-', $qrCode->name);

        $target_path = public_path('storage/users/' . auth()->user()->id . '/qr-codes/' . $qrCode->image);
        $svg = file_get_contents($target_path);
        $newImageName = $qrCode->name . '.' . $request->fileType;

        if (\App::environment() !== 'local') {

            $input['eyeStatus'] = json_decode($qrCode->config, true)['eyeStatus'];
            $input['logo_image'] = $qrCode->logo_image;
            if ($qrCode->crop) {
                $input['transparentImage'] = $qrCode->transparent_background;
                $input['size'] = json_decode($qrCode->crop_data, true)['size'];
                $input['x'] = json_decode($qrCode->crop_data, true)['x'];
                $input['y'] = json_decode($qrCode->crop_data, true)['y'];
            }
            $input['qrCodeType'] = $qrCode->code_type == 1 ? 'dynamic' : 'static';
            $input['action'] = 'Edit';
            $input['uniqueId'] = $qrCode->unique_id;
            $configData['config'] = json_decode($qrCode->config, true);
            $divisor = 1;
            if ($request->fileType == 'eps') {
                $divisor = 0.75;
                $qrCodeSize = round($request->size / $divisor);
            } elseif ($request->fileType == 'pdf') {
                $divisor = 0.26;
                $qrCodeSize = $request->size / $divisor;
            } else {
                $qrCodeSize = $request->size / $divisor;
            }
            $configData['size'] = $qrCodeSize;

            $input['type'] = $qrCode->type;
            $input['config'] = json_encode($configData);
            // Transparent Config data
            if ($qrCode->crop) {
                $input['config'] = json_encode(["config" => [
                    "body" => "square",
                    "frame" => "square",
                    "eyeBall" => "square",
                    "bodyColor" => "#000000",
                    "bgColor" => "#ffffff",
                    "eye1Color" => "#000000",
                    "eye2Color" => "#000000",
                    "eye3Color" => "#000000",
                    "eyeBall1Color" => "#000000",
                    "eyeBall2Color" => "#000000",
                    "eyeBall3Color" => "#000000",
                    "gradientColor1" => "#000000",
                    "gradientColor2" => "#000000",
                    "colorType" => true,
                    "eyeStatus" => "",
                    "gradientType" => "vertical",
                    "gradientOnEyes" => false,
                    "logo" => "0"
                ],
                    "size" => $qrCodeSize
                ]);
            }

            foreach (json_decode($qrCode->fields, true) as $index => $data) {
                $input[$index] = $data;
            }
            $request = new Request($input);
            $generatedQrCodeImage = new QRCodeGeneratorController();
            $response = $generatedQrCodeImage->generateQrCode($request);

            if (json_decode($response->getContent(), true)['status'] == 1) {
                $target_path = public_path('storage/temp/' . json_decode($response->getContent(), true)['image_id']);
                $newImagePath = public_path('storage/temp/' . $newImageName);
                shell_exec('inkscape  ' . $target_path . ' -o  ' . $newImagePath);
            }
        } else {
            $im = new Imagick();
            $im->setBackgroundColor(new ImagickPixel('transparent'));
            $im->readImageBlob($svg);

            // Convert Image
            switch ($request->fileType) {
                case 'svg':
                    $im->setImageFormat($request->fileType);
                    header('Content-type: image/' . $request->fileType);
                    break;
                case 'png':
                    $im->setImageFormat('png32');
                    header('Content-type: image/' . $request->fileType);
                    break;
                case 'pdf':
                    $im->setImageFormat($request->fileType);
                    header('Content-type: application/' . $request->fileType);
                    break;
                case 'eps':
                    $im->setImageFormat("eps");
                    header('Content-type: image/' . $request->fileType);
                    break;
            }
            // Set image height and width
            $im->resizeImage($request->size, $request->size, imagick::FILTER_LANCZOS, 1);

            file_put_contents(public_path('storage/temp/' . $newImageName), $im);
        }
        return response()->download(public_path('storage/temp/' . $newImageName));
        //return Storage::disk('public')->download(asset('storage/temp/' . $newImageName));
    }

    public function transparentImage(Request $request)
    {
        if ($request->hasFile('transparentImage')) {
            $fileName = uniqid();
            $getExt = $request->transparentImage->getClientOriginalExtension();

            $transparentImageUrl = Storage::putFileAs('public/temp', $request->transparentImage, $fileName . '.' . $getExt);
            $transparentImageResponse = [
                'status' => 1,
                'transparentImage' => asset('storage/temp/' . $fileName . '.' . $getExt),
                'transparentImageId' => $fileName . '.' . $getExt,
            ];
            return $transparentImageResponse;
        }
    }

    public function getPackageStatisticsValueCheck()
    {
        $packageStatisticStatusValue = getSubscriptionFeatureCount(9);

        switch ($packageStatisticStatusValue) {
            case 'basic':
                $checkStatisticsStatus = [0, 2];
                break;
            case 'advanced':
                $checkStatisticsStatus = [0, 1, 2];
                break;
            default:
                $checkStatisticsStatus = [0];
        }

        return $checkStatisticsStatus;
    }
}
