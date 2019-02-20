<?php
require_once('db.php');
Class Registration
{	
	public static function registerUser($post)
	{
		global $database;
		$dbconn = $database->getConnection();
		if(isset($post))
		{
			$fname      = $post['fname'];
			$lname      = $post['lname'];
			$email      = $post['email'];
			$password   = $post['password'];
			$confirmpwd = $post['confirmpwd'];
			//$confirmpwd = mysqli_real_escape_string($dbconn, $post['confirmpwd']);
			if (!ctype_alpha($fname))
			{
				$error = ["validation" => "First Name can contain only alphabets"];
				return $error;
			}
			if (strlen($fname) < 2 || strlen($fname) > 20)
			{
				$error = ["validation" => "First name should be between 2 and 20 characters long"];
				return $error;
			}
			if (!ctype_alpha($lname))
			{
				$error = ["validation" => "Last Name can contain only alphabets"];
				return $error;
			}
			if(strlen($lname) < 2 || strlen($lname) > 20)
			{
				$error = ["validation" => "Last name should be between 2 and 20 characters long"];
				return $error;
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$errtext = "\"$email\"" . " is not a valid email address";
				$error = ["validation" => $errtext];
				return $error;
			}
			if (!ctype_alnum($password))
			{
				$error = ["validation" => "Password can contain only alpha-numeric characters"];
				return $error;
			}
			
			if(strlen($password) < 4 || strlen($password) > 12)
			{
				$error = ["validation" => "Password should be between 4 and 12 characters"];
				return $error;
			}
			
			if(!($password === $confirmpwd))
			{
				$error = ["validation" => "Password and Confirm Password do not match"];
				return $error;
			}
			$password   = password_hash($password, PASSWORD_DEFAULT);
			$token = bin2hex(openssl_random_pseudo_bytes(32));
			$query = "INSERT INTO users (fname, lname, email, password, token) VALUES ('$fname', '$lname', '$email', '$password', '$token')";
			$dbupdate = $database->executeQuery($query);
			if($dbupdate === true)
				return $dbupdate;
			elseif(is_array($dbupdate))
			{
				if($dbupdate['sqlstate'] == 23000 && $dbupdate['errno'] == 1062)
				{
					$errdetails = ["email" => $email];
					return $errdetails;	
				}
				else
				{
					$errdetails = ["other" => $dbupdate['errdesc']];
					return $errdetails;	
				}
			}
		}
	}		
}

?>