<?php
	session_start();

	//check if the user is already logged in, if not then redirect him to login page
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
		header("location: login.php");
		exit();
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
					<h2 class="pull-left">Users Details</h2>
					<a href="create.php" class="btn btn-success pull-right">Add New User</a>
				</div>
				<hr>
			</div>
			<div class="col-md-12">
				<?php

					require '../config.php';

					$sql = "SELECT * FROM users";

					$result = $conn->query($sql);

					if($result->num_rows > 0) {
						echo "<table class='table table-bordered table-striped'>";
						echo "<tr>";
						echo "<th>Name</th>";
						echo "<th>Email</th>";
						echo "<th>Phone</th>";
						echo "<th>Gender</th>";
						echo "<th>Actions</th>";
						echo "</tr>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr>";
							echo "<td>" . $row['name'] . "</td>";
							echo "<td>" . $row['email'] . "</td>";
							echo "<td>" . $row['phone'] . "</td>";
							echo "<td>" . $row['gender'] . "</td>";
							echo "<td>";
							echo "<a href='view.php?id=" . $row['id'] . "'><i class='fa fa-eye'></i></a>";
							echo " | ";
							echo "<a href='edit.php?id=" . $row['id'] . "'><i class='fa fa-edit'></i></a>";
							echo " | ";
							echo "<a href='delete.php?id=" . $row['id'] . "'><i class='fa fa-trash'></i></a>";
							echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
						$result->free();
					} else {
						echo "<p>No records found.</p>";
					}

					$conn->close();

				?>
			</div>
		</div>
	</div>
</div>
</div>

<?php require '../includes/footer.php'; ?>