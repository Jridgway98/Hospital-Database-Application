<!-- index.php
Created by Joseph Ridgway
11/20/21 -->

<?php
	require_once("connection.php");

	session_start();

	if(isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
		header("location: admin.php");
		exit;
	}

	else if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
		header("location: home.php");
		exit;
	}

	$username = $password = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = ($_POST["username"]);
		$password = ($_POST["password"]);

		$filter = ['Username' => $username, 'Password' => $password];
		$options = ['projection' => ['user_id' => 1, 'Admin' => 1]];

		$query = new MongoDB\Driver\Query($filter, $options);

		$cursor = $m->executeQuery('hospital.Users', $query);
		$cursor = iterator_to_array($cursor);
		$user_id = "";
		if(sizeof($cursor) == 1) {
			foreach ($cursor as $document) {
				$user_id = $document->user_id;
				$admin = $document->Admin;		
			}

			session_start();

			$_SESSION["loggedIn"] = true;
			$_SESSION["user_id"] = $user_id;
			$_SESSION["admin"] = $admin;
			
			if(!$_SESSION["admin"]) {
				header("location: home.php");
			}
			else {
				header("location: admin.php");
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Log in to your Hospital Account	</title>
</head>
<body style="background-color: steelblue;">

	<div class="d-flex justify-content-center">
		<h1>Hospital Login</h1>
	</div>

	<div class="d-flex justify-content-center">
		<form action="/index.php" method="post">
			<label for="uname">Username:</label><br>
			<input type="text" id="username" name="username" autofocus required><br>
			<label for="pword">Password:</label><br>
			<input type="password" id="password" name="password" required><br>
			<input type="submit" class="btn btn-light">
		</form>
	</div>
	</body>
</html>