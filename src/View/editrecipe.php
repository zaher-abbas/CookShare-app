<?php
/** @var array|null $recipe */

unset($_COOKIE['ErrorAddingRecipe']);
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container my-4">
        <?php if ($action == 'addrecipe'): ?>
            <h3 class="text-center alert alert-light w-75 mx-auto">&#10133; Add New Recipe</h3>
        <?php elseif ($action == 'updaterecipe'): ?>
            <h3 class="text-center alert alert-light w-75 mx-auto">&#9998; Edit Recipe</h3>
        <?php endif; ?>
        <form class="edit-form p-5 w-75 mx-auto rounded" method="post" action="" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="rname" class="form-label">Recipe's Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="rname" name="rname" placeholder="" required maxlength="50"
                       value="<?= $recipe ? htmlspecialchars($recipe['name']) : ''; ?>">
            </div>
            <div class="mb-4">
                <label for="rimage" class="form-label">Recipe's Image <span
                            class="badge rounded-pill bg-secondary ms-1">Optional</span>

                </label>
                <input class="form-control" type="file" id="rimage" name="rimage" accept="image/*"
            </div>
            <div class="my-3 p-2 d-flex flex-column justify-content-center align-items-start">
                <?php if ($recipe && $recipe['image'] != ''): ?>
                    <p class="text-center badge text-bg-success p-2">Current Image</p>
                    <p class="badge text-bg-info fst-italic p-2">If you don't upload a new image, the current one will
                        be
                        kept!</p>
                    <img src="./../View/img/<?= $recipe['image']; ?>" alt="Recipe Image" class="w-25 rounded mb-2">
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="rdescription" class="form-label">Recipe Description <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="rdescription" name="rdescription" rows="8" required>
                    <?= $recipe ? htmlspecialchars(trim($recipe['description'])) : ''; ?>
                </textarea>
            </div>
            <?php
            if (isset($_COOKIE['ErrorAddingRecipe'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['ErrorAddingRecipe'] . "</div>";
                unset($_COOKIE['ErrorAddingRecipe']);
            }
            ?>
            <div class="text-center">
                <?php if ($action == 'addrecipe'): ?>
                    <button type="submit" class="btn btn-success">Add Recipe</button>
                    <a href="index.php?action=home" class="ms-3 btn btn-outline-danger btn-md">Back</a>
                <?php elseif ($action == 'updaterecipe'): ?>
                    <button type="submit" class="btn btn-success">Edit Recipe</button>
                    <a href="index.php?action=userrecipes" class="ms-3 btn btn-outline-danger btn-md">Back</a>
                <?php endif; ?></div>
        </form>
    </section>
</main>