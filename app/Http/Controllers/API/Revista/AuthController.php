<?php

namespace App\Http\Controllers\API\Revista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
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


    public function registrar(Request $request)
    {
        $persona = $request->input('persona');
        $usuario = $request->input('user');
    
        // Validar datos de persona
        $personaValidator = Validator::make($persona, [
            'Nombre' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:80',
            'CodigoTipoDocumento' => 'required|integer',
            'NumDocumento' => 'required|string|max:25|unique:persona,NumDocumento,NULL,NULL,CodigoTipoDocumento,' . $persona['CodigoTipoDocumento'],
            'Telefono' => 'required|string|max:9',
        ]);
    
        if ($personaValidator->fails()) {
            return response()->json([
                'message' => 'Errores en los datos de la persona',
                'errors' => $personaValidator->errors(),
            ], 400);
        }
    

                // Validar datos de usuario
                $usuarioValidator = Validator::make($usuario, [
                    'email' => 'required|email|max:250|unique:users,email',
                    'password' => 'required|string|min:8|max:250',
                ]);
            
                if ($usuarioValidator->fails()) {
                    return response()->json([
                        'message' => 'Errores en los datos del usuario',
                        'errors' => $usuarioValidator->errors(),
                    ], 400);
                }

            DB::beginTransaction();

        try{

            // Registrar persona
            $persona['Correo'] = $usuario['email'];
            $personaData = Persona::create($persona);
            $idPersona = $personaData->Codigo;

            $usuario['Codigo'] = $idPersona;
            $usuario['password'] = bcrypt($usuario['password']);
            $usuario['rol'] = '2'; // Rol de usuario normal
            $usuarioData = User::create($usuario);

            // Todo está bien
            DB::commit();
            return response()->json([
                'message' => 'Registro exitoso'
            ], 200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar',
                'error' => $e->getMessage()
            ], 500);
        }

    }


    public function acceso(Request $request)
    {   
        // Obtener las credenciales del request
        $credenciales = $request->only('email', 'password');
        
        // Validar datos de persona
        $userValidator = Validator::make($credenciales, [
            'email' => 'required|email|max:250',
            'password' => 'required|string|min:8|max:250',
        ]);

        // Si la validación falla, retornamos los errores
        if ($userValidator->fails()) {
            return response()->json([
                'message' => 'Errores en los datos de la persona',
                'errors' => $userValidator->errors(),
            ], 400);
        }

        // Intentar autenticación con las credenciales
        if (Auth::attempt($credenciales)) {
            $user = Auth::user();  // Usar Auth::user() para obtener al usuario autenticado

            // Crear un token con una expiración de 2 horas
            $expiresAt = now()->addHours(2);
            $token = $user->createToken('authToken', ['*'], $expiresAt)->plainTextToken;

            return response()->json([
                'resp' => true,
                'token' => $token,
                'user' => $user,
                'token_expires' => $expiresAt,
                'message' => 'Acceso exitoso',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }
    }
}
