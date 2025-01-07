<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Revista\CombosController;
use App\Http\Controllers\API\Revista\SolicitudController;
use App\Http\Controllers\API\Revista\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('combos/tipodocumento', [CombosController::class, 'getTipoDocumento']);
Route::get('combos/estados', [CombosController::class, 'getEstados']);
Route::post('solicitud/registrar', [SolicitudController::class, 'registrarSolicitud']);
Route::post('solicitud/actualizar', [SolicitudController::class, 'actualizarSolicitud']);
Route::post('solicitud/listar', [SolicitudController::class, 'listarSolicitudes']);

Route::post('solicitud/listarSolicitudesAdmin', [SolicitudController::class, 'listarSolicitudesAdmin']);
Route::post('solicitud/iniciarRevision', [SolicitudController::class, 'iniciarRevision']);
Route::post('solicitud/registrarLevObs', [SolicitudController::class, 'registrarLevObs']);

Route::get('solicitud/consultarRevista/{revista}', [SolicitudController::class, 'consultarRevista']);
Route::get('solicitud/consultarObs/{solicitud}', [SolicitudController::class, 'consultarObs']);
Route::get('solicitud/eliminarRevista/{solicitud}/{revista}', [SolicitudController::class, 'eliminarRevista']);


Route::post('auth/registrar', [AuthController::class, 'registrar']);
Route::post('auth/acceso', [AuthController::class, 'acceso']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
