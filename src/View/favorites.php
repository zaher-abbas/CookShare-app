<?php

/** @var array|null $favoriteRecipes */

?>
<main class="flex-grow-1">
    <section class='container my-4'>
        <h1 class="text-center my-5 fw-bold">&#11088; My Favorite Recipes &#11088;</h1>
        <?php if ($favoriteRecipes): ?>
        <div class="container page-favorites">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 p-2 p-md-3 p-lg-4 justify-content-center">
                <?php foreach ($favoriteRecipes as $recipe): ?>
                    <article class="col">
                        <div class="card h-100 recipe-card bg-sage-light text-forest border border-secondary-subtle border-start-0 rounded-end border-4 mb-3 p-4">
                            <img src="<?= BASE_URL . '/img/' . $recipe['image'] ?>"
                                 class="card-img-top rounded-start w-100 fixed-img"
                                 alt="">
                            <div class="card-body d-flex flex-column justify-content-between pb-0">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($recipe['name']) ?></h5>
                                <div>
                                    <span class="badge rounded-pill text-bg-primary fs-6 p-2 mt-1 me-2">&#8987; <?= htmlspecialchars($recipe['duration']) ?> min</span>
                                    <?php switch ($recipe['difficulty']) {
                                        case 'Easy':
                                            echo '<span class="badge rounded-pill text-bg-success fs-6 p-2 mb-3">&#127919; Easy</span>';
                                            break;
                                        case 'Medium':
                                            echo '<span class="badge rounded-pill text-bg-warning fs-6 p-2 mb-3">&#127919; Medium</span>';
                                            break;
                                        case 'Hard':
                                            echo '<span class="badge rounded-pill text-bg-danger fs-6 p-2 mb-3">&#127919; Hard</span>';
                                            break;
                                    } ?>
                                </div>
                                <p class="card-text">Contributed by <span
                                            class="badge bg-light text-secondary border fs-6">
                                            &#128105;&#8205;&#127859; <?= htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']) ?>
                                        </span></p>
                                <div class="text-center my-4">
                                    <a href="index.php?action=recipe&id=<?= $recipe['id'] ?>"
                                       class="btn btn-success w-100">Check
                                        this Recipe!</a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-body-secondary">&#128197; Date added <?= $recipe['created_at'] ?></small>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($favoriteRecipes == null): ?>
                    <div class="container">
                        <div class="alert alert-info text-center fs-5" role="alert">
                            You have no favorite recipes yet!
                            <br><br>
                            <a href="index.php?action=home" class="btn btn-success">Check our recipes</a>
                        </div>
                    </div>
                <?php endif; ?>
    </section>
</main>