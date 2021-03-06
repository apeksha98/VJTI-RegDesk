<?php
	/*
	 * @author: Arpita Karkera
	 * @date: 5th December, 2016
	 * 
	 * Login logic. To be included in index page
	 *
	 */

	// get the database constants
	require_once(__DIR__ . '/../includes/dbconfig.php');

	// if the user isn't logged in, try to login
	if (!isset($_SESSION['user_id'])) {
		// if the user tried to login
		if (isset($_POST['submit'])) {
			// connect to database
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to database.');

			// grab the login data entered by user
			$user_email = mysqli_real_escape_string($dbc, trim($_POST['user_email']));
			$user_password = mysqli_real_escape_string($dbc, trim($_POST['user_password']));

			// query the database if email and password are not empty
			if (!empty($user_email) && !empty($user_password)) {
				// look up email and password in the database
				$query = "SELECT user_id, first_name, verified from users WHERE email = '$user_email' AND password = SHA('$user_password')";
				$result = mysqli_query($dbc,$query);

				if (mysqli_num_rows($result) == 1) {
					$row = mysqli_fetch_array($result);
					$verified = $row['verified'];
					if ($verified) {
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['name'] = $row['first_name'];
						header('Location: public/dashboard.php');
					}
					else {
						// account is not activated yet
						$err_msg = "You have not activated your account yet.";
					}
				}
				else {
					// email or password was incorrect so set the error message
					$err_msg = "E-mail or password is incorrect.";
				}
			}
			else
				$err_msg = "E-mail or password missing.";
		}
	}
	/*
	else {
		// user is logged in do redirect to dashboard
		header('Location: public/dashboard.php');
	}*/
?>