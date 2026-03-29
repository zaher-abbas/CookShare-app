<?php

namespace App\Model;

use App\Exception\UserAlreadyExists;
use App\Exception\UserNotFound;
use App\Exception\WrongPassword;
use PDO;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //Operation CRUD Create:
    public function createUser(string $firstName, string $lastName, string $password, string $email, int $role_id): void
    {
        $query = "INSERT INTO users (firstName, lastName, email, password, role_id) VALUES
                                   (:firstName, :lastName, :email, :password, :role_id)";
        if (!$this->findUserByEmail($email)) {
            $statement = $this->db->prepare($query);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':role_id', $role_id, PDO::PARAM_INT);
            $statement->execute();
        }
        else
            throw new UserAlreadyExists("An account already exists with this email address!");
    }

    public function findUserByEmail(string $email): bool
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch() ?? null;
        return $user != null;
    }

    public function getUser(string $email, string $password): array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch();
        if (!$user) {
            throw new UserNotFound("User not found");
        }
        if (!password_verify($password, $user["password"])) {
            throw new WrongPassword("Password incorrect");
        }
       return $user;
    }

    public function getUserRoleName(int $userId): string
    {
        $query = "
        SELECT roles.name AS role_name
        FROM users
        JOIN roles ON users.role_id = roles.id
        WHERE users.id = :id";

        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFound('User not found');
        }

        return $row['role_name'];
    }

    public function getUserById(int $id): array
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch();
        if (!$user) {
            throw new UserNotFound("User not found");
        }
        return $user;
    }

    public function updateUserPhoto(int $id, string $photo): void

    {
        $query = "UPDATE users SET photo = :photo WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':photo', $photo);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function getAllUsers(): array|null
    {
        $query = 'SELECT * FROM users';
        $statement = $this->db->query($query);
        return $statement->fetchAll();
    }

    public function deleteUser(int $id): void
    {
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}