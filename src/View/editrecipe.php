<?php
/** @var array|null $recipe */
/** @var array|null $errors */

$action = isset($_GET['action']) ? $_GET['action'] : '';
?>
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container my-4">
        <?php if ($action == 'addrecipe'): ?>
            <h3 class="text-center alert alert-light w-75 mx-auto">&#10133; Add New Recipe</h3>
        <?php elseif ($action == 'updaterecipe'): ?>
            <h3 class="text-center alert alert-light w-75 mx-auto">&#9998; Edit Recipe</h3>
        <?php endif; ?>
        <form class="edit-form p-3 p-lg-5 p-md-4 rounded-3 w-100 mx-auto" method="post" action="" enctype="multipart/form-data">
            <?php
            if (!empty($errors))
            foreach ($errors as $error)
            {
                echo "<div class='form-text alert alert-danger'>" . $error . "</div>";
            }
            ?>
            <div class="mb-4">
                <label for="rName" class="form-label">Recipe's Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="rName" name="rName" placeholder="" required maxlength="50"
                       value="<?= $recipe ? htmlspecialchars($recipe['name']) : ''; ?>">
            </div>
            <div class="mb-4">
                <label for="rImage" class="form-label">Recipe's Image <span
                            class="badge rounded-pill bg-secondary ms-1">Optional</span>

                </label>
                <input class="form-control" type="file" id="rImage" name="rImage" accept="image/*"
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
                <label for="rDuration" class="form-label">Duration <span class="badge text-bg-info"><em>In minutes</em></span>
                    <span class="text-danger">*</span>
                </label>
                <input class="form-control" type="number" min="1" step="1" id="rDuration" name="rDuration" value="<?= $recipe ? htmlspecialchars($recipe['duration']) : '1'; ?>" required>
            </div>
            <div class="mb-4">
                <label for="rDifficulty" class="form-label">Difficulty <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="rDifficulty" name="rDifficulty" required>
                    <?php if ($recipe && in_array($recipe['difficulty'], ['Easy', 'Medium', 'Hard'])): ?>
                    <?php if ($recipe['difficulty'] == 'Easy'): ?>
                    <option selected value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                    <?php elseif ($recipe['difficulty'] == 'Medium'): ?>
                    <option value="Easy">Easy</option>
                    <option selected value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                    <?php elseif ($recipe['difficulty'] == 'Hard'): ?>
                    <option value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option selected value="Hard">Hard</option>
                    <?php endif; ?>
                    <?php else: ?>
                    <option selected disabled>Choose a difficulty:</option>
                    <option value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="rIngredients" class="form-label">&#129379; Ingredients<span class="text-danger"> *</span>
                </label>
                <br>
                <p class="badge text-bg-info fst-italic p-2">Separate ingredients with a comma
                </p>
                <textarea class="form-control" id="rIngredients" name="rIngredients" rows="8" required><?= $recipe ? htmlspecialchars(trim($recipe['ingredients'])) : ''; ?></textarea>
            </div>
            <div class="mb-4">
                <label for="rDescription" class="form-label">&#128195; Instructions <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="rDescription" name="rDescription" rows="8" required><?= $recipe ? htmlspecialchars(trim($recipe['description'])) : ''; ?></textarea>
            </div>
            <div class="text-center">
                <?php if ($action == 'addrecipe'): ?>
                    <button type="submit" class="btn btn-success w-100">Add Recipe</button>
                    <a href="index.php?action=home" class="ms-3 btn btn-outline-danger btn-md">Back</a>
                <?php elseif ($action == 'updaterecipe'): ?>
                    <button type="submit" class="btn btn-success w-100">Edit Recipe</button>
                    <a href="index.php?action=userrecipes" class="ms-3 btn btn-outline-danger btn-md">Back</a>
                <?php endif; ?></div>
        </form>
    </section>
</main>