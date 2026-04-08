<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\config\MySQL;
use App\Controller\RecipeController;
use App\Controller\UserController;

if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();
}
define('BASE_URL', $_ENV['BASE_URL'] ?? '');
session_start();

$db = MySQL::getConnection();
if ($db) {
    $userController = new UserController($db);
    $recipeController = new RecipeController($db);
    $action = $_GET['action'] ?? 'home';
} else {
    echo "Database connection failed.";
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>CookShare - App</title>
</head>
<body class="bg-dark-subtle d-flex flex-column min-vh-100">

<?php
require_once 'header.php';

switch ($action) {
    case 'register':
        $userController->register();
        break;
    case 'login':
        $userController->login();
        break;
    case 'logout':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $userController->logout();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'addrecipe':
    case 'updaterecipe':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->editRecipe();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'profile':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $userController->profile();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'recipe':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->showRecipeDetails();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'search':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {

            $recipeController->searchRecipeByName();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'order' :
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {

            $recipeController->orderRecipes();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You must be connected to order recipes.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'addtofavorites':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->addToFavorites();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'removefromfavorites':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->removeFromFavorites();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'favorites':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {

            $recipeController->showFavorites();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'userrecipes':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {

            $recipeController->listUserRecipes();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'deleterecipe':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->deleteRecipe();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'managerecipes':
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $recipeController->getAllRecipesAdmin();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'manageusers' :
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $userController->getAllUsers();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }

        break;
    case 'deleteuser':
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $userController->deleteUser();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'deletecomment':
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->deleteComment();
        } else {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
            header('Location: index.php?action=error');
        }
        break;
    case 'error':
        require_once 'error.php';
        break;
    case 'home':
    default:
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            $recipeController->showAllRecipes();
        } else {
            require_once 'welcome.php';
        }
        break;
}
require_once 'footer.php';
?>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
