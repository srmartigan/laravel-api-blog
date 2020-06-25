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
        $getToken = null;

        //Validar datos
        $json = $request->input('json');
        $arrayJson = json_decode($json, true);

        if(isset($arrayJson['gettoken']) && $arrayJson['gettoken'] == true)
        {
            $getToken = true;
        }

        $validator = Validator::make($arrayJson, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->messages()
            ], 401);
        }

        $password = hash('sha256', $arrayJson['password']);

        $signup = $jwtAuth->signup($arrayJson['email'], $password,$getToken);

        return response()->json($signup, 200);
    }
}
