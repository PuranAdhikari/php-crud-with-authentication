<?php
	session_start();

	//check if the user is already logged in, if yes then redirect him to dashboard page
	if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
		header("location: dashboard.php");
		exit();
	}

	require 'config.php';

	$email = $password = "";
	$email_error = $password_error = $login_error = "";

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$email = $_POST['email'];
		$password = $_POST['password'];

		if(empty($email)) {
			$email_error = "Email is required.";
		}
		if(empty($password)) {
			$password_error = "Password is required.";
		}

		if(empty($email_error) && empty($password_error)) {
			//now we need to login
			$email = $_POST['email'];
			$password = sha1($_POST['password']);

			$sql = "SELECT * FROM users WHERE email = ? AND password = ?";

			if($stmt = $conn->prepare($sql)) {
				//bind parameters
				$stmt->bind_param("ss", $p_email, $p_password);

				//set the parameters
				$p_email = $email;
				$p_password = $password;

				//attempt to execute
				if($stmt->execute()) {
					$result = $stmt->get_result();
					if($result->num_rows == 1) {
						$data = $result->fetch_assoc();
						//set data to session variables
						$_SESSION["logged_in"] = true;
						$_SESSION["email"] = $email;
						$_SESSION["name"] = $data["name"];

						//redirect user to dashboard
						header("location: index.php");
						exit();
					} else {
						$login_error = "Invalid email or password.";
					}
				} else {
					echo "Something went wrong.";
				}
				$stmt->close();
			} else {
				echo "Something went wrong.";
			}
		}
	}
	$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<style>
		.mt-50 {
			margin-top: 50px;
		}
		.pull-left {
			float: left;
		}
		.pull-right {
			float: right;
		}
		.center {
			margin: auto;
		}
	</style>
	<script src="https://kit.fontawesome.com/40acd2c0d4.js" crossorigin="anonymous"></script>
</head>
<body class="mt-50">
	<div class="container">
		<div class="row">
			<div class="col-md-4 center">
				<h2 class="page-header">Login</h2>
				<hr>
				<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
					<div class="form-group">
						<label>Email</label>
						<input type="email" name="email" class="form-control">
						<span class="text-danger"><i><?php echo $email_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" name="password" class="form-control">
						<span class="text-danger"><i><?php echo $password_error; ?></i></span>
					</div>
					<?php
						if($login_error) {
							echo "<div class='alert alert-danger'>$login_error</div>";
						}
					?>
					<input type="submit" value="Login" class="btn btn-success btn-block">
				</form>
			</div>
		</div>
	</div>
</body>
</html>