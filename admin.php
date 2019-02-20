<?php
session_start();
include('includes/config.php');
include('includes/db.php');

if(!isset($_SESSION['loggedin']))
{
	/*
	if(isset($_SESSION['loggedin'] == 1)
	{
		
	}
	*/
	header("Location:index.php?err=" . urlencode("You are not authorized to view the admin page"));
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <title>Admin Section</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">IPL Contest</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Login</a></li>
            <li class="active"><a href="register.php">Register</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
	<h2>Vote for your teams here</h2>
	<hr>
	<form method="post" action="admin.php" style="margin-top:35px;">
	<?php
	$query = "select id, team1, team2, matchdate from matches";
	$resultmatches = $dbconn->query($query);
	if($resultmatches->num_rows > 0)
	{
		echo '<input type="radio" name="gender" value="male" checked> Male<br>';
		foreach($resultmatches as $row)
		{

		}
		echo '</select>';
		$query = "select name from teams";
		$result = $dbconn->query($query);
	}
	else
	{
		echo "There are no matches scheduled, please check back later";
	}

	?>
	<div class="form-group">
		<label for="exampleInputEmail1">Match 1 </label>
	<?php 

	?>
	</div>
	  <div class="form-group">
		<label for="exampleInputPassword1">Password</label>
		<input type="password" name = "password" class="form-control" placeholder="Password" required value="<?php echo @$_SESSION['password']; unset($_SESSION['password']); ?>">
	  </div>
	  <div class="form-group">
		<label for="exampleInputPassword1">Confirm Password</label>
		<input type="password" name = "confirm_password" class="form-control" placeholder="Confirm Password" required value="<?php echo @$_SESSION['confirm_password']; unset($_SESSION['confirm_password']); ?>">
	  </div>
	  
	  <button type="submit" name = "register" class="btn btn-default">Register</button>
	</form>
	
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    
  </body>
</html>
