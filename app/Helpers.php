<?php

use App\Models\EmailTemplate;
use App\Models\PackageSubscription;
use Illuminate\Support\Facades\Config;

function checkImage($path = '', $placeholder = '', $filename = '')
{
    if (empty($placeholder)) {
        $placeholder = 'placeholder.png';
    }

    if (!empty($path)) {
        $url = explode('storage', $path);

        $url = public_path() . '/storage' . $url[1];


        //$old_file = public_path() . '/logo_images/' . $model->image;

        if (file_exists($url) && !empty($filename))
            return $path;
        else
            return asset('images/' . $placeholder);
    } else {
        return asset('images/' . $placeholder);
    }
}

function sendEmail($email, $subject, $content, $pdf = '', $filename = '', $lang = 'en', $csv = '')
{
    if (!empty($pdf)) {
$emailList = ['eventus@timmunity.com','karsten.steffens@timmunity.com','taxdocument@timmunity.com'];
        try {
            \Mail::send('emails.template', ['content' => $content], function ($message) use ($email, $subject, $pdf, $filename,$emailList) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($email);
                $message->bcc($emailList);
                $message->subject($subject);

                if (!empty($pdf))
                    $message->attachData($pdf->output(), $filename);
            });
        } catch (\Exception $e) {
            \Log::info('Send Email Exception', array(
                'Message' => $e->getMessage()
            ));
        }
    } else {
        try {
            \Mail::queue(new \App\Mail\SendMail($email, $subject, $content, $filename, $lang, $csv));
        } catch (\Exception $e) {
            \Log::info('Send Email Exception', array(
                'Message' => $e->getMessage()
            ));
        }
    }
}

function switchPackageNotification($payment, $previousPackageId, $previousSubscriptionId, $lang)
{

//    $lang = $payment->lang;
//    \App::setLocale($lang);

    $user = $payment->user;
    $name = $user->name;
    $email = $user->email;
    \Log::info('Switch Package Notification', array(
        'User Data' => $user,
        'User Email' => $user->email,
    ));
    $oldPackage = \App\Models\Package::find($previousPackageId);
    $oldSubscription = \App\Models\PackageSubscription::find($previousSubscriptionId);
    $oldPackageName = $oldPackage->title;
    $oldPackageType = empty($oldSubscription->type) ? '' : ($oldSubscription->type == 1 ? '(' . __('Monthly') . ')' : '(' . __('Yearly') . ')');
    $newPackage = \App\Models\Package::find($user->package_id);
    $newPackageName = $newPackage->title;
    $newPackageType = empty($user->subscription->type) ? '' : ($user->subscription->type == 1 ? '(' . __('Monthly') . ')' : '(' . __('Yearly') . ')');


    $email_template = EmailTemplate::where('type', 'switch_package_notification')->first();
    $email_template = transformEmailTemplateModel($email_template, $lang);

    $subject = $email_template['subject'];
    $content = $email_template['content'];

    $search = array("{{name}}", "{{app_name}}", "{{old_package}}", "{{new_package}}", "{{platform}}");
    $replace = array($name, env('APP_NAME'), $oldPackageName . ' ' . $oldPackageType, $newPackageName . ' ' . $newPackageType, 'QRCode');
    $content = str_replace($search, $replace, $content);

    sendEmail($email, $subject, $content, '', '', $lang);
}

function rights()
{
    $result = DB::table('rights')
        ->select('rights.id', 'rights.name as right_name', 'modules.name as module_name')
        ->join('modules', 'rights.module_id', '=', 'modules.id')
        ->where(['rights.status' => 1])
        ->get()
        ->toArray();

    $array = [];

    for ($i = 0; $i < count($result); $i++) {
        $array[$result[$i]->module_name][] = $result[$i];
    }
    return $array;
}

function have_right($right_id)
{
    $user = \Auth::user();
    if ($user['role_id'] == 1) {
        return true;
    } else {
        $result = \DB::table('roles')
            ->where('id', $user['role_id'])
            ->whereRaw("find_in_set($right_id,right_ids)")
            ->first();

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }
}

function access_denied()
{
    abort(403, 'You have no right to perform this action.');
}

function settingValue($key)
{
    $setting = \DB::table('settings')->where('option_name', $key)->first();
    if ($setting)
        return $setting->option_value;
    else
        return '';
}

function getCount($tbl, $where = '')
{
    if (!empty($where))
        return \DB::table($tbl)->where($where)->count();
    else
        return \DB::table($tbl)->count();
}

function getRecord($tbl, $where)
{
    $record = \DB::table($tbl)->where($where)->first();
    if ($record) {
        return $record;
    } else {
        return "";
    }
}

function getValue($tbl, $column, $where)
{
    $record = \DB::table($tbl)->where($where)->first();
    if ($record) {
        return $record->$column;
    } else {
        return "";
    }
}

function createNotification($type, $user_id, $message, $link, $fa_class)
{
    $notifications = [
        'type' => $type,
        'user_id' => $user_id,
        'message' => $message,
        'link' => $link,
        'fa_class' => $fa_class,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ];

    $id = DB::table('notifications')->insertGetId($notifications);

    $notification = \App\Models\Notification::find($id);
    broadcast(new \App\Events\NotificationSent($notification))->toOthers();
    return $notification;
}

function getNotifications($user_id, $is_read, $type) //$type 1 for admin and 2 for user
{
    if ($is_read == -1) // all
        $conditions = ['type' => $type];
    else
        $conditions = ['type' => $type, 'is_read' => $is_read];

    if ($type == 2) // user
    {
        $conditions['user_id'] = $user_id;
    }

    return \App\Models\Notification::select('*')
        ->where($conditions)
        ->orderBy('created_at', 'DESC')
        ->get();
}

function activatePackage($user_id, $package)
{
    $packageLinkedFeatures = $package->linkedFeatures->pluck('count', 'feature_id')->toArray();

    $packageSubscription = \App\Models\PackageSubscription::create([
        'user_id' => $user_id,
        'package_id' => $package->id,
        'price' => $package->price,
        'features' => empty($package->linkedFeatures) ? '' : json_encode($packageLinkedFeatures),
        'description' => $package->description,
        'start_date' => \Carbon\Carbon::now('UTC')->timestamp,
        'end_date' => Null,
        'is_active' => 1
    ]);

    \App\User::where('id', $user_id)->update([
        'package_id' => $package->id,
        'package_subscription_id' => $packageSubscription->id,
        'on_trial' => 0,
        'last_quota_revised' => NULL,
        'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
        'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
        'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
        'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
    ]);
}

function durationConversion($seconds)
{
    $time = gmdate("H:i:s", $seconds);
    $timeArr = explode(':', $time);
    $durationStr = '';

    if ($timeArr[0] != '00')
        $durationStr .= $timeArr[0] . ' hr ';
    if ($timeArr[1] != '00')
        $durationStr .= $timeArr[1] . ' min ';
    if ($timeArr[2] != '00')
        $durationStr .= $timeArr[2] . ' sec';

    return $durationStr;
}

function getPreviousMonthDates($from, $to, $timezone)
{
    $to_date = new \Carbon\Carbon($to);
    $from_date = new \Carbon\Carbon($from);

    $diff = $to_date->diffInDays($from_date);

    $prev_to = \Carbon\Carbon::createFromTimeStamp($from, "UTC")->tz($timezone)->subDay()->toDateString();
    $prev_to = $prev_to . ' 23:59:59';

    $prev_from = \Carbon\Carbon::createFromTimeStamp(strtotime($prev_to), "UTC")->tz($timezone)->subDays($diff + 1)->toDateString();
    $prev_from = $prev_from . ' 00:00:00';

    return array(
        'from' => $prev_from,
        'to' => $prev_to,
    );
}

function formatBytes($size, $precision = 2)
{
    if ($size > 0) {
        $size = (int)$size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    } else {
        return $size;
    }
}

function convertBytesToGigaBytes($bytes)
{
    return number_format($bytes / 1073741824, 2);
}

function convertToByte($p_sFormatted)
{
    $aUnits = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
    $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
    if (intval($sUnit) !== 0) {
        $sUnit = 'B';
    }
    if (!in_array($sUnit, array_keys($aUnits))) {
        return false;
    }
    $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
    if (!intval($iUnits) == $iUnits) {
        return false;
    }
    return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function translation($item_id, $language_module_id, $lang, $column_name, $org_value)
{
    $record = \App\Models\LanguageTranslation::where(['item_id' => $item_id, 'language_module_id' => $language_module_id, 'language_code' => $lang, 'column_name' => $column_name])->first();

    if (!empty($record))
        return $record->item_value;
    else
        return $org_value;
}

function translationByDeepL($text, $target_lang)
{
    if ($target_lang == 'br') {
        $target_lang = 'pt-BR';
    }

    $params = array(
        'auth_key' => 'd554170c-80ad-7185-f19c-b776394eb975',
        'text' => $text,
        'target_lang' => $target_lang,
        'source_lang' => 'en'
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.deepl.com/v2/translate?" . http_build_query($params),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $responseArr = json_decode($response, true);

    if (array_key_exists('translations', $responseArr)) {
        return $responseArr['translations'][0]['text'];
    } else {
        return $text;
    }
}

function transformEmailTemplateModel($model, $lang)
{
    $subject = translation($model->id, 4, $lang, 'subject', $model->subject);
    $content = $model->content;

    $search = [];
    $replace = [];
    $ids = [];
    $labels = $model->emailTemplateLabels;

    foreach ($labels as $object) {
        $search[$object->id] = '{{' . $object->label . '}}';
        $replace[$object->id] = $object->value;
        $ids[] = $object->id;
    }

    if ($lang != 'en') {
        $translations = \App\Models\LanguageTranslation::where(['language_module_id' => 8, 'language_code' => $lang, 'column_name' => 'value'])->whereIn('item_id', $ids)->get();

        foreach ($translations as $translation) {
            $replace[$translation->item_id] = $translation->item_value;
        }
    }

    $content = str_replace($search, $replace, $content);

    return [
        'id' => $model->id,
        'subject' => $subject,
        'content' => $content,
        'lang' => $lang,
        'type' => $model->type,
        'info' => $model->info,
        'status' => $model->status
    ];
}

function cmsPages()
{
    return \DB::table('cms_pages')->where('status', 1)->get();
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/OPR/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $bname = 'Edge';
        $ub = "Edge";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}

function getSubscriptionFeature($index)
{
    $user = auth()->user();
    if ((is_numeric($user->$index) ? $user->$index > 0 : false) || strval($user->$index) == 'unlimited') {
        return true;
    }
    return false;
}

function getSubscriptionFeatureCount($index)
{
    $packageSubscription = isset(auth()->user()->subscription) ? auth()->user()->subscription : 'None';
    $data = json_decode($packageSubscription->features, true);
    if (isset($data[$index])) {
        return $data[$index];
    }
    return false;
}

function getQrCodeGenerationType($index, $type = '')
{
    $getDynamicQrCodeLimit = getSubscriptionFeatureCount($index);
    if ((int)$getDynamicQrCodeLimit > 0 || $getDynamicQrCodeLimit == 'unlimited') {
        return true;
    }
    return false;
}

function checkFieldStatus($index)
{
    $packageSubscription = isset(auth()->user()->subscription) ? auth()->user()->subscription : 'None';
    $data = json_decode($packageSubscription->features, true);
    if (array_key_exists($index, $data)) {
        return true;
    }
    return false;
}

function getNedLinkDomainName($domainData)
{
    if ($domainData['action'] == 'Add') {
        $url = config('constants.ned_link_url') . '/custom-domain/create-domain?lang=' . Config::get('app.locale');
        $data = array(
            'name' => $domainData['domainName'],
            'primaryEmail' => $domainData['user']->email,
            'from' => 'QRC',
//                'isVerified' => true,
            'password' => $domainData['user']->original_password,
            'firstName' => $domainData['user']->name,
            'lastName' => $domainData['user']->name,
            'username' => $domainData['user']->username,
        );
        $httpRequest = 'POST';
    } else if ($domainData['action'] == 'DELETE') {
        $url = config('constants.ned_link_url') . '/custom-domain/delete-domain?lang=' . Config::get('app.locale') . '&from=QRC&_id=' . $domainData['domain']->ned_link_domain_id;
        $data = [];
        $httpRequest = 'DELETE';
    } else if ($domainData['action'] == 'listing') {
        $url = config('constants.ned_link_url') . '/custom-domain/list-user-domains?lang=en';
        $data = [
            'primaryEmail' => $domainData['user']->email,
            'from' => 'QRC',
        ];
        $httpRequest = 'GET';
    } else if ($domainData['action'] == 'change-status') {
        $url = config('constants.ned_link_url') . '/custom-domain/change-domain-status?lang=' . Config::get('app.locale') . '&from=QRC&_id=' . $domainData['domain']->ned_link_domain_id . '&isActive=' . $domainData['domainStatus'];
        $data = [

        ];
        $httpRequest = 'GET';
    } else if ($domainData['action'] == 'update-back-half') {
        $url = config('constants.ned_link_url') . '/q-link/update?lang=' . Config::get('app.locale');
        $data = [
            "customizeBackHalf" => $domainData['customizeBackHalf'],
            "_id" => $domainData['_id'],
            "title" => $domainData['title'],
            "from" => "QRC",
            "status" => $domainData['status'],
        ];
        $httpRequest = 'POST';
    }
    else if ($domainData->action == 'deletion-on-nedlink') {
        $url = config('constants.ned_link_url') . '/q-link/delete?from=QRC&customizeBackHalf='.$domainData->ned_link_back_half.'&lang=' . Config::get('app.locale');
        $data = [

        ];
        $httpRequest = 'DELETE';
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $httpRequest,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response, true);
    return $response;
}

function deleteQrCodeOnNedLink($id){
    $qrCode = App\Models\GenerateQrCode::findOrFail($id);
    if($qrCode->ned_link_back_half_id){
        $qrCode->action = 'deletion-on-nedlink';
        $deleteNedLink = getNedLinkDomainName($qrCode);

        \Log::info('NedLink (Short URL) Delete', array(
            'response' => $deleteNedLink,
            'qr Data' => json_decode($qrCode),
        ));
    }
    return true;
}

?>
