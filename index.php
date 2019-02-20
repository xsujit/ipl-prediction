<?php

require_once("includes/user.php");
require_once("includes/session.php");
require_once("includes/functions.php");

if ($session->getLogin()) //if the user is logged in redirect them to the predictions page
{
	header("Location:predictions.php");
	exit();
}

if(isset($_POST['login']))
{
	$email    = $_POST['email'];
	$givenpwd = $_POST['password'];
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$errtext = "Validation error - " . "\"$email\"" . " is not a valid email address";
		header("Location:index.php?err=" . urlencode($errtext));
		exit();
	}
	elseif (!ctype_alnum($givenpwd))
	{
		header("Location:index.php?err=" . urlencode("Validation error - Passwordord can contain only alpha-numeric characters"));
		exit();
	}
	else
	{
		$userObj  = User::authenticate($email, $givenpwd);	
	}
	if ($userObj === false)
	{
		header("Location:index.php?err=" . urlencode("Authentication error - Username or Password is not correct"));
		exit();
	}
	elseif (is_object($userObj))
	{
		header("Location:predictions.php");
		exit();
	}
}

opHTMLHeader("Login");
opHTMLNavHeader();
?>
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Login</a></li>
			<li><a href="register.php">Register</a></li>
        </ul>
    </div><!--/.nav-collapse -->
    </div>
    </nav>

    <div class="container">
	<div class="page-header">
		<h2>Welcome! <small>Login with your account</small></h2>      
	</div>
	
	
	<?php
		if(isset($_GET['err']))
		{
			echo '<div class="alert alert-danger">' . $_GET['err'] . '</div>';
		} 
		if(isset($_GET['success']))
		{
			echo '<div class="alert alert-success">' .  $_GET['success'] . '</div>';
		}
	?>
	<form method="post" action="index.php" style="margin-top:35px;">
	  <div class="form-group">
		<label for="email">Email address</label>
		<input type="email" name = "email" id="email" class="form-control" placeholder="Email" required autofocus>
	  </div>
	  <div class="form-group">
		<label for="password">Password</label>
		<input type="password" name = "password" id="password" class="form-control" placeholder="Password" required>
	  </div>
	  <!--
	  <div class="checkbox">
		<label>
		  <input type="checkbox" name = "remember_me"> Remember Me
		</label>
	  </div>
	  -->
	  <button type="submit" name = "login" class="btn btn-primary">Login</button>
	  <!--
	  <a href="forgot_password.php">Forgot Password?</a>
	  -->
	</form>
	<p class="text-muted">Don't have an account? Click on Register to create one</p>
<?php
	opHTMLFooter();
?>
