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
//        if (!$this->verityData($param)) {
//            return $this->error('Los datos introducidos no son correctos');
//        }

        //Crear usuario
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = hash('sha256', $request->password);
        $user->save();

        return json_encode($this->ok('Usuario registrado'));
    }


    function error(string $message = 'error'): array
    {
        return [
            'status' => 'error',
            'code' => 400,
            'message' => $message
        ];
    }

    function ok(string $message = 'ok'): array
    {
        return [
            'status' => 'ok',
            'code' => 200,
            'message' => $message
        ];
    }

    /**
     * @param $param
     * @return bool
     */
    public function verityData($param): bool
    {
        $verify = true;
        //Verificamos que los atributos existen
        isset($param->name) ?: $verify = false;
        isset($param->email)?: $verify = false;
        isset($param->password) ?: $verify = false;

        if ($verify == false) return false;
        //Verificamos que no hay cadenas vacias
        empty($param->name)?$verify = false : null;
        empty($param->email)?$verify = false : null;
        empty($param->password)?$verify = false : null;

        return $verify ;
    }
}
