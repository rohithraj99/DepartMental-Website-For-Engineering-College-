<!--
This website is Developed by Parshuram Reddy (parshuram.sudda@gmail.com && VishnuVardhanReddy(vishnurapuru10@gmail.com)
This can be edited for free 
For More details and decsription contact Authors
This is Useful for Engineering Colleges Respectivly 
-->
<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', 'root', 'registration');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user)
  { 
      // if user exists
    if ($user['username'] === $username) 
    {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
      
   echo "<script type=\"text/javascript\">
    alert('User Name ".$year." . Registered')
    </script>";

  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1)
    {
  	  $_SESSION['username'] = $username;
         echo "<script type=\"text/javascript\">
    alert('User Name ".$year." . Logged in ')
    </script>";
  	  $_SESSION['success'] = "You are now logged in";
        
        
        
        
        
        if($username=='Hod')
         {
            if(isset($_SESSION['url'])) 
             $url = $_SESSION['url']; // holds url for last page visited.
             else 
             $url = "index.php"; // default page for 
             echo "<script type=\"text/javascript\">
    alert('Hod Logged in')
    </script>";
                header("Location: HodIndex.php"); // perform correct redirect.
                 
         }
       
        else
        {
             if(isset($_SESSION['url'])) 
             $url = $_SESSION['url']; // holds url for last page visited.
             else 
             $url = "index.php"; // default page for 
            header("Location:Faculty.php"); // perform correct redirect.
              
        }
        
  	}
      
      
      else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>
