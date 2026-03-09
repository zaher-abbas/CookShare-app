<?php
/** @var array|null $userRecipes */

?>
<main>
    <h1 class="text-center my-5 fw-bold">&#129379; My Recipes</h1>
    <?php if ($userRecipes): ?>
    <div class="container">
        <section class="list-group">
            <?php foreach ($userRecipes as $recipe): ?>
                <div class="list-group-item list-group-item-action p-3 mb-3 d-flex justify-content-between align-items-center cursor-pointer">
                    <div class="w-50">
                        <a class="text-decoration-none text-dark"
                           href="index.php?action=recipe&id=<?= $recipe['id'] ?>">
                            &#129379; <?= $recipe['name'] ?></a>
                    </div>
                    <div>
                        <a href="index.php?action=updaterecipe&id=<?= $recipe['id'] ?>" class="btn btn-warning">Edit</a>
                        <button data-bs-toggle="modal" data-bs-target="#deleteModal"
                                class="btn btn-danger">Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="container">
                <div class="alert alert-info text-center fs-5" role="alert">
                    You did not add any recipes yet!
                    <br><br>
                    <a href="index.php?action=addrecipe" class="btn btn-success">Add Recipe</a>
                </div>
                <?php endif; ?>
        </section>
    </div>
</main>
<div class="modal" tabindex="-1" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this recipe?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="index.php?action=deleterecipe&id=<?= $recipe['id'] ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
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

