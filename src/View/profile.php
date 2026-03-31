<?php

/** @var array|null $user */
?>
<main class="flex-grow-1">
    <section class="container my-3">
        <div class="card p-3">
            <div class="card-header text-primary fw-bolder fs-2 mb-3">Profile Info</div>
            <div class="row g-0">
                <div class="col-4">
                    <?php if ($user['photo']): ?>
                        <img src="<?= BASE_URL . '/img/' . $user['photo'] ?>" class="card-img" alt="...">
                    <?php else: ?>
                    <img
                            src="<?= BASE_URL . '/img/' . 'default_user_image.jpg'?>"
                            class="card-img" alt="...">
                    <?php endif; ?>
                    <br>
                    <div class="d-flex justify-content-center mt-3">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="file" name="profilePhoto" accept="image/*" required>
                            <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-outline-primary btn-lg" type="submit">Upload Photo</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body col-8 d-flex flex-column align-items-center justify-content-around">
                    <h3 class="card-title align-self-start">
                        &#128100; <?= htmlspecialchars($user["firstname"]) . " " . htmlspecialchars($user['lastname']) ?></h3>
                    <p class="card-text fs-3 align-self-start">📧 <?= htmlspecialchars($user["email"]) ?> </p>
                    <a href="index.php" class="btn btn-outline-danger btn-lg">Back</a>
                </div>
            </div>
        </div>
    </section>
</main>