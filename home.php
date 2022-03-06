<!-- home.php
Created by Joseph Ridgway
11/20/21 -->

<?php
	//Establishes database connection
	require_once('connection.php');
	//Loads php session
	session_start();


	if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] == false || $_SESSION["admin"] == true){
		header("location: index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
</head>
<body style="padding-top:50px; background-color: steelblue;">
	<a href="closesession.php"><button class="position-absolute top-0 end-0">Logout</button></a>
	<div class="container-fluid border justify-content-center" style="width: 580px; padding-top: 25px; padding-left: 75px; padding-bottom: 10px;">
		<form action="/home.php" method="post">
			<button type="submit" class="btn btn-light p-3" name="patientInformation">Patient Information</button>
			<button type="submit" class="btn btn-light p-3" name="appointments">Appointments</button>
			<button type="submit" class="btn btn-light p-3" name="operations">Operations</button>
		</form>
	</div><br><br>

	<?php
		if($_SERVER['REQUEST_METHOD'] =="POST") {
			if(isset($_POST['patientInformation'])) {

				$filter = ['_id' => $_SESSION["user_id"]];
				$options = ['projection' => ['lastName' => 1,
					'firstName' => 1,
					'DOB' => 1,
					'phoneNumber' => 1,
					'address' => 1,
					'insurance' => 1]];

				$query = new MongoDB\Driver\Query($filter, $options);

				$cursor = $m->executeQuery('hospital.Patients', $query);


				echo "<table width=\"100%\" class=\"table table-striped\">
				<thead>
				<tr>
				<th>Name</th>
				<th>DOB</th>
				<th>Phone Number</th>
				<th>Address</th>
				<th>Insurance</th>
				</tr>
				</thead>";
				foreach ($cursor as $document) {
					echo
					"<tr>"
					. "<td>"
					. $document->lastName
					. ", "
					. $document->firstName
					. "</td>"
					. "<td>"
					. $document->DOB
					. "</td>"
					. "<td>"
					. $document->phoneNumber
					. "</td>"
					. "<td>"
					. $document->address
					. "</td>"
					. "<td>"
					. $document->insurance
					. "</td>"
					. "</tr>";
					
				}
			}
			if(isset($_POST['appointments'])) {
				$filter = ['patientID' => $_SESSION["user_id"]];
				$options = [];

				$query = new MongoDB\Driver\Query($filter, $options);

				$cursor = $m->executeQuery('hospital.Appointments', $query);




				echo "<table width=\"100%\" class=\"table table-striped\">
				<thead>
				<tr>
				<th>Department</th>
				<th>Doctor</th>
				<th>Date and Time</th>
				<th>Room Number</th>
				</tr>
				</thead>";
				foreach ($cursor as $document) {
					$filter = ['_id' => $document->departmentID];
					$options = ['projection' => ['departmentName' => 1]];
					$query = new MongoDB\Driver\Query($filter, $options);
					$departmentCursor = $m->executeQuery('hospital.Departments', $query);
					foreach ($departmentCursor as $departmentDocument) {
						echo "<tr><td>"
						. $departmentDocument->departmentName
						. "</td>";
					}

					$filter = ['_id' => $document->doctorID];
					$options = ['projection' => ['lastName' => 1, 'firstName' => 1]];
					$query = new MongoDB\Driver\Query($filter, $options);
					$doctorCursor = $m->executeQuery('hospital.Doctors', $query);
					foreach ($doctorCursor as $doctorDocument) {
						echo "<td>"
						. $doctorDocument->lastName
						. ", "
						. $doctorDocument->firstName
						. "</td>";
					}

					echo "<td>"
					. $document->dateTime
					. "</td>"
					. "<td>"
					. $document->roomNumber
					."</td></tr>";
				}
			}
			if(isset($_POST['operations'])) {
				$filter = ['patientID' => $_SESSION["user_id"]];
				$options = [];

				$query = new MongoDB\Driver\Query($filter, $options);

				$cursor = $m->executeQuery('hospital.Operations', $query);
				echo "<table width=\"100%\" class=\"table table-striped\">
				<thead>
				<tr>
				<th>Operation Type</th>
				<th>Department</th>
				<th>Doctor</th>
				<th>Nurse</th>
				<th>Cost</th>
				<th>Date and Time</th>
				<th>Room Number</th>
				</tr>
				</thead>";

				foreach ($cursor as $document) {
					
					echo "<tr><td>"
					. $document->operationType
					. "</td>";
					$filter = ['_id' => $document->departmentID];
					$options = ['projection' => ['departmentName' => 1]];
					$query = new MongoDB\Driver\Query($filter, $options);
					$departmentCursor = $m->executeQuery('hospital.Departments', $query);
					foreach ($departmentCursor as $departmentDocument) {
						echo "<td>"
						. $departmentDocument->departmentName
						. "</td>";
					}
					$filter = ['_id' => $document->doctorID];
					$options = ['projection' => ['lastName' => 1, 'firstName' => 1]];
					$query = new MongoDB\Driver\Query($filter, $options);
					$doctorCursor = $m->executeQuery('hospital.Doctors', $query);
					foreach ($doctorCursor as $doctorDocument) {
						echo "<td>"
						. $doctorDocument->lastName
						. ", "
						. $doctorDocument->firstName
						. "</td>";
					}
					$filter = ['_id' => $document->nurseID];
					$options = ['projection' => ['lastName' => 1, 'firstName' => 1]];
					$query = new MongoDB\Driver\Query($filter, $options);
					$nurseCursor = $m->executeQuery('hospital.Nurses', $query);
					foreach ($nurseCursor as $nurseDocument) {
						echo "<td>"
						. $nurseDocument->lastName
						. ", "
						. $nurseDocument->firstName
						. "</td>";
					}
					echo "<td>$"
					. $document->cost
					. "</td>";
					echo "<td>"
					. $document->dateTime
					. "</td>";
					echo "<td>"
					. $document->roomNumber
					. "</td></tr>";
				}
			}
		}
	?>

</body>
</html>