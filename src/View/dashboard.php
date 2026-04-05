<?php

/** @var array|null $recipes */
$user = isset($_SESSION['userFirstName']) ? $_SESSION['userFirstName'] : "";
?>
<?php if ($user != ""): ?>
    <main class="flex-grow-1">
        <section class='container my-4'>
            <h4 class="text-center fw-bold mt-5 mb-4">Welcome back <strong
                        class="badge text-bg-success"><?= htmlspecialchars($user) ?></strong>
            </h4>
            <h1 class="text-center mb-5 fw-bold">Discover Our Recipes</h1>
            <form class="d-flex justify-content-center" role="search" method="get" action="index.php">
                <input type="hidden" name="action" value="search"/>
                <div class="input-group input-group-lg" style="max-width: 400px;">
                    <span class="input-group-text">🔍</span>
                    <input
                            class="form-control"
                            name="query"
                            type="search"
                            placeholder="Search recipes..."
                            aria-label="Search recipes"
                    />
                    <button class="btn btn-success" type="submit" title="Search">Go</button>
                </div>
            </form>
            <div class="d-flex justify-content-center">
                <form class="d-flex justify-content-center mt-5" method="get" action="index.php">
                    <input type="hidden" name="action" value="order"/>
                    <select class="form-select" name="orderBy" onchange="this.form.submit()">
                        <option value="" selected disabled>Order recipes by:</option>
                        <option value="nameAZ">Order by name (A-Z)</option>
                        <option value="nameZA">Order by name (Z-A)</option>
                        <option value="dateNewest">Order by date (Newest first)</option>
                        <option value="dateOldest">Order by date (oldest first)</option>
                    </select>
                </form>
            </div>
        </section>
        <?php if ($recipes): ?>
        <section class="container">
            <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-1 g-4 p-2 p-lg-4 p-md-3 justify-content-center">
                <?php foreach ($recipes as $recipe): ?>
                    <article class="col">
                        <div class="<?= (isset($recipe['isFavorite']) && $recipe['isFavorite']) ? 'border border-3 border-warning' : 'border border-secondary-subtle border-start-0 rounded-end border-4' ?> card h-100 recipe-card bg-sage-light text-forest mb-3 p-4">
                            <?php if (isset($recipe['isFavorite']) && ($recipe['isFavorite'] === true)): ?>
                                <h4>&#11088;</h4>
                            <?php else: ?>
                                <h4 class="p-3"></h4>
                            <?php endif; ?>
                            <?php if ($recipe['image'] === ''): ?>
                                <img src="<?= BASE_URL . '/img/' . 'recipe_placeholder.png'?>"
                                     class="card-img-top rounded-start w-100 fixed-img" alt="recipe placeholder image">
                            <?php else: ?>
                                <img src="<?= BASE_URL . '/img/' . $recipe['image'] ?>"
                                     class="card-img-top rounded-start w-100 fixed-img"
                                     alt="Image of recipe <?= htmlspecialchars($recipe['name']) ?>">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($recipe['name']) ?></h5>
                                <div>
                                    <span class="badge rounded-pill text-bg-primary p-2 mt-1 me-2">&#8987; <?= htmlspecialchars($recipe['duration']) ?> min</span>
                                    <?php switch ($recipe['difficulty']) {
                                        case 'Easy':
                                            echo '<span class="badge rounded-pill text-bg-success p-2 mb-3">&#127919; Easy</span>';
                                            break;
                                        case 'Medium':
                                            echo '<span class="badge rounded-pill text-bg-warning p-2 mb-3">&#127919; Medium</span>';
                                            break;
                                        case 'Hard':
                                            echo '<span class="badge rounded-pill text-bg-danger p-2 mb-3">&#127919; Hard</span>';
                                            break;
                                    } ?>
                                </div>
                                <p class="card-text">Contributed by<span
                                            class="badge bg-light text-secondary border fs-6">
                                            &#128105;&#8205;&#127859; <?= htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']) ?>
                                        </span></p>
                                <div class="text-center my-4">
                                    <a href="index.php?action=recipe&id=<?= $recipe['id'] ?>"
                                       class="btn btn-success w-60">Check
                                        this Recipe</a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-body-secondary">Date added
                                    <time class="fs-6"><?= date('d/m/Y', strtotime($recipe['created_at'])) ?></time>
                                </small>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                <?php endif; ?>
    </main>
    <?php if ($recipes == null): ?>
        <main class="flex-grow-1">
            <div class="container">
                <div class="alert alert-info text-center fs-5" role="alert">
                    No recipes found. Be the first to add a recipe!
                    <br><br>
                    <a href="index.php?action=addrecipe" class="btn btn-success">Add a Recipe</a>
                </div>
            </div>
        </main>
    <?php endif; ?>
<?php endif; ?>


