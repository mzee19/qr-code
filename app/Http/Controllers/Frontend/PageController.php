<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\LanguageTranslation;

class PageController extends Controller
{
    public function contact() {
        return view('frontend.pages.contact');
    }

    public function show(Request $request, $slug)
    {
    	$lang = \App::getLocale();
    	$page = CmsPage::where('slug',$slug)->first();

    	if(!empty($page))
    	{
    		$title = translation($page->id,5,$lang,'title',$page->title);
	        $content = $page->content;

	        $search = [];
	        $replace = [];
	        $ids = [];
	        $labels = $page->cmsPageLabels;

	        foreach($labels as $object)
	        {
	            $search[$object->id] = '{{'.$object->label.'}}';
	            $replace[$object->id] = $object->value;
	            $ids[] = $object->id;
	        }

	        if($lang != 'en')
	        {
	            $translations = LanguageTranslation::where(['language_module_id' => 7, 'language_code' => $lang, 'column_name' => 'value'])->whereIn('item_id',$ids)->get();

	            foreach($translations as $translation)
	            {
	                $replace[$translation->item_id] = $translation->item_value;
	            }
	        }

	        $content  = str_replace($search,$replace,$content);

	        $data = array();
	        $data['title'] = $title;
	        $data['content'] = $content;

    		return view('frontend.pages.show')->with($data);
    	}
    	else
    	{
    		abort(404);
    	}
    }
}
