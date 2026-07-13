<?php

namespace App\Http\Controllers;

/**
 * @OA\Get(
 *     path="/api/tareas",
 *     summary="Mostrar lista de tareas",
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa"
 *     )
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
