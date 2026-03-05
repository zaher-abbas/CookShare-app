<?php
/** @var array|null $recipe */
/** @var array|null $recipeIngredients */
/** @var array|null $comments */
/** @var boolean $isRecipeFavorite */

?>
<main class="flex-grow-1">
    <section class="container">
        <?php if ($recipe !== null): ?>
            <div class="card my-4 recipe-details p-3">
                <div class="row g-1">
                    <div class="col-lg-4 col-md-4 d-flex align-items-center">
                        <img src="<?= './../View/img/' . $recipe['image']; ?>" class="img-fluid rounded-start"
                             alt="<?= htmlspecialchars($recipe['name']) ?>">
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="card-body">
                            <div class="d-flex justify-content-lg-end justify-content-md-center justify-content-sm-center mb-md-4 mb-sm-4">
                                <?php if (!$isRecipeFavorite): ?>
                                    <a href="index.php?action=addtofavorites&id=<?= $recipe['id'] ?>"
                                       class="btn btn-warning btn-lg my-4">
                                        ★ Add to Favorites
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?action=removefromfavorites&id=<?= $recipe['id'] ?>"
                                       class="btn btn-danger btn-lg my-4">
                                        ★ Remove from Favorites
                                    </a>
                                <?php endif; ?>
                            </div>
                            <h1 class="card-title"><?= htmlspecialchars($recipe['name']) ?></h1>
                            <h5 class="my-4">
                                <span>
                                    <span>Submitted by</span>
                                    <span>
                                        <span class="badge bg-light text-secondary border">
                                            &#128100; <?= htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']) ?>
                                        </span>
                                    </span>
                                    <span>
                                        on
                                        <time class="badge bg-light text-secondary border">
                                            <?= htmlspecialchars(date('d/m/Y', strtotime($recipe['created_at']))) ?>
                                        </time>
                                    </span>
                                </span>
                            </h5>
                            <br>
                            <h2>&#129379; Ingredients</h2>
                            <?php if ($recipeIngredients): ?>
                            <ul class="list-group">
                                <?php foreach ($recipeIngredients as $ingredient): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($ingredient) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                                <p>No ingredients available.</p>
                            <?php endif; ?>
                            <br>
                            <h2>&#128151; Description</h2>
                            <p class="card-text"><?= htmlspecialchars($recipe['description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-center my-4">
            <a href="index.php?action=home"
               class="btn btn-outline-danger btn-lg">
                Back
            </a>
        </div>
        <form method="post" action="" class="my-4 p-3 rounded">
            <h6 class="mb-4">Rate this recipe and leave a comment:</h6>
            <div class="mb-4">
                <select class="form-select" aria-label="Default select example" name="note" required>
                    <option selected disabled>Rate this recipe</option>
                    <option value="1">&#11088;</option>
                    <option value="2">&#11088; &#11088;</option>
                    <option value="3">&#11088; &#11088; &#11088;</option>
                    <option value="4">&#11088; &#11088; &#11088; &#11088;</option>
                    <option value="5">&#11088; &#11088; &#11088; &#11088; &#11088;</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="comment" class="form-label">Recipe Description</label>
                <textarea class="form-control" id="comment" name="comment" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php if ($comments !== null): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php if (isset($comment['author_picture_name'])): ?>
                        <h5 class="card-title"><img src="./../View/img/<?= $comment['author_picture_name'] ?>" alt="User Profile Picture" class="rounded-circle profile-img me-2"><?= $comment["author_name"] ?> on <?= $comment["date"] ?></h5>
                     <?php else: ?>
                        <h5 class="card-title"><img src="./../View/img/default_user_image.jpg" alt="User Profile Picture" class="rounded-circle profile-img me-2"><?= $comment["author_name"] ?> on <?= $comment["date"] ?></h5>
                        <?php endif; ?>
                        <?php switch ($comment["note"]) {
                            case 1:
                                echo "<p class='card-text'>	&#11088;</p>";
                                break;
                            case 2:
                                echo "<p class='card-text'>	&#11088; &#11088;</p>";
                                break;
                            case 3:
                                echo "<p class='card-text'>	&#11088; &#11088; &#11088;</p>";
                                break;
                            case 4:
                                echo "<p class='card-text'>	&#11088; &#11088; &#11088; &#11088;</p>";
                                break;
                            case 5:
                                echo "<p class='card-text'>	&#11088; &#11088; &#11088; &#11088; &#11088;</p>";
                                break;
                        } ?>
                        <p class="card-text"><?= htmlspecialchars($comment["comment"]) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>
<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo htmlspecialchars($_SESSION['toast']['message'] ?? ''); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "<?php echo ($_SESSION['toast']['type'] ?? 'danger') === 'success' ? '#16a34a' : '#dc3545'; ?>",
                close: true
            }).showToast();
        });
    </script>
    <?php unset($_SESSION['toast']); endif; ?>

