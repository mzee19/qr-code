<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\GenerateQrCode;
use App\Models\Logo;
use App\Models\PackageFeature;
use App\Models\PackageSubscription;
use App\Models\Shape;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use App\User;
use App\Models\Package;
use Hashids;
use Session;
use SVG\SVG;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Embedded\SVGImage;

class TestController extends Controller
{
    public function index()
    {
        dd('Test');
    }

    // Delete Temp Folder
    public function cleanQrCodeTemp()
    {
        $path = 'storage/temp';

        if (\File::exists(public_path() . '/' . $path)) {
            $file = new Filesystem;
            $file->cleanDirectory($path);
        }

        dd('Temp folder has been deleted successfully.');
    }

    public function activateFreePackage()
    {
        $users = User::all();
        foreach($users as $user) {

            $user->update([
                'is_expired' => 0,
                'on_hold_package_id' => null,
                'package_recurring_flag' => 0,
                'switch_to_paid_package' => 0,
                'package_updated_by_admin' => 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 0,
                'last_quota_revised' => NULL,
            ]);

            $package = Package::find(2);
            activatePackage($user->id,$package);
        }
        dd('Trial Package is activated.');
    }

    public function transparentQrCode()
    {
        $image = SVG::fromFile(public_path('/storage/test.svg'));
        $doc = $image->getDocument();

        $rect = $doc->getChild(0);
        $rect->setStyle('fill-opacity', '0.1');

        $doc->addChild(
            (new SVGImage("https://mdn.mozillademos.org/files/6457/mdn_logo_only_color.png", 0, 0, 300, 300)),0
        );

        header('Content-Type: image/svg+xml');
        echo $image;
    }

    public function imageCropper()
    {
        return view('frontend.image-cropper');
    }

    public function imageCrop(Request $request)
    {
        // Create an image from given image
        $im = imagecreatefromjpeg(public_path('storage/image.jpeg'));

        // Set the crop image size
        $im2 = imagecrop($im, ['x' => $request->x, 'y' => $request->y, 'width' => $request->size, 'height' => $request->size]);
        if ($im2 !== FALSE) {
            //header("Content-type: image/png");
            imagepng($im2, public_path('storage/cropped_image.png'));
            imagedestroy($im2);
        }
        imagedestroy($im);

        // Add transparent image background

        $image = SVG::fromFile(public_path('/storage/test.svg'));
        $doc = $image->getDocument();

        $rect = $doc->getChild(0);
        $rect->setStyle('fill-opacity', '0.5');

        $doc->addChild(
            (new SVGImage(asset('storage/cropped_image.png'), 0, 0, 300, 300)),0
        );

        header('Content-Type: image/svg+xml');
        echo $image;
    }

    public function qrCode() {
        $parameters = request()->all();
        $parameters['unique_id'] = uniqid();
        $data['parameters'] = $parameters;
        $data['parameters']['type'] = 'static';
        $data['parameters']['content_type'] = 'email';
        $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();

        $data['campaigns'] = Campaign::where('user_id', auth()->user()->id)->get();
        $data['logos'] = Logo::where('status', 1)->get();
        $data['bodyShapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        $data['generateQrCode'] = new GenerateQrCode();

        $data['timezones'] = Timezone::all();
        $data['action'] = "Add";
        $data['tabTitle'] = __('Add');


        return view('frontend.dashboard.generate-qr-codes.form-update', $data);
    }

    public function qrCodeEdit($id){
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
        if (checkFieldStatus(6)) {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1])->get();
        } else {
            $data['templateImages'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 1, 'crop' => false])->get();
        }
        $data['timezones'] = Timezone::all();
        $data['action'] = "Edit";
        $data['tabTitle'] = __('Edit');
        return view('frontend.dashboard.generate-qr-codes.form-update', $data);
    }

    public function designPackage(){
        $data['packageFeatures'] = PackageFeature::where('status', 1)->get();
        $data['packages'] = Package::where('status', 1)->where('id', '!=', 1)->get();
        $data['packageSubscription'] = auth()->user()->subscription;

        $vat_percentage = settingValue('vat');

        if(!empty(auth()->user()->country_id) && auth()->user()->country->apply_default_vat == 0 && auth()->user()->country->status == 1)
        {
            $vat_percentage =  auth()->user()->country->vat;
        }

        $data['vatAmount'] =  $vat_percentage / 100;
        $data['vatPercentage'] =  $vat_percentage;

        return view('dummy.update_package', $data);
    }
}
