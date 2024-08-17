<?php
include 'includes/head.php';
include 'includes/navbar.php';
require_once 'google-config.php';
session_start();

// Check if user is already logged in and redirect to their profile
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == 'Admin') {
        header("Location: admin/profile.php");
    } elseif ($_SESSION['user_role'] == 'Employee') {
        header("Location: employee/profile.php");
    }
    exit();
}
?>

<main>
    <section id="registration">
        <div class="text-white text-center">
            <h3 class="text-uppercase">Login</h3>
        </div>
        <!-- Registration form -->
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <h4 class="text-white">Admin Login</h4>
                    <form action="queries/login_form.php" method="POST" class="w-75 mx-auto">
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Email</label>
                            <input type="email" class="form-control" name="user_email" placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Password</label>
                            <input type="password" class="form-control" name="user_password" placeholder="Password" required>
                        </div>
                        <input type="hidden" name="user_role" value="Admin">
                        <div class="mt-4">
                            <div class='text-center'>
                                <button type="submit" name="login" class="btn btn-custom fw-bold w-25">LOGIN</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <a href="<?php echo htmlspecialchars($adminAuthUrl); ?>" class="btn btn-danger">Login as Admin with Google</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <h4 class="text-white">Employee Login</h4>
                    <form action="queries/login_form.php" method="POST" class="w-75 mx-auto">
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Email</label>
                            <input type="email" class="form-control" name="user_email" placeholder="example@gmail.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Password</label>
                            <input type="password" class="form-control" name="user_password" placeholder="Password" required>
                        </div>
                        <input type="hidden" name="user_role" value="Employee">
                        <div class="mt-4">
                            <div class='text-center'>
                                <button type="submit" name="login" class="btn btn-custom fw-bold w-25">LOGIN</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <a href="<?php echo htmlspecialchars($employeeAuthUrl); ?>" class="btn btn-danger">Login as Employee with Google</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
?>
