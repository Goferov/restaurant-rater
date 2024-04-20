<?php

namespace App\Repository;
use PDO;
use App\Models\User;

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare
        ('
    SELECT * FROM public.is_user WHERE email = :email
    ');

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return new User
        (
            $user['user_id'],
            $user['name'],
            $user['password'],
            $user['email'],
            $user['role_id']
        );
    }
}