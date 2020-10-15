<?php

declare(strict_types=1);

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
        $this->call(SitesTableSeeder::class);
        $this->call(TimeZonesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(FoldersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(ProductTypesTableSeeder::class);
        $this->call(BlogsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}
