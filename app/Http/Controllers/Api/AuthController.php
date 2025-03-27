<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller{

    /**
     * Registra un nuevo administrador en el sistema.
     *
     * @param Request $request La solicitud HTTP que contiene los datos del administrador.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function register(Request $request){
        // Validar los datos de entrada del administrador.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // El nombre es obligatorio, debe ser una cadena y tener un máximo de 255 caracteres.
            'lastName' => 'required|string|max:255', // El apellido es obligatorio, debe ser una cadena y tener un máximo de 255 caracteres.
            'email' => 'required|string|email|max:255|unique:admins', // El correo es obligatorio, debe ser único y válido.
            'password' => 'required|string|min:10|confirmed', // La contraseña es obligatoria, debe tener al menos 10 caracteres y ser confirmada.
        ]);
        
        // Si la validación falla, devolver un error con los detalles.
        if($validator->fails()){
            return response()->json(["error" => $validator->errors()], 422);
        }

        // Crear un nuevo administrador con los datos proporcionados.
        Admin::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encriptar la contraseña antes de guardarla.
        ]);

        // Devolver una respuesta indicando que el administrador fue creado exitosamente.
        return response()->json(["message" => "Admin created successfully"], 201);
    }

    /**
     * Inicia sesión un administrador en el sistema.
     *
     * @param Request $request La solicitud HTTP que contiene las credenciales del administrador.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el token de autenticación o un mensaje de error.
     */
    public function login(Request $request){
        // Validar los datos de entrada del administrador.
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email', // El correo es obligatorio y debe ser válido.
            'password' => 'required|string|min:10' // La contraseña es obligatoria y debe tener al menos 10 caracteres.
        ]);

        // Si la validación falla, devolver un error con los detalles.
        if($validator->fails()){
            return response()->json(["error" => $validator->errors()], 422);
        }

        // Obtener las credenciales del administrador.
        $credentials = $request->only(['email', 'password']);
        try{
            // Intentar autenticar al administrador con las credenciales proporcionadas.
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401); // Credenciales inválidas.
            }
            // Devolver el token de autenticación si las credenciales son correctas.
            return response()->json(['token' => $token], 200);
        }catch (JWTException $e){
            // Devolver un error si no se pudo generar el token.
            return response()->json(['error' => 'Could not create token', $e], 500);
        }
    }

    /**
     * Obtiene la información del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del usuario autenticado.
     */
    public function getUser(){
        // Obtener el usuario actualmente autenticado.
        $user = Auth::user();
        // Devolver la información del usuario en formato JSON.
        return response()->json([$user, 200]);
    }

    /**
     * Cierra la sesión del administrador autenticado.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON indicando que la sesión se cerró correctamente.
     */
    public function logout(){
        // Invalidar el token de autenticación actual.
        JWTAuth::invalidate(JWTAuth::getToken());
        // Devolver una respuesta indicando que la sesión se cerró exitosamente.
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Actualiza la contraseña de un administrador específico.
     *
     * @param Request $request La solicitud HTTP que contiene la nueva contraseña.
     * @param int $adminId El ID del administrador cuya contraseña se actualizará.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function forbbidenPassword(Request $request, $adminId){

        // Buscar al administrador por su ID.
        $admin = Admin::find($adminId);

        // Validar los datos de entrada, asegurándose de que la contraseña cumpla con los requisitos.
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:10|confirmed', // La contraseña es obligatoria, debe tener al menos 10 caracteres y ser confirmada.
        ]);

        // Si la validación falla, devolver un error con los detalles.
        if($validator->fails()){
            $data = [
                "message" => "Error en la validación de datos", // Mensaje de error en la validación.
                "errors" => $validator->errors(), // Detalles de los errores de validación.
                "status" => 422 // Código de estado HTTP para errores de validación.
            ];
            return response()->json(["error" => $validator->errors()], 422);
        }

        // Actualizar la contraseña del administrador con la nueva contraseña encriptada.
        $admin->password = bcrypt($request->password);
        $admin->save(); // Guardar los cambios en la base de datos.

        // Preparar la respuesta indicando que la contraseña fue actualizada correctamente.
        $data = [
            "message" => "Contraseña actualizada correctamente", // Mensaje de éxito.
            "status" => 200 // Código de estado HTTP para éxito.
        ];
        return response()->json($data, 200); // Devolver la respuesta en formato JSON.
    }
}
