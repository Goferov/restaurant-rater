<?php

namespace App\Models;

class User {
    private int $id;
    private string $login;
    private string $password;
    private string $email;
    private int $roleId;

    public function __construct(int $id, string $login, string $password, string $email, int $roleId)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->roleId = $roleId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }


}