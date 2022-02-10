<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserDomain extends Model
{
    protected $fillable = [
        'user_id',
        'ned_link_domain_id',
        'is_verified',
        'domain',
        'status',
    ];

//    Relationship

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
