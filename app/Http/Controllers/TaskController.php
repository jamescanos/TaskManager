<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['user', 'category']);

        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('estado')) {
            if ($request->estado == 'completada') {
                $query->completadas();
            } else {
                $query->pendientes();
            }
        }

        //$tareas = $query->orderBy('created_at', 'desc')->get();
        $tareas = $query->orderBy('created_at', 'desc')
                    ->paginate(15)
                    ->withQueryString();

        return view('tasks.index', compact('tareas'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);

        $categorias = Category::all();
        return view('tasks.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $request->validate([
            'titulo' => 'required|string|max:150|unique:tasks,titulo',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_limite' => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $categorias = Category::all();
        return view('tasks.edit', compact('task', 'categorias'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'titulo' => 'required|string|max:150|unique:tasks,titulo,' . $task->id,
            'descripcion' => 'nullable|string|max:1000',
            'fecha_limite' => 'nullable|date|after_or_equal:today',
            'category_id' => 'required|exists:categories,id',
            'estado' => 'required|in:pendiente,en_progreso,completada',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada.');
    }
}