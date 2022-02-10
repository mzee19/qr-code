<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
        $description = $this->description;

        if($request->lang != 'en')
        {
            $name = translation($this->id,6,$request->lang,'name',$this->name);
            $description = translation($this->id,6,$request->lang,'description',$this->description);
        }

        return [
            'id'         => $this->id,
            'name'       => $name,
            'description'=> $description,
            'image'      => checkImage(asset('storage/features/' . $this->image),'placeholder.png',$this->image),
            'lang'       => $request->lang
        ];
    }
}