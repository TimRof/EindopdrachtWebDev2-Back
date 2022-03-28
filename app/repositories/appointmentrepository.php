<?php

namespace Repositories;

use Models\Appointment;
use Models\User;
use PDO;
use PDOException;
use Repositories\TypesRepository;
use Repositories\Repository;

class AppointmentRepository extends Repository
{
    private $errors = [];

    function getAll($date)
    {
        $dayAfter = clone $date;
        $dayAfter->setTime(23, 59, 59);
        $date->setTime(8, 0, 0);

        try {
            $sql = 'SELECT appointments.id, user_id, timeslot, starttime, endtime, types.type, usersbasic.name, usersbasic.email 
        FROM appointments 
        INNER JOIN types ON appointments.type = types.id
        INNER JOIN usersbasic ON appointments.user_id = usersbasic.id WHERE starttime >= :selectedDate AND starttime < :dayAfter ORDER BY starttime ASC';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':selectedDate', date_format($date, 'Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':dayAfter', date_format($dayAfter, 'Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Appointment');
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $e;
        }
    }

    // for demo purposes only: pagination
    function getAllv2($offset = NULL, $limit = NULL)
    {
        $sql = "SELECT product.*, category.name as category_name FROM product INNER JOIN category ON product.category_id = category.id";
        if (isset($limit) && isset($offset)) {
            $sql .= " LIMIT :limit OFFSET :offset ";
        }
        $stmt = $this->connection->prepare($sql);
        if (isset($limit) && isset($offset)) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Appointment');
        $stmt->execute();

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // for demo purposes only: filtering
    function getAllv3($query)
    {
        $sql = 'SELECT * FROM appointments WHERE id LIKE :id OR user_id LIKE :user_id OR type LIKE :type OR starttime LIKE :starttime OR endtime LIKE :endtime order by id desc';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $query, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $query, PDO::PARAM_INT);
        $stmt->bindValue(':type', $query, PDO::PARAM_INT);
        $stmt->bindValue(':starttime', $query, PDO::PARAM_STR);
        $stmt->bindValue(':endtime', $query, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    function getAllByDate($selectedDate)
    {
        $dayAfter = clone $selectedDate;
        $dayAfter->setTime(23, 59, 59);
        $selectedDate->setTime(8, 0, 0);

        $sql = 'SELECT * FROM appointments WHERE starttime >= :selectedDate AND starttime < :dayAfter';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':selectedDate', date_format($selectedDate, 'Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':dayAfter', date_format($dayAfter, 'Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Appointment');

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // function getOne($id)
    // {
    //     try {
    //         $query = "SELECT product.*, category.name as category_name FROM product INNER JOIN category ON product.category_id = category.id WHERE product.id = :id";
    //         $stmt = $this->connection->prepare($query);
    //         $stmt->bindParam(':id', $id);
    //         $stmt->execute();

    //         $stmt->setFetchMode(PDO::FETCH_ASSOC);
    //         $row = $stmt->fetch();
    //         $product = $this->rowToProduct($row);

    //         return $product;
    //     } catch (PDOException $e) {
    //         echo $e;
    //     }
    // }

    function insert($type, $timeslot, $id)
    {
        try {
            $this->validate($timeslot);
            if (empty($this->errors)) {
                $sql = 'INSERT INTO appointments (user_id, timeslot, starttime, endtime, type) VALUES (:user_id, :timeslot, :starttime, :endtime, :type)';
                $stmt = $this->connection->prepare($sql);

                $stmt->bindValue(':user_id', $id, PDO::PARAM_STR);
                $stmt->bindValue(':timeslot', $timeslot->timeslot, PDO::PARAM_STR);
                $stmt->bindValue(':starttime', date_format($timeslot->start, 'Y-m-d H:i:s'), PDO::PARAM_STR);
                $stmt->bindValue(':endtime', date_format($timeslot->end, 'Y-m-d H:i:s'), PDO::PARAM_STR);
                $stmt->bindValue(':type', $type, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $id = $this->connection->lastInsertId();

                    return $this->getOne($id);
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            return $e;
        }
    }

    function getOne($id)
    {
        try {
            $sql = 'SELECT appointments.id, user_id, timeslot, starttime, endtime, usersbasic.name, type FROM appointments INNER JOIN usersbasic ON appointments.user_id = usersbasic.id WHERE appointments.id = :id';

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Appointment');

            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update($id, $type)
    {
        try {
            $sql = 'UPDATE appointments
        SET type = :type
        WHERE id = :id';
            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return $e;
        }
    }

    function delete($id)
    {
        try {
            $sql = 'DELETE FROM appointments WHERE appointments.id = :id';
            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            return $e;
        }
    }

    private function validate($appointment)
    {
        // check taken
        if ($appointment->taken) {
            $this->errors[] = 'Timeslot is already taken';
        }
        // check date past
        if (date_format($appointment->start, 'Y-m-d H:i:s') < date('Y-m-d H:i:s')) {
            $this->errors[] = 'Can not book in the past';
        }
    }
}
