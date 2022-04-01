<?php

namespace Services;

use Repositories\UserRepository;

class UserService
{
    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }
    function create($user)
    {
        return $this->repository->create($user);
    }
}
