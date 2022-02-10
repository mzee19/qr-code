<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->name;
        $info = $this->info;

        if($request->lang != 'en')
        {
            $name = translation($this->id,1,$request->lang,'name',$this->name);
            $info = translation($this->id,1,$request->lang,'info',$this->info);
        }

        return [
            'id'         => $this->id,
            'name'       => $name,
            'info'       => $info,
            'count'      => $this->count,
            'status'     => $this->status,
            'lang'       => $request->lang
        ];
    }
}