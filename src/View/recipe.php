<?php
/** @var array|null $recipe */
/** @var array|null $recipeIngredients */
/** @var array|null $comments */
/** @var boolean $isRecipeFavorite */

?>
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container">
        <?php if ($recipe !== null): ?>
            <article class="card my-4 recipe-details bg-sage-light p-3 p-sm-2 p-md-4 p-lg-5 border-light shadow-sm">
                <div class="row g-1">
                    <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center">
                        <?php if ($recipe['image'] !== ''): ?>
                        <img src="<?= BASE_URL . '/img/' . $recipe['image']; ?>" class="img-fluid rounded-start"
                             alt="<?= htmlspecialchars($recipe['name']) ?>">
                        <?php else: ?>
                        <img src="<?= BASE_URL . '/img/' . 'recipe_placeholder.png' ?>" class="img-fluid rounded-start"
                        alt="<?= htmlspecialchars($recipe['name']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-6 col-lg-7">
                        <div class="card-body px-0 px-md-2 px-lg-4">
                            <div class="d-flex justify-content-lg-end justify-content-md-center justify-content-sm-center mb-md-4 mb-sm-4">
                                <?php if (!$isRecipeFavorite): ?>
                                    <a href="index.php?action=addtofavorites&id=<?= $recipe['id'] ?>"
                                       class="btn btn-outline-warning btn-lg my-4 fw-bold btn-favorite">
                                        &#x2B50; Add to Favorites
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?action=removefromfavorites&id=<?= $recipe['id'] ?>"
                                       class="btn btn-outline-danger btn-lg my-4">
                                        ★ Remove from Favorites
                                    </a>
                                <?php endif; ?>
                            </div>
                            <h1 class="card-title"><?= htmlspecialchars($recipe['name']) ?></h1>
                            <span class="badge rounded-pill text-bg-primary p-3 mt-3 fs-6 me-2">&#8987; <?= htmlspecialchars($recipe['duration']) ?> min</span>
                            <?php switch ($recipe['difficulty']) {
                                case 'Easy':
                                    echo '<span class="badge rounded-pill text-bg-success p-3 fs-6">&#127919; Easy</span>';
                                    break;
                                case 'Medium':
                                    echo '<span class="badge rounded-pill text-bg-warning p-3 fs-6">&#127919; Medium</span>';
                                    break;
                                case 'Hard':
                                    echo '<span class="badge rounded-pill text-bg-danger p-3 fs-6">&#127919; Hard</span>';
                                    break;
                            } ?>
                            <br>
                            <h5 class="recipe-author my-4 bg-light text-secondary rounded-3 d-inline-block p-1 p-md-2 p-lg-2">
                                <span>
                                    <span class="me-2">Contributed by</span>
                                    <span>
                                     <span>
                                            <?php if ($recipe['photo']): ?>
                                                <img src="<?= BASE_URL . '/img/' . $recipe['photo']; ?>"
                                                     alt="User Profile Picture" class="rounded-circle profile-img me-0">
                                            <?php else: ?>
                                                <img src="<?= BASE_URL . '/img/' . 'default_user_image.jpg' ?>"
                                                     alt="User Profile Picture" class="rounded-circle profile-img me-0">
                                            <?php endif; ?>
                                         </span>
                                        <span class="me-2">
                                            <?= htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']) ?>
                                        </span>
                                    </span>
                                    <span>on
                                        <time class="badge bg-light text-secondary border">
                                            <?= htmlspecialchars(date('d/m/Y', strtotime($recipe['created_at']))) ?>
                                        </time>
                                    </span>
                                </span>
                            </h5>
                            <br><br>
                            <h2 class="mb-3">&#129379; Ingredients</h2>
                            <?php if ($recipeIngredients): ?>
                                <ul class="list-group border-start border-4 border-info bg-light rounded-3 shadow-sm">
                                    <?php foreach ($recipeIngredients as $ingredient): ?>
                                        <li class="list-group-item list-group-item-action"><?= htmlspecialchars($ingredient) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No ingredients available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="mb-3 ">&#128195; Instructions</h2>
                        <div class="border-start border-4 border-success ps-3 py-3 bg-light rounded-3 shadow-sm">
                        <p class="card-text"><?= htmlspecialchars($recipe['description']) ?></p>
                        </div>
                    </div>
                </div>
            </article>
        <div class="d-flex justify-content-center my-4">
            <a href="index.php?action=home"
               class="btn btn-outline-danger btn-lg">
                Back
            </a>
        </div>
        <div class="card mt-5 mb-4 border-light shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-4">&#128101; Community Reviews</h2>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="card mb-4 border-secondary-subtle bg-light shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                <?php if (isset($comment['author_picture_name'])): ?>
                                    <h5 class="card-title"><img
                                                src="<?= BASE_URL . '/img/' . htmlspecialchars($comment['author_picture_name']) ?>"
                                                alt="User Profile Picture"
                                                class="rounded-circle profile-img me-2"><?= htmlspecialchars($comment["author_name"]) ?>
                                        <span class="fw-lighter fs-6">on</span> <?= $comment["date"] ?></h5>
                                <?php else: ?>
                                    <h5 class="card-title"><img src="<?= BASE_URL . '/img/' . 'default_user_image.jpg'?>"
                                                                alt="User Profile Picture"
                                                                class="rounded-circle profile-img me-2"><?= htmlspecialchars($comment["author_name"]) ?>
                                        <span class="fw-lighter fs-6">on</span> <?= $comment["date"] ?></h5>
                                <?php endif; ?>
                                    <?php if ($comment["author_name"] === $_SESSION['userFirstName'] . ' ' . $_SESSION['userLastName'] || $_SESSION["userRole"] === "admin"): ?>
                                    <div>
                                        <button data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                class="btn btn-outline-danger deleteBtn"><span>&#x274C;</span>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
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
                <?php else: ?>
                <p class="fst-italic">No reviews yet. Be the first to share your review about this recipe!</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="card mt-5 mb-4 border-light shadow-sm">
            <form method="post" action="" class="my-4 p-3 rounded w-90">
                <fieldset>
                    <legend class="mb-3">Rate this recipe and leave a comment:</legend>
                    <div class="mb-4">
                        <select class="form-select" aria-label="Default select example" name="note" required>
                            <option value="" selected disabled>Rate this recipe</option>
                            <option value="1">&#11088;</option>
                            <option value="2">&#11088; &#11088;</option>
                            <option value="3">&#11088; &#11088; &#11088;</option>
                            <option value="4">&#11088; &#11088; &#11088; &#11088;</option>
                            <option value="5">&#11088; &#11088; &#11088; &#11088; &#11088;</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="form-label">Your thoughts</label>
                        <textarea class="form-control" id="comment" name="comment" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </fieldset>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-danger text-center fs-3" role="alert"> Recipe not found. </div>
        <?php endif; ?>
    </section>
</main>
<div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this comment and rating?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= 'index.php?action=deletecomment&commentId=' . $comment['_id'] . '&recipeId=' . $recipe['id'] ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>



