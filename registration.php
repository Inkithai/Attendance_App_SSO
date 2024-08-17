<?php
include 'includes/head.php';
include 'includes/navbar.php';
require_once 'google-config.php'; // Include your Google Client configuration
?>

<main>
  <section id="registration">
    <div class="text-white text-center">
      <h3 class="text-uppercase">Registration</h3>
    </div>
    <!-- Registration Form -->
    <div>
      <form action="queries/registration_form.php" method="POST" enctype="multipart/form-data">
        <!-- Existing Registration Form Fields -->
        <!-- ... -->
      </form>
      <!-- Google SSO Sign-Up Buttons -->
      <div class="text-center mt-4">
        <a href="<?php echo htmlspecialchars($adminSignUpAuthUrl); ?>" class="btn btn-danger">Sign Up with Google (Admin)</a>
        <a href="<?php echo htmlspecialchars($employeeSignUpAuthUrl); ?>" class="btn btn-primary">Sign Up with Google (Employee)</a>
      </div>
    </div>
  </section>
</main>
