@extends('layouts.app')

@section('titulo', 'Detalle de Tarea')
@section('titulo_pagina', 'Detalle de Tarea')

@section('contenido')
    <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-xl font-semibold text-gray-800">{{ $task->titulo }}</h2>
        <p class="mt-3 text-gray-600">{{ $task->descripcion ?? 'Sin descripción' }}</p>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->estado }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->category->nombre ?? 'Sin categoría' }}</dd>
            </div>
        </dl>
    </div>
@endsection
