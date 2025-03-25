<?php

require('db.php');

$errors = array(); 
$msg = "";

if (isset($_POST['gym'])) {

  // Validate Gym ID - Now a text field
  if (empty($_POST['id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Gym ID is required.</b></div>");
  } else {
    $gym_id = mysqli_real_escape_string($conn, $_POST['id']);
  }

  // Validate other fields
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $type = mysqli_real_escape_string($conn, $_POST['type']);

  // Check if the Gym ID already exists
  if (!isset($errors[0])) { // Proceed if no ID error
    $user_check_query = "SELECT * FROM gym WHERE gym_id='$gym_id' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { 
      array_push($errors, "<div class='alert alert-warning'><b>Gym ID already exists</b></div>");
    }
  }

  // Insert gym data if there are no errors
  if (count($errors) == 0) {
    $query = "INSERT INTO gym (gym_id, gym_name, address, type) 
              VALUES('$gym_id', '$name', '$address', '$type')";
    $sql = mysqli_query($conn, $query);

    $msg = $sql ? "<div class='alert alert-success'><b>Gym added successfully</b></div>"
                : "<div class='alert alert-warning'><b>Gym not added</b></div>";
  }
}

?>

<div class="w3-container">
	<form class="form-group mt-3" method="post" action="">
		<div><h3>ADD GYM</h3></div>
		<?php 
      include('errors.php'); 
      echo @$msg;
    ?>

		<label class="mt-3">GYM ID</label>
		<input type="text" name="id" class="form-control" required>

		<label class="mt-3">GYM NAME</label>
		<input type="text" name="name" class="form-control" required>

		<label class="mt-3">GYM ADDRESS</label>
		<input type="text" name="address" class="form-control" required>

		<label class="mt-3">GYM TYPE</label>
		<select name="type" class="form-control mt-3" required>
      <option value="" disabled selected>Select Gym Type</option>
      <option value="unisex">UNISEX</option>
      <option value="women">WOMEN</option>
      <option value="men">MEN</option>  
    </select>

    <!-- Centered & Larger Button -->
    <div class="d-flex justify-content-center">
		  <button class="btn btn-danger btn-lg mt-4 px-5" type="submit" name="gym">ADD GYM</button>
    </div>
	</form>
</div>