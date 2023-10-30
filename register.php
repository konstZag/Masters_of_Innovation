<style>
    <?php
    include ('form.css');
    ?>
</style>
<?php # register.php #

session_start();
$page_title = 'Register';
//include ('includes/header.html');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}

	// Check for a gender:
	if (isset($_POST['gender'])) {
		$g = mysqli_real_escape_string($dbc, trim($_POST['gender']));	
	} 

	// Check for a country:
	if (isset($_POST['country'])) {
		$c = mysqli_real_escape_string($dbc, trim($_POST['country']));
	}

	// Check for a country:
		if (empty($_POST['username'])) {
			$errors[] = 'You forgot to enter your username.';
		} else {
			$un = mysqli_real_escape_string($dbc, trim($_POST['username']));
		}

	// Check for a password and match against the confirmed password:
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Register the user in the database...
		
		// Make the query:
		$q = "INSERT INTO users (first_name, last_name, email, gender, country, username, pass, registration_date) VALUES ('$fn', '$ln', '$e','$g','$c','$un', SHA1('$p'), NOW() )";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
			<p>You are now registered!</p><p><br /></p>';
			//$_SESSION['status'] = "Inserted Succesfully";
			header("Location: index.php");	
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
			header("Location: index.php");
						
		} // End of if ($r) IF.
		
		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/footer.html'); 
		exit();
		
	} else { // Report the errors.
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<h1>Register</h1>
<form action="register.php" method="post">
	<div><label for="first_name">First Name: </label><input type="text" name="first_name" size="20" maxlength="20" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></div>
	<div><label for="last_name">Last Name: </label><input type="text" name="last_name" size="20" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></div>
	<div><label for="email">Email Address: </label><input type="email" name="email" size="20" maxlength="80" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </div>
	<div><label for="gender">Gender:</label><select class="form-select" id="gender" name="gender">
		<option value="Male">Male</option>
		<option value="Female">Female</option>
	</select></div>
	<div><label for="country">Country:</label><select id="country" name="country" >
    <option value="none">Select Country</option> 
    <?php
    // Read the JSON file  
    $json = file_get_contents('country.json'); 
    // Decode the JSON file 
    $json_data = json_decode($json,true); 
    foreach($json_data as $t) {
        echo '<option value="'. $t['name'].  '">'. $t['name'].'</option>';      
    } 
    ?>	
	</select></div>
	<div><label for="username">Username:</label> <input type="text" name="username" id="username" size="20" maxlength="80" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"/></div>
	<div><label for="pass1">Password: </label><input type="password" name="pass1" size="20" maxlength="20" /></div>
	<div><label for="pass2">Confirm Password: </label><input type="password" name="pass2" size="20" maxlength="20" /></div>
	<input type="submit" name="submit" value="Register" size="20"/>
	<input type="hidden" name="submitted" value="TRUE" />

</form>
<?php
include ('includes/footer.html');
?>
