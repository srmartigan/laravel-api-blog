<?php

namespace App\Http\Controllers;

use App\Http\Domain\User\UserEntity;
use App\Http\infrastructure\User\EloquentCreateUserRepository;
use Illuminate\Http\Request;
use App\Http\UseCase\User\CreateUserUseCase;

class RegisterController extends Controller
{
    function signup(Request $request)
    {
        $json = $request->input('json');
        $datosUser = json_decode($json);
        $userEntity = $this->getUserEntity($datosUser);

        try {
            $createUser = new CreateUserUseCase(new EloquentCreateUserRepository());
            $createUser->execute($userEntity);
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

    private function getUserEntity($datosUser) :UserEntity
    {
        return new UserEntity(
            $datosUser->name,
            $datosUser->email,
            $datosUser->password
        );
    }

}
