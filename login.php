<?php
ini_set("display_errors", false);
require_once __DIR__ . "/vendor/autoload.php";
session_start();

use AuthApp\Connect;
use AuthApp\Auth;

Connect::db();

if(Auth::check($_SESSION["id"]))
{
    header("Location: /ok.php");
    die();

}
?>

<!DOCTYPE html>
<html lang="en">

    <?php require_once "includes/head.php" ?>

<body>
    <main>
        <div class="container mt-5">
            <h3>Войти</h3>

            <form class="mt-5" method="POST" action="login.php">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" name="login" value="<?=$_COOKIE["login"]?>">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div>
                    <button type="submit" name="submit" class="btn btn-success">Войти</button>
                    <a href="/" class="pl-3">Регистрация</a>
                </div>
                <?php
                    if(!is_null($_POST["submit"]))
                    {
                        Auth::login($_POST["login"], $_POST["password"]);
                    }

                    if($_SESSION["error_message"])
                    {
                    ?>
                        <div class="alert alert-danger mt-4" role="alert">
                            <?= $_SESSION["error_message"] ?>
                        </div>
                    <?php
                        unset($_SESSION["error_message"]);
                    }
                ?>
            </form>
        </div>
    </main>
</body>

</html>