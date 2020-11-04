<?php
session_start();
// esatblish connection with databse
$DATABASE_HOST = 'sql306.epizy.com';
$DATABASE_USER = 'epiz_26655262';
$DATABASE_PASS = 'yqCK6qp7tPwQ';
$DATABASE_NAME = 'epiz_26655262_asdf';
//connecting with sql server.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error .
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}




// check if user has given input.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}



if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result 
    
	$stmt->store_result();


	if ($stmt->num_rows > 0) {
		$stmt->bind_result($id, $password);
		$stmt->fetch();
		// if account exists we check passwd
		
		if ($_POST['password'] === $password) {
			// Verification successfull
			//start a session
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['username'];
			$_SESSION['id'] = $id;
			header('Location: upload.php');  // if password is correct then we redirect to this paghe
		} else {
			// Incorrect password
			echo 'Incorrect username and/or password!';
		}
	} else {
		// Incorrect username
		echo 'Incorrect username and/or password!';
	}


	$stmt->close();
}
?>




