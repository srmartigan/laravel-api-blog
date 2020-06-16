<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth
{
    public $key;

    public function __construct()
    {
        $this->key =  'esta es la clave secreta que vamos a utilizar -*4982709847298742934-434*';
    }

    public function signup($email, $password, $getToken = null)
    {
        $user = User::query()->where([
            'email' => $email,
            'password' => $password
        ])->first();

        if (is_object($user)) {
            $token = [
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($token,$this->key,'HS256');
            $decoded = JWT::decode($jwt,$this->key,['HS256']);

            if(!is_null($getToken)){
                return $jwt;
            }else{
                return $decoded;
            }

        }else{
            // Devolver error
            return ['status' => 'error', 'message' => 'Login ha fallado !!'];
        }

    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
            try{
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            }catch(\UnexpectedValueException $e){
                $auth = false;
            }catch(\DomainException $e){
                $auth = false;
            }


        if (is_object($decoded) && isset($decoded->sub)){
                $auth = true;
            }else{
                $auth = false;
            }

            if($getIdentity){
                return $decoded;
            }

        return $auth;
    }
}
