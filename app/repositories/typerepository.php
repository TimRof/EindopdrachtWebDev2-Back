<?php

namespace Repositories;

use Models\Appointment;
use Models\User;
use Models\Type;
use PDO;
use PDOException;
use Repositories\Repository;

class TypeRepository extends Repository
{
    function getAll()
    {
        try {
            $sql = 'SELECT * FROM types';

            $stmt = $this->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Type');

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }
}
