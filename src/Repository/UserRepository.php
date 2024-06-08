<?php

namespace App\Repository;
use PDO;
use App\Models\User;

class UserRepository extends Repository implements IUserRepository
{
    public function getUser(string $email): ?User {
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

    public function addUser(User $user): void {
        $stmt = $this->database->connect()->prepare
        ('
            INSERT INTO public.is_user (name, password, email, role_id) VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getName(),
            $user->getPassword(),
            $user->getEmail(),
            $user->getRoleId()
        ]);
    }

    public function updateUserPassword(int $id, string $newPassword): void {
        $stmt = $this->database->connect()->prepare(
            'UPDATE public.is_user SET password = :password WHERE user_id = :id'
        );

        $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}