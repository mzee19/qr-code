<?php

namespace App\Http\Controllers\Frontend;

use App\Models\GenerateQrCode;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Logo;
use App\Models\Shape;
use App\Models\Subscriber;
use App\User;
use Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickPixel;
use Session;
use Validator;

class HomeController extends Controller
{
    public function index()
    {
        $data['bodyShapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        $data['qrCodeFrames'] = Shape::where('type', 4)->where('status', 1)->get();
        $data['logos'] = Logo::where('status', 1)->get();
        $data['features'] = Feature::where('status', 1)->get();
        $data['faqs'] = Faq::where('status', 1)->orderBy('order_by', 'ASC')->get();
        $data['adminQrCodes'] = GenerateQrCode::where(['user_id' => null, 'status' => 1])->get();
        $data['languages'] = Language::where('status', 1)->orderBy('name', 'ASC')->get();

        if (!session()->has('locale')) {
            // set IP address and API access key
            $ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '221.120.216.102' : $_SERVER['REMOTE_ADDR'];
            $access_key = 'ec4274066fafbae34caf95e8036a2584';

            // Initialize CURL:
            $ch = curl_init('http://api.ipstack.com/' . $ip . '?access_key=' . $access_key . '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

            // Decode JSON response:
            $api_result = json_decode($json, true);

            $locale = $api_result['location']['languages'][0]['code'] ?? 'en';
            \App::setLocale($locale);
            session()->put('locale', $locale);
        }

        return view('frontend.home', $data);
    }

    public function verifyLogin($id)
    {
        $id = Hashids::decode($id)[0];
        $user = User::find($id);
        if ($user->status == 0 || $user->status == 3) {
            Session::flash('flash_danger', __('Your account has been disabled .Please Contact with admin'));
            return redirect()->route('login');
        }

        $user->update(['status' => 1]);

        if (Auth::guard('web')->attempt(['email' => $user->email, 'password' => $user->original_password])) {
            return redirect()->route('frontend.user.dashboard');
        }

        Session::flash('flash_success', __('Your account has been verified successfully'));
        return redirect()->route('login');
    }

    public function adminQrCodeData(Request $request)
    {
        $id = Hashids::decode($request->id)[0];
        $adminQrCodes = GenerateQrCode::where(['user_id' => null, 'status' => 1, 'id' => $id])->first();

        if ($adminQrCodes) {
            $configData = json_decode($adminQrCodes->config, true);
            $image = '<img src="' . checkImage(asset('storage/admin-qr-codes/' . $adminQrCodes->image), 'placeholder.png', $adminQrCodes->image) . '"
                                    class="img-responsive" alt="" id="image">';
            $data = $adminQrCodes->data;
            return response()->json([
                'status' => 1,
                'config_data' => $configData,
                'image' => $image,
                'data' => $data,
            ]);
        }
    }

    public function downloadQrCode(Request $request)
    {
        $newImageName = uniqid() . '.' . $request->fileType;

        if (\App::environment() == 'local') {
            $input['eyeStatus'] = $request->generatedLogoEyeStatus == 'true' ? true : false;
            $input['logo_image'] = $request->generatedLogoImage;
            $input['qrCodeType'] = 'static';
            $configData = json_decode($request->generatedLogoConfigData, true);
            $divisor = 1;
            if ($request->fileType == 'eps'){
                $divisor = 0.75;
                $qrCodeSize = round($request->size / $divisor);
            } elseif ($request->fileType == 'pdf'){
                $divisor = 0.26;
                $qrCodeSize = $request->size / $divisor;
            } else{
                $qrCodeSize = $request->size / $divisor;
            }
            $configData['size'] = $qrCodeSize;

            $input['config'] = json_encode($configData);
            $input['size'] = $qrCodeSize;
            foreach (json_decode($request->formData, true) as $index => $data) {
                $input[$data['name']] = $data['value'];
            }
            $request = new Request($input);
            $generatedQrCodeImage = new QRCodeGeneratorController();
            $response = $generatedQrCodeImage->generateQrCode($request);

            if (json_decode($response->getContent(), true)['status'] == 1) {
                $target_path = public_path('storage/temp/' . json_decode($response->getContent(), true)['image_id']);
                $newImagePath = public_path('storage/temp/' . $newImageName);
                shell_exec('inkscape  ' . $target_path . ' -o  ' . $newImagePath);
            }
            dd($response,$request->all());
//            shell_exec('inkscape -w ' . (int)$request->size . ' -h ' . (int)$request->size . ' ' . $target_path . ' -o  ' . $newImagePath);
        } else {
            $target_path = public_path('storage/temp/' . $request->imageName);
            $svg = file_get_contents($target_path);

            $im = new Imagick();
            $im->setBackgroundColor(new ImagickPixel('transparent'));
            $im->readImageBlob($svg);

            // Generate new file name
            // Set image height and width
            $im->resizeImage($request->size, $request->size, imagick::FILTER_LANCZOS, 1);

            // Convert Image
            switch ($request->fileType) {
                case 'svg':
                    $im->setImageFormat('svg');
                    // header('Content-type: image/svg');
                    break;
                case 'png':
                    $im->setImageFormat('png32');
                    // header('Content-type: image/png');
                    break;
                case 'pdf':
                    $im->setImageFormat('pdf');
                    // header('Content-type: application/pdf');
                    break;
                case 'eps':
                    $im->setImageFormat('eps');
                    // header('Content-type: image/eps');
                    break;
            }

            $im->getImageBlob();
            file_put_contents(public_path('storage/temp/' . $newImageName), $im);
        }

        return Storage::disk('public')->download('temp/' . $newImageName);
    }

    public function subscribe(Request $request)
    {
        $flash_message = __('Thank You For Subscribing!');
        $subscriber = Subscriber::where('email', $request->email)->first();

        if ($subscriber)
            $flash_message = __('You are already subscribed!');

        Subscriber::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email
            ]
        );

        Session::flash('flash_custom_message', $flash_message);
        return redirect()->back();
    }

    public function checkUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->all()
            ]);
        }

        $user = User::where(['email' => $request->email])->first();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => "We can't find a user."
            ]);
        } else {
            return response()->json([
                'data' => array(
                    'user' => $user->only('name', 'email', 'country_id', 'timezone')
                ),
                'status' => 1,
                'message' => "User already exists."
            ]);
        }
    }
}
