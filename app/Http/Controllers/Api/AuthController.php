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

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:10|confirmed',
        ]);
        
        if($validator->fails()){
            return response()->json(["error" => $validator->errors()], 422);
        }

        Admin::create([
            'name' => $request->name,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(["message" => "Admin created successfully"], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:10'
        ]);

        if($validator->fails()){
            return response()->json(["error" => $validator->errors()], 422);
        }

        $credentials = $request->only(['email', 'password']);
        try{
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            return response()->json(['token' => $token], 200);
        }catch (JWTException $e){
            return response()->json(['error' => 'Could not create token', $e], 500);
        }
    }

    public function getUser(){
        $user = Auth::user();
        return response()->json([$user, 200]);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function forbbidenPassword(Request $request, $adminId){

        $admin = Admin::find($adminId);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:10|confirmed',
        ]);

        if($validator->fails()){
            $data = [
                "message" => "Error en la validacion de datos",
                "errors" => $validator->errors(),
                "status" => 422
            ];
            return response()->json(["error" => $validator->errors()], 422);
        }

        $admin->password = bcrypt($request->password);
        $admin->save();

        $data = [
            "message" => "Contraseña actualizada correctamente",
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
