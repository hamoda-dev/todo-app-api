<?php

namespace Database\Seeders;

use App\Models\Setting;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check first if setting exist
        $setting = Setting::first();

        if (!is_null($setting)) {
            throw new Exception('Setting Exist Can\'t Seed it');
        }

        // add setting to database
        Setting::factory()->create();
    }
}
