<?php
session_start();
include '../includes/connection.php';
include '../includes/user_head.php';
include '../includes/user_navbar.php';
include '../includes/user_sidebar.php';

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_email'])) {
    die("User not logged in.");
}

$user_email = $_SESSION['user_email'];

// Get user_id based on user_email
$stmt = $connection->prepare("SELECT user_id FROM users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    die("User not found.");
}

$user_id = $user_result->fetch_assoc()['user_id'];

echo "User ID: " . htmlspecialchars($user_id) . "<br>";

?>

<main>
    <section>
        <div>
            <div class="d-flex justify-content-between">
                <h3 class="text-uppercase"><i>Applied Leaves</i></h3>
                <a href="apply_leave.php" class='btn btn-custom m-1'>Apply Leave</a>
            </div>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Description</th>
                            <th scope="col">Total</th>
                            <th scope="col">Availed</th>
                            <th scope="col">Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $query = "SELECT * FROM leaves";
                        $query_conn = mysqli_query($connection, $query);

                        if (!$query_conn) {
                            die("Query failed: " . mysqli_error($connection));
                        }

                        if (mysqli_num_rows($query_conn) == 0) {
                            echo "<tr><td colspan='4'>No leave types found.</td></tr>";
                        } else {
                            while ($result = mysqli_fetch_assoc($query_conn)) {
                                $leaves_description = $result['leave_description'];
                                $leaves_total = $result['leave_total'];

                                echo "Leave type: " . htmlspecialchars($leaves_description) . "<br>";

                                $stmt = $connection->prepare("SELECT SUM(DATEDIFF(leave_to, leave_from) + 1) AS availed_days FROM applied_leaves WHERE user_id = ? AND status = 'Approved' AND description = ?");
                                $stmt->bind_param("is", $user_id, $leaves_description);
                                $stmt->execute();
                                $result_availed = $stmt->get_result();
                                $availed_days = $result_availed->fetch_assoc()['availed_days'] ?? 0;

                                $remaining_days = $leaves_total - $availed_days;

                                ?>
                                <tr>
                                    <th scope="row"><?php echo htmlspecialchars($leaves_description); ?></th>
                                    <td><?php echo htmlspecialchars($leaves_total); ?></td>
                                    <td><?php echo htmlspecialchars($availed_days); ?></td>
                                    <td><?php echo htmlspecialchars($remaining_days); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Reason</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Attachment</th>
                            <th scope="col">Status</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $connection->prepare("SELECT * FROM applied_leaves WHERE user_id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $query_conn = $stmt->get_result();

                        if (!$query_conn) {
                            die("Query failed: " . $stmt->error);
                        }

                        if (mysqli_num_rows($query_conn) == 0) {
                            echo "<tr><td colspan='9'>No applied leaves found.</td></tr>";
                        } else {
                            while ($result = mysqli_fetch_assoc($query_conn)) {
                                $leave_id = $result['id'];
                                $leave_type = $result['leave_type'];
                                $description = $result['description'];
                                $reason = $result['reason'];
                                $leave_from = $result['leave_from'];
                                $leave_to = $result['leave_to'];
                                $attachment = $result['attachment'];
                                $status = $result['status'];

                                ?>
                                <tr>
                                    <th scope="row"><?php echo htmlspecialchars($leave_id); ?></th>
                                    <td><?php echo htmlspecialchars($leave_type); ?></td>
                                    <td><?php echo htmlspecialchars($description); ?></td>
                                    <td><?php echo htmlspecialchars($reason); ?></td>
                                    <td><?php echo htmlspecialchars($leave_from); ?></td>
                                    <td><?php echo htmlspecialchars($leave_to); ?></td>
                                    <td>
                                        <?php if (!empty($attachment)): ?>
                                            <a href="../images/<?php echo htmlspecialchars($attachment); ?>" download>
                                                <button class='btn btn-info'>Download</button>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td>
                                        <?php if ($status != 'Approved'): ?>
                                        <a onClick="javascript: return confirm('Do you want to delete this leave?');" href="../queries/delete_applied_leave_btn.php?delete=<?php echo htmlspecialchars($leave_id); ?>" class="btn btn-danger">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<?php
include '../includes/user_footer.php';
?>
