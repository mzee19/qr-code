<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class PackageSubscription extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'price', 'features', 'description', 'type', 'start_date', 'end_date', 'repetition', 'payment_option', 'is_active'
    ];

    // ************************** //
    //        Relationships       //
    // ************************** //

    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function package()
    {
      return $this->belongsTo(Package::class, 'package_id');
    }

    public function payment()
    {
      return $this->hasOne(Payment::class, 'subscription_id');
    }

    // ************************** //
    //        	Attributes        //
    // ************************** //

    public function getPackageImageAttribute()
    {
      return $this->attributes['package_image'] = checkImage(asset('storage/packages/' . $this->package->icon),'placeholder.png',$this->package->icon);
    }

    public function getPackageTitleAttribute()
    {
      return $this->attributes['package_title'] = $this->package->title;
    }

    public function getLinkedFeaturesAttribute()
    {
      $features = json_decode($this->features,true);
      $linked_features = [];

      if(!empty($features))
      {
        foreach($features as $key => $value)
        {
            $packageFeature = PackageFeature::find($key);

            $arr['id']   = $key;
            $arr['name'] = $packageFeature->name;
            $arr['info'] = $packageFeature->info;
            $arr['count']= $value;

            $linked_features[] = $arr;
        }
      }

    	return $this->attributes['linked_features'] = $linked_features;
    }
}
