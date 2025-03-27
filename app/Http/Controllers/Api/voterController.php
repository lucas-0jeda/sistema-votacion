<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voter;
use Illuminate\Support\Facades\Validator;

class VoterController extends Controller{
    
    /**
     * Recuperar todos los votantes de la base de datos.
     *
     * Este método obtiene todos los registros del modelo Voter y los devuelve
     * en una respuesta JSON junto con un código de estado 200.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene todos los votantes y un código de estado.
     */
    public function getAllVoters() {
        $voters = Voter::all();
        $data = [
            "voters" => $voters,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Recuperar una lista de candidatos.
     *
     * Este método obtiene todos los votantes que están marcados como candidatos
     * (donde el campo 'is_candidate' está establecido en 1) de la base de datos.
     * Devuelve la lista de candidatos junto con un código de estado en formato JSON.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene:
     * - 'candidates': Una colección de votantes marcados como candidatos.
     * - 'status': Código de estado HTTP (200).
     */
    public function getCandidates(){
        $candidates = Voter::where('is_candidate', 1)->get();
        $data = [
            "candidates" => $candidates,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Verifica si un votante está habilitado para votar.
     *
     * @param string $document El documento de identificación del votante.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el estado del votante.
     * 
     * - Si el votante no es encontrado:
     *   Devuelve un mensaje de error con código de estado 404.
     * 
     * - Si el votante ya ha votado:
     *   Devuelve un mensaje indicando que el votante ya emitió su voto, con código de estado 404.
     * 
     * - Si el votante está habilitado para votar:
     *   Devuelve los datos del votante con un código de estado 200.
     */
    public function ableToVote($document){
        $voter = Voter::select('voters.*', 'votes.candidate_voted_id')
        ->leftJoin('votes', 'voters.id', '=', 'votes.candidate_id')
        ->where('voters.document', $document)
        ->first();

        if(!$voter){
            $data = [
                "message" => "Votante {$document} no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        if($voter->candidate_voted_id !== null){
            $data = [
                "message" => "El votante {$document} ya votó",
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

    /**
     * Crear un nuevo votante.
     *
     * Este método valida los datos de la solicitud entrante, crea un nuevo registro
     * de votante en la base de datos y devuelve una respuesta JSON con el votante creado
     * o un mensaje de error si la operación falla.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante que contiene los datos del votante.
     *
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene los datos del votante creado
     *                                        o un mensaje de error con el código de estado HTTP correspondiente.
     *
     * Reglas de validación:
     * - name: requerido, cadena
     * - lastName: requerido, cadena
     * - document: requerido, cadena, 8 dígitos, único en la tabla de votantes
     * - dob: requerido, fecha
     * - is_candidate: requerido, booleano
     *
     * Códigos de respuesta:
     * - 201: Votante creado exitosamente.
     * - 400: Error de validación.
     * - 500: Error interno del servidor al crear el votante.
     */
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

    /**
     * Buscar un votante por su ID.
     *
     * Este método recupera un registro de votante de la base de datos utilizando el ID proporcionado.
     * Si el votante no es encontrado, devuelve una respuesta JSON con un código de estado 404 y un mensaje de error.
     * Si el votante es encontrado, devuelve una respuesta JSON con los datos del votante y un código de estado 200.
     *
     * @param int $voterId El ID del votante a recuperar.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene los datos del votante o un mensaje de error.
     */
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

    /**
     * Elimina un votante por su ID.
     *
     * Este método busca un votante en la base de datos utilizando el ID proporcionado.
     * Si el votante no se encuentra, devuelve una respuesta JSON con un mensaje de error
     * y un código de estado 404. Si el votante es encontrado, se elimina de la base de datos
     * y se devuelve una respuesta JSON con un mensaje de éxito y un código de estado 200.
     *
     * @param int $voterId El ID del votante que se desea eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de la operación.
     */
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

    /**
     * Actualiza la información de un votante por su ID.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos a actualizar.
     * @param int $voterId El ID del votante a actualizar.
     * 
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con el estado de la operación.
     * 
     * - Si el votante no es encontrado:
     *   - Devuelve una respuesta 404 con un mensaje indicando que el votante no fue encontrado.
     * 
     * - Si la validación falla:
     *   - Devuelve una respuesta 400 con los mensajes de error de validación.
     * 
     * - Si la actualización es exitosa:
     *   - Devuelve una respuesta 200 con la información actualizada del votante.
     * 
     * Reglas de validación:
     * - `name`: Opcional, debe ser una cadena.
     * - `lastName`: Opcional, debe ser una cadena.
     * - `document`: Opcional, debe ser una cadena, exactamente 8 dígitos, y único en la tabla `voters`.
     * - `dob`: Opcional, debe ser una fecha válida.
     * - `is_candidate`: Opcional, debe ser un booleano.
     */
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
