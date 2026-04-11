<?php
?>
<main class="flex-grow-1 d-flex justify-content-center align-items-center">
    <div class="row justify-content-center text-center my-3">
        <div class="col-11 col-sm-11 col-md-8 col-lg-8 col-xl-10">
            <section class="hero-section p-3 mx-2 my-2 p-lg-5 p-md-3 p-sm-4 rounded-5">
                <h1 class="display-2 fw-bold mb-4">Welcome to CookShare &#127869;</h1>
                <p class="lead my-5">Discover tasty recipes 🥗 from kitchens around the world 🌍, share your signature
                    dishes 👨‍🍳, and turn every meal into something unforgettable ✨
                </p>
                <p class="lead mb-5">Try it...it is Fun!</p>
                <div class="d-flex gap-3 justify-content-center align-items-center flex-wrap">
                    <a href="index.php?action=register"
                       class="btn btn-light btn-lg px-4 py-2 btnHero">Register</a>
                    <a href="index.php?action=login"
                       class="btn btn-outline-light btn-lg px-4 py-2 btnHero">Login</a>
                </div>
            </section>
        </div>
    </div>
</main>
<?php if (isset($_COOKIE['loggedOut']) && $_COOKIE['loggedOut'] === 'true'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo 'You have been logged out successfully.'; ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
                close: true
            }).showToast();
        });
    </script>
    <?php endif; ?>