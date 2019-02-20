<?php
require_once('includes/functions.php');
require_once('includes/registration.php');

if(isset($_POST['register']))
{
	$result = Registration::registerUser($_POST);
	if(is_bool($result))
	{
		if($result === true)
		{
			header("Location:index.php?success=" . 
			urlencode("Registration successful! Your account is now active - you may login to the app using your registered email and password"));
			exit();	
		}
	}
	elseif(is_array($result))
	{
		if(key($result) == "email")
		{
			$email = $result['email'];
			header("Location:register.php?err=" . urlencode("The email " . "\"$email\"" . " already exists in the system. If this is your account and have forgotten the password, please contact the system administrator for password reset"));
			exit();
		}
		elseif(key($result) == "validation")
		{
			header("Location:register.php?err=" . urlencode($result['validation']));
			exit();
		}
		elseif(key($result) == "other")
		{
			header("Location:register.php?err=" . urlencode("Sorry, registration failed: " . $result['other']));
			exit();
		}	
	}
}

opHTMLHeader("Registration");
opHTMLNavHeader();
?>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Login</a></li>
            <li class="active"><a href="register.php">Register</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
    <div class="container">
	<div class="page-header">
		<h2>Welcome! <small>Register yourself to play SIMS IPL 2017</small></h2>      
	</div>
	
<?php
	if(isset($_GET['err']) || isset($_GET['success']))
	{
		if(isset($_GET['err']))
		{
			echo '<div class="alert alert-danger">';
			echo $_GET['err'];
			echo '</div>';
		}
		elseif(isset($_GET['success']))
		{
			echo '<div class="alert alert-success">';
			echo $_GET['success'];
			echo '</div>';
		}
	}
	$enddate = new DateTime('2017-03-31');
	$now = new DateTime();
	if ($now > $enddate)
	{
		echo '<div class="alert alert-danger">';
		echo "Sorry, registration closed on " . $enddate->format('d M, Y');
		echo '</div>';
	}
	else
	{
?>
	
	<form method="post" action="register.php" style="margin-top:35px;">
	  <div class="form-group">
		<label for="fname">First Name</label>
		<input type="text" name = "fname" id="fname" class="form-control" data-toggle="tooltip" 
		data-placement="left" title="Between 2 - 20 characters long" placeholder="First Name" required value="" autofocus>
	  </div>
	  <div class="form-group">
		<label for="lname">Last Name</label>
		<input type="text" name = "lname" id="lname" class="form-control" data-toggle="tooltip" 
		data-placement="left" title="Between 2 - 20 characters long" placeholder="Last Name" required value="">
	  </div>
	  <div class="form-group">
		<label for="email">Email address</label>
		<input type="email" name="email" id="email" class="form-control" data-toggle="tooltip" 
		data-placement="left" title="Your Mastek Email" placeholder="Email ID" required value="">
	  </div>
	  <div class="form-group">
		<label for="password">Password</label>
		<input type="password" name = "password" id="password" class="form-control" data-toggle="tooltip" 
		data-placement="left" title="Between 4 - 12 characters long" placeholder="Password" required value="">
	  </div>
	  <div class="form-group">
		<label for="confirmpwd">Confirm Password</label>
		<input type="password" name = "confirmpwd" id="confirmpwd" class="form-control" data-toggle="tooltip" 
		data-placement="left" title="Between 4 - 12 characters long" placeholder="Confirm Password" required value="">
		<span class="help-block">Password can contain Alpha-numeric characters only</span>
	  </div>
	  <button type="submit" name = "register" class="btn btn-primary">Register Me</button>
	</form>
<?php
	}
?>
	<hr>
<?php
	opHTMLFooter();
?>