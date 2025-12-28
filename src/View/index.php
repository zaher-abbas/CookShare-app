<?php
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\config\MySQL;
use App\Controller\RecipeController;
use App\Controller\UserController;

$db = MySQL::getConnection();
$userController = new UserController($db);
$recipeController = new RecipeController($db);
$action = $_GET['action'] ?? 'home';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>CookShare - App</title>
</head>
<body class="bg-dark-subtle d-flex flex-column min-vh-100">
</body>
</html>

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
        $userController->logout();
        break;
    case 'addrecipe':
    case 'updaterecipe':
        $recipeController->editRecipe();
        break;
    case 'home':
        $recipeController->showAllRecipes();
        break;
    case 'profile':
        $userController->profile();
        break;
    case 'recipe':
        $recipeController->showRecipeDetails();
        break;
    case 'search':
        $recipeController->searchRecipeByName();
        break;
    case 'addtofavorites':
        $recipeController->addtoFavorites();
        break;
    case 'removefromfavorites':
        $recipeController->removeFromFavorites();
        break;
    case 'favorites':
        $recipeController->showFavorites();
        break;
    case 'userrecipes':
        $recipeController->listUserRecipes();
        break;
    case 'deleterecipe':
        $recipeController->deleteRecipe();
        break;
    default:
        require_once 'dashboard.php';
        break;
}
require_once 'footer.php';
?>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

