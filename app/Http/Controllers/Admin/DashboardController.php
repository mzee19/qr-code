<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use App\Models\Notification;


use Carbon\Carbon;
use Hashids;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
    	$data['roles'] = DB::table('roles')->count();
    	$data['admins'] = DB::table('admins')->count();
    	$data['users'] = DB::table('users')->count();
    	$data['packages'] = DB::table('packages')->count();
    	$data['faqs'] = DB::table('faqs')->count();
    	$data['languages'] = DB::table('languages')->count();
        $data['email_templates'] = DB::table('email_templates')->count();
        $data['cms_pages'] = DB::table('cms_pages')->count();
        $data['deleted_users'] = User::where('status',3)->orderBy('name','DESC')->get();
        $data['qrcode_templates'] = DB::table('generate_qr_codes')->whereNull('user_id')->count();
        $data['payments'] = DB::table('payments')->sum('total_amount');

        return view('admin.dashboard')->with($data);
    }

    public function ajaxReceivedNotification(Request $request)
    {
        $notification = Notification::find($request->id);
        if($notification && $notification->user)
        {
            $message = str_replace("[name]" , $notification->user->name , $notification->message );

            $html = '<li><a href="'.url($notification->link.'?notification_id='.$notification->id).'" class="notification-item" style="background:#e4edfc"><i class="fa fa-tags custom-bg-green2"></i><p><span class="text">'.$message.'</span><span class="timestamp">'.Carbon::createFromTimeStamp(strtotime($notification->created_at), "UTC")->diffForHumans().'</span></p></a></li>';

            return response()->json(['success'=>true, 'html' => $html]);
        }
    }
}
