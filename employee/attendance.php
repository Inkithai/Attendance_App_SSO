<?php
include '../includes/user_head.php';
include '../includes/user_navbar.php';
include '../includes/user_sidebar.php';
?>

<main>
    <section>
        <div>
            <div class="d-flex justify-content-between">
                <h3 class="text-uppercase">Attendance</h3>
                <a href="mark_attendance.php" class='btn btn-success btn-custom m-1'>Mark Attendance</a>
            </div>
            <!-- toggle buttons -->
            <div class="mb-2">
                <button type="button" class="btn btn-warning btn-custom" onclick="toggleForm('monthlyForm')">Monthly</button>
                <button type="button" class="btn btn-warning btn-custom" onclick="toggleForm('yearlyForm')">Yearly</button>
            </div>
            <!-- monthly form -->
            <div id="monthlyForm" class="form-container">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Month:</label>
                        <input type="month" class="form-control w-25" name="month" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-custom" name="monthly">View Attendance</button>
                    </div>
                </form>
            </div>
            <!-- yearly form -->
            <div id="yearlyForm" class="form-container">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Year:</label>
                        <input type="number" class="form-control w-25" name="year" min="2000" max="2099"
                            placeholder="2023" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="yearly">View Attendance</button>
                    </div>
                </form>
            </div>
            <!-- display attendance -->
            <div class="mt-2">
                <?php
                // Get the selected month and year from the form
                if (isset($_POST['monthly'])) {
                    $selectedDate = $_POST['month'];
                    $selectedYear = date("Y", strtotime($selectedDate));
                    $selectedMonth = date("m", strtotime($selectedDate));

                    // Assuming you have stored employee_id in a session after login
                    $employeeId = $_SESSION['user_id'];

                    // Get the last day of the selected month
                    $lastDayOfMonth = date("t", strtotime($selectedDate));

                    // Query to fetch attendance for the selected month and employee
                    $sql = "SELECT DAY(attendance_date) AS day, status FROM attendance
            WHERE user_id = $employeeId
            AND YEAR(attendance_date) = $selectedYear
            AND MONTH(attendance_date) = $selectedMonth";

                    $result = mysqli_query($connection, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<h5>Monthly Attendance - " . date("F Y", strtotime($selectedDate)) . "</h5>";
                        // Create an empty attendance array for all days in the month
                        $attendanceData = array_fill(1, $lastDayOfMonth, 'Absent');

                        // Populate the attendance array with fetched data
                        while ($row = mysqli_fetch_assoc($result)) {
                            $attendanceData[$row['day']] = $row['status'];
                        }

                        // Query to fetch leave records for the selected month and employee
                        $leaveSql = "SELECT DAY(leave_from) AS day, DAY(leave_to) AS day_to FROM applied_leaves
                     WHERE user_id = $employeeId
                     AND status = 'Approved'
                     AND YEAR(leave_from) = $selectedYear
                     AND MONTH(leave_from) = $selectedMonth";

                        $leaveResult = mysqli_query($connection, $leaveSql);

                        if (mysqli_num_rows($leaveResult) > 0) {
                            // Update the attendanceData array to show "On leave" for leave days
                            while ($leaveRow = mysqli_fetch_assoc($leaveResult)) {
                                $leaveFrom = $leaveRow['day'];
                                $leaveTo = $leaveRow['day_to'];

                                // Store all "On leave" days for each leave entry
                                for ($day = $leaveFrom; $day <= $leaveTo; $day++) {
                                    if ($day >= 1 && $day <= $lastDayOfMonth) {
                                        $attendanceData[$day] = 'On leave';
                                    }
                                }
                            }
                        }

                        // Display the attendance table
                        echo "<div class='monthly'>";
                        // Generate headers for the days in the selected month
                        for ($day = 1; $day <= $lastDayOfMonth; $day++) {
                            $attendanceStatus = $attendanceData[$day];
                            $statusClass = ($attendanceStatus == 'Present') ? 'present' : (($attendanceStatus == 'On leave') ? 'on-leave' : 'absent');
                            echo '<div class="' . $statusClass . ' monthly-item">';
                            echo $day . '<br>' . $attendanceStatus;
                            echo '</div>';
                        }

                        echo "</div>";
                    } else {
                        echo "No attendance records found for the selected month.";
                    }
                } elseif (isset($_POST['yearly'])) {
                    $selectedYear = $_POST['year'];

                    // Assuming you have stored employee_id in a session after login
                    $employeeId = $_SESSION['user_id'];

                    // Query to fetch attendance for the selected year and employee
                    $sql = "SELECT MONTH(attendance_date) AS month, DAY(attendance_date) AS day, status FROM attendance
            WHERE user_id = $employeeId
            AND YEAR(attendance_date) = $selectedYear
            ORDER BY attendance_date"; // Sort records by date
                
                    $result = mysqli_query($connection, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<h5>Yearly Attendance - " . $selectedYear . "</h5>";
                        $attendanceData = array();

                        // Populate the attendance data array
                        while ($row = mysqli_fetch_assoc($result)) {
                            $month = $row['month'];
                            $day = $row['day'];
                            $status = $row['status'];

                            $attendanceData[$month][$day] = $status;
                        }

                        // Query to fetch leave records for the selected year and employee
                        $leaveSql = "SELECT MONTH(leave_from) AS month, DAY(leave_from) AS day, DAY(leave_to) AS day_to FROM applied_leaves
                        WHERE user_id = $employeeId
                        AND status = 'Approved'
                        AND YEAR(leave_from) = $selectedYear";

                        $leaveResult = mysqli_query($connection, $leaveSql);

                        if (mysqli_num_rows($leaveResult) > 0) {
                            // Update the attendanceData array to show "On leave" for leave days
                            while ($leaveRow = mysqli_fetch_assoc($leaveResult)) {
                                $leaveMonth = $leaveRow['month'];
                                $leaveFrom = $leaveRow['day'];
                                $leaveTo = $leaveRow['day_to'];

                                // Store all "On leave" days for each leave entry
                                for ($day = $leaveFrom; $day <= $leaveTo; $day++) {
                                    $attendanceData[$leaveMonth][$day] = 'On leave';
                                }
                            }
                        }

                        // Display the attendance tables for each month
                        for ($month = 1; $month <= 12; $month++) {
                            echo "<h5>" . date("F", mktime(0, 0, 0, $month, 1)) . " " . $selectedYear . "</h5>";

                            // Display the attendance table
                            echo "<div class='monthly'>";

                            // Loop through all days in the month
                            for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $selectedYear); $day++) {
                                $attendanceStatus = isset($attendanceData[$month][$day]) ? $attendanceData[$month][$day] : 'Absent';
                                $statusClass = ($attendanceStatus == 'Present') ? 'present' : (($attendanceStatus == 'On leave') ? 'on-leave' : 'absent');
                                echo '<div class="' . $statusClass . ' monthly-item">';
                                echo $day . '<br>' . $attendanceStatus;
                                echo '</div>';
                            }

                            echo "</div>";
                        }
                    } else {
                        echo "No attendance records found for the selected year.";
                    }
                }

                // Close the database connection
                mysqli_close($connection);
                ?>

            </div>
        </div>
    </section>
</main>

<?php
include '../includes/user_footer.php';
?>

