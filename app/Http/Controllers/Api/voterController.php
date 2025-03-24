<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voter;
use Illuminate\Support\Facades\Validator;

class VoterController extends Controller{
    
    public function getAllVoters() {
        $voters = Voter::all();
        $data = [
            "voters" => $voters,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function getCandidates(){
        $candidates = Voter::where('is_candidate', 1)->get();
        $data = [
            "candidates" => $candidates,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    public function createVoter(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "lastName" => "required|string",
            "document" => "required|string|digits:8|unique:voters",
            "dob" => "required|date",
            "is_candidate" => "required|bool"
        ]); 

        if($validator->fails()){
            $data = [
                "message" => "Error en la validacion de datos",
                "errors" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }

        $voter = Voter::create([
            "name" => $request->name,
            "lastName" => $request->lastName,
            "document" => $request->document,
            "dob" => $request->dob,
            "is_candidate" => $request->is_candidate
        ]);

        if(!$voter){
            $data = [
                "message" => "Error al crear el votante",
                "status" => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            "voter" => $voter,
            "status" => 201
        ];
        
        return response()->json($data, 201);
    }

    public function findVoterById($voterId){
        $voter = Voter::find($voterId);

        if(!$voter){
            $data = [
                "message" => "Votante {$voterId} no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "voter" => $voter,
            "status" => 200
        ];
        
        return response()->json($data, 200);
    }

    public function deleteVoterById($voterId){
        $voter = Voter::find($voterId);

        if(!$voter){
            $data = [
                "message" => "Votante {$voterId} no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $voter->delete();

        $data = [
            "message" => "Votante {$voterId} eliminado",
            "status" => 200
        ];
        
        return response()->json($data, 200);
    }

    public function updateVoterById(Request $request, $voterId){
        $voter = Voter::find($voterId);

        if(!$voter){
            $data = [
                "message" => "Votante {$voterId} no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            "name" => "string",
            "lastName" => "string",
            "document" => "string|digits:8|unique:voters",
            "dob" => "date",
            "is_candidate" => "bool"
        ]); 

        if($validator->fails()){
            $data = [
                "message" => "Error en la validacion de datos",
                "errors" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }

        if($request->has("name")) $voter->name = $request->name;
        if($request->has("lastName")) $voter->lastName = $request->lastName;
        if($request->has("document")) $voter->document = $request->document;
        if($request->has("dob")) $voter->dob = $request->dob;
        if($request->has("is_candidate")) $voter->is_candidate = $request->is_candidate;
        
        $voter->save();
       
        
        $data = [
            "message" => "Votante {$voterId} actualizado",
            "voter" => $voter,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
