<?php

namespace Services;

use Repositories\AppointmentRepository;

class AppointmentService
{

    private $repository;

    function __construct()
    {
        $this->repository = new AppointmentRepository();
    }

    public function getAll($date)
    {
        return $this->repository->getAll($date);
    }
    // for demo purposes only: pagination
    public function getAllv2($offset = NULL, $limit = NULL)
    {
        return $this->repository->getAllv2($offset = NULL, $limit = NULL);
    }
    // for demo purposes only: filtering
    public function getAllv3($query)
    {
        return $this->repository->getAllv3($query);
    }
    public function getAllByDate($selectedDate)
    {
        return $this->repository->getAllByDate($selectedDate);
    }

    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }

    public function insert($type, $timeslot, $id)
    {
        return $this->repository->insert($type, $timeslot, $id);
    }

    public function update($id, $type)
    {
        return $this->repository->update($id, $type);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
