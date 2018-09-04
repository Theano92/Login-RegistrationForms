<?php
require 'debug.include.php';
require 'authentication.include.php';
?>
<?php

$con = mysqli_connect("localhost","root","","vantheano") // connect with the database
or die("Failed to connect to MySQL: " . mysql_error());

if (isset($_POST['submit'])) // if te user clicks the submit button
{
	// retrieve from the form the username, the password, the email and the phone number using POST method
	$username = mysqli_real_escape_string($con,$_POST['username']);
	$password = mysqli_real_escape_string($con,$_POST['password']);
	$email = mysqli_real_escape_string($con,$_POST['email']);
	$phone = mysqli_real_escape_string($con,$_POST['phone']);
	if( $_FILES['photo']['tmp_name'] <> '' )
	{
		$photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));//insert the photo
	}
	else
	{
		$photo = '';
	}
	
	
	
	$error_msg = '';
	$success_msg = '';
	if ($username == '')
	{  
		$error_msg = 'Please enter your name!';
		
	}
	else if ($password == '')
	{
		$error_msg = 'Please enter your password!';
	}
	else if ($email == '')
	{
		$error_msg = 'Please enter your email!';
	}
	else
	{
		mysqli_query($con, "select * from user where username='$username'"); //check if the username already exists in the db
		if (mysqli_affected_rows($con) > 0)
		{
			$error_msg = 'Your username already exists. Please enter another username';
		}
		else
		{
			mysqli_query($con, "select * from user where email='$email'"); // check if the email already exists in the db
			if (mysqli_affected_rows($con) > 0)
			{
				$error_msg = 'Your email already exists. Please enter another email';
			}
			
			
			else
			{
				$encrypted_password = password_encrypt($password);
				// insert user's details into the database
				$query = "INSERT INTO user (username,encrypted_password,email,phone,photo) VALUES ('$username','$encrypted_password','$email','$phone','$photo')";
				
				if (mysqli_query($con, $query))
				{
					$success_msg = 'Your registration has been successful!';
				}
				else
				{
					$error_msg = 'Error has been detected!';
				}
			}
		}
	}
}

?>
<html>
<head>
	<title>sign up form</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<link rel="stylesheet" href="sign_up.css">
	
	<script>
	<?php 
	
	
	if( isset($error_msg) && $error_msg != '' )
	{   
        
		echo "alert ('" . $error_msg . "'); " . PHP_EOL;
		
	}
	
	if( isset($success_msg) && $success_msg != '' )
	{
		echo "alert ('" . $success_msg . "'); " . PHP_EOL;
		echo "window.location.href='/vantheano/ptyxiaki.php';" . PHP_EOL;
	}
	
	?>
	</script>
</head>
<!-- REGISTRATION FORM -->
<body>
	<form method="POST" action="sign_up.php" class="sign_up" enctype="multipart/form-data">
		<h1>Sign up</h1>
		
		*Username:<br> 
		<input type="username" name="username" class="sign_up-input" value="<?php if( isset($username) ) echo $username; ?>"> 
		
		*Password:<br>
		<input type="password" name="password" class="sign_up-input"> 
		
		*e-mail:<br>
		<input type="email" name="email" class="sign_up-input" value="<?php if( isset($email) ) echo $email; ?>">
		
		Phone number:<br>
		<input type="phone" name="phone" class="sign_up-input" value="<?php if( isset($phone) ) echo $phone; ?>">
		
		Profile picture:<br>
		<input type="file" name="photo" class="sign_up-input">
		
		<input type="submit" name="submit" value="Εγγραφή" class="sign_up-submit">
	</form>
</body>
</html>
