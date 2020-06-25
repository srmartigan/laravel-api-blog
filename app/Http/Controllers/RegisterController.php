<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\UseCase\User\CreateUserUseCase;

class RegisterController extends Controller
{
    function signup(Request $request)
    {
        $json = $request->input('json');
        $datosUser = json_decode($json);

        try {
            $createUser = new CreateUserUseCase($datosUser);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], 401);
        }

        return response()->json([
            'ok' => 'true',
            'status' => 'success',
            'code' => 200,
            'message' => 'Usuario registrado!!'
        ], 200);
    }

}
