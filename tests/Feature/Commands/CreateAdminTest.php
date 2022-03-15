<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can Create Admin
     *
     * @test
     * @return void
     */
    public function can_create_admin_successfully(): void
    {
        $this->artisan('todo:create-admin')
            ->expectsQuestion('Enter Admin Name', 'Admin')
            ->expectsQuestion('Enter Email', 'admin@admin.com')
            ->expectsQuestion('Enter Password (type it\'s hidden)', 'password')
            ->assertSuccessful();
    }

    /**
     * Test Can Validate Name When Create Admin
     *
     * @test
     * @return void
     */
    public function can_validate_name_when_create_admin(): void
    {
        $this->artisan('todo:create-admin')
            ->expectsQuestion('Enter Admin Name', null)
            ->expectsOutput('The name field is required.')
            ->expectsQuestion('Enter Admin Name', 'admin')
            ->expectsQuestion('Enter Email', 'admin@admin.com')
            ->expectsQuestion('Enter Password (type it\'s hidden)', 'password')
            ->assertSuccessful();
    }

    /**
     * Test Can Validate Email When Create New Admin
     *
     * @test
     * @void
     */
    public function can_validate_email_when_create_admin(): void
    {
        $this->artisan('todo:create-admin')
            ->expectsQuestion('Enter Admin Name', 'admin')
            ->expectsQuestion('Enter Email', null)
            ->expectsOutput('The email field is required.')
            ->expectsQuestion('Enter Email', 'admin@admin.com')
            ->expectsQuestion('Enter Password (type it\'s hidden)', 'password')
            ->assertSuccessful();
    }

    /**
     * Test Can Validate Password When Create New Admin
     *
     * @test
     * @void
     */
    public function can_validate_password_when_create_admin(): void
    {
        $this->artisan('todo:create-admin')
            ->expectsQuestion('Enter Admin Name', 'admin')
            ->expectsQuestion('Enter Email', 'admin@admin.com')
            ->expectsQuestion('Enter Password (type it\'s hidden)', null)
            ->expectsOutput('The password field is required.')
            ->expectsQuestion('Enter Password (type it\'s hidden)', 'password')
            ->assertSuccessful();
    }
}
