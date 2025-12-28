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
            $rname = isset($_POST['rname']) ? trim($_POST['rname']) : null;
            $rimage = $_FILES['rimage'] ?? null;
            $rdescription = isset($_POST['rdescription']) ? trim($_POST['rdescription']) : null;

            if ($rname && $rdescription) {

                if ($rimage['error'] == 0) {
                    $image_name = $rimage['name'];
                    $image_name = time() . $image_name;
                    $folderName = './../View/img/';
                    if (!is_dir($folderName)) {
                        mkdir($folderName, 0775, true);
                    }

                    move_uploaded_file($rimage['tmp_name'], $folderName . $image_name);
                } else {
                    $image_name = '';
                }
                if ($action == 'addrecipe') {
                    $this->recipe->createRecipe($_SESSION['userId'], $rname, $image_name, $rdescription);
                    $_SESSION['toast'] = [
                        'type' => 'success',
                        'message' => 'Recipe added successfully.'
                    ];
                } elseif ($action == 'updaterecipe') {
                    if ($image_name != '') {
                        $this->recipe->updateRecipe($id, $rname, $image_name, $rdescription);
                    } else {
                        $this->recipe->updateRecipe($id, $rname, $recipe['image'], $rdescription);
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
        require_once './../View/dashboard.php';
    }

    public function showRecipeDetails(): void
    {
        $id = $_GET['id'] ?? null;
        $isRecipeFavorite = false;
        if ($id) {
            $isRecipeFavorite = $this->recipe->isRecipeInFavorites($id, $_SESSION['userId']);
            $this->comment = new Comment();
            $recipe = $this->recipe->getRecipeById($id);
            $comments = $this->comment->getCommentsByRecipeId($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment = isset($_POST["comment"]) ? trim($_POST['comment']) : null;
                $note = isset($_POST["note"]) ? $_POST['note'] : null;
                if ($comment && $note) {
                    $this->comment->createComment($id, $_SESSION['userName'], $comment, $note);
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


