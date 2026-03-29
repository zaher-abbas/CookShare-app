<?php
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\config\MySQL;
use App\Controller\RecipeController;
use App\Controller\UserController;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$db = MySQL::getConnection();
$userController = new UserController($db);
$recipeController = new RecipeController($db);
$action = $_GET['action'] ?? 'home';
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
</body>
</html>

<?php
require_once 'header.php';

switch ($action) {
    case 'home':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $recipeController->showAllRecipes();
        } else {
            require_once 'welcome.php';
        }
        break;
    case 'register':
        $userController->register();
        break;
    case 'login':
        $userController->login();
        break;
    case 'logout':
        $userController->logout();
        break;
    case 'addrecipe':
    case 'updaterecipe':
    if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
        $recipeController->editRecipe();
    } else {
        header('Location: index.php?action=error');
        $_SESSION['toast'] = [
            'type' => 'danger',
            'message' => 'You dont have the permission to access this page.'
        ];
    }
    break;
    case 'profile':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $userController->profile();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'recipe':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $recipeController->showRecipeDetails();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'search':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {

            $recipeController->searchRecipeByName();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'order' :
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {

            $recipeController->orderRecipes();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You must be connected to order recipes.'
            ];
        }
        break;
    case 'addtofavorites':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $recipeController->addtoFavorites();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'removefromfavorites':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $recipeController->removeFromFavorites();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'favorites':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {

            $recipeController->showFavorites();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'userrecipes':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {

            $recipeController->listUserRecipes();
        } else {
            header('Location: index.php?action=error');
        }
        break;
    case 'deleterecipe':
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
            $recipeController->deleteRecipe();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'managerecipes':
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $recipeController->getAllRecipesAdmin();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'manageusers' :
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $userController->getAllUsers();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }

        break;
    case 'deleteuser':
        if (isset($_SESSION['userRole']) && $_SESSION['userRole'] === 'admin') {
            $userController->deleteUser();
        } else {
            header('Location: index.php?action=error');
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'You dont have the permission to access this page.'
            ];
        }
        break;
    case 'error':
        require_once 'error.php';
        break;
    default:
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
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

