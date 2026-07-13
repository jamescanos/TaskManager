@extends('layouts.app')

@section('titulo', 'Listado de Tareas')
@section('titulo_pagina', 'Listado de Tareas')

@section('contenido')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        @can('create', App\Models\Task::class)
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Nueva Tarea
            </a>
        @endcan

        <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-col gap-2 sm:flex-row">
            <input type="text" name="buscar" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Buscar tarea..." value="{{ request('buscar') }}">
            <select name="estado" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                <option value="en_progreso" {{ request('estado')=='en_progreso'?'selected':'' }}>En Progreso</option>
                <option value="completada" {{ request('estado')=='completada'?'selected':'' }}>Completada</option>
            </select>
            <button type="submit" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Filtrar</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <div class="overflow-x-auto p-4">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Título</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Categoría</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha Límite</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($tareas as $tarea)
                        <tr>
                            <td class="px-4 py-3 text-gray-700">{{ $tarea->id }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $tarea->titulo }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $color = $tarea->estado == 'completada' ? 'bg-green-100 text-green-800' : ($tarea->estado == 'en_progreso' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700');
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }}">{{ $tarea->estado }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $tarea->category->nombre ?? 'Sin categoría' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $tarea->fecha_limite ?? 'No definida' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    @can('update', $tarea)
                                        <a href="{{ route('tasks.edit', $tarea->id) }}" class="rounded-md bg-slate-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-400">Editar</a>
                                    @endcan
                                    @can('delete', $tarea)
                                        <form action="{{ route('tasks.destroy', $tarea->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-500" onclick="return confirm('¿Eliminar esta tarea?')">Eliminar</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay tareas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $tareas->links() }}
    </div>
    
@endsection