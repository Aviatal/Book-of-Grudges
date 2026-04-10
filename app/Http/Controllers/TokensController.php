<?php

namespace App\Http\Controllers;

use App\Events\Session\MoveTokenEvent;
use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    public function getTokens(): \Illuminate\Database\Eloquent\Collection
    {
        return Token::all();
    }

    public function moveToken(Request $request, Token $token): \Illuminate\Http\JsonResponse
    {
        $token->update($request->only(['x', 'y']));
        broadcast(new MoveTokenEvent($token->getAttribute('id'), $token->getAttribute('x'), $token->getAttribute('y')))->toOthers();
        return response()->json($token);
    }
}
