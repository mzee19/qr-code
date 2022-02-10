<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageModule extends Model
{
    protected $fillable = [
        'name', 'table', 'columns', 'status'
    ];

    public function languageTranslations()
    {
        return $this->hasMany('App\Models\LanguageTranslation', 'language_module_id');
    }
}
