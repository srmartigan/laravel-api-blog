<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegisterController extends Controller
{
    function register(Request $request)
    {
        //Recoger Post
        $json = $request->input('json', null);
        if (is_null($json)) {
            return $this->error('Los datos no se han recibido');
        }
        $param = json_decode($json);

        //Verificar Datos
        if (!$this->verityData($param)) {
            return $this->error('Los datos introducidos no son correctos');
        }

        //Crear usuario
        $user = new User();
        $user->name = $param->name;
        $user->email = $param->email;
        $user->password = hash('sha256', $param->password);

        //Verificar que el usuario no existe
        $issetUser = User::query()->where('email', '=', $user->email)->first();

        if (is_null($issetUser)) {
            $user->save();
            $data = $this->ok('Usuario registrado correctamente');
        } else {
            $data = $this->error('Usuario duplicado');
        }

        return json_encode($data);
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
