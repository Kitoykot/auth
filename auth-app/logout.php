<?php

require_once "../vendor/autoload.php";

use AuthApp\Auth;

session_start();
Auth::logout();
header("Location: /login.php");