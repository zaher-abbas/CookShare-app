<?php
?>
<main class="flex-grow-1 d-flex justify-content-center align-items-center">
    <section class="hero-section my-3 p-5 container rounded-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Welcome to CookShare &#127869;</h1>
                    <p class="lead my-5">View recipes from all over the world, shared by everyone, you can
                        add yours too!</p>
                    <p class="lead mb-5">Try it...it is Fun!</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="index.php?action=register"
                           class="btn btn-light btn-lg px-4 py-2 me-4 btnHero">Register</a>
                        <a href="index.php?action=login" class="btn btn-outline-light btn-lg px-4 py-2 btnHero">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
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
    <?php endif; ?>