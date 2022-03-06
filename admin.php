<!-- admin.php
Created by Joseph Ridgway
11/20/21 -->

<?php
	
	//Establishes database connection
	require_once("connection.php");

	session_start();

	if(!isset($_SESSION["admin"]) || $_SESSION["admin"] == false){
		header("location: index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>
</head>
<body style="padding-top:50px; background-color: steelblue;">
	<a href="closesession.php"><button class="position-absolute top-0 end-0">Logout</button></a>
	<div class="container-fluid border justify-content-center" style="width: 1000px; padding-top: 25px; padding-left: 45px; padding-bottom: 10px;">
		<form action="/admin.php" method="post">
			<button type="submit" class="btn btn-light p-3" name="doctorInformation">Doctor Information</button>
			<button type="submit" class="btn btn-light p-3" name="nurseInformation">Nurse Information</button>
			<button type="submit" class="btn btn-light p-3" name="patientInformation">Patient Information</button>
			<button type="submit" class="btn btn-light p-3" name="appointments">Appointments</button>
			<button type="submit" class="btn btn-light p-3" name="operations">Operations</button>
			<button type="submit" class="btn btn-light p-3" name="departments">Departments</button>
		</form>
	</div><br><br>
<?php
	if($_SERVER['REQUEST_METHOD'] =="POST") {

		//Add Patient!!!
		if(isset($_POST['appointments'])) {
			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Appointments', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"appointmentTable\">
			<thead>
			<tr>
			<th>Patient</th>
			<th>Department</th>
			<th>Doctor</th>
			<th>Date and Time</th>
			<th>Room Number</th>
			</tr>
			</thead>";
			foreach ($cursor as $document) {
				$filter = ['_id' => $document->patientID];
				$options = ['projection' => ['firstName' => 1, 'lastName' => 1]];
				$query = new MongoDB\Driver\Query($filter, $options);
				$patientCursor = $m->executeQuery('hospital.Patients', $query);
				foreach ($patientCursor as $patientDocument) {
					echo "<tr><td>"
					. $patientDocument->lastName
					. ", "
					. $patientDocument->firstName
					. "</td>";
				}
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

				echo "<td>"
				. $document->dateTime
				. "</td>"
				. "<td>"
				. $document->roomNumber
				. "</td>"
				. "</tr>";
			}

			echo "<a href=\"insertappointment.php\"><button>Insert New</button></a>";
		}
		if(isset($_POST['operations'])) {
			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Operations', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"operationTable\">
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
				echo "<tr><td id=\"field\">"
				. $document->operationType
				. "</td>";

				$filter = ['_id' => $document->departmentID];
				$options = ['projection' => ['departmentName' => 1]];
				$query = new MongoDB\Driver\Query($filter, $options);
				$departmentCursor = $m->executeQuery('hospital.Departments', $query);

				foreach ($departmentCursor as $departmentDocument) {
					echo "<td id=\"field\">"
					. $departmentDocument->departmentName
					. "</td>";
				}
				$filter = ['_id' => $document->doctorID];
				$options = ['projection' => ['lastName' => 1, 'firstName' => 1]];
				$query = new MongoDB\Driver\Query($filter, $options);
				$doctorCursor = $m->executeQuery('hospital.Doctors', $query);

				foreach ($doctorCursor as $doctorDocument) {
					echo "<td id=\"field\">"
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
					echo "<td id=\"field\">"
					. $nurseDocument->lastName
					. ", "
					. $nurseDocument->firstName
					. "</td>";
				}

				echo "<td id=\"field\">$"
				. $document->cost
				. "</td>"
				. "<td id=\"field\">"
				. $document->dateTime
				. "</td>"
				. "<td id=\"field\">"
				. $document->roomNumber
				. "</td>"
				. "</tr>";
			}

			echo "<a href=\"insertoperation.php\"><button>Insert New</button></a>";
		}
		if(isset($_POST['patientInformation'])) {
			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Patients', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"patientTable\">
			<thead>
			<tr>
			<th>ID</th>
			<th>Name</th>
			<th>SSN</th>
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
				. $document->_id
				. "</td>"
				. "<td>"
				. $document->lastName
				. ", "
				. $document->firstName
				. "</td>"
				. "<td>"
				. $document->SSN
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

			echo "<a href=\"insertpatient.php\"><button>Insert New</button></a>";
		}
		if(isset($_POST['nurseInformation'])) {

			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Nurses', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"nurseTable\">
			<thead>
			<tr>
			<th>ID</th>
			<th>Name</th>
			<th>SSN</th>
			<th>DOB</th>
			<th>Phone Number</th>
			<th>Address</th>
			<th>Department</th>
			</tr>
			</thead>";

			foreach ($cursor as $document) {
				echo
				"<tr>"
				. "<td>"
				. $document->_id
				. "</td>"
				. "<td>"
				. $document->lastName
				. ", "
				. $document->firstName
				. "</td>"
				. "<td>"
				. $document->SSN
				. "</td>"
				. "<td>"
				. $document->DOB
				. "</td>"
				. "<td>"
				. $document->phoneNumber
				. "</td>"
				. "<td>"
				. $document->address
				. "</td>";

				$filter = ['_id' => $document->departmentID];
				$options = ['projection' => ['departmentName' => 1]];
				$query = new MongoDB\Driver\Query($filter, $options);
				$departmentCursor = $m->executeQuery('hospital.Departments', $query);

				foreach ($departmentCursor as $departmentDocument) {
					echo "<td>"
					. $departmentDocument->departmentName
					. "</td>"
					. "</tr>";
				}				
			}

			echo "<a href=\"insertnurse.php\"><button>Insert New</button></a>";
		}
		if(isset($_POST['doctorInformation'])) {

			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Doctors', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"doctorTable\">
			<thead>
			<tr>
			<th>ID</th>
			<th>Name</th>
			<th>SSN</th>
			<th>DOB</th>
			<th>Phone Number</th>
			<th>Address</th>
			<th>Department</th>
			</tr>
			</thead>";
			foreach ($cursor as $document) {
				echo
				"<tr>"
				. "<td>"
				. $document->_id
				. "</td>"
				. "<td>"
				. $document->lastName
				. ", "
				. $document->firstName
				. "</td>"
				. "<td>"
				. $document->SSN
				. "</td>"
				. "<td>"
				. $document->DOB
				. "</td>"
				. "<td>"
				. $document->phoneNumber
				. "</td>"
				. "<td>"
				. $document->address
				. "</td>";

				$filter = ['_id' => $document->departmentID];
				$options = ['projection' => ['departmentName' => 1]];
				$query = new MongoDB\Driver\Query($filter, $options);
				$departmentCursor = $m->executeQuery('hospital.Departments', $query);

				foreach ($departmentCursor as $departmentDocument) {
					echo "<td>"
					. $departmentDocument->departmentName
					. "</td>"
					. "</tr>";
				}
			}

			echo "<a href=\"insertdoctor.php\"><button>Insert New</button></a>";
		}
		if(isset($_POST['departments'])) {

			$filter = [];
			$options = [];
			$query = new MongoDB\Driver\Query($filter, $options);
			$cursor = $m->executeQuery('hospital.Departments', $query);

			echo "<table width=\"100%\" class=\"table table-striped\" id=\"doctorTable\">
			<thead>
			<tr>
			<th>ID</th>
			<th>Department Name</th>
			</tr>
			</thead>";
			foreach ($cursor as $document) {
				echo
				"<tr>"
				. "<td>"
				. $document->_id
				. "</td>"
				. "<td>"
				. $document->departmentName
				. "</td>"
				. "</tr>";
			}
		}
	}
?>
</body>
</html>