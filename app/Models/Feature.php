<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($model) 
        {
            $path = 'storage/features/'.$model->image;
            if (\File::exists(public_path() . '/' . $path)) 
            {
                \File::delete($path);
            }
        });
    }

    // ************************** //
    //  Append Extra Attributes   //
    // ************************** //

    protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] = checkImage(asset('storage/features/' . $this->image),'placeholder.png',$this->image);
    }
}
