<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'James Cano',
            //'email' => 'jjcano4@example.com',
        ]);

        // crear 10 categorías
        $categorias = Category::factory(10)->create();

        // Crear 50 tareas asignadas al usuario admin
        Task::factory(50)->create([
            'user_id' => $user->id,
            'category_id' => $categorias->random()->id,
        ]);

        // Crear 20 tareas adicionales para otros usuarios
        Task::factory(20)->create();
    }
}
