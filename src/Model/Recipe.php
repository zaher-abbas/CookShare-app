<?php

namespace App\Model;

use PDO;

class Recipe
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createRecipe(int $userId, string $name, string $image, int $duration, string $difficulty, string $description, string $ingredients): void
    {
        $query = "INSERT INTO recipes (user_id, name, image, duration, difficulty, description, ingredients) VALUES
                                   (:user_id, :name, :image, :duration, :difficulty, :description, :ingredients)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':image', $image);
        $statement->bindValue(':duration', $duration);
        $statement->bindValue(':difficulty', $difficulty);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':ingredients', $ingredients);
        $statement->execute();
    }

    public function getRecipes(): array|null
    {
        $query = "SELECT recipes.*, firstname, lastname FROM recipes JOIN users u ON u.id = recipes.user_id";
        $statement = $this->db->query($query);
        return $statement->fetchAll() ?? null;
    }

    public function getRecipeById(int $id): array|null
    {
        $query = "SELECT recipes.*, firstname, lastname, photo FROM recipes JOIN users u ON u.id = recipes.user_id WHERE recipes.id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return $statement->fetch();
        }
        else
            return null;
    }

    public function searchRecipeByName(string $query): array|null
    {
        $sql = "SELECT recipes.*, firstname, lastname FROM recipe JOIN users u ON u.id = recipes.user_id WHERE recipes.name LIKE :query";
        $statement = $this->db->prepare($sql);
        $statement->bindValue(':query', '%' . $query . '%');
        $statement->execute();
        return $statement->fetchAll() ?? null;
    }
    public function orderRecipesByNameAscending(): array|null
    {
        $sql = "SELECT recipes.*, firstname, lastname FROM recipes JOIN users u ON u.id = recipes.user_id ORDER BY recipes.name";
        $statement = $this->db->query($sql);
        return $statement->fetchAll() ?? null;
    }
    public function orderRecipesByNameDescending(): array|null
    {
        $sql = "SELECT recipes.*, firstname, lastname FROM recipes JOIN users u ON u.id = recipes.user_id ORDER BY recipes.name DESC";
        $statement = $this->db->query($sql);
        return $statement->fetchAll() ?? null;
    }
    public function orderRecipesByDateNewest(): array|null
    {
        $sql = "SELECT recipes.*, firstname, lastname FROM recipes JOIN users u ON u.id = recipes.user_id ORDER BY recipes.created_at DESC";
        $statement = $this->db->query($sql);
        return $statement->fetchAll() ?? null;
    }
    public function orderRecipesByDateOldest(): array|null
    {
        $sql = "SELECT recipes.*, firstname, lastname FROM recipes JOIN users u ON u.id = recipes.user_id ORDER BY recipes.created_at";
        $statement = $this->db->query($sql);
        return $statement->fetchAll() ?? null;
    }

    public function addRecipeToFavorites(int $recipeId, int $userId): void
    {
        $query = "INSERT INTO favorites (recipe_id, user_id) VALUES (:recipe_id, :user_id)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
    }

    public function removeRecipeFromFavorites(int $recipeId, int $userId): void
    {
        $query = "DELETE FROM favorites WHERE recipe_id = :recipe_id AND user_id = :user_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
    }

    public function isRecipeInFavorites(int $recipeId, int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM favorites WHERE recipe_id = :recipe_id AND user_id = :user_id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':recipe_id', $recipeId);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    public function getFavoritesByUserId(int $userId): array|null
    {
        $query = "SELECT r.*, u.firstname, u.lastname FROM recipes r JOIN favorites f ON f.recipe_id = r.id JOIN `users` u ON u.id = r.user_id WHERE f.user_id = :user_id;";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        return $statement->fetchAll() ?? null;
    }

    public function getRecipesByUserId(int $userId): array|null
    {
        $query = "SELECT r.*, u.firstname, u.lastname FROM recipes r JOIN `users` u ON u.id = r.user_id WHERE r.user_id = :user_id;";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        return $statement->fetchAll() ?? null;
    }

    public function deleteRecipe(int $recipeId): void
    {
        $query = "DELETE FROM recipes WHERE id = :recipeId";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':recipeId', $recipeId);
        $statement->execute();
    }

    public function updateRecipe(int $recipeId, string $name, string $image, int $duration, string $difficulty, string $description, string $ingredients): void
    {
        $query = "UPDATE recipes SET name = :name, image = :image, duration = :duration, difficulty = :difficulty, description = :description, ingredients = :ingredients WHERE id = :recipeId";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':image', $image);
        $statement->bindValue(':duration', $duration);
        $statement->bindValue(':difficulty', $difficulty);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':ingredients', $ingredients);
        $statement->bindValue(':recipeId', $recipeId);
        $statement->execute();
    }
}
