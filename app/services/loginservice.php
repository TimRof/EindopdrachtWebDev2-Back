<?php

namespace Services;

use Repositories\LoginRepository;

class LoginService
{

    private $repository;

    function __construct()
    {
        $this->repository = new LoginRepository();
    }
    function checkEmailPassword($email, $password)
    {
        return $this->repository->checkEmailPassword($email, $password);
    }
}
