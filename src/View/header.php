<?php
$user = isset($_SESSION['userFirstName']) ? $_SESSION['userFirstName'] : "Guest";
$isConnected = isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true;
?>
<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo $_SESSION['toast']['message'] ?? ''; ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "<?php echo ($_SESSION['toast']['type']) === 'success' ? '#16a34a' : '#dc3545'; ?>",
                close: true
            }).showToast();
        });
    </script>
    <?php unset($_SESSION['toast']); endif; ?>
<header>
    <nav class="navbar navbar-expand-lg p-2 p-md-3 p-lg-4 text-light">
        <div class="container-fluid  d-flex flex-column flex-md-row align-items-center justify-content-md-between">
            <a class="navbar-brand text-light fs-1 fw-bold logo" href="index.php"><span class="logo-icon">🍽️</span> CookShare</a>
            <button class="navbar-toggler fw-bold mt-2 mb-3 mt-md-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse w-100" id="navbarSupportedContent">
                <?php
                if ($isConnected && $_SESSION['userRole'] === 'user') {
                    echo "<ul class='navbar-nav me-auto mb-2 mb-lg-0 mb-md-0'>
                        <li class='nav-item me-lg-3 mb-2 mb-md-2 mb-lg-0'>
                            <a class='btn btn-outline-success border-bottom border-3 text-light btn-md rounded-bottom-5 p-2 w-100 text-center' href='index.php?action=home'>&#127968; Home</a>
                        </li>
                        <div class='w-50 divider'>
                        <hr>
                        </div>
                        <li class='nav-item mb-2 mb-lg-0 mb-md-2'>
                            <a class='btn btn-outline-primary border-bottom border-3 text-light btn-md rounded-bottom-5 p-2 w-100 text-center' href='index.php?action=addrecipe'>&#10133; Add Recipe</a>
                        </li>
                         <div class='w-50 divider'>
                        <hr>
                        </div>
                        <li class='nav-item ms-lg-3 mb-2 mb-lg-0 mb-md-2'>
                        <a class='btn btn-outline-warning border-bottom border-3 text-light btn-md rounded-bottom-5 p-2 w-100 text-center' href='index.php?action=favorites'>&#11088; My Favorites</a>
                        </li>
                         <div class='w-50 divider'>
                        <hr>
                        </div>
                          <li class='nav-item ms-lg-3 mb-2 mb-lg-0 mmb-md-2'>
                        <a class='btn btn-outline-info border-bottom border-3 text-light btn-md rounded-bottom-5 p-2 w-100 text-center' href='index.php?action=userrecipes'>&#128105;&#8205;&#127859; My Recipes</a>
                        </li>
                         <div class='w-50 divider'>
                        <hr>
                        </div>
                    </ul>";
                }
                ?>
                <?php if ($isConnected && $_SESSION['userRole'] === 'admin'): ?>
                    <ul class='navbar-nav me-auto mb-2 mb-lg-0 mb-md-0'>
                        <li class="nav-item ms-lg-3 mt-sm-2 mt-lg-0 mt-md-0 dropdown">
                            <a class="nav-link dropdown-toggle bg-primary-subtle btn btn-outline-info btn-md p-2 rounded-2 mt-0 w-100 fw-bold p-3 fs-6" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">&#9881; Admin Panel</a>
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
                <span class="d-flex align-items-center justify-content-center">
                    <?php if (!$isConnected): ?>
                        <ul class='navbar-nav mt-3 mt-lg-0 my-md-0 mb-lg-0 mb-md-0'>
                          <li class='nav-item me-lg-3 me-md-3 mb-2 mb-lg-0 mb-md-0 registerBtn'>
                                <a class='btn btn-outline-primary border-bottom border-3 rounded-bottom-5 text-light fw-bold mt-0 w-100'
                                   href='index.php?action=register'>&#128221; Register</a>
                                </li>
                            <div class='w-50 divider'>
                           <hr>
                           </div>
                          <li class='nav-item  loginBtn'>
                                <a class='btn btn-outline-success border-bottom border-3 rounded-bottom-5 text-light fw-bold mt-0 w-100'
                                   href='index.php?action=login'>&#128273; Login</a>
                                </li>
                        </ul>
                    <?php else: ?>
                        <ul class='navbar-nav mt-2 mt-lg-0 mt-md-0 d-flex justify-content-center align-items-center'>
                            <li class='nav-item mt-sm-2 mt-lg-0 mt-md-0 mb-2 mb-lg-0 mb-md-0 profileBtn'>
                      <a href='index.php?action=profile'
                         class='btn btn-outline-primary text-light btn-md border-0 p-2 text-decoration-none mt-0 w-100 fw-bold'>
                            <img src="<?= BASE_URL . '/img/' . htmlspecialchars($_SESSION['userPhoto']) ?>"
                                 class="rounded-circle profile-img me-2"
                                 alt="User Profile Picture">
                          <?= $user ?></a>
                            </li>
                         <li class='nav-item ms-xxl-4 ms-xl-3 ms-lg-3 mt-2 mt-md-2 mt-lg-0 logoutBtn'>
                                <a class='btn btn-outline-danger btn-md p-2 rounded-2 mt-0 w-100 fw-bold'
                                   href='index.php?action=logout'>Logout &#8618;</a>
                        </li>
                        </ul>
                    <?php endif;?>
                </span>
            </div>
        </div>
    </nav>
</header>
