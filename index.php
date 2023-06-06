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
            <h3>Создать учётную запись</h3>

            <form class="mt-5" method="POST" action="/">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" name="name" value="<?=$_COOKIE["name"]?>">
                </div>

                <div class="form-group">
                    <label for="email">Электронная почта</label>
                    <input type="text" class="form-control" name="email" value="<?=$_COOKIE["email"]?>">
                </div>

                <div class="form-group">
                    <label for="phone">Номер телефона</label>
                    <input type="number" class="form-control" name="phone" value="<?=$_COOKIE["phone"]?>">
                </div>

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" name="login" value="<?=$_COOKIE["login"]?>">
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="form-group">
                    <label for="password-confirm">Подтверждение пароля</label>
                    <input type="password" class="form-control" name="password-confirm">
                </div>

                <div>
                    <button name="submit" type="submit" class="btn btn-success">Создать аккаунт</button>
                    <a href="login.php" class="pl-3">Войти</a>
                </div>

                <?php
                    if(!is_null($_POST["submit"]))
                    {
                        $reg = Auth::reg($_POST["name"],$_POST["email"],$_POST["phone"], 
                                         $_POST["login"],$_POST["password"],$_POST["password-confirm"]);
                        if($reg)
                        {
                            setcookie("name", "", time()-3600, "/");
                            setcookie("email", "", time()-3600, "/");
                            setcookie("phone", "", time()-3600, "/");
                            setcookie("login", "", time()-3600, "/");

                            header("Location: /login.php");
                            die();

                        } else {
                        ?>
                            <div class="alert alert-danger mt-4" role="alert">
                                Ошибка при регистрации
                            </div>
                        <?php
                        } 
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