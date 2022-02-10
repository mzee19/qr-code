<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'user_id','qr_code_id','city','country','browser','platform','device','location','language','ip','status','statistics_status'
    ];

    /*Relationships*/

    public function qrCode() {
        return $this->belongsTo(GenerateQrCode::class,'qr_code_id','id');
    }

    /* End Relationships*/

}
