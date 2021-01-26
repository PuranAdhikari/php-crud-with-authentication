<?php
	session_start();

	//check if the user is already logged in, if not then redirect him to login page
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true) {
		header("location: login.php");
		exit();
	}
	
	//process delete operation after confirmation
	if(isset($_POST['id']) && !empty($_POST['id'])) {
		require '../config.php';

		$sql = "DELETE FROM users WHERE id = ?";

		if($stmt = $conn->prepare($sql)) {
			//bind
			$stmt->bind_param("i", $p_id);

			//set parameters
			$p_id = trim($_POST['id']);

			//execute
			if($stmt->execute()) {
				//record deleted successfully
				header("location: index.php");
				exit();
			} else {
				echo "Oops! Something went wrong. Please try again later.";
			}
		}
		$stmt->close();
		$conn->close();
	} else {
		//check for the existence of id parameter
		if(!isset($_GET['id']) || empty(trim($_GET['id']))) {
			//URL doesn't contain id parameter. Redirect to home page.
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
				<div class="page-header">
					<h2>Delete Record</h2>
				</div>
				<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
					<input type="hidden" name="id" value="<?= trim($_GET['id']); ?>">
					<div class="alert alert-danger">
						<p>Are you sure you want to delete this record ?</p>
						<input type="submit" value="Yes" class="btn btn-danger">
						<a href="index.php" class="btn btn-success">No</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<?php require '../includes/footer.php'; ?>