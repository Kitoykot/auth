<?php
ini_set("display_errors", false);
require_once __DIR__ . "/vendor/autoload.php";
session_start();

use AuthApp\Connect;
use AuthApp\Auth;

Connect::db();

if(!Auth::check($_SESSION["id"]))
{
    header("Location: /login.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

  <?php require_once "includes/head.php" ?>

<body>
  <div class="container">
    <div class="alert alert-primary" role="alert">
      Welcome, <?= $_SESSION["name"] ?>!
    </div>
    <a href="auth-app/logout.php">Выйти</a>
  </div>
</body>

</html>