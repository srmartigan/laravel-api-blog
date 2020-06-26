<?php


namespace App\Http\Domain\User;


interface CreateUserRepository
{
    public function validation(UserEntity $userEntity) :bool ;
    public function create(UserEntity $userEntity);
}
