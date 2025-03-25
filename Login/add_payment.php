<?php

require('db.php');

$errors = array(); 
$msg = "";

if (isset($_POST['payment'])) {

  // Validate Payment ID - Now a text field
  if (empty($_POST['id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Payment ID is required.</b></div>");
  } else {
    $pay_id = mysqli_real_escape_string($conn, $_POST['id']);
  }

  // Validate Amount - It should be a valid decimal number
  if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Amount must be a valid number.</b></div>");
  } else {
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
  }

  // Validate Gym ID - Now a text field
  if (empty($_POST['gym_id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Gym ID is required.</b></div>");
  } else {
    $gym_id = mysqli_real_escape_string($conn, $_POST['gym_id']);
  }

  // Check if the Payment ID already exists
  if (!isset($errors[0])) { // Proceed if no ID error
    $user_check_query = "SELECT * FROM payment WHERE pay_id='$pay_id' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { 
      array_push($errors, "<div class='alert alert-warning'><b>Payment ID already exists</b></div>");
    }
  }

  // Insert data if no errors
  if (count($errors) == 0) {
    $query = "INSERT INTO payment (pay_id, amount, gym_id) 
              VALUES('$pay_id', '$amount', '$gym_id')";
    $sql = mysqli_query($conn, $query);

    $msg = $sql ? "<div class='alert alert-success'><b>Payment area added successfully</b></div>"
                : "<div class='alert alert-warning'><b>Payment area not added</b></div>";
  }
}

?>

<div class="container">
	<form class="mt-3 form-group" method="post" action="">
		<h3>ADD PAYMENT AREA</h3>
		<?php 
      include('errors.php'); 
      echo @$msg;
    ?>
    
		<label class="mt-3">PAYMENT AREA ID</label>
		<input type="text" name="id" class="form-control" required>
    
		<label class="mt-3">AMOUNT</label>
		<input type="number" name="amount" class="form-control" required min="0" step="0.01">
    
		<label class="mt-3">GYM ID</label>
		<input type="text" name="gym_id" class="form-control" required>
    
    <!-- Centered & Larger Button -->
    <div class="d-flex justify-content-center">
		  <button class="btn btn-danger btn-lg mt-4 px-5" type="submit" name="payment">ADD PAYMENT</button>
    </div>
	</form>
</div>