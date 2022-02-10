<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    protected $fillable = [
        'title',
        'image',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($model)
        {
            $path = 'storage/logos/'.$model->image;
            if (\File::exists(public_path() . '/' . $path))
            {
                \File::delete($path);
            }
        });
    }
}
