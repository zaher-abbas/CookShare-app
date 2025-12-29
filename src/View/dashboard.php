<?php

/** @var array|null $recipes */
$user = isset($_SESSION['userName']) ? $_SESSION['userName'] : "";
?>
    <?php if ($user != ""): ?>
<main class="flex-grow-1">
    <section class='container my-4'>
        <h4 class="text-center fw-bold mt-5 mb-3">Welcome back <strong
                    class="badge text-bg-success">&#128100; <?= htmlspecialchars($user) ?></strong>
        </h4>
        <h2 class="text-center mb-5 fw-bold">Discover Our Recipes - <span class="badge text-bg-success">🍽️</span>
            <span class="badge text-bg-warning">🍹</span></h2>
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
    </section>
    <?php if ($recipes): ?>
    <section class="container">
        <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-1 g-4 p-4 justify-content-center">
            <?php foreach ($recipes as $recipe): ?>
                <div class="col">
                    <div class="card h-100 recipe-card bg-sage-light text-forest border border-secondary-subtle border-start-0 rounded-end border-4 mb-3 p-4">
                        <?php if ($recipe['image'] === ''): ?>
                            <img src="./../View/img/recipe_placeholder.png"
                                 class="card-img-top rounded-start w-100 fixed-img" alt="recipe image placeholder">
                        <?php else: ?>
                            <img src="./../View/img/<?= $recipe['image'] ?>"
                                 class="card-img-top rounded-start w-100 fixed-img"
                                 alt="Image of recipe <?= htmlspecialchars($recipe['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($recipe['name']) ?></h5>
                            <p class="card-text">Submitted by <span class="badge bg-light text-secondary border">
                                            &#128100; <?= htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']) ?>
                                        </span></p>
                            <div class="text-center my-4">
                                <a href="index.php?action=recipe&id=<?= $recipe['id'] ?>" class="btn btn-success w-50">Check
                                    this Recipe</a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-body-secondary">Submitted on
                                <time class="fs-6"><?= date('d/m/Y', strtotime($recipe['created_at'])) ?></time>
                            </small>
                        </div>
                    </div>
                </div>
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
            <?php else: ?>
            <main class="flex-grow-1 d-flex justify-content-center align-items-center">
            <section class="hero-section my-3 p-5 container rounded-5">
                    <div class="container">
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-8">
                                <h1 class="display-4 fw-bold mb-4">Welcome to CookShare &#129379;</h1>
                                <p class="lead my-5">View recipes from all over the world, shared by everyone, you can
                                    add yours too!</p>
                                <p class="lead mb-5">Try it...it is Fun!</p>
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    <a href="index.php?action=register"
                                       class="btn btn-light btn-lg px-4 py-2">Register</a>
                                    <a href="index.php?action=login" class="btn btn-outline-light btn-lg px-4 py-2">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
            <?php endif; ?>
<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo htmlspecialchars($_SESSION['toast']['message'] ?? ''); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "<?php echo ($_SESSION['toast']['type'] ?? 'info') === 'success' ? '#16a34a' : '#2563eb'; ?>",
                close: true
            }).showToast();
        });
    </script>
    <?php unset($_SESSION['toast']); endif; ?>
<?php if (isset($_COOKIE['loggedOut']) && $_COOKIE['loggedOut'] === 'true'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo htmlspecialchars('You have been logged out successfully.'); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#eb253c",
                close: true
            }).showToast();
        });
    </script>
    <?php setcookie('loggedOut', 'false'); endif; ?>

