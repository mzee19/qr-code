<?php

use Illuminate\Database\Seeder;

class LanguageModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('language_modules')->delete();
        
        \DB::table('language_modules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Package Features',
                'table' => 'package_features',
                'columns' => 'name,info',
                'status' => 0,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Packages',
                'table' => 'packages',
                'columns' => 'title,sub_title,description',
                'status' => 0,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'FAQs',
                'table' => 'faqs',
                'columns' => 'question,answer',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Email Templates',
                'table' => 'email_templates',
                'columns' => 'subject',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'CMS Pages',
                'table' => 'cms_pages',
                'columns' => 'title',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Features',
                'table' => 'features',
                'columns' => 'name,description',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'CMS Page Lables',
                'table' => 'cms_page_labels',
                'columns' => 'value',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Email Template Labels',
                'table' => 'email_template_labels',
                'columns' => 'value',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Products',
                'table' => 'products',
                'columns' => 'name,description',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Projects',
                'table' => 'projects',
                'columns' => 'name',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-09-22 15:05:12',
                'updated_at' => '2020-09-22 15:05:12',
            ),
        ));
        
        
    }
}