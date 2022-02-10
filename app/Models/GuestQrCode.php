<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestQrCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address',
        'type',
        'image',
        'logo_image',
        'fields',
        'data',
        'config',
        'browser',
        'city',
        'country',
        'platform',
        'device',
        'location'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {

            /*
            ** Delete user's files
            */

            $path = 'storage/temp/' . $model->id;
            if (\File::exists(public_path() . '/' . $path)) {
                \File::deleteDirectory(public_path() . '/' . $path);
            }

        });
    }
}
