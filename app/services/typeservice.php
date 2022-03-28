<?php

namespace Services;

use Repositories\TypeRepository;

class TypeService
{

    private $repository;

    function __construct()
    {
        $this->repository = new TypeRepository();
    }
    function getAll()
    {
        return $this->repository->getAll();
    }
}
