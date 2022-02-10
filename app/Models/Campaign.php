<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
      'name','status','user_id'
    ];

    public function qrCodes()
    {
        return $this->hasMany('App\Models\GenerateQrCode', 'campaign_id');
    }
}
