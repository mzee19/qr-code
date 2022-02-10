<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LanguageTranslation;

class EmailTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subject = translation($this->id,4,$request->lang,'subject',$this->subject);
        $content = $this->content;

        $search = [];
        $replace = [];
        $ids = [];
        $labels = $this->emailTemplateLabels;

        foreach($labels as $object)
        {
            $search[$object->id] = '{{'.$object->label.'}}';
            $replace[$object->id] = $object->value;
            $ids[] = $object->id;
        }
        
        if($request->lang != 'en')
        {
            $translations = LanguageTranslation::where(['language_module_id' => 8, 'language_code' => $request->lang, 'column_name' => 'value'])->whereIn('item_id',$ids)->get();

            foreach($translations as $translation)
            {
                $replace[$translation->item_id] = $translation->item_value;
            }
        }

        $content  = str_replace($search,$replace,$content);

        return [
            'id'      => $this->id,
            'subject' => $subject,
            'content' => $content,
            'lang'    => $request->lang,
            'type'    => $this->type,
            'info'    => $this->info,
            'status'  => $this->status
        ];
    }
}