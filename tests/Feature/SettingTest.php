<?php

namespace Tests\Feature;

use App\Models\Setting;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check can't create two setting in database
     *
     * @test
     * @return void
     */
    public function can_not_create_more_than_one_setting_in_database(): void
    {
        // add setting to database
        Setting::factory()->create();
        // check it exist
        $this->assertDatabaseCount('settings', 1);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Can\'t Create New Setting It is Exist.');
        Setting::factory()->create();
    }
}
