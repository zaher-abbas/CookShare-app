<?php
session_start();
$user = isset($_SESSION['userFirstName']) ? $_SESSION['userFirstName'] : "Guest";
$connected = $user != "Guest";
?>
<?php if (!empty($_SESSION['toast'])): ?>
    <?php
    $toastMsg = is_array($_SESSION['toast']) ? ($_SESSION['toast']['message'] ?? '') : $_SESSION['toast'];
    $toastType = is_array($_SESSION['toast']) ? ($_SESSION['toast']['type'] ?? 'success') : 'success';

    $bgStyle = $toastType === 'danger'
        ? '#a12525'
        : '#0c818a';
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Toastify({
                text: "<?= addslashes($_SESSION['toast']['message'] ?? $_SESSION['toast']) ?>",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "<?= $bgStyle ?>",
                }
            }).showToast();
        });
    </script>
    <?php unset($_SESSION['toast']); // Clear it so it doesn't show on refresh ?>
<?php endif; ?>
<header>
    <nav class="navbar navbar-expand-md p-4 text-light">
        <div class="container-fluid  d-flex flex-column flex-md-row align-items-center justify-content-md-between">
            <a class="navbar-brand text-light fs-2 logo" href="index.php">&#127869; CookShare</a>
            <button class="navbar-toggler mt-2 mb-2 mt-md-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse w-100" id="navbarSupportedContent">
                <?php
                if ($connected && $_SESSION['userRole'] === 'user') {
                    echo "<ul class='navbar-nav me-auto mb-2 mb-lg-0 mb-md-0'>
                        <li class='nav-item me-lg-3 mb-sm-2 mb-lg-0 mb-md-0'>
                            <a class='btn btn-success btn-outline-dark btn-md rounded-2 p-2 w-100 fw-bold' aria-current='page' href='index.php?action=home'>&#127968; Home</a>
                        </li>
                        <li class='nav-item'>
                            <a class='btn btn-primary btn-outline-dark btn-md rounded-2 p-2 w-100 fw-bold' href='index.php?action=addrecipe'>➕ Add Recipe</a>
                        </li>
                        <li class='nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0'>
                        <a class='btn btn-warning btn-outline-dark btn-md rounded-2 p-2 w-100 fw-bold' href='index.php?action=favorites'>&#11088; My Favorites</a>
                        </li>
                          <li class='nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0'>
                        <a class='btn btn-info btn-outline-dark btn-md rounded-2 p-2 w-100 fw-bold' href='index.php?action=userrecipes'>&#129379; My Recipes</a>
                        </li>
                    </ul>";
                }
                ?>
                <?php if ($connected && $_SESSION['userRole'] === 'admin'): ?>
                    <ul class='navbar-nav me-auto mb-2 mb-lg-0 mb-md-0'>
                        <li class="nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0 dropdown">
                            <a class="nav-link dropdown-toggle bg-primary-subtle btn btn-outline-info btn-md p-2 rounded-2 mt-0 w-100 fw-bold" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                Admin Panel
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item btn btn-outline-info" href="index.php?action=manageusers">&#128101; Manage Users</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item btn btn-outline-info" href="index.php?action=managerecipes">&#128214; Manage Recipes</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif; ?>
                <span class="d-lg-flex d-xl-flex align-items-center justify-content-evenly gap-4">
                    <?php if (!$connected): ?>
                        <ul class='navbar-nav mb-2 mb-lg-0 mb-md-0'>
                          <li class='nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0'>
                                <a class='btn btn-primary btn-outline-light btn-md p-2 rounded-2 fw-bold mt-0 w-100' href='index.php?action=register'>&#128221; Register</a>
                                </li>
                          <li class='nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0'>
                                <a class='btn btn-success btn-outline-light btn-md p-2 rounded-2 fw-bold mt-0 w-100' href='index.php?action=login'>&#128273; Login</a>
                                </li>
                        </ul>
                </ul>
                    <?php else: ?>
                        <ul class='navbar-nav mb-2 mb-lg-0 mb-md-0 d-flex justify-content-center align-items-center'>
                            <li class='nav-item mt-sm-2 mt-lg-0 mt-md-0'>
                      <a href='index.php?action=profile' class='btn btn-outline-primary text-light btn-md border-0 p-2 text-decoration-none mt-0 w-100 fw-bold'>
                            <img src="./../View/img/<?= $_SESSION['userPhoto'] ?>" class="rounded-circle profile-img me-2"
                                 alt="User Profile Picture">
                          <?= $user ?></a>
                      </li>
                         <li class='nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0'>
                                <a class='btn btn-outline-danger btn-md p-2 rounded-2 mt-0 w-100 fw-bold' href='index.php?action=logout'>Log-out ↪</a>
                        </li>
                        </ul>
                    <?php endif;?>
                </span>
            </div>
        </div>
    </nav>
</header>