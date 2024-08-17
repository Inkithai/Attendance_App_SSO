<?php
include '../includes/connection.php'; 
include '../includes/user_head.php';
include '../includes/user_navbar.php';
include '../includes/user_sidebar.php';
?>

<main>
    <section>
        <div>
            <div class="d-flex justify-content-between">
                <h3 class="text-uppercase">Update User</h3>
                <a onclick="goBack()" class='btn btn-success m-1'>Back</a>
            </div>
            <div>
                <?php
                if (isset($_GET['edit'])) {
                    $user_email = $_GET['edit'];

                    $stmt = $connection->prepare("SELECT * FROM users WHERE user_email = ?");
                    $stmt->bind_param("s", $user_email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        $user_name = $user['user_name'];
                        $user_image = $user['user_image'];
                        $user_address = $user['user_address'];
                        $user_contact = $user['user_contact'];
                        $user_email = $user['user_email'];
                        $user_password = $user['user_password'];
                        $user_role = $user['user_role'];
                    } else {
                        echo "<p class='alert alert-danger'>User not found!</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<p class='alert alert-danger'>No user email provided!</p>";
                }
                ?>
                <form action="../queries/update_user_form.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" name="user_name" placeholder="Full Name" value="<?php echo htmlspecialchars($user_name ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Image</label>
                        <?php if (!empty($user_image)): ?>
                            <img class="rounded" src="../images/<?php echo htmlspecialchars($user_image); ?>" width="50" height="50">
                        <?php endif; ?>
                        <input class="form-control" type="file" name="user_image">
                        <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($user_image ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" name="user_address" id="floatingTextarea2" style="height: 100px"><?php echo htmlspecialchars($user_address ?? ''); ?></textarea>
                            <label for="floatingTextarea2">Address</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" class="form-control" name="user_contact" value="<?php echo htmlspecialchars($user_contact ?? ''); ?>" placeholder="+9412345678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" name="user_email" value="<?php echo htmlspecialchars($user_email ?? ''); ?>" placeholder="example@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" name="user_password" value="<?php echo htmlspecialchars($user_password ?? ''); ?>" placeholder="Password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select class="form-select" name="user_role">
                            <option value="<?php echo htmlspecialchars($user_role ?? ''); ?>"><?php echo htmlspecialchars($user_role ?? ''); ?></option>
                            <!-- Add other roles if needed -->
                        </select>
                    </div>
                    <input type="hidden" name="old_email" value="<?php echo htmlspecialchars($user_email ?? ''); ?>">
                    <div class="m-3">
                        <div class='text-center'>
                            <button type="submit" name="update" class="btn btn-success fw-bold w-25">UPDATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
include '../includes/user_footer.php';
?>
