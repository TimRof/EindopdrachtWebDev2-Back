<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    private $errors = [];

    function create($user)
    {
        $this->validate($user);

        if (empty($this->errors)) {
            $password_hash = password_hash($user->password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO usersbasic (name, email, password_hash) VALUES (:name, :email, :password_hash)';
            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':name', $user->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        } else {
            return $this->errors;
        }
    }

    protected function validate($user)
    {
        // name
        if ($user->name == '') {
            $this->errors[] = 'Name is required.';
        }

        // email address
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email.';
        }
        if ($this->emailExists($user->email)) {
            $this->errors[] = 'Email is already taken';
        }

        // password
        if ($user->password != $user->passwordRepeat) {
            $this->errors[] = 'Passwords do not match.';
        }
        if (strlen($user->password) < 6) {
            $this->errors[] = 'Password should be at least 6 characters';
        }
    }
    public function emailExists($email)
    {
        return $this->findByEmail($email) !== false;
    }

    public function findByEmail($email)
    {
        $sql = 'SELECT * FROM usersbasic WHERE email = :email';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
}
