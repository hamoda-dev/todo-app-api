<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Task;
use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private array $headers;

    private User $user;

    private Setting $setting;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(SettingSeeder::class);
        $this->setting = Setting::first();
        $this->user = User::factory()->create();

        $this->headers = [
            'Accept' => 'application/json',
            'x-api-key' => $this->setting->key,
            'Authorization' => 'Bearer ' . $this->user->createToken($this->user->name)->plainTextToken,
        ];
    }

    /**
     * Test Can List Tasks
     *
     * @test
     * @return void
     */
    public function can_list_tasks(): void
    {
        Task::factory(10)->create(['user_id' => $this->user->id]);

        $response = $this->get(uri: route('api.tasks.index'), headers: $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'tasks list success',
            ]);
    }


    /**
     * Test Can Create New Task
     *
     * @test
     * @return void
     */
    public function can_create_new_task_successfully(): void
    {
        $response = $this->post(
            uri: route('api.tasks.store'),
            data: [
                'title' => $this->faker->sentence(),
                'body' => $this->faker->text(),
            ],
            headers: $this->headers
        );

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'task created success',
            ]);
        $this->assertDatabaseCount('tasks', 1);
    }

    /**
     * Test Can Validate Create New Task
     *
     * @test
     * @return void
     */
    public function can_validate_create_new_task(): void
    {
        $response = $this->post(
            uri: route('api.tasks.store'),
            data: [],
            headers: $this->headers
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Can Get Spacific Task
     *
     * @test
     * @return void
     */
    public function can_get_task_successfully()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(uri: route('api.tasks.show', $task), headers: $this->headers);

        $response->assertStatus(200)
            ->assertJson(['message' => 'task get success']);
    }

    /**
     * Can't Get Spacific Task It Unauthorized
     *
     * @test
     * @return void
     */
    public function can_not_get_task_it_unauthorized(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(uri: route('api.tasks.show', $task), headers: $this->headers);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized.']);
    }

    /**
     * Test Can Update Task
     *
     * @test
     * @return void
     */
    public function can_update_exists_task_successfully(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(
            uri: route('api.tasks.update', $task),
            data: [
                'title' => 'update task title',
                'body' => 'update task body'
            ],
            headers: $this->headers
        );

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'task updated success',
                'data' => [
                    'title' => 'update task title',
                    'body' => 'update task body',
                ],
            ]);
    }

    /**
     * Test Can Validate Update Task
     *
     * @test
     * @return void
     */
    public function can_validate_update_exists_task_successfully(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(
            uri: route('api.tasks.update', $task),
            data: [
                'title' => '',
            ],
            headers: $this->headers
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test Autherization When Update Task
     *
     * @test
     * @return void
     */
    public function can_not_update_exists_task_it_unauthorized(): void
    {
        $task = Task::factory()->create();

        $response = $this->patch(
            uri: route('api.tasks.update', $task),
            data: [
                'title' => 'update task title',
                'body' => 'update task body'
            ],
            headers: $this->headers
        );

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized.']);
    }

    /**
     * Test Can Done Task
     *
     * @test
     * @return void
     */
    public function can_done_task_success()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(
            uri: route('api.tasks.done', $task),
            headers: $this->headers
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'task done success']);
    }

    /**
     * Test Can't Done Task it Unauthorized
     *
     * @test
     * @return void
     */
    public function can_not_done_task_it_unauthorized()
    {
        $task = Task::factory()->create();

        $response = $this->patch(
            uri: route('api.tasks.done', $task),
            headers: $this->headers
        );

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized.']);
    }

    /**
     * Test Can UnDone Task
     *
     * @test
     * @return void
     */
    public function can_undone_task_success()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patch(
            uri: route('api.tasks.undone', $task),
            headers: $this->headers
        );

        $response->assertStatus(200)
            ->assertJson(['message' => 'task undone success']);
    }

    /**
     * Test Can't UnDone Task it Unauthorized
     *
     * @test
     * @return void
     */
    public function can_not_undone_task_it_unauthorized()
    {
        $task = Task::factory()->create();

        $response = $this->patch(
            uri: route('api.tasks.undone', $task),
            headers: $this->headers
        );

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized.']);
    }

    /**
     * Test can delete task
     *
     * @test
     * @return void
     */
    public function can_delete_task_success(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(uri: route('api.tasks.destroy', $task), headers: $this->headers);

        $response->assertStatus(204);
    }

    /**
     * Test Autherization When Delete Task
     *
     * @test
     * @return void
     */
    public function can_not_delete_task_it_unautherized(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(uri: route('api.tasks.destroy', $task), headers: $this->headers);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized.']);
    }
}
