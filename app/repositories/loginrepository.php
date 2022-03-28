<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class LoginRepository extends Repository
{
    function checkEmailPassword($email, $password)
    {
        try {
            $user = $this->findByEmail($email);

            if ($user) {
                if (password_verify($password, $user->password_hash)) {
                    return $user;
                }
            }

            return false;
        } catch (PDOException $e) {
            echo $e;
        }
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

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
}
