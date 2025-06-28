<?php
session_start();
include("include/config.php");
error_reporting(0);

if (isset($_POST['submit'])) {
    $uname     = trim($_POST['username'] ?? '');
    $dpassword = md5($_POST['password'] ?? '');

    if ($uname === '' || ($_POST['password'] ?? '') === '') {
        $_SESSION['errmsg'] = "Email and password are required.";
        header("location:index.php");
        exit();
    }

    // Use a prepared statement to prevent SQL injection.
    $stmt = mysqli_prepare($con, "SELECT id FROM doctors WHERE docEmail = ? AND password = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $uname, $dpassword);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $num    = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        $uip = $_SERVER['REMOTE_ADDR'];

        if ($num) {
            $_SESSION['dlogin'] = $uname;
            $_SESSION['id']     = $num['id'];
            $uid    = $num['id'];
            $status = 1;
            // Log successful login.
            $log_stmt = mysqli_prepare($con, "INSERT INTO doctorslog (uid, username, userip, status) VALUES (?, ?, ?, ?)");
            if ($log_stmt) {
                mysqli_stmt_bind_param($log_stmt, 'issi', $uid, $uname, $uip, $status);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
            header("location:dashboard.php");
            exit();
        } else {
            $status = 0;
            $log_stmt = mysqli_prepare($con, "INSERT INTO doctorslog (username, userip, status) VALUES (?, ?, ?)");
            if ($log_stmt) {
                mysqli_stmt_bind_param($log_stmt, 'ssi', $uname, $uip, $status);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }
            $_SESSION['errmsg'] = "Invalid username or password.";
            header("location:index.php");
            exit();
        }
    } else {
        error_log('HMS doctor login prepare failed: ' . mysqli_error($con));
        $_SESSION['errmsg'] = "A server error occurred. Please try again.";
        header("location:index.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doctor Login</title>
		<meta charset="utf-8" />
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body class="login">
		<div class="row">
			<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="logo margin-top-30">
				<a href="../../index.php"><h2> HMS | Doctor Login</h2></a>
				</div>

				<div class="box-login">
					<form class="form-login" method="post">
						<fieldset>
							<legend>
								Sign in to your account
							</legend>
							<p>
								Please enter your name and password to log in.<br />
								<span style="color:red;"><?php echo htmlspecialchars($_SESSION['errmsg'] ?? ''); ?><?php $_SESSION['errmsg'] = ''; ?></span>
							</p>
							<div class="form-group">
								<span class="input-icon">
									<input type="text" class="form-control" name="username" placeholder="Username" required>
									<i class="fa fa-user"></i> </span>
							</div>
							<div class="form-group form-actions">
								<span class="input-icon">
									<input type="password" class="form-control password" name="password" placeholder="Password" required>
									<i class="fa fa-lock"></i>
									 </span>
									 <a href="forgot-password.php">
									Forgot Password ?
								</a>
							</div>
							<div class="form-actions">

								<button type="submit" class="btn btn-primary pull-right" name="submit">
									Login <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>


						</fieldset>
					</form>

					<div class="copyright">
					<span class="text-bold text-uppercase"> Hospital Management System</span>
					</div>

				</div>

			</div>
		</div>
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="vendor/jquery-validation/jquery.validate.min.js"></script>

		<script src="assets/js/main.js"></script>

		<script src="assets/js/login.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>

	</body>
	<!-- end: BODY -->
</html>
