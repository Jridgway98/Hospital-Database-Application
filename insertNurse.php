<!-- home.php
Created by Joseph Ridgway
11/20/21 -->

<?php
require_once("connection.php");

	session_start();

	if(!isset($_SESSION["admin"]) || $_SESSION["admin"] == false){
		header("location: index.php");
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$lastname = $_POST['lastname'];
		$firstname = $_POST['firstname'];
		$ssn = $_POST['ssn'];
		$dob = $_POST['dob'];
		$phonenumber = $_POST['phonenumber'];
		$department = $_POST['department'];
		$address = $_POST['address'];

		$bulk = new MongoDB\Driver\BulkWrite;

		$document = ['_id' => new MongoDB\BSON\ObjectID, 'lastName' => $lastname, 'firstName' => $firstname,'SSN' => $ssn, 'DOB' => $dob, 'phoneNumber' => $phonenumber, 'address' => $address, 'departmentID' => new MongoDB\BSON\ObjectId($department)];

		$bulk->insert($document);
		$m->executeBulkWrite('hospital.Nurses', $bulk);

		header("location: admin.php");

	}

	$filter = [];
	$options = ['projection' => ['_id' => 1]];
	$query = new MongoDB\Driver\Query($filter, $options);
	$Cursor = $m->executeQuery('hospital.Departments', $query);
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insert a New Nurse</title>
</head>
<body style="background-color: steelblue;">
	<h3>Insert a New Nurse</h3>
	<form action="insertnurse.php" method="POST">
		<ul class="list-group">
			<li class="list-group-item">Last Name:<input type="text" name="lastname"></input></li>
			<li class="list-group-item">First Name:<input type="text" name="firstname">
			<li class="list-group-item">SSN:<input type="text" name="ssn"></input></li>
			<li class="list-group-item">DOB:<input type="text" name="dob"></input></li>
			<li class="list-group-item">Phone Number:<input type="text" name="phonenumber"></input></li>
			<li class="list-group-item">Department:<select name="department">
				<?php
					foreach ($Cursor as $document) {
						echo
						"<option>"
						. $document->_id
						. "</option>";
					}
				?>
			</select></li>
			<li class="list-group-item">Address:<input type="text" name="address"></input></li>
		</ul>
		<input type="submit">
	</form>
</body>
</html>