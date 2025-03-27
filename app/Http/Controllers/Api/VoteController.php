<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vote;

class VoteController extends Controller
{
    /**
     * Inserta un nuevo voto en la base de datos.
     *
     * Este método valida los datos de la solicitud entrante, asegurándose de que los campos requeridos
     * estén presentes y cumplan con los criterios especificados. Si la validación es exitosa, se crea
     * y almacena un nuevo voto en la base de datos. Si ocurren errores durante la validación o creación,
     * se devuelve una respuesta JSON apropiada.
     *
     * @param \Illuminate\Http\Request $request El objeto de solicitud HTTP que contiene los datos del voto.
     * 
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene el resultado de la operación:
     * - En caso de fallo de validación: Devuelve un código de estado 400 con los mensajes de error de validación.
     * - En caso de creación exitosa: Devuelve un código de estado 201 con los datos del voto creado.
     * - En caso de fallo al crear: Devuelve un código de estado 500 con un mensaje de error.
     *
     * Reglas de Validación:
     * - `candidate_voted_id`: Requerido, debe ser un entero, debe existir en la tabla `voters` donde `is_candidate` es 1.
     * - `candidate_id`: Requerido, debe ser un entero, debe ser único en la tabla `votes`.
     */
    public function insertVote(Request $request){
        $validator = Validator::make($request->all(), [
            "candidate_voted_id" => "required|int|exists:voters,id,is_candidate,1",
            "candidate_id" => "required|int|unique:votes"
        ]); 

        if($validator->fails()){
            $data = [
                "message" => "Error en la validacion de datos",
                "errors" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }

        $vote = Vote::create([
            "candidate_voted_id" => $request->candidate_voted_id,
            "candidate_id" => $request->candidate_id,
            "date" => date("Y-m-d H:i:s"),
        ]);

        if(!$vote){
            $data = [
                "message" => "Error al crear el voto",
                "status" => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            "vote" => $vote,
            "status" => 201
        ];

        return response()->json($data, 201);
    }

    /**
     * Recupera los detalles de un voto específico por su ID.
     *
     * Este método obtiene los detalles de un voto uniendo la tabla `votes` con la tabla `voters`
     * en base al ID del candidato. Si el voto no se encuentra, devuelve una respuesta JSON con
     * un código de estado 404 y un mensaje apropiado. De lo contrario, devuelve los detalles del voto
     * junto con un código de estado 200.
     *
     * @param int $voteId El ID del voto a recuperar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON que contiene los detalles del voto o un mensaje de error.
     */
    public function getVoteDetails($voteId){
        $vote = Vote::join('voters', 'votes.candidate_id', '=', 'voters.id')
        ->where('votes.id', $voteId)
        ->get();

        if(!$vote){
            $data = [
                "message" => "Voto no encontrado",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "vote" => $vote,
            "status" => 200
        ];

        return response()->json($data, 200);
    }

    /**
     * Recupera los 5 candidatos más votados.
     *
     * Este método consulta la tabla de votos para contar el número de votos de cada candidato,
     * agrupa los resultados por el ID del candidato, los ordena en orden descendente por la cantidad
     * de votos y limita los resultados a los 5 candidatos más votados.
     *
     * @return \Illuminate\Http\JsonResponse
     * - Si se encuentran candidatos:
     *   Devuelve una respuesta JSON con los 5 candidatos más votados y un código de estado 200.
     * - Si no se encuentran candidatos:
     *   Devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     */
    public function getMostVotedCandidates(){
        $candidatesMostVoted = Vote::selectRaw('COUNT(*) as votes, candidate_voted_id')
        ->groupBy('candidate_voted_id')
        ->orderByDesc('votes')
        ->limit(5)
        ->get();

        if(!$candidatesMostVoted){
            $data = [
                "message" => "No se encontraron candidatos mas votados",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "candidatesMostVoted" => $candidatesMostVoted,
            "status" => 200
        ];
        return response()->json($data, 200);
    }

    /**
     * Recupera todos los votos registrados en la base de datos.
     *
     * Este método obtiene una lista de todos los votos, incluyendo información detallada
     * sobre los votantes y los candidatos asociados a cada voto. Los resultados se ordenan
     * en orden descendente por la fecha del voto.
     *
     * @return \Illuminate\Http\JsonResponse
     * - Si se encuentran votos:
     *   Devuelve una respuesta JSON con la lista de votos y un código de estado 200.
     * - Si no se encuentran votos:
     *   Devuelve una respuesta JSON con un mensaje de error y un código de estado 404.
     */
    public function getAllVotes(){
        $votes = Vote::select('votes.*', 'voter_voted.name as voter_voted_name', 'voter_voted.lastName as voter_voted_last_name', 'voter.name as voter_name', 'voter.lastName as voter_last_name', 'voter.document as voter_document')
        ->join('voters as voter_voted', 'votes.candidate_voted_id', '=', 'voter_voted.id')
        ->join('voters as voter', 'votes.candidate_id', '=', 'voter.id')
        ->orderBy('votes.date', 'desc')
        ->get();

        if(!$votes){
            $data = [
                "message" => "No se encontraron votos",
                "status" => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            "votes" => $votes,
            "status" => 200
        ];
        return response()->json($data, 200);
    }
}
