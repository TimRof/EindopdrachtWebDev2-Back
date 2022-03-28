<?php

namespace Controllers;

use Exception;
use Services\UserService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function create()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $user = $this->createObjectFromPostedJson("Models\\User");
                $errors = $this->service->create($user);

                if ($errors === true) {
                    $this->respond("OK");
                } else {
                    $this->respond($errors);
                }
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
