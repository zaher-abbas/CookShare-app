<main class="flex-grow-1 d-flex align-items-center justify-content-center">
    <section class="container my-4">
        <h3 class="text-center alert alert-info w-75 mx-auto">Log-in to your account</h3>
        <form class="auth-form p-5 rounded-3 w-75 mx-auto" action="" method="post">
            <div class="mb-4">
                <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required
                       maxlength="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
            </div>
            <?php
            if (isset($_COOKIE['UserNotFound'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['UserNotFound'] . "</div>";
                unset($_COOKIE['UserNotFound']);
            }
            ?>
            <div class="mb-4">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <?php
            if (isset($_COOKIE['WrongPassword'])) {
                echo "<div class='form-text alert alert-danger'>" . $_COOKIE['WrongPassword'] . "</div>";
                unset($_COOKIE['WrongPassword']);
            }
            ?>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Log-in</button>
            </div>
        </form>
    </section>
</main>
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

