<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container my-4 text-white">
        <h3 class="text-center alert alert-info w-75 mx-auto">Register New Account</h3>
        <form class="auth-form p-5 rounded-3 w-75 mx-auto" action="" method="post" class="p-3 rounded">
            <div class="mb-4">
                <label for="firstname" class="form-label">First Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="firstname" name="firstname" required maxlength="20"
                       value="<?php echo !isset($_COOKIE['firstname']) ? '' : $_COOKIE['firstname']; ?>">
            </div>
            <div class="mb-4">
                <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="lastname" name="lastname" required maxlength="50"
                       value="<?php echo !isset($_COOKIE['lastname']) ? '' : $_COOKIE['lastname']; ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Email address <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required
                       maxlength="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       value="<?php echo !isset($_COOKIE['email']) ? '' : $_COOKIE['email']; ?>">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
            </div>
            <div class="mb-4">
                <label for="pwdConfirm" class="form-label">Confirm Password <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-control" id="pwdConfirm" name="pwdConfirm" required minlength="8">
            </div>
            <?php
            if (isset($_COOKIE['UserAlreadyExists'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['UserAlreadyExists'] . "</div>";
            }
            if (isset($_COOKIE['ErrorEmptyFields'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['ErrorEmptyFields'] . "</div>";
            }
            if (isset($_COOKIE['ErrorPwdNotMatch'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['ErrorPwdNotMatch'] . "</div>";
            }
            ?>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Register</button>
            </div>
        </form>
    </section>
</main>