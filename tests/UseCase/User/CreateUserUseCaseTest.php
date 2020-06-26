<?php

namespace Tests\UseCase\User;

use App\Http\UseCase\User\CreateUserUseCase;
use App\User;
use PHPUnit\Framework\TestCase;

class CreateUserUseCaseTest extends TestCase
{
    private function correctData() :object
    {
        $datos = [
            'name' => 'jonatan',
            'email' => 'admin@admin.com',
            'password' => 'admin'
        ];
        return (object)$datos;
    }

    private function incorrectData() :object
    {
        $datos = [
            'name' => 'Da igual',
            'email' => 'noExiste@esteEmail.com',
            'password' => 'PasswordFalse'
        ];
        return (object)$datos;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_verify_correct_user()
    {
        $datos = $this->correctData();

        $createUser = new CreateUserUseCase();

        $this->assertTrue($createUser->validation($datos));
    }

    public function test_verify_incorrect_user()
    {
        //TODO: Falta implementar este test
        $this->assertTrue(true);
    }

}
