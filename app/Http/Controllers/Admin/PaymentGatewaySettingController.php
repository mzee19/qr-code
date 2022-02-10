<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use Session;

class PaymentGatewaySettingController extends Controller
{
	public function index()
	{
		if(!have_right(67))
            access_denied();
		$data['model'] = PaymentGatewaySetting::first();
		return view('admin.payment_gateway_settings')->with($data);
	}

	public function update(Request $request)
	{
		$model = PaymentGatewaySetting::first();
		$model->fill($request->input());
		$model->save();

		Session::flash('flash_success', 'Payment Gateway Settings has been updated successfully.');
        return redirect()->back();
	}
}
