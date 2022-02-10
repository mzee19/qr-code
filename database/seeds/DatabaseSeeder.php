<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CmsPagesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(PackageFeaturesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(RightsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(TimezonesTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(PaymentGatewaySettingsTableSeeder::class);
        $this->call(PackageLinkFeaturesTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
        $this->call(HomeContentsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(LanguageModulesTableSeeder::class);
        $this->call(CmsPageLabelsTableSeeder::class);
        $this->call(EmailTemplateLabelsTableSeeder::class);
        $this->call(LanguageTranslationsTableSeeder::class);
    }
}
