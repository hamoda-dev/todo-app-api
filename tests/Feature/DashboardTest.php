<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Check if can render dashboard page for admin
     *
     * @test
     * @return void
     */
    public function can_render_dashboard_page_for_admin(): void
    {
        // create new admin
        User::factory()->create(['role' => Role::Admin]);
        $admin = User::first();

        $response = $this->actingAs($admin)
                        ->get(route('dashboard.show'));

        $response->assertStatus(200);
    }

    /**
     * Check if can't render dashboard page for none admin
     *
     * @test
     * @return void
     */
    public function can_not_render_dashboard_page_for_none_admin(): void
    {
        // create new admin
        User::factory()->create(['role' => Role::User]);
        $admin = User::first();

        $response = $this->actingAs($admin)
                        ->get(route('dashboard.show'));

        $response->assertStatus(403);
    }

    /**
     * Check can regenrate api key
     *
     * @test
     * @return void
     */
    public function can_regenrate_api_key(): void
    {
        User::factory()->create(['role' => Role::Admin]);
        $admin = User::first();

        $response = $this->actingAs($admin)
                        ->post(route('dashboard.regenrate-key'));

        $response->assertSessionHas('success_message');
        $response->assertRedirect(route('dashboard.show'));
    }

        /**
     * Check can't regenrate api key for none admin
     *
     * @test
     * @return void
     */
    public function can_not_regenrate_api_key_for_none_admin(): void
    {
        User::factory()->create(['role' => Role::User]);
        $admin = User::first();

        $response = $this->actingAs($admin)
            ->post(route('dashboard.regenrate-key'));

        $response->assertStatus(403);
    }
}
