<?php

namespace AuthApp;
session_start();

class Auth extends Connect
{
    public static function reg($name, $email, $phone, $login, $password, $password_confirm)
    {
        $name = htmlspecialchars($name);
        $name = trim($name);
        $name = preg_replace("#\s{2,}#", " ", $name);

        $email = htmlspecialchars($email);
        $email = trim($email);
        $email = str_replace(" ", "", $email);

        $phone = htmlspecialchars($phone);
        $phone = trim($phone);
        $phone = str_replace(" ", "", $phone);

        $login = htmlspecialchars($login);
        $login = trim($login);
        $login = str_replace(" ", "", $login);

        $password = htmlspecialchars($password);
        $password = trim($password);
        $password = str_replace(" ", "", $password);

        $password_confirm = htmlspecialchars($password_confirm);
        $password_confirm = trim($password_confirm);
        $password_confirm = str_replace(" ", "", $password_confirm);

        setcookie("name", $name, time()+9999, "/");
        setcookie("email", $email, time()+9999, "/");
        setcookie("phone", $phone, time()+9999, "/");
        setcookie("login", $login, time()+9999, "/");

        if( $name === "" || $email === "" || $phone === "" ||
            $login === "" || $password === "" || $password_confirm === "")
            {
                $_SESSION["error_message"] = "Все поля обязательны для заполнения";
                header("Location: /");
                die();
            }
        
        if($password !== $password_confirm)
        {
            $_SESSION["error_message"] = "Пароли не совпадают";
            header("Location: /");
            die();
        }

        $sql_find = "SELECT * FROM `users`";
        $users = mysqli_query(Connect::db(), $sql_find);
        
        while($user = mysqli_fetch_assoc($users))
        {
            if($email == $user["email"])
            {
                $_SESSION["error_message"] = "Пользователь с таким email уже существует";
                header("Location: /");
                die(); 
            }
    
            if($phone == $user["phone"])
            {
                $_SESSION["error_message"] = "Пользователь с таким телефоном уже существует";
                header("Location: /");
                die(); 
            }
    
            if($login == $user["login"])
            {
                $_SESSION["error_message"] = "Пользователь с таким логином уже существует";
                header("Location: /");
                die(); 
            }
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users` (`name`, `email`, `phone`, `login`, `password`) 
                VALUES ('$name', '$email', '$phone', '$login', '$hash')";
        $query = mysqli_query(self::db(), $sql);

        return $query ? true : false;
    }

    public static function login($login, $password)
    {
        $login = htmlspecialchars($login);
        $password = htmlspecialchars($password);

        if($login === "" || $password === "")
        {
            $_SESSION["error_message"] = "Неверный логин или пароль";
            header("Location: /login.php");
            die();
        }

        $sql_find = "SELECT * FROM `users` WHERE `login` = '$login'";
        $find_user = mysqli_query(self::db(), $sql_find);

        if(mysqli_num_rows($find_user) == 1)
        {
            $user = mysqli_fetch_assoc($find_user);
            
            if(password_verify($password, $user["password"]))
            {
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["login"] = $user["login"];

                header("Location: /ok.php");
                die();

            } else {

                $_SESSION["error_message"] = "Неверный логин или пароль";
                header("Location: /login.php");
                die();
            }

        } else {

            $_SESSION["error_message"] = "Пользователь не найден";
            header("Location: /login.php");
            die();
        }
    }

    public static function logout()
    {
        unset($_SESSION["id"]);
        unset($_SESSION["login"]);
        unset($_SESSION["name"]);
        unset($_SESSION);
        session_destroy();
    }

    public static function check($id)
    {
        $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
        $query = mysqli_query(self::db(), $sql);

        return mysqli_num_rows($query) > 0 ? true : false;
    }
}