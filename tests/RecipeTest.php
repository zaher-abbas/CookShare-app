<?php

namespace tests;

use PDO;
use App\Model\Recipe;
use PHPUnit\Framework\TestCase;

class RecipeTest extends TestCase
{
    private PDO $db;
    private Recipe $recipe;

    protected function setUp(): void
    {
        // Connexion à la base de test
        $this->db = new PDO('mysql:host=localhost;dbname=cookshare-app', 'zaher', '6666');
        $this->recipe = new Recipe($this->db);
    }

    public function testCreateRecipe(): void
    {
        $userId = 4;
        $name = 'Test Recipe Create';
        $image = 'test.jpg';
        $duration = 30;
        $difficulty = 'Easy';
        $description = 'Test description';
        $ingredients = 'Ingredient 1, Ingredient 2';

        $this->recipe->createRecipe($userId, $name, $image, $duration, $difficulty, $description, $ingredients);

        $stmt = $this->db->query("SELECT COUNT(*) FROM recipes WHERE name = '$name'");
        $count = $stmt->fetchColumn();

        $this->assertEquals(1, $count);
    }

    public function testGetRecipeById(): void
    {
        $name = 'Test Recipe Get ';
        $image = 'test.jpg';
        $duration = 30;
        $difficulty = 'Easy';
        $description = 'Test description';
        $ingredients = 'Ingredient 1, Ingredient 2';

        // Créer la recette
        $this->recipe->createRecipe(
            4,
            $name,
            $image,
            $duration,
            $difficulty,
            $description,
            $ingredients
        );
        $stmt = $this->db->prepare("SELECT id FROM recipes WHERE name = :name");
        $stmt->execute([':name' => $name]);
        $recipeId = $stmt->fetchColumn();
        $recipe = $this->recipe->getRecipeById($recipeId);

        $this->assertNotNull($recipe);
        $this->assertArrayHasKey('name', $recipe);
        $this->assertArrayHasKey('ingredients', $recipe);
    }

    protected function tearDown(): void
    {

        $stmt = $this->db->prepare("DELETE FROM recipes WHERE name LIKE :name");
        $stmt->execute([':name' => 'Test Recipe%']);
    }
}