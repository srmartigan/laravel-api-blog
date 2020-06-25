<?php


namespace App\Http\UseCase\User;


use App\User;
use \Exception;
use Validator;

class CreateUserUseCase
{
    public function __construct(object $datosUser)
    {
        try {
            $this->validation($datosUser);
        }catch (Exception $e){

            throw new Exception($e->getMessage());
        }
        $this->createUser($datosUser);
    }

    private function validation(object $datosUser) :void
    {

        $validator = Validator::make((array)$datosUser, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $messageError = $validator->errors()->first();
            throw new Exception($messageError);
        }

    }

    private function createUser(object $datosUser)
    {
        $user = new User();
        $user->name = $datosUser->name;
        $user->email = $datosUser->email;
        $user->password = hash('sha256', $datosUser->password);
        $user->save();
    }
}
