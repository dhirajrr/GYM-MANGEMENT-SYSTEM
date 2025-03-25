<?php

require('db.php');

$errors = array(); 
$msg = "";

if (isset($_POST['member'])) {

  // Validate Member ID (Now Text)
  if (empty($_POST['id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Member ID is required.</b></div>");
  } else {
    $mem_id = mysqli_real_escape_string($conn, $_POST['id']);
  }

  // Validate Name
  if (empty($_POST['name'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Name is required.</b></div>");
  } else {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
  }

  // Validate DOB & Calculate Age
  if (empty($_POST['dob'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Date of Birth is required.</b></div>");
  } else {
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $today->diff($birthdate)->y;
  }

  // Validate Package (Decimal)
  if (!isset($_POST['package']) || !is_numeric($_POST['package'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Package must be a valid decimal number.</b></div>");
  } else {
    $package = mysqli_real_escape_string($conn, $_POST['package']);
  }

  // Validate Mobile Number (10 Digits)
  if (!isset($_POST['mobileno']) || !preg_match('/^\d{10}$/', $_POST['mobileno'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Mobile number must be exactly 10 digits.</b></div>");
  } else {
    $mobileno = mysqli_real_escape_string($conn, $_POST['mobileno']);
  }

  // Validate Payment ID (Now Text)
  if (empty($_POST['pay_id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Payment Area ID is required.</b></div>");
  } else {
    $pay_id = mysqli_real_escape_string($conn, $_POST['pay_id']);
  }

  // Validate Trainer ID (Now Text)
  if (empty($_POST['trainer_id'])) {
    array_push($errors, "<div class='alert alert-warning'><b>Trainer ID is required.</b></div>");
  } else {
    $trainer_id = mysqli_real_escape_string($conn, $_POST['trainer_id']);
  }

  // Check if Member ID already exists
  if (!isset($errors[0])) {
    $user_check_query = "SELECT * FROM member WHERE mem_id='$mem_id' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { 
      array_push($errors, "<div class='alert alert-warning'><b>ID already exists</b></div>");
    }
  }

  // Insert into database if no errors
  if (count($errors) == 0) {
    $query = "INSERT INTO member (mem_id, name, age, dob, package, mobileno, pay_id, trainer_id) 
              VALUES('$mem_id', '$name', '$age', '$dob', '$package', '$mobileno', '$pay_id', '$trainer_id')";
    $sql = mysqli_query($conn, $query);

    $msg = $sql ? "<div class='alert alert-success'><b>Member added successfully</b></div>"
                : "<div class='alert alert-warning'><b>Member not added</b></div>";
  }
}

?>

<div class="container">
	<form class="form-group mt-3" method="post" action="">
		<div><h3>ADD MEMBER</h3></div>
		 <?php include('errors.php'); 
    echo @$msg;
    ?>

		<label class="mt-3">MEMBER ID</label>
		<input type="text" name="id" class="form-control" required>

		<label class="mt-3">MEMBER NAME</label>
		<input type="text" name="name" class="form-control" required>

		<label class="mt-3">DOB</label>
		<input type="date" name="dob" class="form-control" required onchange="calculateAge()">

		<label class="mt-3">AGE</label>
		<input type="number" name="age" id="age" class="form-control" readonly>

		<label class="mt-3">PACKAGE</label>
		<input type="number" name="package" class="form-control" required min="0" step="0.01">

		<label class="mt-3">MOBILE NO</label>
		<input type="text" name="mobileno" class="form-control" required pattern="\d{10}" title="Enter a 10-digit mobile number">

		<label class="mt-3">PAYMENT AREA ID</label>
		<input type="text" name="pay_id" class="form-control" required>

		<label class="mt-3">TRAINER ID</label>
		<input type="text" name="trainer_id" class="form-control" required>

    <!-- Centered & Larger Button -->
    <div class="d-flex justify-content-center">
		  <button class="btn btn-primary btn-lg mt-4 px-5" type="submit" name="member">ADD</button>
    </div>
	</form>
</div>

<script>
function calculateAge() {
    let dob = document.querySelector("input[name='dob']").value;
    if (dob) {
        let today = new Date();
        let birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        let monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        document.getElementById("age").value = age;
    }
}
</script>