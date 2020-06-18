<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    function login(Request $request)
    {
        $jwtAuth = new JwtAuth();

        //Verificar Datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $password = hash('sha256', $request->password);

        $signup = $jwtAuth->signup($request->email, $password);

        return response()->json($signup, 200);
    }
}
