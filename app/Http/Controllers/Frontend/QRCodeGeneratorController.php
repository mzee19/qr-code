<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Libraries\SimpleQrcode\Facades\QrCode;
use App\Models\EmailTemplate;
use App\Models\GenerateQrCode;
use App\Models\GuestQrCode;
use App\Models\Logo;
use App\Models\Shape;
use Carbon\Carbon;
use Browser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;
use ImagickPixel;
use Intervention\Image\Facades\Image;
use SVG\Nodes\Embedded\SVGImage;
use SVG\SVG;

class QRCodeGeneratorController extends Controller
{
    private $logoSize = 32;

    private $transparentImageSize = 300;

    public function generateQrCode(Request $request)
    {
        $configData = json_decode($request->config, true);
        $getQrCodeData = $request->except('range', '_token', 'contentType', 'config', 'logo_image', 'type', 'action', 'id', 'qrCodeType', 'uniqueId', 'name', 'status', 'qrcodeDownloadableType', 'qrcodeEventTimezone', 'qrcodeEventReminder', 'qrcodeEventDescription', 'generate_short_link', 'transparentImage', 'x', 'y', 'size', 'replicate_transparent_background_name', 'clone_logo_image', 'cropper_image_data', 'eyeStatus', 'subscription_update_status', 'transparent_image_status', 'transparent_image', 'ned_link', 'ned_link_call');
        if ($request->hasFile('logo_image')) {
            $rules['logo_image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:1024';
        }

        //      Start Error Correction
        $range = $request->range;
        if ((int)$range >= 1550) {
            $errorCorrection = 'H';
        } else if ((int)$range >= 1110) {
            $errorCorrection = 'Q';
        } else if ((int)$range >= 650) {
            $errorCorrection = 'M';
        } else {
            $errorCorrection = 'L';
        }

        //      Set Logo Size
        $this->logoSize = $configData['size'] / 4.6875;
        $this->transparentImageSize = $configData['size'];

        $userInfoString = '';
        $userInfoArray = [];

        //       Count Input Field Data
        $countData = count($getQrCodeData);

        // Check aye and frame status
        $eyeColorStatus = $request->eyeStatus == 'true' ? true : false;
        //       Check the Code Type
        if (!isset($request->qrCodeType) || $request->qrCodeType == 'static') {

            //       Check the type of Content
            if ($request->type == 'url' || $request->type == 'facebook' || $request->type == 'twitter' || $request->type == 'youtube' || $request->type == 'text' || $request->type == 'phone' || $request->type == 'bitcoin' || $request->type == 'downloadable') {
                //              Data collect in string form
                $autoIncrement = 1;
                foreach ($getQrCodeData as $index => $userData) {
                    $userInfoString .= $userData;
                    if ($autoIncrement < $countData) {
                        $userInfoString = $userInfoString . ',';
                    }
                    $autoIncrement = $autoIncrement + 1;
                }
            } else if ($request->type == 'event' || $request->type == 'vcard' || $request->type == 'mecard' || $request->type == 'wifi') {

                //              Data with given key and value
                foreach ($getQrCodeData as $index => $userData) {
                    if ($index == 'endDateTime' || $index == 'startDateTime') {
                        $date = date_create($userData);
                        $userData = date_format($date, "Y-m-d H:i");
                    }
                    $userInfoArray[$index] = $userData;
                }
            } else {
                //             Data with 0,1,2... key and given values.
                $increment = 0;
                foreach ($getQrCodeData as $index => $userData) {
                    $userInfoArray[$increment] = $userData;
                    $increment = $increment + 1;
                }
            }
        } else {
            if ($request->action == 'Add' && !preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $request->uniqueId) && empty($request->ned_link_domain_url)) {
                if (isset($request->ned_link_call) && $request->ned_link_call == '1') {
                    $endLinkResponse['shortLink'] = url('/q/' . $request->uniqueId);
                    $endLinkResponse['backHalf'] = $request->uniqueId;
                } else {
                    $endLinkResponse = $this->nedLinkUrl(url('/q/' . $request->uniqueId),$request->name);
                }
                $backHalf = $endLinkResponse['backHalf'] ?? '';
                if (empty($backHalf)) {
                    unset($backHalf);
                }
                $backHalfId = $endLinkResponse['backHalfId'] ?? '';
                if (empty($backHalfId)) {
                    unset($backHalfId);
                }
                $userInfoString = $endLinkResponse['shortLink'];
            } elseif (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $request->uniqueId)) {
                $userInfoString = $request->uniqueId;
            } elseif (!empty($request->ned_link_domain_url)) {
                $userInfoString = $request->ned_link_domain_url;
            } elseif ($request->action == 'Edit') {
                $generateQRCode = GenerateQrCode::where('unique_id', $request->uniqueId)->first();
                if ($generateQRCode->ned_link) {
                    $userInfoString = $generateQRCode->ned_link;
                } else {
                    $userInfoString = url('/q/' . $request->uniqueId);
                }
            } else {
                $userInfoString = url('/q/' . $request->uniqueId);
            }
        }
        //      Find the Logo
        $logo = Logo::find($configData['config']['logo']);

        $qrCodeFrame = Shape::where('type', 4)->where('id', isset($configData['config']['qrCodeFrameId']) ? $configData['config']['qrCodeFrameId'] : 0)->first();
        //      Get Config data
        $requestedBodyColor = $bodyColor = $configData['config']['bodyColor'];
        $eye1Color = $configData['config']['eye1Color'];
        $eyeBallColor = $configData['config']['eyeBall1Color'];
        $gradientColor1 = $configData['config']['gradientColor1'];
        $gradientColor2 = $configData['config']['gradientColor2'];
        $gradientType = $configData['config']['gradientType'];
        $qrCodeSize = $configData['size'];

        //      Color Change hex to RGB
        $bgColor = $this->hex2rgb($bodyColor);
        $shapeColor = $this->hex2rgb($gradientColor1);
        $shape2Color = $this->hex2rgb($gradientColor2);
        $frameColor = $this->hex2rgb($eye1Color);
        $eyeColor = $this->hex2rgb($eyeBallColor);
        //      Color Change hex to RGB

        //      Set Logo url
        $logoUrl = '';
        $adminLogoStatus = false;

        if ($logo) {
            $logoUrl = '/storage/logos/' . $logo->image;
            $adminLogoStatus = true;
        }
        //      Check the Image
        if ($request->hasFile('logo_image')) {
            $tempImage = uniqid() . '.' . $request->logo_image->getClientOriginalExtension();
            Image::make($request->logo_image)->resize(200, 200)->save('storage/temp/' . $tempImage);
            $logoUrl = '/storage/temp/' . $tempImage;
            $adminLogoStatus = false;
        }

        if ($request->clone_logo_image) {
            $logoUrl = '/storage/users/' . auth()->user()->id . '/qr-codes/logo-images/' . str_replace('temp/', '', $request->clone_logo_image);
            $adminLogoStatus = false;
        }
        // uploaded Logo image
        if (!$request->hasFile('logo_image') && (string)$request->logo_image != 'undefined' && !empty((string)$request->logo_image)) {
            // Update Condition
            $logoUrl = 'storage/users/0/qr-codes/logo-images/' . $request->logo_image;

            if (auth()->check()) {
                $logoUrl = 'storage/users/' . auth()->user()->id . '/qr-codes/logo-images/' . $request->logo_image;
            }

            // Add Condition
            if (!file_exists($logoUrl)) {
                $logoUrl = 'storage/' . $request->logo_image;
                if (!file_exists($logoUrl)) {
                    $logoUrl = 'storage/temp/' . $request->logo_image;
                }
            }
            $tempImage = $request->logo_image;
            $adminLogoStatus = false;
        }

        if ($request->transparentImage && $request->transparentImage != 'undefined') {
            $logoUrl = '';
            $adminLogoStatus = false;
        }

        //      Get QR Code svg
        if (!isset($request->qrCodeType) || $request->qrCodeType == 'static') {
            switch ($request->type) {
                case 'url':
                    if (strlen($userInfoString) <= 22) {
                        $this->logoSize = $configData['size'] / 5.9875;
                    }
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'downloadable':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);

                    break;
                case 'facebook':
                    $shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . $request->qrcodeFacebookShare;
                    $userInfoString = $request->qrcodeFacebookType == 'share' ? $shareUrl : $request->qrcodeFacebookUrl;
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'twitter':
                    $qrCodeTwitterTweetString = $request->qrcodeTwitterTweet;
                    if (strpos($qrCodeTwitterTweetString, "#") !== false) {
                        $qrCodeTwitterTweetString = str_replace('#', '%23', $qrCodeTwitterTweetString);
                    }

                    if (isset($request->qrcodeTwitterUrl)) {
                        $qrCodeTwitterTweetString = $request->qrcodeTwitterUrl;
                    }
                    $shareUrl = 'https://twitter.com/intent/tweet?text=' . $qrCodeTwitterTweetString;
                    $userInfoString = $request->qrcodeTwitterType == 'tweet' ? $shareUrl : $qrCodeTwitterTweetString;
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'youtube':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'vcard':
                    $userInfoArray["qrcodeVcardUrl"] = str_replace('//', '', $userInfoArray["qrcodeVcardUrl"]);
                    $vCard = 'BEGIN:VCARD
VERSION:' . $userInfoArray["vcardVersion"] . '
N:' . $userInfoArray["qrcodeVcardLastName"] . ';' . $userInfoArray["qrcodeVcardFirstName"] . ';;
FN:' . $userInfoArray["qrcodeVcardFirstName"] . '
ORG:' . $userInfoArray["qrcodeVcardOrganization"] . '
TEL;TYPE=MOBILE,VOICE:' . $userInfoArray["qrcodeVcardPhoneMobile"] . '
TEL;TYPE=WORK,VOICE:' . $userInfoArray["qrcodeVcardPhoneWork"] . '
TEL;TYPE=FAX:' . $userInfoArray["qrcodeVcardFaxWork"] . '
URL;TYPE=WEBSITE:' . $userInfoArray["qrcodeVcardUrl"] . '
ADR;TYPE=WORK,PREF:;;' . $userInfoArray["qrcodeVcardStreet"] . ';' . $userInfoArray["qrcodeVcardCity"] . ';' . $userInfoArray["qrcodeVcardState"] . ';' . $userInfoArray["qrcodeVcardZipcode"] . ';' . $userInfoArray["qrcodeVcardCountry"] . '
LABEL;TYPE=WORK,PREF:' . $userInfoArray["qrcodeVcardStreet"] . '\n' . $userInfoArray["qrcodeVcardCity"] . '\, ' . $userInfoArray["qrcodeVcardState"] . ' ' . $userInfoArray["qrcodeVcardZipcode"] . '\n' . $userInfoArray["qrcodeVcardCountry"] . '
EMAIL:' . $userInfoArray["qrcodeVcardEmail"] . '
REV:2021-04-24T19:52:43Z
END:VCARD';
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $vCard, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'mecard':
                    $meCard = 'MECARD:Simple, Software;Some ' . $userInfoArray["qrcodeMecardStreet"] . ', Somewhere, ' . $userInfoArray["qrcodeMecardZipcode"] . ';TEL:' . $userInfoArray["qrcodeMecardPhone1"] . ';EMAIL:' . $userInfoArray["qrcodeMecardEmail"] . ';';
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $meCard, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'text':
                    if (strlen($userInfoString) <= 16) {
                        $this->logoSize = $configData['size'] / 5.6875;
                    }
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'sms':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'SMS', $userInfoArray, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'email':
                    $printSubject = count($userInfoArray) >= 2 ? $userInfoArray[1] : '';
                    $printBody = count($userInfoArray) >= 3 ? $userInfoArray[2] : '';

                    $printSubject = str_replace(' ', '%20', $printSubject);
                    $printBody = str_replace(' ', '%20', $printBody);
                    if ($logoUrl) {
                        $base64image = QrCode::encoding('UTF-8')->format("svg")
//                            ->merge(public_path() . '/' . $logoUrl, .2, true)
                            ->errorCorrection($errorCorrection)
                            ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                            ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                            ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                            ->frame($configData['config']['frame'])->eye($configData['config']['eyeBall'])->style($configData['config']['body'])->size($qrCodeSize)
                            ->generate('mailto:' . $userInfoArray[0] . '?subject=' . $printSubject . '&body=' . $printBody);

                        // Get Height and Width of Qr code
                        $image = new Imagick();
                        $image->readImageBlob($base64image);
                        $width = $image->getImageWidth();
                        $height = $image->getImageHeight();
                        $center_width = $width / 2 - $this->logoSize / 2;
                        $center_height = $height / 2 - $this->logoSize / 2;
                        // End Get Height and Width of Qr code

                        $path = public_path($logoUrl);
                        //                Check logo transparent
                        $path = $this->logoImageTransparentCheck($path, $adminLogoStatus, $requestedBodyColor);

                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        $image = SVG::fromString($base64image);
                        $doc = $image->getDocument();

                        $doc->addChild(new \SVG\Nodes\Embedded\SVGImage($base64, $center_width, $center_height, $this->logoSize, $this->logoSize));
                        $base64image = $image;

                        $image = $this->base64ToSvgConvertImage($base64image);
                        $generatedQRCodeHTML['image'] = $image['image'];
                        $generatedQRCodeHTML['imageId'] = $image['imageName'];
                    } else {
                        $generatedQRCodeHTML['image'] = (string)QrCode::encoding('UTF-8')->errorCorrection($errorCorrection)
                            ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                            ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                            ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                            ->frame($configData['config']['frame'])->eye($configData['config']['eyeBall'])->style($configData['config']['body'])
                            ->size($qrCodeSize)->generate('mailto:' . $userInfoArray[0] . '?subject=' . $printSubject . '&body=' . $printBody);

                        $image = $this->getImagePath($generatedQRCodeHTML['image']);
                        $generatedQRCodeHTML['image'] = $image['image'];
                        $generatedQRCodeHTML['imageId'] = $image['imageName'];
                    }
                    break;
                case 'phone':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'phoneNumber', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'event':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'calendar', $userInfoArray, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'wifi':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'wiFi', $userInfoArray, $qrCodeSize, $eyeColorStatus);
                    break;
                case 'location':
                    if ($logoUrl) {
                        $base64image = QrCode::encoding('UTF-8')->format("svg")
//                            ->merge(public_path() . '/' . $logoUrl, .2, true)
                            ->errorCorrection($errorCorrection)
                            ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                            ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                            ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])
                            ->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                            ->frame($configData['config']['frame'])
                            ->eye($configData['config']['eyeBall'])
                            ->style($configData['config']['body'])
                            ->size($qrCodeSize)
                            ->geo($userInfoArray[0], $userInfoArray[1]);

                        // Get Height and Width of Qr code
                        $image = new Imagick();
                        $image->readImageBlob($base64image);
                        $width = $image->getImageWidth();
                        $height = $image->getImageHeight();
                        $center_width = $width / 2 - $this->logoSize / 2;
                        $center_height = $height / 2 - $this->logoSize / 2;
                        // End Get Height and Width of Qr code

                        $path = public_path($logoUrl);
                        //                Check logo transparent
                        $path = $this->logoImageTransparentCheck($path, $adminLogoStatus, $requestedBodyColor);

                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        $image = SVG::fromString($base64image);
                        $doc = $image->getDocument();

                        $doc->addChild(new \SVG\Nodes\Embedded\SVGImage($base64, $center_width, $center_height, $this->logoSize, $this->logoSize));
                        $base64image = $image;

                        $image = $this->base64ToSvgConvertImage($base64image);
                        $generatedQRCodeHTML['image'] = $image['image'];
                        $generatedQRCodeHTML['imageId'] = $image['imageName'];
                    } else {
                        $generatedQRCodeHTML['image'] = (string)QrCode::encoding('UTF-8')->errorCorrection($errorCorrection)
                            ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                            ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                            ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                            ->frame($configData['config']['frame'])->eye($configData['config']['eyeBall'])->style($configData['config']['body'])->size($qrCodeSize)->geo($userInfoArray[0], $userInfoArray[1]);
                        $image = $this->getImagePath($generatedQRCodeHTML['image']);
                        $generatedQRCodeHTML['image'] = $image['image'];
                        $generatedQRCodeHTML['imageId'] = $image['imageName'];
                    }
                    break;
                case 'bitcoin':
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'BTC', $userInfoString, $qrCodeSize, $eyeColorStatus);
                    break;
                default:
                    $appUrl = env('APP_URL');
                    $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $appUrl, $qrCodeSize, $eyeColorStatus);
                    break;
            }
        } else {
            if (strlen($userInfoString) <= 45) {
                $this->logoSize = $configData['size'] / 5.6875;
            }
            $generatedQRCodeHTML = $this->makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $configData['config']['frame'], $configData['config']['eyeBall'], $configData['config']['body'], $logoUrl, $adminLogoStatus, 'generate', $userInfoString, $qrCodeSize, $eyeColorStatus);
        }
        // Qr Code for Transparent Image
        if ($request->transparentImage && $request->transparentImage != 'undefined') {
            $transparentImageUrl = public_path('storage/temp/' . $request->transparentImage);
            if (!file_exists($transparentImageUrl)) {
                $transparentImageUrl = public_path('storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->transparentImage);

                if (!file_exists($transparentImageUrl)) {
                    $fileName = uniqid();
                    if (str_contains($request->transparentImage, '.jpeg')) {
                        $getExt = '.jpeg';
                    } elseif (str_contains($request->transparentImage, '.jpg')) {
                        $getExt = '.jpg';
                    } else {
                        $getExt = '.png';
                    }
                    $target_path = 'temp/' . $fileName . $getExt;
                    Storage::disk('public')->copy('temp/' . $request->transparentImage, $target_path);
                }
            }

            $transparentImage[] = $this->transparentImage($generatedQRCodeHTML['imageId'], $transparentImageUrl, $request->all());
            $generatedQRCodeHTML['image'] = $transparentImage[0]['image'];
            $generatedQRCodeHTML['imageId'] = $transparentImage[0]['imageId'];
        }

        if ($request->replicate_transparent_background_name) {
            $transparentImageUrl = public_path('storage/temp/' . $request->replicate_transparent_background_name);
            if (!file_exists($transparentImageUrl)) {
                $transparentImageUrl = public_path('storage/users/' . auth()->user()->id . '/qr-codes/transparent-images/' . $request->replicate_transparent_background_name);
            }

            $transparentImage[] = $this->transparentImage($generatedQRCodeHTML['imageId'], $transparentImageUrl, $request->all());
            $generatedQRCodeHTML['image'] = $transparentImage[0]['image'];
            $generatedQRCodeHTML['imageId'] = $transparentImage[0]['imageId'];
        }

        $uploadedLogoName = '';
        if ($request->hasFile('logo_image') || (!$request->hasFile('logo_image') && (string)$request->logo_image != 'undefined' && (string)!empty($request->logo_image))) {
            $uploadedLogoName = isset($tempImage) ? $tempImage : '';
        }
        if (!empty($qrCodeFrame)) {
            $qrCodePosition = $this->qrCodeFramePosition($qrCodeFrame);
            $generatedQRCodeHTML = $this->frameMergeWithQrCode($generatedQRCodeHTML['imageId'], $qrCodeFrame->image, $qrCodePosition,$qrCodeSize);
//                        $generatedQRCodeHTML = $this->frameMergeWithQrCode($generatedQRCodeHTML['imageId'], $qrCodeFrame->image, $qrCodePosition,$qrCodeSize);
        }

        $imageId = isset($generatedQRCodeHTML['imageId']) ? $generatedQRCodeHTML['imageId'] : 0;

        if (!auth()->check() && isset($userInfoString) && $userInfoString != config('app.url').'/') {
            // set IP address and API access key
            $ip = $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? '221.120.216.102' : $_SERVER['REMOTE_ADDR'];
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
            $qrCodeData = $this->qrCodeTypeData($request->all());

            if (!empty($api_result) && array_key_exists('country', $api_result) && !empty($api_result['country'])) {
                $device = 'Desktop';
                if (Browser::isMobile())
                    $device = 'Phone';
                else if (Browser::isTablet())
                    $device = 'Tablet';
                GuestQrCode::create([
                    'ip_address' => $ip,
                    'type' => $request->type,
                    'image' => $imageId,
                    'logo_image' => $uploadedLogoName,
                    'fields' => json_encode($getQrCodeData),
                    'data' => $qrCodeData,
                    'config' => json_encode($configData),
                    'browser' => Browser::browserFamily(),
                    'city' => $api_result['city'],
                    'country' => $api_result['country'],
                    'platform' => Browser::platformFamily(),
                    'device' => $device,
                    'location' => $api_result['lat'] . ',' . $api_result['lon'],
                ]);
            }
        }

        $jsonData = [
            'status' => 1,
            'html' => $generatedQRCodeHTML['image'],
            'image_id' => $imageId,
            'ned_link' => $userInfoString,
            'logo_image' => $uploadedLogoName,
            'back_half' => $backHalf ?? '',
            'back_half_id' => $backHalfId ?? '',
        ];
        return response()->json($jsonData);
    }

    /*Color convert hex to rgb*/
    public function hex2rgb($hex_color)
    {
        $values = str_replace('#', '', $hex_color);
        switch (strlen($values)) {
            case 3;
                list($r, $g, $b) = sscanf($values, "%1s%1s%1s");
                return [hexdec("$r$r"), hexdec("$g$g"), hexdec("$b$b")];
            case 6;
                return array_map('hexdec', sscanf($values, "%2s%2s%2s"));
            default:
                return false;
        }
    }

    /*Data convert string to array*/
    public function arrayToString($array)
    {
        $string = '';
        $countArray = count($array);
        foreach ($array as $index => $data) {
            $string .= $data;
            if ($index + 1 < $countArray) {
                $string .= ',';
            }
        }
        return $string;
    }

    /*Made Qr Code for Image and without image*/
    protected function makeQrCode($errorCorrection, $eyeColor, $frameColor, $shapeColor, $shape2Color, $gradientType, $bgColor, $requestedBodyColor, $frame, $eye, $body, $logoUrl = null, $adminLogoStatus, $generateType, $data, $qrCodeSize, $eyeColorStatus)
    {
        if ($generateType == 'SMS') {
            if ($logoUrl) {
                $generatedQRCodeHTML = QrCode::encoding('UTF-8')->format('svg')
                    // ->merge(public_path() . '/' . $logoUrl, .2, true)
                    ->errorCorrection($errorCorrection)
                    ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                    ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                    ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])
                    ->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                    ->frame($frame)
                    ->eye($eye)
                    ->style($body)
                    ->size($qrCodeSize)
                    ->$generateType($data['0'], $data['1']);

                // Get Height and Width of Qr code
                $image = new Imagick();
                $image->readImageBlob($generatedQRCodeHTML);
                $width = $image->getImageWidth();
                $height = $image->getImageHeight();
                $center_width = $width / 2 - $this->logoSize / 2;
                $center_height = $height / 2 - $this->logoSize / 2;
                // End Get Height and Width of Qr code

                $path = public_path($logoUrl);
                //                Check logo transparent
                $path = $this->logoImageTransparentCheck($path, $adminLogoStatus, $requestedBodyColor);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $image = SVG::fromString($generatedQRCodeHTML);
                $doc = $image->getDocument();
                $doc->addChild(new \SVG\Nodes\Embedded\SVGImage($base64, $center_width, $center_height, $this->logoSize, $this->logoSize));
                $generatedQRCodeHTML = $image;

                $generatedQRCodeHTML = $this->base64ToSvgConvertImage($generatedQRCodeHTML);

                return ['image' => $generatedQRCodeHTML['image'], 'imageId' => $generatedQRCodeHTML['imageName']];
            }
            $generatedQRCodeHTML = (string)QrCode::encoding('UTF-8')->errorCorrection($errorCorrection)
                ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)
                ->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                ->frame($frame)->eye($eye)->style($body)->size($qrCodeSize)->$generateType($data['0'], $data['1']);
        } else {
            if ($logoUrl) {
                $generatedQRCodeHTML = QrCode::encoding('UTF-8')->format('svg')
                    ->errorCorrection($errorCorrection)
                    ->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])
                    ->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                    ->frame($frame)->eye($eye)->style($body)->size($qrCodeSize)->$generateType($data);

                // Get Height and Width of Qr code
                $image = new Imagick();
                $image->readImageBlob($generatedQRCodeHTML);
                $width = $image->getImageWidth();
                $height = $image->getImageHeight();
                $center_width = $width / 2 - $this->logoSize / 2;
                $center_height = $height / 2 - $this->logoSize / 2;
                // End Get Height and Width of Qr code

                $path = public_path($logoUrl);
//                Check logo transparent
                $transparentLogoImagePath = $this->logoImageTransparentCheck($path, $adminLogoStatus, $requestedBodyColor);
//                $transparentLogoImagePath = $this->logoImageTransparentCheck($path,$adminLogoStatus, $requestedBodyColor,$frameColor);

                $type = pathinfo($transparentLogoImagePath, PATHINFO_EXTENSION);
                $data = file_get_contents($transparentLogoImagePath);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $image = SVG::fromString($generatedQRCodeHTML);
                $doc = $image->getDocument();

                $doc->addChild(new \SVG\Nodes\Embedded\SVGImage($base64, $center_width, $center_height, $this->logoSize, $this->logoSize));
                $generatedQRCodeHTML = $image;

                $generatedQRCodeHTML = $this->base64ToSvgConvertImage($generatedQRCodeHTML);

                return ['image' => $generatedQRCodeHTML['image'], 'imageId' => $generatedQRCodeHTML['imageName']];
            }

            $generatedQRCodeHTML = (string)QrCode::encoding('UTF-8')->format('svg')
                ->errorCorrection($errorCorrection)->{$eyeColorStatus ? 'eyeColor' : 'size'}(0, $eyeColor[0], $eyeColor[1], $eyeColor[2], $frameColor[0], $frameColor[1], $frameColor[2])->gradient($shapeColor[0], $shapeColor[1], $shapeColor[2], $shape2Color[0], $shape2Color[1], $shape2Color[2], $gradientType)->color($shapeColor[0], $shapeColor[1], $shapeColor[2])->backgroundColor($bgColor[0], $bgColor[1], $bgColor[2])
                ->frame($frame)->eye($eye)->style($body)->size($qrCodeSize)->$generateType($data);
        }
        //  Get Image and Image Name
        $image = $this->getImagePath($generatedQRCodeHTML);

        return ['image' => $image['image'], 'imageId' => $image['imageName']];
    }

    // Image save and return image tag and path ...
    function getImagePath($generatedQRCodeHTML)
    {
        $newImageName = uniqid() . '.svg';
        file_put_contents(public_path('storage/temp/' . $newImageName), $generatedQRCodeHTML);

        $imageUrl = Storage::url('temp/' . $newImageName);
        $saveImage['image'] = '<img src="' . asset($imageUrl) . '" >';
        $saveImage['imageName'] = $newImageName;

        return $saveImage;
    }

    function base64ToSvgConvertImage($base64_string,$designFrameStatus = '',$designFrameData = '')
    {
        $im = $base64_string;
        $newImageName = uniqid() . '.svg';
        file_put_contents(public_path('storage/temp/' . $newImageName), $im);
        $imageUrl = Storage::url('temp/' . $newImageName);

//        dd($this->svgScaleHack(file_get_contents(public_path($imageUrl)),100,100));

        $saveImage['image'] = '<img src="' . asset($imageUrl) . '" >';
        $saveImage['imageName'] = $newImageName;

        return $saveImage;
    }

    public function nedLinkUrl($link, $title = '')
    {
        if (\App::environment() === 'local') {
            $responseData['shortLink'] = $link;
            return $responseData;
        }
        if (isset(auth()->user()->userDomain->domain)) {
            $userDomain = auth()->user()->userDomain;
            if ($userDomain->is_verified) {
                if ($userDomain->status) {
                    $domain = $userDomain->domain;
                } else {
                    $domain = 'www.ned.link';
                }
            } else {
                $domain = 'www.ned.link';
            }
        } else {
            $domain = 'www.ned.link';
        }

        $data = array(
            "domain" => $domain,
            "link" => $link,
            'primaryEmail' => auth()->user()->email,
            'password' => auth()->user()->original_password,
            'username' => auth()->user()->username,
            'firstName' => auth()->user()->name,
            'lastName' => '',
            'from' => 'QRC',
            'title' => empty($title) ? auth()->user()->name : $title,
            'ipAddress' => $_SERVER['REMOTE_ADDR']
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('constants.ned_link_url') . "/q-link/create",
//            CURLOPT_URL => "https://www.ned.link/q-link/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true);
        $responseData['shortLink'] = $link;
        if (isset($response['qlink']['customizeBackHalf'])) {
            $responseData['shortLink'] = "https://" . $domain . '/' . $response['qlink']['customizeBackHalf'];
            $responseData['backHalf'] = $response['qlink']['customizeBackHalf'];
            $responseData['backHalfId'] = $response['qlink']['_id'];
        }
        if (isset($response['userCreated']) && $response['userCreated'] == true) {
            /*
            ** Start send email to user
            */

            $name = auth()->user()->name;
            $email = auth()->user()->email;
            $lang = \App::getLocale();

            $email_template = EmailTemplate::where('type', 'lite_account_created_on_other_platforms')->first();
            $email_template = transformEmailTemplateModel($email_template, $lang);

            $subject = $email_template['subject'];
            $content = $email_template['content'];

            $search = array("{{name}}", "{{app_name}}", "{{platforms}}");
            $replace = array($name, env('APP_NAME'), 'NED.link');
            $content = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content, '', '', $lang);

            /*
            ** End send email to user
            */
        }
        return $responseData;
    }

    public function transparentImage($qrImage, $transparentImage, $request)
    {
        $uploadedImage = file_get_contents($transparentImage);
        // Create an imagick object
        $im = new Imagick();
        $im->setBackgroundColor(new ImagickPixel('transparent'));
        $im->readImageBlob($uploadedImage);
        $im->cropImage((int)$request['size'], (int)$request['size'], (int)$request['x'], (int)$request['y']);

        $im->setImageFormat('png');

        $fileName = uniqid();

        file_put_contents(public_path('storage/temp/' . $fileName . '.png'), $im);

        // Add transparent image background
        $image = SVG::fromFile(public_path('/storage/temp/' . $qrImage));
        $doc = $image->getDocument();

        $rect = $doc->getChild(0);
        $rect->setStyle('fill-opacity', '0.5');

        $img = file_get_contents(public_path('storage/temp/' . $fileName . '.png'));
        //        dd($img);
        // Encode the image string data into base64
        $data = base64_encode($img);
        $doc->addChild(
            (new SVGImage('data:image/png;base64,' . $data, 0, 0, $this->transparentImageSize, $this->transparentImageSize)),
            0
        );

        file_put_contents(public_path('storage/temp/' . $fileName . '.svg'), $image);
        //exit;
        // header('Content-Type: image/svg+xml');
        $saveImage['image'] = '<img src="' . asset('storage/temp/' . $fileName . '.svg') . '" >';
        $saveImage['imageId'] = $fileName . '.svg';
        $saveImage['transparentImage'] = $transparentImage;

        return $saveImage;
    }

    function logoImageTransparentCheck($path, $adminLogoStatus, $requestedBodyColor)
    {
        if (!$adminLogoStatus) {
            $logoImage = new Imagick();
            $logoImage->readImage($path);
            $hasTransparency = $logoImage->getImageAlphaChannel();
            if ($hasTransparency) {
//                Logo path
                $img = Image::make($path);
//                $HSV = $this->RGB_TO_HSV($frameColor);
//                dump($HSV);
//                $img->colorize($HSV['H'], $HSV['S'], $HSV['V']);

// just add a little green tone to the image
//                $img->colorize(0, 30, 0);

//                made transparent color image
                $backgroundColor = Image::canvas(200, 200, $requestedBodyColor);
//                Append transparent image with background color
                $img->insert($backgroundColor, 'center', 0, 0);
//                Append image transparent image with original logo image
                $img->insert($path, 'center', 0, 0);
//                Replace image with existing image
                $ext = pathinfo(
                    parse_url($path, PHP_URL_PATH),
                    PATHINFO_EXTENSION
                );
//                $logoImage->transformimagecolorspace(12);
                $name = uniqid() . '.' . $ext;
                $path = public_path('/storage/temp/' . $name);
                $img->save($path);
            }
        }
        return $path;
    }

    public function frameMergeWithQrCode($qrImage, $frame, $qrCodePosition,$size)
    {
        $qrCodePath = public_path('/storage/temp/' . $qrImage);
        $framePath = public_path('/storage/shapes/' . $frame);

        $uniqueNumber = uniqid();

//        QR Code svg to png generate
        $image = new Imagick();
        $image->readImageBlob(file_get_contents($qrCodePath));
        /*png settings*/
        $image->setImageFormat("png24");
//        $image->resizeImage(300, 300, imagick::FILTER_LANCZOS, 1);
        if (\App::environment() !== 'local') {
            $newImagePath = public_path('storage/temp/' . $uniqueNumber.'.png');
            shell_exec('inkscape  ' . $qrCodePath . ' -o  ' . $newImagePath);
        } else{
            $newImagePath = public_path('storage/temp/' . $uniqueNumber.'.png');
            file_put_contents($newImagePath, $image);
        }

        //                Check logo transparent
        $type = pathinfo($newImagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($newImagePath);

        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $framePath = preg_replace("/\r|\n|\t/", "",  file_get_contents($framePath));
        $image = SVG::fromString($framePath);
        $doc = $image->getDocument();
        $doc->addChild(new \SVG\Nodes\Embedded\SVGImage($base64, $qrCodePosition['x'], $qrCodePosition['y'], $qrCodePosition['width'], $qrCodePosition['height']));

        $generatedQRCodeHTML = $image;
        $generatedQRCodeHTML = $this->base64ToSvgConvertImage($generatedQRCodeHTML);
        $generatedQRCodeHTML['imageId'] = $generatedQRCodeHTML['imageName'];
        $size = '';
        if(!empty($size)){

            $svg = file_get_contents(public_path('storage/temp/' . $generatedQRCodeHTML['imageId']));

            $im = new Imagick();
            $im->setBackgroundColor(new ImagickPixel('transparent'));
            $im->readImageBlob($svg);

            // Generate new file name
            // Set image height and width
            $im->resizeImage($size, $size, imagick::FILTER_LANCZOS, 1);

            $im->getImageBlob();
            file_put_contents(public_path('storage/temp/' . $uniqueNumber.'.svg'), $im);

            $generatedQRCodeHTML['image'] = '<img src="' . asset('storage/temp/' . $uniqueNumber.'.svg') . '" >';
            $generatedQRCodeHTML['imageName'] = $uniqueNumber.'.svg';
            $generatedQRCodeHTML['imageId'] = $uniqueNumber.'.svg';
        }

        return $generatedQRCodeHTML;
    }

    public function qrCodeFramePosition($qrCodeFrame)
    {
        switch ($qrCodeFrame->id) {
            case 36:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 35;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 37:
                $qrCodePosition['x'] = 110;
                $qrCodePosition['y'] = 30;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 38:
                $qrCodePosition['x'] = 38;
                $qrCodePosition['y'] = 38;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 39:
                $qrCodePosition['x'] = 25;
                $qrCodePosition['y'] = 120;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 40:
                $qrCodePosition['x'] = 70;
                $qrCodePosition['y'] = 34;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 41:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 35;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 42:
                $qrCodePosition['x'] = 25;
                $qrCodePosition['y'] = 30;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 43:
                $qrCodePosition['x'] = 33;
                $qrCodePosition['y'] = 33;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 44:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 210;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 45:
                $qrCodePosition['x'] = 70;
                $qrCodePosition['y'] = 300;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 46:
                $qrCodePosition['x'] = 160;
                $qrCodePosition['y'] = 152;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;

                break;
            case 47:
                $qrCodePosition['x'] = 60;
                $qrCodePosition['y'] = 30;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 48:
                $qrCodePosition['x'] = 100;
                $qrCodePosition['y'] = 25;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 49:
                $qrCodePosition['x'] = 90;
                $qrCodePosition['y'] = 85;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 50:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 30;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 51:
                $qrCodePosition['x'] = 60;
                $qrCodePosition['y'] = 157;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 52:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 90;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 53:
                $qrCodePosition['x'] = 65;
                $qrCodePosition['y'] = 90;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 54:
                $qrCodePosition['x'] = 205;
                $qrCodePosition['y'] = 150;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 55:
                $qrCodePosition['x'] = 65;
                $qrCodePosition['y'] = 190;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 56:
                $qrCodePosition['x'] = 105;
                $qrCodePosition['y'] = 240;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 57:
                $qrCodePosition['x'] = 35;
                $qrCodePosition['y'] = 35;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 58:
                $qrCodePosition['x'] = 36;
                $qrCodePosition['y'] = 305;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 59:
                $qrCodePosition['x'] = 88;
                $qrCodePosition['y'] = 175;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 60:
                $qrCodePosition['x'] = 45;
                $qrCodePosition['y'] = 165;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            case 61:
                $qrCodePosition['x'] = 95;
                $qrCodePosition['y'] = 113;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
            default:
                $qrCodePosition['x'] = 0;
                $qrCodePosition['y'] = 0;
                $qrCodePosition['width'] = 320;
                $qrCodePosition['height'] = 320;
                break;
        }

        return $qrCodePosition;
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
                return isset($data['text']) ?$data['text'] : $data['qrcodeText'];
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

    function svgScaleHack($svg, $minWidth, $minHeight)
    {
        $reW = '/(.*<svg[^>]* width=")([\d.]+px)(.*)/si';
        $reH = '/(.*<svg[^>]* height=")([\d.]+px)(.*)/si';
        preg_match($reW, $svg, $mw);
        preg_match($reH, $svg, $mh);
        $width = floatval($mw[2]);
        $height = floatval($mh[2]);
        if (!$width || !$height) return false;

        // scale to make width and height big enough
        $scale = 1;
        if ($width < $minWidth)
            $scale = $minWidth/$width;
        if ($height < $minHeight)
            $scale = max($scale, ($minHeight/$height));

        $width *= $scale*2;
        $height *= $scale*2;

        $svg = preg_replace($reW, "\${1}{$width}px\${3}", $svg);
        $svg = preg_replace($reH, "\${1}{$height}px\${3}", $svg);

        return $svg;
    }
}
