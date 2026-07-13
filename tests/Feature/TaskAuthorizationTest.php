<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_create_tasks(): void
    {
        $user = User::factory()->create(['rol' => 'user']);

        $this->actingAs($user);

        $response = $this->get('/tasks/create');

        $response->assertStatus(403);
    }

    public function test_owner_can_update_own_task(): void
    {
        $user = User::factory()->create(['rol' => 'user']);
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get('/tasks/' . $task->id . '/edit');

        $response->assertStatus(200);
    }
}
