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
            $cgu = isset($_POST['cgu']);

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($pwdConfirm)) {
                $errors["empty"] = "Please fill all the fields before submitting!";
            }
            if ($password != $pwdConfirm) {
                $errors["pwdMatch"] = "Passwords do not match!";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Invalid email format!";
            }
            if (strlen($password) < 8) {
                $errors["pwdLength"] = "Password must be at least 8 characters long!";
            }
            if (!$cgu)
            {
                $errors["cgu"] = "You must accept the terms and conditions!";
            }
            if (empty($errors)) {
                $password_crypted = password_hash($password, PASSWORD_BCRYPT);

                try {
                    $this->user->createUser($firstname, $lastname, $password_crypted, $email, 2);

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

                if (isset($errors["pwdLength"])) {
                    setcookie("ErrorPasswordLength", $errors["pwdLength"]);
                    $_COOKIE["ErrorPasswordLength"] = $errors["pwdLength"];
                } else
                    unset($_COOKIE['ErrorPwdNotMatch']);

                if (isset($errors["pwdMatch"])) {
                    setcookie("ErrorPwdNotMatch", $errors["pwdMatch"]);
                    $_COOKIE["ErrorPwdNotMatch"] = $errors["pwdMatch"];
                } else
                    unset($_COOKIE['ErrorPwdNotMatch']);

                if (isset($errors["email"])) {
                    setcookie("ErrorEmail", $errors["email"]);
                    $_COOKIE["ErrorEmail"] = $errors["email"];
                } else
                    unset($_COOKIE['ErrorEmail']);

                if (isset($errors["cgu"])) {
                    setcookie("ErrorCgu", $errors["cgu"]);
                    $_COOKIE["ErrorCgu"] = $errors["cgu"];
                } else
                    unset($_COOKIE['ErrorCgu']);

                $this->persistUserInfo($firstname, $lastname, $email);
            }
            if (empty($errors)) {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'User registered successfully. Please login to continue!'
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
                $_SESSION['connected'] = true;
                $_SESSION['userFirstName'] = $user['firstname'];
                $_SESSION['userLastName'] = $user['lastname'];
                $_SESSION['userPhoto'] = $user['photo'] ?? "default_user_image.jpg";
                $_SESSION['userId'] = $user['id'];
                $_SESSION['userRole'] = $this->user->getUserRoleName($user['id']);
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'You are now logged in!'
                ];
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profilePhoto = $_FILES['profilePhoto'] ?? null;
            if ($profilePhoto && $profilePhoto['error'] == 0) {
                $image_name = 'profile_photo_' . $user['id'];
                $folderName = './../View/img/';
                if (!is_dir($folderName)) {
                    mkdir($folderName, 0775, true);
                }
                move_uploaded_file($profilePhoto['tmp_name'], $folderName . $image_name);
                $this->user->updateUserPhoto($_SESSION['userId'], $image_name);
                $_SESSION['userPhoto'] = $image_name;
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'Profile photo updated successfully.'
                ];
               header('Location: index.php?action=profile');
               exit();
            } else {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Error updating profile photo.'
                ];
            }
        }
                require_once './../View/profile.php';
    }
    public function logout(): void
    {
        setcookie("loggedOut", "true", time() + 5, "/");;
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
            setcookie("lastname", $lastname);
            $_COOKIE["lastname"] = $lastname;
        }
        if (!empty($email)) {
            setcookie("email", $email);
            $_COOKIE["email"] = $email;
        }
    }
    public function getAllUsers(): void
    {
        $users = $this->user->getAllUsers();

        require_once './../View/manageusers.php';
    }

    public function deleteUser(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($_SESSION['userRole'] == 'admin') {
                $this->user->deleteUser($id);
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'User deleted successfully.'
                ];
                header('Location: index.php?action=manageusers');
            }
            else {
                header('Location: index.php?action=error');
            }
        }
    }
}
