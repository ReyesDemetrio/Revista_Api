<?php

namespace App\Http\Controllers\API\Revista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CombosController extends Controller
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


    public function getTipoDocumento()
    {
        try{
            $resultado = DB::table('tipodocumento')
            ->select(
                'Codigo as codigo',
                'Siglas as descripcion'
            )
            ->where('Vigente', 1)
            ->get();
            return response()->json($resultado, 200);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEstados(){
        try{
            $resultado = DB::table('estado')
            ->select(
                'Codigo',
                'Nombre'
            )
            ->where('Vigente', 1)
            ->get();
            return response()->json($resultado, 200);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
