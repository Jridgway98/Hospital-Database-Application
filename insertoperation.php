<?php
require_once("connection.php");

	session_start();

	if(!isset($_SESSION["admin"]) || $_SESSION["admin"] == false){
		header("location: index.php");
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$operationtype = $_POST['operationtype'];
		$nurse = $_POST['nurse'];
		$doctor = $_POST['doctor'];
		$patient = $_POST['patient'];
		$cost = $_POST['cost'];
		$roomnumber = $_POST['roomnumber'];
		$datetime = $_POST['datetime'];
		$department = $_POST['department'];

		$bulk = new MongoDB\Driver\BulkWrite;

		$document = ['_id' => new MongoDB\BSON\ObjectID, 'operationType' => $operationtype, 'nurseID' => new MongoDB\BSON\ObjectId($nurse), 'doctorID' => new MongoDB\BSON\ObjectId($doctor),
			'patientID' => new MongoDB\BSON\ObjectId($patient),
			'departmentID' => new MongoDB\BSON\ObjectId($department), 'cost' => $cost, 'roomNumber' => $roomnumber,
			'dateTime' => $datetime];

		$bulk->insert($document);
		$m->executeBulkWrite('hospital.Operations', $bulk);

		header("location: admin.php");

	}

	$filter = [];
	$options = ['projection' => ['_id' => 1]];
	$query = new MongoDB\Driver\Query($filter, $options);
	$doctorCursor = $m->executeQuery('hospital.Doctors', $query);
		
	$patientCursor = $m->executeQuery('hospital.Patients', $query);

	$nurseCursor = $m->executeQuery('hospital.Nurses', $query);

	$departmentCursor = $m ->executeQuery('hospital.Departments', $query);
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insert a New Operation</title>
</head>
<body style="background-color: steelblue;">
	<h3>Insert a New Operation</h3>
	<form action="insertoperation.php" method="POST">
		<ul class="list-group">
			<li class="list-group-item">Operation Type:<input type="text" name="operationtype"></input></li>
			<li class="list-group-item">Nurse:<select name="nurse">
				<?php
					foreach ($nurseCursor as $document) {
						echo
						"<option>"
						. $document->_id
						. "</option>";
					}
				?>
			</select></li>
			<li class="list-group-item">Doctor:<select name="doctor">
				<?php
					foreach ($doctorCursor as $document) {
						echo
						"<option>"
						. $document->_id
						. "</option>";
					}
				?>
			</select></li>
			<li class="list-group-item">Patient:<select name="patient">
				<?php
					foreach ($patientCursor as $document) {
						echo 
						"<option>"
						. $document->_id
						. "</option>";
					}

				?>
			</select></li>
			<li class="list-group-item">Cost:<input type="text" name="cost"></input></li>
			<li class="list-group-item">Room Number:<input type="text" name="roomnumber"></input></li>
			<li class="list-group-item">Date and Time:<input type="text" name="datetime"></input></li>
			<li class="list-group-item">Department:<select name="department">
				<?php
					foreach ($departmentCursor as $document) {
						echo
						"<option>"
						. $document->_id
						. "</option>";
					}
				?>
			</select></li>
		</ul>
		<input type="submit">
	</form>
</body>
</html>