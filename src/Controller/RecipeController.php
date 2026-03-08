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
            $rName = isset($_POST['rName']) ? trim($_POST['rName']) : null;
            $rImage = $_FILES['rImage'] ?? null;
            $rDescription = isset($_POST['rDescription']) ? trim($_POST['rDescription']) : null;
            $rIngredients = isset($_POST['rIngredients']) ? trim($_POST['rIngredients']) : null;
            if ($rName && $rDescription && $rIngredients) {

                if ($rImage['error'] == 0) {
                    $image_name = $rImage['name'];
                    $image_name = time() . $image_name;
                    $folderName = './../View/img/';
                    if (!is_dir($folderName)) {
                        mkdir($folderName, 0775, true);
                    }

                    move_uploaded_file($rImage['tmp_name'], $folderName . $image_name);
                } else {
                    $image_name = '';
                }
                if ($action == 'addrecipe') {
                    $this->recipe->createRecipe($_SESSION['userId'], $rName, $image_name, $rDescription, $rIngredients);
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Recipe added successfully.'
                    ];
                } elseif ($action == 'updaterecipe') {
                    if ($image_name != '') {
                        $this->recipe->updateRecipe($id, $rName, $image_name, $rDescription, $rIngredients);
                    } else {
                        $this->recipe->updateRecipe($id, $rName, $recipe['image'], $rDescription, $rIngredients);
                    }
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Recipe edited successfully.'
                    ];
                }
            }
            if ($action == 'addrecipe') {
                header('Location: index.php?action=home');
            } elseif ($action == 'updaterecipe') {
                header('Location: index.php?action=userrecipes');
            }
        } else {
            setcookie("ErrorAddingRecipe", "Error; Please fill all the required fields before submitting!");
            $_COOKIE["ErrorAddingRecipe"] = "Error; Please fill all the required fields before submitting!";
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

    public function showRecipeDetails(): void
    {
        $id = $_GET['id'] ?? null;
        $isRecipeFavorite = false;
        $recipeIngredients = [];
        if ($id) {
            $isRecipeFavorite = $this->recipe->isRecipeInFavorites($id, $_SESSION['userId']);
            $this->comment = new Comment();
            $recipe = $this->recipe->getRecipeById($id);
            if ($recipe['ingredients'] != null)
                $recipeIngredients = array_filter(
                    array_map('trim', explode(",", $recipe['ingredients'])),
                    fn($item) => !empty($item)
                );
            $comments = $this->comment->getCommentsByRecipeId($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment = isset($_POST["comment"]) ? trim($_POST['comment']) : null;
                $note = isset($_POST["note"]) ? $_POST['note'] : null;
                if ($comment && $note) {
                    $this->comment->createComment($id, $_SESSION['userName'], $_SESSION['userPhoto'], $comment, $note);
                    header('Location: index.php?action=recipe&id=' . $id);
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
            $this->recipe->deleteRecipe($id);
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Recipe was deleted successfully.'
            ];
            header('Location: index.php?action=userrecipes');
        }

    }
}


