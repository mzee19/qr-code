<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use File;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Storage;
use Hashids;

class AccountController extends Controller
{
    /*main page of account */
    public function account()
    {
        return view('frontend.dashboard.accounts.account');
    }

    /*Setting page*/
    public function setting()
    {
        $user = Auth::user();
        $data['timezones'] = Timezone::all();
        $data['countries'] = Country::all();
        $data['user'] = $user;
        return view('frontend.dashboard.accounts.setting', $data);
    }

    public function updateSetting(Request $request)
    {
        $messages = [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name may not be greater than 100 characters.'),
            'email.required' => __('The email field is required.'),
            'email.max' => __('The email field may not be greater than 100 characters.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 8 characters.'),
            'password.max' => __('The password may not be greater than 30 characters.'),
            'timezone.required' => __('The timezone field is required.'),
            'profile_image.mimes' => __('The image must be a file of type: image/jpg, png, jpeg, svg+xml'),
            'profile_image.max' => __('The profile image may not be greater than 8192 kilobytes.'),
      ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'password' => 'required|string|min:8|max:30',
            'timezone' => 'required',
            'profile_image' => 'file|mimes:jpg,png,jpeg,gif,svg|max:' . config('constants.file_size'),
        ],$messages);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
        $input = $request->all();
        $user = Auth::user();

        //MAKE DIRECTORY
        $upload_path = 'public/users/'.$user->id.'/';
        if (!File::exists(public_path() . $upload_path)) {
            Storage::makeDirectory($upload_path);
        }
        if (!empty($request->files) && $request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $type = $file->getClientOriginalExtension();
            $file_temp_name = 'profile-image-' . uniqid() . '.' . $type;

            $old_file = public_path() . '/storage/users/'.$user->id . '/' . $user->profile_image;
            if (file_exists($old_file) && !empty($user->profile_image)) {
                Storage::delete($upload_path . '/' . $user->profile_image);
            }
            $path = Storage::putFileAs($upload_path, $request->file('profile_image'), $file_temp_name);
            $input['profile_image'] = $file_temp_name;
        }

        $input['original_password'] = $request->input('password');
        $input['password'] = Hash::make($request->input('password'));

        $user->update($input);
        $request->session()->flash('flash_success', __('Profile has been updated successfully!'));
        return redirect()->route('frontend.user.setting');
    }
}
