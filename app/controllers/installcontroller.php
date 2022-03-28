<?php

namespace Controllers;

class InstallController extends Controller
{
    function install(){
        require __DIR__ . '../../install.php';
    }
}
