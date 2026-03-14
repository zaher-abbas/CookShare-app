<?php
/** @var array|null $users */
?>
    <main>
        <div class="container">
            <h1 class="text-center my-4 fw-bold alert alert-info">&#128101; Manage All Users</h1>
        </div>
        <?php if ($users): ?>
        <div class="container">
            <section class="list-group">
                <?php foreach ($users as $user): ?>
                    <div class="list-group-item list-group-item-action p-3 mb-3 d-flex justify-content-between align-items-center cursor-pointer">
                        <div class="w-25">
                            <div class="text-dark">&#128100; <?= $user['firstname'] . ' ' . $user['lastname'] ?></div>
                        </div>
                        <div class="w-25">
                            <span class="text-dark"><span class="fs-5">&#9993;</span> <?= $user['email'] ?></span>
                        </div>
                        <div>
                            <button data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    class="btn btn-danger deleteBtn">Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="container">
                    <div class="alert alert-info text-center fs-5" role="alert">
                        No users found!
                    </div>
                    <?php endif; ?>
            </section>
        </div>
    </main>
    <div class="modal" tabindex="-1" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="index.php?action=deleteuser&id=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toastify({
                text: "<?php echo htmlspecialchars($_SESSION['toast']['message'] ?? ''); ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "<?php echo ($_SESSION['toast']['type'] ?? 'danger') === 'success' ? '#16a34a' : '#dc3545'; ?>",
                close: true
            }).showToast();
        });
    </script>
    <?php unset($_SESSION['toast']); endif; ?>