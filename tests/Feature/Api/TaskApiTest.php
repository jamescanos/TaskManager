<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'api@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_authenticated_user_can_list_tasks(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Task::factory()->count(2)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/tareas');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/tareas', [
                'titulo' => 'Nueva tarea API',
                'descripcion' => 'Creada por prueba',
                'fecha_limite' => now()->addDay()->toDateString(),
                'category_id' => $category->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('titulo', 'Nueva tarea API');
    }
}
