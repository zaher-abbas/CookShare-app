<?php

namespace App\Controller;

use App\Exception\UserAlreadyExists;
use App\Exception\UserNotFound;
use App\Exception\WrongPassword;
use App\Model\User;
class UserController
{
    private User $user;

    public function __construct($db)
    {
        $this->user = new User($db);
    }

    public function register(): void
    {
        unset($_COOKIE['UserAlreadyExists']);
        unset($_COOKIE['ErrorEmptyFields']);
        unset($_COOKIE['ErrorPwdNotMatch']);
        unset($_COOKIE['firstname']);
        unset($_COOKIE['lastname']);
        unset($_COOKIE['email']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
            $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $pwdConfirm = isset($_POST['pwdConfirm']) ? trim($_POST['pwdConfirm']) : '';

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($pwdConfirm)) {
                $errors["empty"] = "Please fill all the fields before submitting!";
            }
            if ($password != $pwdConfirm) {
                $errors["pwd"] = "Passwords do not match!";
            }
            if (empty($errors)) {
                $password_crypted = password_hash($password, PASSWORD_BCRYPT);

                try {
                    $this->user->createUser($firstname, $lastname, $password_crypted, $email);

                } catch (UserAlreadyExists $e) {
                    $errors["UserAlreadyExists"] = $e->getMessage();
                    setcookie("UserAlreadyExists", $e->getMessage());
                    $_COOKIE["UserAlreadyExists"] = $e->getMessage();
                    $this->persistUserInfo($firstname, $lastname, $email);
                }
            } else {
                if (isset($errors["empty"])) {
                    setcookie("ErrorEmptyFields", $errors["empty"]);
                    $_COOKIE["ErrorEmptyFields"] = $errors["empty"];
                } else
                    unset($_COOKIE['ErrorEmptyFields']);
                if (isset($errors["pwd"])) {
                    setcookie("ErrorPwdNotMatch", $errors["pwd"]);
                    $_COOKIE["ErrorPwdNotMatch"] = $errors["pwd"];
                } else
                    unset($_COOKIE['ErrorPwdNotMatch']);
                $this->persistUserInfo($firstname, $lastname, $email);
            }
            if (empty($errors)) {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'User registered successfully. Please login.'
                ];
                header('Location: index.php?action=login');
                exit();
            }
        }
        require_once './../View/register.php';
    }

    public function login(): void
    {
        unset($_COOKIE['UserNotFound']);
        unset($_COOKIE['WrongPassword']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $user = [];
            try {
                $user = $this->user->getUser($email, $password);

            } catch (UserNotFound $e) {
                setcookie("UserNotFound", $e->getMessage());
                $_COOKIE["UserNotFound"] = $e->getMessage();

            } catch (WrongPassword $e) {
                setcookie("WrongPassword", $e->getMessage());
                $_COOKIE["WrongPassword"] = $e->getMessage();

            }
            if (!empty($user)) {
                $_SESSION['userName'] = $user['firstname'];
                $_SESSION['userId'] = $user['id'];
                header('Location: index.php?action=home');
                exit();
            }
        }
        require_once './../View/login.php';
    }

    public function profile(): void
    {
        $user = [];
        try {
            if (isset($_SESSION['userId'])) {
                $user = $this->user->getUserById($_SESSION['userId']);
            } else {
                header('Location: index.php?action=login');
            }
        } catch (UserNotFound $e) {
            header('Location: index.php?action=login');
        }
        require_once './../View/profile.php';
    }

    public function logout(): void
    {
        setcookie("loggedOut", "true");
        session_destroy();
        Header('Location: index.php?action=home');
    }

    public function persistUserInfo(string $firstname, string $lastname, string $email): void
    {
        if (!empty($firstname)) {
            setcookie("firstname", $firstname);
            $_COOKIE["firstname"] = $firstname;
        }
        if (!empty($lastname)) {
            setcookie("lastname", $lastname);;
            $_COOKIE["lastname"] = $lastname;
        }
        if (!empty($email)) {
            setcookie("email", $email);
            $_COOKIE["email"] = $email;
        }
    }
}