<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUsQuery extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status'
    ];
}
