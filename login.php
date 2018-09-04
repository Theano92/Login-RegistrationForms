<?php
require 'debug.include.php' ;
require('authentication.include.php');

 $con = database_connect();

if (isset($_SESSION['uid'])) {
	
	exit(); 
}



if (! isset($_POST['username']) || ! isset($_POST['password']) ) { // if the user tries to login without typing the username and the password
	
	die("No username or password submitted."); 
}

$username=mysqli_real_escape_string($con, $_POST['username']);
$password=mysqli_real_escape_string($con, $_POST['password']);

$encrypted_password = password_encrypt($password);

//elegxos ean yparxei o sugekrimenos xristis
$results = mysqli_query($con,"select * from user where username='$username'AND encrypted_password='$encrypted_password'");


if(mysqli_affected_rows($con)>0) // if username and password exist in the db
{
	$row = $results->fetch_array(MYSQLI_ASSOC);
	$_SESSION['uid'] = $row['uid'];
	$_SESSION['photo'] = $row['photo'];
	header("Location: /vantheano/ptyxiaki.php"); 
    exit();
   
	
  
}
else 
{
	echo "<script>
           alert('Check the username or the password!!');
           window.location.href='/vantheano/ptyxiaki.php';
           </script>";
}

?>

</body>
</html>