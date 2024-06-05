<?php

namespace App\Repository;

use App\Models\User;

interface UserRepositoryI
{
    public function getUser(string $email): ?User;
    public function addUser(User $user): void;
    public function updateUserPassword(int $id, string $newPassword): void;
}