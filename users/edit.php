<?php
	session_start();

	//check if the user is already logged in, if not then redirect him to login page
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
		header("location: login.php");
		exit();
	}

	require '../config.php';

	$name = $email = $password = $confirm_password = $phone = $gender = "";
	$name_error = $email_error = $password_error = $confirm_password_error = $phone_error = $gender_error = "";

	if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
		$phone = $_POST['phone'];
		$gender = isset($_POST['gender']) ? $_POST['gender'] : '';

		if(empty($name)) {
			$name_error = "Name is required.";
		} else {
			if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
			 	$name_error = "Only letters and white space allowed.";
			}
		}
		if(empty($email)) {
			$email_error = "Email is required.";
		} else {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$email_error = "Invalid email format.";
			}
		}
		if(empty($password)) {
			$password_error = "Password is required.";
		} else {
			$pwdLength = strlen($password);
			if($pwdLength < 8) {
				$password_error = "Password must be at least 8 characters long.";
			}
		}
		if(empty($confirm_password)) {
			$confirm_password_error = "Password confirmation is required.";
		} else {
			if($password !== $confirm_password) {
				$password_error = "Password and Confirm Password must be same.";
			}
		}
		if(empty($phone)) {
			$phone_error = "Phone number is required.";
		}
		if(empty($gender)) {
			$gender_error = "Gender is required.";
		}

		//check input errors before inserting in database
		if(empty($name_error) && empty($email_error) && empty($password_error) && empty($confirm_password_error) && empty($phone_error) && empty($gender_error)) {
			
			//die("here");
			//prepare an insert statement
			$sql = "UPDATE users SET name=?, email=?, phone=?, password=?, gender=? WHERE id=?";
			if($stmt = $conn->prepare($sql)) {
				//bind variables to the prepared statement as parameters
				$stmt->bind_param("sssssi", $p_name, $p_email, $p_phone, $p_password, $p_gender, $p_id);

				//set parameters
				$p_name = $name;
				$p_email = $email;
				$p_phone = $phone;
				$p_password = sha1($password);
				$p_gender = $gender;
				$p_id = $id;

				//attempt to execute the prepared statement
				if($stmt->execute()) {
					//record created successfully. redirect to index page
					header("location: index.php");
				} else {
					echo "Something went wrong. Please try again later.";
				}
			}
			//close statement
			$stmt->close();
		}
		//close connection
		$conn->close();
	} else {
		if(isset($_GET['id']) && !empty($_GET['id'])){
		
			//prepare a select statement
			$sql = "SELECT id, name, email, phone, gender FROM users WHERE id = ?";

			if($stmt = $conn->prepare($sql)) {
				//bind the variables to the prepared statement as parameters
				$stmt->bind_param("i", $p_id);

				//set parameters
				$p_id = trim($_GET['id']);

				//attempt to execute the prepared statement
				if($stmt->execute()) {
					$result = $stmt->get_result();

					if($result->num_rows == 1) {
						//fetch result row as an associative array.
						//Since the result set contains only one row, we don't need to use while loop
						$row = $result->fetch_assoc();

						$name = $row['name'];
						$email = $row['email'];
						$gender = $row['gender'];
						$phone = $row['phone'];
						$id = $row['id'];
					} else {
						header("location: index.php");
						exit();
					}
				} else {
					echo "Oops!!! Something went wrong.";
				}
			}

			//close statement
			$stmt->close();

			//close connection
			$conn->close();
		} else {
			header("location: index.php");
			exit();
		}
	}

	require '../includes/header.php';
  	require '../includes/sidebar.php';
?>
	 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="page-header clearfix">
					<h2 class="pull-left">Update User</h2>
					<a href="index.php" class="btn btn-primary pull-right">Back</a>
				</div>
				<hr>
			</div>
			<div class="col-md-6">
				<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
					<div class="form-group">
						<label>Name *</label>
						<input type="text" name="name" class="form-control" value="<?= $name; ?>">
						<span class="text-danger"><i><?php echo $name_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Email *</label>
						<input type="email" name="email" class="form-control" value="<?= $email; ?>">
						<span class="text-danger"><i><?php echo $email_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Password *</label>
						<input type="password" name="password" class="form-control" value="<?= $password; ?>">
						<span class="text-danger"><i><?php echo $password_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Confirm Password *</label>
						<input type="password" name="confirm_password" class="form-control" value="<?= $confirm_password; ?>">
						<span class="text-danger"><i><?php echo $confirm_password_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Phone Number *</label>
						<input type="text" name="phone" class="form-control" value="<?= $phone; ?>">
						<span class="text-danger"><i><?php echo $phone_error; ?></i></span>
					</div>
					<div class="form-group">
						<label>Gender</label><br>
						<label>
							<input type="radio" name="gender" value="M" <?= $gender == "M" ? "checked" : ""?>> Male
						</label>
						<br>
						<label>
							<input type="radio" name="gender" value="F" <?= $gender == "F" ? "checked" : ""?>> Female
						</label>
						<br>
						<label>
							<input type="radio" name="gender" value="O" <?= $gender == "O" ? "checked" : ""?>> Other
						</label>
						<br>
						<span class="text-danger"><i><?php echo $gender_error; ?></i></span>
					</div>
					<input type="hidden" name="id" value="<?= $id; ?>">
					<input type="submit" class="btn btn-primary" value="Update">
				</form>
			</div>
		</div>
	</div>
</div>
</div>



<?php require '../includes/footer.php'; ?>