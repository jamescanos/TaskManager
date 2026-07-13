<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     type="object",
 *     title="Task",
 *     description="Modelo de una tarea",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="titulo", type="string", example="Mi primera tarea"),
 *     @OA\Property(property="estado", type="string", enum={"pendiente", "en_progreso", "completada"}, example="pendiente"),
 *     @OA\Property(property="fecha_limite", type="string", format="date", example="2026-08-01"),
 *     @OA\Property(property="categoria", ref="#/components/schemas/CategoryResource"),
 *     @OA\Property(property="usuario", ref="#/components/schemas/UserResource"),
 * )
 */
class TaskResourceSchema
{
    // Clase contenedora para el schema
}