<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Language extends Model
{	
    protected $fillable = [
        'name', 'code', 'status'
    ];

    public function totalUsers()
    {
        return User::where('language','=', $this->code)->count();
    }
}
