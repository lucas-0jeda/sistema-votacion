<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vote;

class VoteController extends Controller
{
    public function insertVote(Request $request){
        $validator = Validator::make($request->all(), [
            "candidate_voted_id" => "required|int",
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

    public function getAllVotes(){
        $votes = Vote::join('voters', 'voters.id', '=', 'votes.candidate_voted_id')
        ->orderByDesc('votes.date')
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
