<?php
include '../includes/admin_head.php';
include '../includes/admin_navbar.php';
include '../includes/admin_sidebar.php';

session_start(); // Make sure session is started

// Include your connection file
include '../includes/connection.php';

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
                $showToast = false; // Variable to control if toast should be shown

                if (isset($_GET['edit'])) {
                    $user_email = $_GET['edit']; // Use email to fetch user details

                    // Prepare the query to prevent SQL injection
                    $query = "SELECT * FROM users WHERE user_email = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('s', $user_email);
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
                        $showToast = true; // Set to show toast if user is not found
                        $error_message = "User not found!";
                    }

                    $stmt->close();
                } else {
                    $showToast = true; // Set to show toast if no email is provided
                    $error_message = "No user email provided!";
                }
                ?>

                <!-- Add Toast HTML Structure -->
                <div aria-live="polite" aria-atomic="true">
                    <div class="toast-container position-fixed bottom-0 end-0 p-3">
                        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <strong class="me-auto">Notification</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="../queries/update_user_form.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" name="user_name" placeholder="Full Name"
                            value="<?php echo htmlspecialchars($user_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Image</label>
                        <?php if ($user_image): ?>
                            <img class="rounded" src="../images/<?php echo htmlspecialchars($user_image); ?>" width="50" height="50">
                        <?php endif; ?>
                        <input class="form-control" type="file" name="user_image">
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" name="user_address" id="floatingTextarea2"
                                style="height: 100px" required><?php echo htmlspecialchars($user_address); ?></textarea>
                            <label for="floatingTextarea2">Address</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contact Number</label>
                        <input type="text" class="form-control" name="user_contact" value="<?php echo htmlspecialchars($user_contact); ?>"
                            placeholder="+9412345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" name="user_email" value="<?php echo htmlspecialchars($user_email); ?>"
                            placeholder="example@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" class="form-control" name="user_password"
                            value="<?php echo htmlspecialchars($user_password); ?>" placeholder="Password" required>
                    </div>
                    <?php
                    if ($user_email != $_SESSION['user_email']) {
                        ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select class="form-select" name="user_role">
                                <option value="<?php echo htmlspecialchars($user_role); ?>"><?php echo htmlspecialchars($user_role); ?></option>
                                <?php
                                if ($user_role == 'Admin') {
                                    echo "<option value='Employee'>Employee</option>";
                                } else {
                                    echo "<option value='Admin'>Admin</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    } else {
                    ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select class="form-select" name="user_role">
                                <option value="<?php echo htmlspecialchars($user_role); ?>"><?php echo htmlspecialchars($user_role); ?></option>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_email); ?>">
                    <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
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
include '../includes/admin_footer.php';
?>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show toast if the variable is set
    <?php if ($showToast): ?>
        const toast = new bootstrap.Toast(document.getElementById('errorToast'));
        toast.show();
    <?php endif; ?>
});
</script>
