@extends('layouts.app')

@section('titulo', 'Crear Tarea')
@section('titulo_pagina', 'Crear Nueva Tarea')

@section('contenido')
    <div class="rounded-lg bg-white p-6 shadow">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-red-500 @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                @error('titulo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría <span class="text-red-500">*</span></label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror" id="category_id" name="category_id" required>
                    <option value="">Seleccione...</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="fecha_limite" class="block text-sm font-medium text-gray-700">Fecha Límite</label>
                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('fecha_limite') border-red-500 @enderror" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite') }}">
                @error('fecha_limite')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">Guardar Tarea</button>
                <a href="{{ route('tasks.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
@endsection