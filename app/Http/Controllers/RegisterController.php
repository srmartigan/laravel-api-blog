<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    function signup(Request $request)
    {
        //Verificar Datos
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        //Crear usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = hash('sha256', $request->password);
        $user->save();

        return json_encode($this->ok('Usuario registrado'));
    }

    function ok(string $message = 'ok'): array
    {
        return [
            'status' => 'ok',
            'code' => 200,
            'message' => $message
        ];
    }

}
