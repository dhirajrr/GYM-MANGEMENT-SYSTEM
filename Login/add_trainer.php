<?php

require('db.php');

$errors = array(); 
$msg = "";

if (isset($_POST['trainer'])) {

  // Trainer ID - Text format
  if (empty($_POST['id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Trainer ID is required.</b></div>");
  } else {
    $trainer_id = mysqli_real_escape_string($conn, $_POST['id']);
  }

  // Trainer Name - Required
  if (empty($_POST['name'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Trainer Name is required.</b></div>");
  } else {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
  }

  // Time - Required (Stored as TIME format)
  if (empty($_POST['time'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Time is required.</b></div>");
  } else {
    $time = mysqli_real_escape_string($conn, $_POST['time']);
  }

  // Mobile Number - Must be a 10-digit integer
  if (!isset($_POST['mobileno']) || !preg_match('/^[0-9]{10}$/', $_POST['mobileno'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Mobile number must be exactly 10 digits.</b></div>");
  } else {
    $mobileno = mysqli_real_escape_string($conn, $_POST['mobileno']);
  }

  // Payment Area ID - Text format
  if (empty($_POST['pay_id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Payment Area ID is required.</b></div>");
  } else {
    $pay_id = mysqli_real_escape_string($conn, $_POST['pay_id']);
  }

  // Check if Trainer ID already exists
  if (count($errors) == 0) {
    $user_check_query = "SELECT * FROM trainer WHERE trainer_id='$trainer_id' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
      array_push($errors, "<div class='alert alert-warning'><b>Trainer ID already exists</b></div>");
    }
  }

  // Insert data if no errors
  if (count($errors) == 0) {
    $query = "INSERT INTO trainer (trainer_id, name, time, mobileno, pay_id) 
              VALUES('$trainer_id', '$name', '$time', '$mobileno', '$pay_id')";
    $sql = mysqli_query($conn, $query);

    $msg = $sql ? "<div class='alert alert-success'><b>Trainer added successfully</b></div>"
                : "<div class='alert alert-warning'><b>Trainer not added</b></div>";
  }
}

?>

<div class="container">
	<form class="mt-3 form-group" method="post" action="">
		<h3>ADD TRAINER</h3>
		<?php 
      include('errors.php'); 
      echo @$msg;
    ?>

		<label class="mt-3">TRAINER ID</label>
		<input type="text" name="id" class="form-control" required>

		<label class="mt-3">TRAINER NAME</label>
		<input type="text" name="name" class="form-control" required>

		<label class="mt-3">TIME</label>
		<input type="time" name="time" class="form-control" required>

		<label class="mt-3">MOBILE NO</label>
		<input type="text" name="mobileno" class="form-control" pattern="[0-9]{10}" title="Enter a 10-digit mobile number" required>

		<label class="mt-3">PAYMENT AREA ID</label>
		<input type="text" name="pay_id" class="form-control" required>

		<div class="d-flex justify-content-center">
    <button class="btn btn-danger btn-lg mt-4 px-5" type="submit" name="payment">ADD TRAINER</button>
	</form>
</div>