<?php


namespace App\Http\UseCase\User;

use App\Http\Domain\User\CreateUserRepository;
use App\Http\Domain\User\UserEntity;
use App\User;


class CreateUserUseCase
{
    public $repository;

    public function __construct(CreateUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($userEntity)
    {
        if ($this->repository->validation($userEntity)){
            $this->repository->create($userEntity);
        }
    }

}
