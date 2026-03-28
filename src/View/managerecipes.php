<?php
/** @var array|null $recipes */
?>
    <main>
        <div class="container">
            <h1 class="text-center my-4 fw-bold alert alert-info">&#128214; Manage All Recipes
        </div>
        <?php if ($recipes): ?>
        <div class="container">
            <section class="list-group">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="list-group-item list-group-item-action p-3 mb-3 d-flex justify-content-between align-items-center cursor-pointer">
                        <div class="w-25">
                            <a class="text-decoration-none text-dark"
                               href="index.php?action=recipe&id=<?= $recipe['id'] ?>">
                                &#129379; <?= $recipe['name'] ?></a>
                        </div>
                        <div class="w-25">
                            <span>Contributed by: &#128100;
                                <?= $recipe['firstname'] . ' ' . $recipe['lastname'] ?>
                            </span>
                        </div>
                        <div>
                            <a href="index.php?action=updaterecipe&id=<?= $recipe['id'] ?>" class="btn btn-warning me-3 editBtn">Edit</a>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    class="btn btn-danger deleteBtn">Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="container">
                    <div class="alert alert-info text-center fs-5" role="alert">
                        No recipes found!
                    </div>
                    <?php endif; ?>
            </section>
        </div>
    </main>
    <div class="modal" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
