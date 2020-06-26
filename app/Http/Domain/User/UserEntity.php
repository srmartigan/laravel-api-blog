<?php


namespace App\Http\Domain\User;


use App\User;

class UserEntity
{
    private $name;
    private $email;
    private $password;


    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function validate(){

        if ($this->name == '' || $this->name == null) {
            throw new ValidationExceptionDomain('Hay que introducir un nombre');
        }
        if ($this->password == '' || $this->password == null) {
            throw new ValidationExceptionDomain('Hay que añadir un password valido');
        }
        $user = User::query()->where('email', '=', $this->email)->first();

        if ($user != null){
            throw new ValidationExceptionDomain('El email ya existe');
        };

        return true;
    }

}
