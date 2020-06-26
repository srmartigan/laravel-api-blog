<?php


namespace App\Http\infrastructure\User;


use App\Http\Domain\User\CreateUserRepository;
use App\Http\Domain\User\UserEntity;
use App\Http\Domain\User\ValidationExceptionDomain;
use App\User;

class EloquentCreateUserRepository implements CreateUserRepository
{
    private $model;

    public function __construct()
    {
        $model = new User();
    }

    public function validation(UserEntity $userEntity) :bool
    {
        if ($userEntity->getName() == '' || $userEntity->getName() == null) {
            throw new ValidationExceptionDomain('Hay que introducir un nombre');
        }
        if ($userEntity->getPassword() == '' || $userEntity->getPassword() == null) {
            throw new ValidationExceptionDomain('Hay que introducir un password valido');
        }
        $user = User::query()-> where('email', '=', $userEntity->getEmail())->first();

        if ($user != null){
            throw new ValidationExceptionDomain('El email ya existe');
        };

        return true;
    }

    public function create(UserEntity $userEntity)
    {
        $user = new User();
        $user->name = $userEntity->getName();
        $user->email = $userEntity->getEmail();
        $user->password = hash('sha256', $userEntity->getPassword());
        $user->save();
    }
}
