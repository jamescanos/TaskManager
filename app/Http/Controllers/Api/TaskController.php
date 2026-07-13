<?php

/**
 * @OA\Info(title="API Task Manager", version="1.0.0")
 * * @OA\Server(
 *     url="http://localhost/api",
 *     description="Servidor de desarrollo local"
 * )
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Obtener lista de tareas",
     *     description="Retorna un listado de todas las tareas del usuario autenticado.",
     *     tags={"Tareas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autenticado")
     * )
     */

    public function index()
    {
        $tasks = Task::with(['category', 'user'])->get();

        return response()->json($tasks, 200);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Crear una nueva tarea",
     *     tags={"Tareas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la tarea a crear",
     *         @OA\JsonContent(
     *             required={"titulo", "category_id"},
     *             @OA\Property(property="titulo", type="string", example="Nueva tarea"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la tarea"),
     *             @OA\Property(property="fecha_limite", type="string", format="date", example="2026-08-15"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(response=422, description="Error de validación")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date|after:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        $task = Task::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_limite' => $validated['fecha_limite'] ?? null,
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'estado' => 'pendiente',
        ]);

        return response()->json($task->load(['category', 'user']), 201);
    }

    public function show($id)
    {
        $task = Task::with(['category', 'user'])->findOrFail($id);
        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        // Obtén la tarea con las relaciones (opcional)
        $task = Task::findOrFail($id);

        // Validación (sin cambios)
        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'nullable|date|after:today',
            'category_id' => 'sometimes|exists:categories,id',
            'estado' => 'sometimes|in:pendiente,en_progreso,completada',
        ]);

        // (Opcional) Verifica autorización manualmente
        $user = auth()->user();
        $isAdmin = $user && $user->rol === 'admin';
        if (! $isAdmin && $user->id !== $task->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $task->update($validated);

        return response()->json($task->fresh()->load(['category', 'user']), 200);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // (Opcional) Verifica autorización
        $user = auth()->user();
        $isAdmin = $user && $user->rol === 'admin';
        if (! $isAdmin && $user->id !== $task->user_id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $task->delete();

        return response()->json(null, 204);
    }
}
