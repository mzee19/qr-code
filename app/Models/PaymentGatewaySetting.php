<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewaySetting extends Model
{
    protected $fillable = [
        'mollie_sandbox_api_key',
        'mollie_live_api_key',
        'mollie_mode',
        'mollie_status',

    ];
}
