<?php

namespace App\Http\Controllers\API\Revista;

use App\Http\Controllers\Controller;
use App\Models\HistorialSolicitud;
use Illuminate\Http\Request;
use App\Models\Revista;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function eliminarRevista(int $solicitud, int $revista)
    {

        DB::beginTransaction();
        try {

            Solicitud::where('Codigo', $solicitud)->update(['Vigente' => 0]);

            Revista::where('Codigo', $revista)->update(['Vigente' => 0]);

            DB::commit();

            return response()->json([
                'message' => 'Revista eliminada correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar la revista',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function consultarRevista(int $id)
    {

        try {

            $revista = Revista::find($id);
            return $revista;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al consultar la revista',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function consultarObs(int $solicitud)
    {

        try {
            $registro = HistorialSolicitud::select('CodigoSolicitud', 'Observacion', 'Fecha')
                ->where('CodigoSolicitud', $solicitud)
                ->where('Vigente', 1)
                ->orderByDesc('Codigo') // Equivale a `order by Codigo desc`
                ->first(); // Obtiene el primer resultado como objeto

            return $registro;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al consultar la observación',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function actualizarSolicitud(Request $request)
    {

        $revista = $request->input('revista');

        try {

            Revista::where('Codigo', $revista['Codigo'])->update($revista);

            return response()->json([
                'message' => 'Solicitud actualizada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registrarSolicitud(Request $request)
    {
        $fecha_Actual = date('Y-m-d');
        $solicitud = $request->input('solicitud');
        $articulo = $request->input('articulo');

        $articulo['Archivo'] = 'archivo.pdf';

        $solicitud['CodigoEstado'] = 1;
        $solicitud['FechaRegistro'] = $fecha_Actual;

        DB::beginTransaction();
        try {

            $articuloCreado = Revista::create($articulo);
            $solicitud['CodigoRevista'] = $articuloCreado->Codigo;

            $solicitudCreada = Solicitud::create($solicitud);

            $historial_articulo = [
                'CodigoSolicitud' => $solicitudCreada->Codigo,
                'CodigoEstado' => 1,
                'Fecha' => $fecha_Actual,
                'Observacion' => 'Solicitud Registrada'
            ];

            HistorialSolicitud::create($historial_articulo);

            DB::commit();

            return response()->json([
                'message' => 'Solicitud registrada correctamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registrarLevObs(Request $request)
    {
        $solicitud = $request->input('solicitud');
        $fecha_Actual = date('Y-m-d');
        DB::beginTransaction();

        try {

            Solicitud::where('Codigo', $solicitud['CodigoSolicitud'])->update(['CodigoEstado' => 5]);

            $historial_articulo = [
                'CodigoSolicitud' => $solicitud['CodigoSolicitud'],
                'CodigoEstado' => 5,
                'Fecha' => $fecha_Actual,
                'Observacion' => $solicitud['Observacion']
            ];

            HistorialSolicitud::create($historial_articulo);

            DB::commit();

            return response()->json([
                'message' => 'Solicitud actualizada correctamente',
                $solicitud
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el Levantamiento de Observación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listarSolicitudes(Request $request)
    {
        $persona = $request->input('Persona');

        try {
            $solicitudes = DB::table('solicitud as s')
                ->select([
                    's.Codigo as CodigoSolicitud',
                    's.CodigoRevista as CodigoRevista',
                    'r.Titulo',
                    's.FechaRegistro',
                    'e.Nombre as Estado',
                    'e.Codigo as CodigoEstado',
                    DB::raw('CASE WHEN s.CodigoEstado = 1 THEN 1 ELSE 0 END as Eliminar'),
                    DB::raw('CASE WHEN s.CodigoEstado IN (1, 3) THEN 1 ELSE 0 END as Editar'),
                    DB::raw('CASE WHEN s.CodigoEstado = 3 THEN 1 ELSE 0 END as Observacion'),
                ])
                ->join('revista as r', 'r.Codigo', '=', 's.CodigoRevista')
                ->join('estado as e', 'e.Codigo', '=', 's.CodigoEstado')
                ->where('s.CodigoPersona', $persona)
                ->where('s.Vigente', 1)
                ->where('r.Vigente', 1)
                ->where('e.Vigente', 1)
                ->orderBy('s.Codigo', 'desc')
                ->get();

            return $solicitudes;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al listar las solicitudes',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function listarSolicitudesAdmin(Request $request)
    {
        $query = $request->input('query');

        // Extraer los valores de los parámetros del request
        $nombrePersona = $query['persona'] ?? null;
        $nombreRevista = $query['revista'] ?? null;

        try {
            // Construir la consulta
            $solicitudes = DB::table('solicitud as s')
                ->join('revista as r', 'r.Codigo', '=', 's.CodigoRevista')
                ->join('estado as e', 'e.Codigo', '=', 's.CodigoEstado')
                ->join('persona as p', 'p.Codigo', '=', 's.CodigoPersona')
                ->select(
                    's.Codigo as CodigoSolicitud',
                    'p.Nombre',
                    'p.Apellidos',
                    'p.Correo',
                    'p.Telefono',
                    'r.Titulo',
                    'e.Nombre as Estado',
                    'e.Codigo as CodigoEstado',
                    's.FechaRegistro'
                )
                ->where('s.Vigente', 1)
                ->where('r.Vigente', 1)
                ->where('p.Vigente', 1)
                // Agregar filtros condicionales para persona y revista
                ->when($nombrePersona, function ($query, $nombrePersona) {
                    return $query->where('p.Nombre', 'LIKE', "%$nombrePersona%");
                })
                ->when($nombreRevista, function ($query, $nombreRevista) {
                    return $query->where('r.Titulo', 'LIKE', "%$nombreRevista%");
                })
                ->get();

            // Retornar la lista de solicitudes
            return response()->json([
                'message' => 'Solicitudes obtenidas exitosamente',
                'data' => $solicitudes,
            ], 200);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json([
                'message' => 'Error al listar las solicitudes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function iniciarRevision(Request $request)
    {
        $solicitud = $request->input('solicitud');
        $fecha_Actual = date('Y-m-d');
        DB::beginTransaction();
        try {

            if ($solicitud['codObservacion']) {
                $solicitud['CodigoEstado'] = 3;
            } else {
                $solicitud['CodigoEstado'] = 2;
                $solicitud['observacion'] = 'Solicitud en Trámite';
            }

            Solicitud::where('Codigo', $solicitud['CodigoSolicitud'])->update(['CodigoEstado' => $solicitud['CodigoEstado']]);


            $historial_articulo = [
                'CodigoSolicitud' => $solicitud['CodigoSolicitud'],
                'CodigoEstado' => $solicitud['CodigoEstado'],
                'Fecha' => $fecha_Actual,
                'Observacion' => $solicitud['observacion']
            ];

            HistorialSolicitud::create($historial_articulo);

            DB::commit();

            return response()->json([
                'message' => 'Solicitud actualizada correctamente',
                $solicitud
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar la solicitud',
                $solicitud,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
