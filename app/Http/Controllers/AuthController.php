<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use HttpResponses;

    /**
     * Método responsável pelo login e a autenticação do usuário.
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {

            return $this->response(
                'Authorized',
                200,
                [
                    'token' => $request->user()->createToken('Token_Authorization')->plainTextToken
                ]
            );
        }

        return $this->response('Not authorized', 401);
    }

    /**
     * Método responsável pelo logout e a remoção da autenticação do usuário.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('token Revoked', 200);
    }
}
