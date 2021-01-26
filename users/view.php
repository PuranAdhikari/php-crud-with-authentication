<?php
	session_start();

	//check if the user is already logged in, if not then redirect him to login page
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
		header("location: login.php");
		exit();
	}

	$gender = $email = $name = $phone = "";

	if(isset($_GET['id']) && !empty($_GET['id'])){
		//include config file
		require '../config.php';

		//prepare a select statement
		$sql = "SELECT name, email, phone, gender FROM users WHERE id = ?";

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

	require '../includes/header.php';
  	require '../includes/sidebar.php';
?>

	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="page-header clearfix">
					<h2 class="pull-left">User Details</h2>
					<a href="index.php" class="btn btn-primary pull-right">Back</a>
				</div>
				<hr>
			</div>
			<div class="col-md-4">
				<table class="table table-bordered">
					<tr>
						<th>Name</th>
						<td><?php echo $name; ?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo $email; ?></td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><?= $phone; ?></td>
					</tr>
					<tr>
						<th>Gender</th>
						<td><?= $gender; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<?php require '../includes/footer.php'; ?>