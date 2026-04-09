<?php
/** @var array|null $errors */
?>
<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container my-3">
        <h3 class="auth-title text-center alert alert-info w-50 mx-auto">&#128273; Log-in to your account</h3>
        <form class="auth-form p-3 p-lg-5 p-md-4 rounded-3 w-50 mx-auto" action="" method="post">
            <?php
            if (isset($_COOKIE['UserNotFound'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['UserNotFound'] . "</div>";
            }
            if (isset($_COOKIE['WrongPassword'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['WrongPassword'] . "</div>";
            }
            if (!empty($errors))
                foreach ($errors as $error)
                {
                    echo "<div class='form-text alert alert-danger'>" . $error . "</div>";
                }
            ?>
            <div class="mb-4">
                <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required
                       maxlength="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100">Login</button>
            </div>
        </form>
    </section>
</main>


