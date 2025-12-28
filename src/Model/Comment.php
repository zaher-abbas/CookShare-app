<?php


namespace App\Model;
use App\config\MongoDB;

class Comment
{
  private $dbCollection;
  private const COLLECTION_NAME = 'comment';

  public function __construct() {
      $collectionName = self::COLLECTION_NAME;
      $this->dbCollection = MongoDB::getConnection()->$collectionName ?? null;
  }

  public function createComment(int $recipeId, string $authorName, string $comment, int $note): void {

      $this->dbCollection->insertOne([
          "recipe_id" => $recipeId,
          "author_name" => $authorName,
          "comment" => $comment,
          "note" => $note,
          "date" => date("d-m-Y H:i:s")
      ]);

  }

  public function getCommentsByRecipeId(int $recipeId): array|null {
   return $this->dbCollection->find(["recipe_id" => $recipeId])->toArray();
  }

 }