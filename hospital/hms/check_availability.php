<?php
require_once("include/config.php");

if (!empty($_POST["email"])) {
    $email = trim($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span style='color:red'>Invalid email address.</span>";
        exit();
    }

    // Use a prepared statement to prevent SQL injection.
    $stmt = mysqli_prepare($con, "SELECT email FROM users WHERE email = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            echo "<span style='color:red'>Email already exists.</span>";
            echo "<script>$('#submit').prop('disabled',true);</script>";
        } else {
            echo "<span style='color:green'>Email available for Registration.</span>";
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }
    } else {
        error_log('HMS check_availability prepare failed: ' . mysqli_error($con));
    }
}
?>
