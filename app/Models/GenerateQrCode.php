<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GenerateQrCode extends Model
{
    protected $fillable = [
        'name', 'user_id', 'campaign_id', 'unique_id','ned_link','ned_link_back_half_id','ned_link_back_half',
        'crop','crop_data','transparent_background',
        'type', 'code_type', 'icon', 'short_url', 'image', 'scans',
        'archive','logo_image', 'fields', 'data', 'config', 'size',
        'file', 'download', 'template', 'status'
    ];

    /*Relationships*/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /* End Relationships*/
    /*Image destroy*/
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $path = 'storage/users/' . $model->user_id . '/qr-codes/' . $model->image;
            $templatePath = 'storage/users/' . $model->user_id . '/qr-codes/templates/' . $model->image;
            $logoPath = 'storage/users/' . $model->user_id . '/qr-codes/logo-images/' . $model->logo_image;
            $transparentImagePath = 'storage/users/' . $model->user_id . '/qr-codes/transparent-images/' . $model->transparent_background;
            $adminPath = 'storage/admin-qr-codes/' . $model->image;

            if(!empty($model->ned_link_back_half_id)){
                deleteQrCodeOnNedLink($model->id);
            }

            if (\File::exists(public_path() . '/' . $path)) {
                \File::delete($path);
            } else if (\File::exists(public_path() . '/' . $templatePath)) {
                // Delete Template Image
                \File::delete($templatePath);
            } else if ($model->user_id == null && \File::exists(public_path() . '/' . $adminPath)) {
                // Delete Image in Admin side
                \File::delete($adminPath);
            }

            if (\File::exists(public_path() . '/' . $logoPath)) {
                \File::delete($logoPath);
            }

            if (\File::exists(public_path() . '/' . $transparentImagePath)) {
                \File::delete($transparentImagePath);
            }

        });
    }
}
