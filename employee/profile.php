<?php
session_start(); // Ensure this is only called once and at the very top
include '../includes/connection.php'; // Ensure connection is included
include '../includes/user_head.php';
include '../includes/user_navbar.php';
include '../includes/user_sidebar.php';
?>

<main>
  <section>
    <?php
    if (isset($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];

        // Use prepared statements to prevent SQL injection
        $stmt = $connection->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_name = $user['user_name'];
            $user_image = $user['user_image'];
            $user_password = $user['user_password'];
            $user_address = $user['user_address'];
            $user_contact = $user['user_contact'];
            $user_role = $user['user_role'];
        } else {
            echo "<p class='alert alert-danger'>User not found!</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='alert alert-danger'>No user logged in!</p>";
    }
    ?>
    <div>
      <div class="d-flex justify-content-between">
        <h3 class="text-uppercase">Profile</h3>
        <a onclick="savePageHistory()" href="edit_user.php?edit=<?php echo htmlspecialchars($user_email); ?>" class='btn btn-custom m-1'>Update</a>
      </div>
      <div>
        <div class='row g-0'>
          <div class='col-4 text-center'>
            <img class="rounded" src='../images/<?php echo htmlspecialchars($user_image); ?>' width="150" height="120" />
          </div>
          <div class='col-8'>
            <p><b>Name:</b> <?php echo htmlspecialchars($user_name); ?></p>
            <p><b>Role:</b> <?php echo htmlspecialchars($user_role); ?></p>
            <p><b>Address:</b> <?php echo htmlspecialchars($user_address); ?></p>
            <p><b>Contact:</b> <?php echo htmlspecialchars($user_contact); ?></p>
            <p><b>Email:</b> <?php echo htmlspecialchars($user_email); ?></p>
            <p><b>Password:</b> <?php echo htmlspecialchars($user_password); ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
include '../includes/user_footer.php';
?>
