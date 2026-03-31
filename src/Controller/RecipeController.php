<?php
namespace App\Controller;

use App\Model\Recipe;
use App\Model\Comment;

class RecipeController
{
    private Recipe $recipe;
    private Comment $comment;

    public function __construct($db)
    {
        $this->recipe = new Recipe($db);
    }

    public function editRecipe(): void
    {
        $recipe = null;
        $id = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;
        if ($action == 'updaterecipe') {
            $recipe = $this->recipe->getRecipeById($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $image_name = '';
            $rName = isset($_POST['rName']) ? trim($_POST['rName']) : null;
            $rImage = $_FILES['rImage'] ?? null;
            $rDuration = isset($_POST['rDuration']) ? trim($_POST['rDuration']) : null;
            $rDifficulty = isset($_POST['rDifficulty']) ? trim($_POST['rDifficulty']) : null;
            $rIngredients = isset($_POST['rIngredients']) ? trim($_POST['rIngredients']) : null;
            $rDescription = isset($_POST['rDescription']) ? trim($_POST['rDescription']) : null;

            $isValidDuration = filter_var($rDuration, FILTER_VALIDATE_INT, [
                "options" => ["min_range" => 1]
            ]);

            if (!$rName)
                $errors["rName"] = "Please enter a recipe name.";
            if (!$isValidDuration)
                $errors["ValidDuration"] = "Please enter a valid duration.";
            if (!$rDifficulty)
                $errors["rDifficulty"] = "Please select a difficulty.";
            if (!$rIngredients)
                $errors["rIngredients"] = "Please enter the recipe ingredients.";
            if (!$rDescription)
                $errors["rDescription"] = "Please enter the recipe instructions.";
            if ($rName && strlen($rName) > 50)
                $errors["rName"] = "Recipe name must be less than 50 characters.";


            if (empty($errors)) {
                if ($rImage && $rImage['error'] === UPLOAD_ERR_OK) {
                    $allowedMimeTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'image/gif',
                    ];
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($rImage['tmp_name']);
                    if (!in_array($mimeType, $allowedMimeTypes, true)) {
                        $errors["rImage"] = "Please upload a valid image (JPG, PNG, WEBP or GIF).";
                    }
                    $maxSize = 2 * 1024 * 1024;
                    if ($rImage['size'] > $maxSize) {
                        $errors["rImageSize"] = "Image File is too large. Maximum 2MB allowed!";
                    }
                    if (empty($errors)) {
                        $image_name = $rImage['name'];
                        $image_name = time() . $image_name;
                        $uploadDir = dirname(__DIR__, 2) . '/src/View/img/';

                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0775, true);
                        }
                        move_uploaded_file($rImage['tmp_name'], $uploadDir . $image_name);
                    }
                } else if ($rImage && ($rImage['error'] === UPLOAD_ERR_INI_SIZE || $rImage['error'] === UPLOAD_ERR_FORM_SIZE)) {
                    $errors["rImageSize"] = "Image File is too large. Maximum 5MB allowed!";
                } else if (!$rImage) {
                    $image_name = '';
                }
                if (empty($errors) && $action == 'addrecipe') {
                    $this->recipe->createRecipe($_SESSION['userId'], $rName, $image_name, $rDuration, $rDifficulty, $rDescription, $rIngredients);
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Recipe added successfully.'
                    ];
                    header('Location: index.php?action=home');
                    exit();
                } elseif (empty($errors) && $action == 'updaterecipe') {
                    if ($image_name != '') {
                        $this->recipe->updateRecipe($id, $rName, $image_name, $rDuration, $rDifficulty, $rDescription, $rIngredients);
                    } else {
                        $this->recipe->updateRecipe($id, $rName, $recipe['image'], $rDuration, $rDifficulty, $rDescription, $rIngredients);
                    }
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Recipe edited successfully.'
                    ];
                    header('Location: index.php?action=userrecipes');
                    exit();
                }
            }
        }
        require_once './../View/editrecipe.php';
    }

    public function showAllRecipes(): void
    {
        $recipes = $this->recipe->getRecipes();

        $userId = $_SESSION['userId'] ?? null;
        if ($userId) {
            $favoriteRecipes = $this->recipe->getFavoritesByUserId($userId);
            foreach ($recipes as $key => $recipe) {
                foreach ($favoriteRecipes as $favoriteRecipe) {
                    if ($recipe['id'] == $favoriteRecipe['id']) {
                        $recipes[$key]['isFavorite'] = true;
                    }
                }
            }
        }
        require_once './../View/dashboard.php';
    }

    public function getAllRecipesAdmin(): void
    {
        $recipes = $this->recipe->getRecipes();
        require_once './../View/managerecipes.php';
    }


    public function showRecipeDetails(): void
    {
        $id = $_GET['id'] ?? null;
        $isRecipeFavorite = false;
        $recipeIngredients = [];
        $alreadyRated = false;
        if ($id) {
            $isRecipeFavorite = $this->recipe->isRecipeInFavorites($id, $_SESSION['userId']);
            $this->comment = new Comment();
            $recipe = $this->recipe->getRecipeById($id);
            if ($recipe !== null) {
                if ($recipe['ingredients'] != null)
                    $recipeIngredients = array_filter(
                        array_map('trim', explode(",", $recipe['ingredients'])),
                        fn($item) => !empty($item)
                    );
                $comments = $this->comment->getCommentsByRecipeId($id);
                if ($comments) {
                    foreach ($comments as $key => $comment) {
                        if ($comment['author_name'] === $_SESSION['userFirstName'] . ' ' . $_SESSION['userLastName']) {
                            $alreadyRated = true;
                            break;
                        }
                    }
                }
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $comment = isset($_POST["comment"]) ? trim($_POST['comment']) : null;
                    $note = isset($_POST["note"]) ? $_POST['note'] : null;

                    if ($comment && $note && !$alreadyRated) {
                        $fullUserName = $_SESSION['userFirstName'] . ' ' . $_SESSION['userLastName'];
                        $this->comment->createComment($id, $fullUserName, $_SESSION['userPhoto'], $comment, $note);
                        $_SESSION['toast'] = [
                            'type' => 'success',
                            'message' => 'Your comment is added successfully.'
                        ];
                        header('Location: index.php?action=recipe&id=' . $id);
                        exit();
                    }
                    else if ($alreadyRated) {
                        $_SESSION['toast'] = [
                            'type' => 'danger',
                            'message' => 'Error adding comment. You already rated this recipe.'
                        ];
                        header('Location: index.php?action=recipe&id=' . $id);
                        exit();
                    } else {
                        $_SESSION['toast'] = [
                            'type' => 'danger',
                            'message' => 'Error adding comment. Please try again.'
                        ];
                        header('Location: index.php?action=recipe&id=' . $id);
                        exit();
                    }
                }
            }
            require_once './../View/recipe.php';
        }
    }

    public function searchRecipeByName(): void
    {
        $query = isset($_GET['query']) ? trim($_GET['query']) : null;
        $recipes = [];
        if ($query) {
            $recipes = $this->recipe->searchRecipeByName($query);
        }
        require_once './../View/dashboard.php';
    }

    public function orderRecipes(): void
    {
        $order = isset($_GET['orderBy']) ? trim($_GET['orderBy']) : null;
        $recipes = [];
        if (!$order) {
            $recipes = $this->recipe->getRecipes();
        }
        else {
            switch ($order) {
                case 'nameAZ':
                    $recipes = $this->recipe->orderRecipesByNameAscending();
                    break;
                case 'nameZA':
                     $recipes = $this->recipe->orderRecipesByNameDescending();
                     break;
                case 'dateNewest' :
                    $recipes = $this->recipe->orderRecipesByDateNewest();
                    break;
                case 'dateOldest' :
                    $recipes = $this->recipe->orderRecipesByDateOldest();
                    break;
            }
        }
    require_once './../View/dashboard.php';
    }

    public function addtoFavorites(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->recipe->addRecipeToFavorites($id, $_SESSION['userId']);
            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Recipe added to favorites successfully.'
            ];
            header('Location: index.php?action=recipe&id=' . $id);
        }
    }

    public function removeFromFavorites(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->recipe->removeRecipeFromFavorites($id, $_SESSION['userId']);
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Recipe removed from favorites successfully.'
            ];
            header('Location: index.php?action=recipe&id=' . $id);
        }
    }

    public function showFavorites(): void
    {
        $favoriteRecipes = $this->recipe->getFavoritesByUserId($_SESSION['userId']);
        require_once './../View/favorites.php';
    }

    public function listUserRecipes(): void
    {
        $userRecipes = $this->recipe->getRecipesByUserId($_SESSION['userId']);
        require_once './../View/userrecipes.php';
    }

    public function deleteRecipe(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $recipe = $this->recipe->getRecipeById($id);
            if (($recipe['user_id'] != $_SESSION['userId']) && ($_SESSION['userRole'] != 'admin')) {
                header('Location: index.php?action=error');
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'You dont have the permission to access this page.'
                ];
                exit();
            }
            else
            $this->recipe->deleteRecipe($id);
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Recipe was deleted successfully.'
            ];
            header('Location: index.php?action=userrecipes');
        }
    }
}


