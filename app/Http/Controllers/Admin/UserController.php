<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use App\Models\Language;
use App\Models\Timezone;
use App\Models\Country;
use App\Models\Package;
use App\Models\PackageSubscription;
use App\Models\Payment;
use App\Models\EmailTemplate;
use App\Models\OrderVoucher;
use App\Models\Voucher;
use App\Models\Project;
use App\Classes\PaymentHandler;
use Carbon\Carbon;
use Auth;
use Hashids;
use File;
use Storage;
use Session;
use Hash;
use DB;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!have_right(72))
            access_denied();

        $data = [];

        $language_code = '';
        $package_id = '';

        if ($request->has('language_code') && !empty($request->language_code)) {
            $language_code = $request->language_code;
        } else if ($request->has('package_id') && !empty($request->package_id)) {
            $package_id = $request->package_id;
        }
        $data['language_code'] = $language_code;
        $data['package_id'] = $package_id;

        if ($request->ajax()) {
            $db_record = new User();

            if ($request->has('language_code') && !empty($request->language_code)) {
                $language_code = $request->language_code;
                $db_record = $db_record->where('language', $language_code);
            } else if ($request->has('package_id') && !empty($request->package_id)) {
                $package_id = Hashids::decode($request->package_id)[0];
                $db_record = $db_record->where('package_id', $package_id);
            }

            $db_record = $db_record->orderBy('created_at', 'DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->addColumn('package_title', function ($row) {
                $package_title = 'N/A';
                if (isset($row->package->title)) {
                    $package_title = $row->package->title;
                }
                return $package_title;
            });
            $datatable = $datatable->editColumn('is_approved', function ($row) {
                $is_approved = '<span class="label label-warning">Pending</span>';
                if ($row->is_approved == 1) {
                    $is_approved = '<span class="label label-success">Approved</span>';
                } else if ($row->is_approved == 2) {
                    $is_approved = '<span class="label label-danger">Rejected</span>';
                }

                return $is_approved;
            });
            $datatable = $datatable->editColumn('platform', function ($row) {
                switch ($row->platform) {
                    case 1:
                        $platformName = 'Web';
                        break;
                    case 2:
                        $platformName = 'Mobile';
                        break;
                    case 5:
                        $platformName = 'Move Immunity';
                        break;
                    case 6:
                        $platformName = 'Ned Link';
                        break;
                    case 7:
                        $platformName = 'aikQ';
                        break;
                    case 8:
                        $platformName = 'Inbox';
                        break;
                    case 9:
                        $platformName = 'Overmail';
                        break;
                    case 10:
                        $platformName = 'Maili';
                        break;
                    case 11:
                        $platformName = 'Product Immunity';
                        break;
                    case 12:
                        $platformName = 'Transfer Immunity';
                        break;
                    default:
                        $platformName = 'Web';
                        break;
                }
                $platform = '<span class="label label-default">' . $platformName . '</span>';

                return $platform;
            });
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                } else if ($row->status == 2) {
                    $status = '<span class="label label-warning">Unverified</span>';
                } else if ($row->status == 3) {
                    $status = '<span class="label label-danger">Deleted</span>';
                }

                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right(1)) {
                    $actions .= '&nbsp;<a title="Payments" class="btn btn-primary" href="' . url("admin/users/payments/" . Hashids::encode($row->id)) . '"><i class="fa fa-credit-card-alt"></i></a>';
                }

                if (have_right(2)) {
                    $actions .= '&nbsp;<a title="Subscriptions" class="btn btn-primary" href="' . url("admin/users/subscriptions/" . Hashids::encode($row->id)) . '"><i class="fa fa-tasks"></i></a>';
                }

                if (have_right(3)) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/packages/" . Hashids::encode($row->id)) . '" title="Update Package"><i class="fa fa-sliders"></i></a>';
                }

                if (have_right(4)) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/" . Hashids::encode($row->id) . '/qr-codes') . '" title="Qr Codes"><i class="fa fa-qrcode"></i></a>';
                }
                if (have_right(6)) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/" . Hashids::encode($row->id)) . '" title="View"><i class="fa fa-eye"></i></a>';
                }

                if (have_right(7)) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/users/" . Hashids::encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                }

                if (have_right(8)) {
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/users/" . Hashids::encode($row->id)) . '" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['platform', 'is_approved', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!have_right(5))
            access_denied();

        $data['user'] = new User();
        $data['languages'] = Language::where('status', 1)->whereNull('deleted_at')->get();
        $data['timezones'] = Timezone::all();
        $data['countries'] = Country::all();
        $data['action'] = "Add";
        return view('admin.users.form')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!have_right(7))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['user'] = User::findOrFail($id);
        $data['languages'] = Language::where('status', 1)->whereNull('deleted_at')->get();
        $data['timezones'] = Timezone::all();
        $data['countries'] = Country::all();
        return view('admin.users.form')->with($data);
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

        if ($input['action'] == 'Add') {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'max:100', Rule::unique('users')],
                'username' => ['required', 'string', 'max:100', Rule::unique('users')],
                'name' => ['required', 'string', 'max:100'],
                'password' => 'required|string|min:8|max:30',
                'country_id' => 'required'
            ]);

            if ($validator->fails()) {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $input['original_password'] = $input['password'];
            $input['password'] = Hash::make($input['password']);
            $input['language'] = "en";

            $model = new User();
            $flash_message = 'User has been created successfully.';
        } else {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', Rule::unique('users')->ignore($input['id'])],
                'username' => ['required', 'string', Rule::unique('users')->ignore($input['id'])],
                'password' => 'required|string|min:8|max:30',
                'country_id' => 'required'
            ]);

            if ($validator->fails()) {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            if (!empty($input['password'])) {
                $input['original_password'] = $input['password'];
                $input['password'] = Hash::make($input['password']);
            } else {
                unset($input['password']);
            }

            $model = User::findOrFail($input['id']);
            $flash_message = 'User has been updated successfully.';
        }

        $model->fill($input);
        $model->disabled_at = ($input['status'] == "0") ? date("Y-m-d H:i:s") : Null;
        $model->deleted_at = ($input['status'] == "3") ? date("Y-m-d H:i:s") : Null;
        $model->save();

        if ($input['action'] == 'Add') {
            $package = Package::where(['id' => 1, 'status' => 1])->first(); // Trial Package
            $end_date = Carbon::now('UTC')->addDays(settingValue('number_of_days'))->timestamp;
            $on_trial = 1;

            if (empty($package) || (!empty($package) && settingValue('number_of_days') == 0)) // Trial is not active
            {
                $package = Package::find(2); // Free Package
                $end_date = Null;
                $on_trial = 0;
            }

            $packageLinkedFeatures = $package->linkedFeatures->pluck('count', 'feature_id')->toArray();

            $model->dynamic_qr_codes = array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null;
            $model->static_qr_codes = array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null;
            $model->qr_code_scans = array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null;
            $model->bulk_import_limit = array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null;

            $packageSubscription = PackageSubscription::create([
                'user_id' => $model->id,
                'package_id' => $package->id,
                'price' => 0,
                'features' => empty($package->linkedFeatures) ? '' : json_encode($packageLinkedFeatures),
                'description' => $package->description,
                'start_date' => Carbon::now('UTC')->timestamp,
                'end_date' => $end_date,
                'is_active' => 1
            ]);

            $model->package_id = $package->id;
            $model->package_subscription_id = $packageSubscription->id;
            $model->on_trial = $on_trial;
            $model->package_recurring_flag = 0;
            $model->save();
        }

        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/users');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!have_right(6))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['user'] = User::findOrFail($id);
        return view('admin.users.view')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, Request $request)
    {
        if (!have_right(8))
            access_denied();

        $id = Hashids::decode($id)[0];
        User::destroy($id);
        Session::flash('flash_success', 'User has been deleted successfully.');

        if ($request->has('page') && $request->page == 'dashboard') {
            return redirect('admin/dashboard');
        } else {
            return redirect('admin/users');
        }
    }

    /**
     * Show the user subscription.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function subscriptions(Request $request, $id)
    {
        if (!have_right(2))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);

        if ($request->ajax()) {
            $db_record = PackageSubscription::with('package');

            if ($request->has('search') && !empty($request->search)) {
                $db_record = $db_record->whereHas('package', function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->search . '%');
                })->where('user_id', $id);
            } else {
                $db_record = $db_record->whereNotNull('id');
            }

            $db_record = $db_record->where('user_id', $id)->orderBy('created_at', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->addColumn('package_title', function ($row) {
                return $row->package_title;
            });
            $datatable = $datatable->editColumn('price', function ($row) {
                return '<sup>' . config('constants.currency')['symbol'] . '</sup>' . (!empty($row->price) ? $row->price : '0');
            });
            $datatable = $datatable->editColumn('type', function ($row) {
                $type = '';

                if ($row->package_id == 1)
                    $type = '<span class="label label-primary">Trial</span>';
                else if ($row->package_id == 2)
                    $type = '<span class="label label-primary">Free</span>';
                else {
                    $type = '<span class="label label-success">Paid</span>';
                }

                return $type;
            });
            $datatable = $datatable->editColumn('start_date', function ($row) {
                return Carbon::createFromTimeStamp($row->start_date, "UTC")->tz(session('timezone'))->format('d M, Y');
            });
            $datatable = $datatable->editColumn('end_date', function ($row) {
                return (!empty($row->end_date)) ? Carbon::createFromTimeStamp($row->end_date, "UTC")->tz(session('timezone'))->format('d M, Y') : 'Lifetime';
            });
            $datatable = $datatable->addColumn('status', function ($row) {
                $currentTimestamp = Carbon::now('UTC')->timestamp;
                $status = '';

                if ($row->id == $row->user->package_subscription_id) {
                    if (empty($row->end_date) || $row->end_date > $currentTimestamp)
                        $status = '<span class="label label-success">Active</span>';
                    else
                        $status = '<span class="label label-warning">Expired</span>';
                } else
                    $status = '<span class="label label-danger">In-Active</span>';

                return $status;
            });

            $datatable = $datatable->rawColumns(['type', 'price', 'status']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.subscriptions', $data);
    }

    public function payments(Request $request, $id)
    {
        if (!have_right(1))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);

        if ($request->ajax()) {
            $db_record = Payment::where('user_id', $id)->whereNotNull('timestamp')->orderBy('timestamp', 'DESC');

            if ($request->has('search') && !empty($request->search)) {
                $db_record = $db_record->where('item', 'LIKE', '%' . $request->search . '%');
            } else {
                $db_record = $db_record->whereNotNull('id');
            }

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('item', function ($row) {
                $payload = json_decode($row->payload, true);
                return $payload["subscription_desc"];
            });

            $datatable = $datatable->editColumn('amount', function ($row) {
                return '<sup>' . config('constants.currency')['symbol'] . '</sup>' . $row->amount;
            });

            $datatable = $datatable->editColumn('vat_amount', function ($row) {
                return '<sup>' . config('constants.currency')['symbol'] . '</sup>' . $row->vat_amount;
            });

            $datatable = $datatable->editColumn('discount_amount', function ($row) {
                return '<sup>' . config('constants.currency')['symbol'] . '</sup>' . $row->discount_amount;
            });

            $datatable = $datatable->editColumn('total_amount', function ($row) {
                return '<sup>' . config('constants.currency')['symbol'] . '</sup>' . $row->total_amount;
            });

            $datatable = $datatable->editColumn('payment_method', function ($row) {
                $payment_method = '';

                switch ($row->payment_method) {
                    case config('constants.payment_methods')['PAYPAL']:
                        $payment_method = '<span class="label label-primary">Paypal</span>';
                        break;
                    case config('constants.payment_methods')['MOLLIE']:
                        $payment_method = '<span class="label label-success">Mollie</span>';
                        break;
                    case config('constants.payment_methods')['ADMIN']:
                        $payment_method = '<span class="label label-warning">Admin</span>';
                        break;
                    case config('constants.payment_methods')['VOUCHER_PROMOTION']:
                        $payment_method = '<span class="label label-warning">Voucher Promotion</span>';
                        break;
                }

                return $payment_method;
            });

            $datatable = $datatable->addColumn('payment_date', function ($row) {
                return Carbon::createFromTimeStamp($row->timestamp, "UTC")->tz(session('timezone'))->format('d M, Y - h:i A');
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right(93)) {
                    $actions .= '&nbsp;<a title="Download Invoice" class="btn btn-primary" href="' . url("/subscriptions/download-payment-invoice/" . Hashids::encode($row->id)) . '"><i class="fa fa-download"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['amount', 'vat_amount', 'discount_amount', 'total_amount', 'payment_method', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.payments', $data);
    }

    public function packages(Request $request, $id)
    {
        if (!have_right(3))
            access_denied();

        $user = User::find(Hashids::decode($id)[0]);
        $data['user'] = $user;
        $data['packages'] = Package::whereNotIn('id', [1])->where('status', 1)->orderBy('monthly_price')->get();

        $subscription = $user->subscription;
        $currentTimestamp = Carbon::now('UTC')->timestamp;

        if (!empty($subscription->end_date) && $subscription->end_date < $currentTimestamp) {
            //************************//
            // Subscribe Free Package //
            //************************//

            $user->update([
                'is_expired' => 0,
                'on_hold_package_id' => $subscription->package_id,
                'package_recurring_flag' => 0,
                'switch_to_paid_package' => 0,
                'package_updated_by_admin' => 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 1,
                'last_quota_revised' => NULL,
            ]);

            $package = Package::find(2);
            activatePackage($user->id, $package);

            // ****************************************************//
            // Send Email About Package downraded to free package  //
            // *************************************************** //

            $email_template = EmailTemplate::where('type', 'package_downgrade_after_subscription_expired')->first();
            $name = $user->name;
            $email = $user->email;
            $upgrade_link = url('/upgrade-package');
            $contact_link = url('/contact-us');
            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}", "{{from}}", "{{to}}", "{{upgrade_link}}", "{{contact_link}}", "{{app_name}}");
            $replace = array($name, $subscription->package_title, $package->title, $upgrade_link, $contact_link, env('APP_NAME'));
            $content = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content);

            $user = User::find(Hashids::decode($id)[0]);
            $data['user'] = $user;
        }

        return view('admin.users.packages', $data);
    }

    public function updatePackage(Request $request)
    {
        $user = User::find($request->user_id);
        $name = $user->name;
        $email = $user->email;

        if ($request->payment_option == 1) {
            PaymentHandler::checkout($request, $request->package_id, $request->type, $request->user_id, 3, '', '', 'Admin');
            Session::flash('flash_success', 'Package has been updated successfully.');
        } else {
            $payment_link = url('/subscribe?package_id=' . $request->package_id . '&type=' . $request->type . '&repetition=' . $request->repetition);
            $email_template = EmailTemplate::where('type', 'unpaid_package_upgrade_downgrade_by_admin')->first();
            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}", "{{link}}", "{{app_name}}");
            $replace = array($name, $payment_link, env('APP_NAME'));
            $content = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content);
            Session::flash('flash_success', 'Package change request has been initiated successfully.');
            $user->update([
                'package_updated_by_admin' => 0,
                'unpaid_package_email_by_admin' => 1,
                'expired_package_disclaimer' => 0
            ]);
        }

        return redirect()->back();
    }

    public function sendPassword($id)
    {
        $user = User::find(Hashids::decode($id)[0]);
        $name = $user->name;
        $email = $user->email;

        $email_template = EmailTemplate::where('type', 'send_password')->first();
        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}", "{{password}}", "{{app_name}}");
        $replace = array($name, $user->original_password, env('APP_NAME'));
        $content = str_replace($search, $replace, $content);

        sendEmail($email, $subject, $content);

        Session::flash('flash_success', 'Password has been sent successfully.');
        return redirect('admin/users/' . $id . '/edit');
    }
}
