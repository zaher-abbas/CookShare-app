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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
            $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $pwdConfirm = isset($_POST['pwdConfirm']) ? trim($_POST['pwdConfirm']) : '';
            $cgu = isset($_POST['cgu']);

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($pwdConfirm)) {
                $errors["empty"] = "Error: Please fill all the fields before submitting!";
            }
            if (strlen($firstname) > 20) {
                $errors["firstname"] = "Error: First name must be less than 20 characters!";
            }
            if (strlen($lastname) > 50) {
                $errors["lastname"] = "Error: Last name must be less than 50 characters!";
            }
            if (strlen($email) > 50) {
                $errors["email"] = "Error: Email must be less than 50 characters!";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Error: Invalid email format!";
            }
            if (strlen($password) < 8) {
                $errors["pwdLength"] = "Error: Password must be at least 8 characters long!";
            }
            if ($password != $pwdConfirm) {
                $errors["pwdMatch"] = "Error: Passwords do not match!";
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
                    setcookie("UserAlreadyExists", $e->getMessage(), time() + 5, "/");
                    $_COOKIE["UserAlreadyExists"] = $e->getMessage();
                    $_SESSION['toast'] = [
                        'type' => 'danger',
                        'message' => 'Error: User already exists. Please login instead!'
                    ];
                    $this->persistUserInfo($firstname, $lastname, $email);
                    header('Location: index.php?action=register');
                    exit();
                }
            } else {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            if (empty($email) || empty($password)) {
                $errors["empty"] = "Error: Please fill all the fields before submitting!";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["email"] = "Error: Invalid email format!";
            }
            if (strlen($password) < 8) {
                $errors["pwdLength"] = "Error: Password must be at least 8 characters long!";
            }
            if (empty($errors)) {
                $user = [];
                try {
                    $user = $this->user->getUser($email, $password);

                } catch (UserNotFound $e) {
                    setcookie("UserNotFound", $e->getMessage(), time() + 5, "/");
                    $_COOKIE["UserNotFound"] = $e->getMessage();
                    $_SESSION['toast'] = [
                        'type' => 'danger',
                        'message' => 'Error: User not found. Please register first!'
                    ];
                    header('Location: index.php?action=login');
                    exit();

                } catch (WrongPassword $e) {
                    setcookie("WrongPassword", $e->getMessage(), time() + 5, "/");
                    $_COOKIE["WrongPassword"] = $e->getMessage();
                    $_SESSION['toast'] = [
                        'type' => 'danger',
                        'message' => 'Error: Wrong password. Please try again!'
                    ];
                    header('Location: index.php?action=login');
                    exit();

                }
                if (!empty($user)) {
                    $_SESSION['isConnected'] = true;
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
            $errors = [];
            $profilePhoto = $_FILES['profilePhoto'] ?? null;

            if ($profilePhoto && $profilePhoto['error'] == UPLOAD_ERR_OK) {
                $allowedMimeTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/gif',
                ];
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($profilePhoto['tmp_name']);
                $maxSize = 2 * 1024 * 1024;
                if ($profilePhoto['size'] > $maxSize) {
                    $errors["profilePhotoSize"] = "File too large. Maximum 2MB allowed!";
                }
                if (!in_array($mimeType, $allowedMimeTypes, true)) {
                    $errors["profilePhoto"] = "Please upload a valid image (JPG, PNG, WEBP or GIF).";
                }
                if (empty($errors)) {
                    $image_name = 'profile_photo_' . $user['id'];
                    $uploadDir = dirname(__DIR__, 2) . '/src/View/img/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }
                    move_uploaded_file($profilePhoto['tmp_name'], $uploadDir . $image_name);
                    $this->user->updateUserPhoto($_SESSION['userId'], $image_name);
                    $_SESSION['userPhoto'] = $image_name;
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Profile photo updated successfully.'
                    ];
                    header('Location: index.php?action=profile');
                    exit();
                } else if (isset($errors['profilePhotoSize'])) {
                    $_SESSION['toast'] = [
                        'type' => 'danger',
                        'message' => 'Error uploading the profile photo! Maximum file size is 2MB!'
                    ];
                    header('Location: index.php?action=profile');;
                    exit();
                } else if (isset($errors['profilePhoto'])) {
                    $_SESSION['toast'] = [
                        'type' => 'danger',
                        'message' => 'Error uploading the profile photo! Please upload a valid image (JPG, PNG, WEBP or GIF).'
                    ];
                    header('Location: index.php?action=profile');
                    exit();
                }
            } else {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Error uploading the profile photo!'
                ];
                header('Location: index.php?action=profile');
                exit();
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
            setcookie("firstname", $firstname, time() + 5, "/");
            $_COOKIE["firstname"] = $firstname;
        }
        if (!empty($lastname)) {
            setcookie("lastname", $lastname, time() + 5, "/");
            $_COOKIE["lastname"] = $lastname;
        }
        if (!empty($email)) {
            setcookie("email", $email, time() + 5, "/");
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
            if ($_SESSION['userRole'] === 'admin') {
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
