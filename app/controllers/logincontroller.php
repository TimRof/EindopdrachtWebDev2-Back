<?php

namespace Controllers;

use Exception;
use Services\LoginService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new LoginService();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $postedUser = $this->createObjectFromPostedJson("Models\\User");
                $user = $this->service->checkEmailPassword($postedUser->email, $postedUser->password);

                if (!$user) {
                    $this->respondWithError(401, "Invalid credentials");
                    return;
                }

                $key = "thisismysecretthingy";
                $tokenExpiration = time() + 5000;
                $payload = array(
                    "iss" => "http://localhost", // issuer = domein naam van website
                    "aud" => "http://localhost", // checker van token
                    "iat" => time(), // issued at
                    "nbf" => time(), // not before
                    "exp" => $tokenExpiration, // not before
                    "data" => array(
                        "id" => $user->id,
                        "email" => $user->name,
                        "admin" => $user->admin
                    )
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                $admin = $user->admin ? true : false;
                $this->respond(["token" => $jwt, "id" => $user->id, "email" => $user->email, "name" => $user->name, "admin" => $admin, "tokenExpiration" => $tokenExpiration]);
            } catch (Exception $e) {
                $this->respondWithError(500, $e->getMessage());
            }
        }
    }
}
