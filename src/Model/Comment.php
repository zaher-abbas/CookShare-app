<?php


namespace App\Model;

use App\config\MongoDB;

class Comment
{
    private $dbCollection;
    private const COLLECTION_NAME = 'comment';

    public function __construct()
    {
        $collectionName = self::COLLECTION_NAME;
        $this->dbCollection = MongoDB::getConnection()->$collectionName ?? null;
    }

    public function createComment(int $recipeId, string $authorName, string $authorPictureName, string $comment, int $note): void
    {
        $this->dbCollection->insertOne([
            "recipe_id" => $recipeId,
            "author_name" => $authorName,
            "author_picture_name" => $authorPictureName,
            "comment" => $comment,
            "note" => $note,
            "date" => date("d/m/Y H:i")
        ]);
    }

    public function getCommentsByRecipeId(int $recipeId): array|null
    {
        return $this->dbCollection->find(["recipe_id" => $recipeId])->toArray();
    }
    public function deleteComment(string $id): void
    {
        $this->dbCollection->deleteOne(["_id" => new \MongoDB\BSON\ObjectId($id)]);
    }

}
