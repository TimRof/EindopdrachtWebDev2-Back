<?php

namespace Controllers;

use Exception;
use Services\TypeService;

class TypeController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new TypeService();
    }

    public function getAll()
    {
        $jwt = $this->checkForToken();
        if (!$jwt)
            return;

        $products = $this->service->getAll();

        $this->respond($products);
    }
}
