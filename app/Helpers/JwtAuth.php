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
        $this->key = '*;Jz^`6tx]g!mwxsMfsbw|zI$jdDp%02]-<u%(#ogDt`-P@o66/&YBm?!)^"H`0';
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

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            if (is_null($getToken)) {
                return $jwt;
            } else {
                return $decoded;
            }

        } else {
            // Devolver error
            return ['status' => 'error', 'message' => 'Login ha fallado !!'];
        }

    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;
        $decoded = null;
        $user = null;

        //Descodificamos JWT
        try {
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        //Validamos
        if (is_object($decoded)) {
            $user = User::query()->where([
                'email' => $decoded->email,
                'id' => $decoded->sub
            ])->first();
        }else{
            $auth = false;
        }

        if (is_object($user)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}
