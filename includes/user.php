<?php

require_once('db.php');
require_once('session.php');

class User
{
	private $userid;
	private $fname;
	private $lname;
	private $email;
	private $isadmin;
	
	public static function authenticate($email="", $givenpwd="")
    {
		global $database;
		$dbconn     = $database->getConnection();
		$email      = $dbconn->real_escape_string($email);
		$givenpwd   = $dbconn->real_escape_string($givenpwd);
		$userRecord = self::findUserByEmail($email);
		if(is_array($userRecord))
		{
			$dbpwd = $userRecord['password'];
			if(password_verify($givenpwd, $dbpwd))
			{
				global $session;
				$usrObj = self::instantiate($userRecord);
				$session->login($usrObj);
				return $usrObj;
			}
		}
		return false;
	}
	public function getFirstName()
	{
		return $this->fname;
	}
	public function getUserid()
	{
		return $this->userid;
	}
	
	public static function instantiate($userRecord)
	{
		$usrObj          	= new self;
		$usrObj->userid     = $userRecord['id'];   //set the class variables for the authenticated user
		$usrObj->fname   	= $userRecord['fname'];
		$usrObj->lname   	= $userRecord['lname'];
		$usrObj->email  	= $userRecord['email'];
		$usrObj->isadmin 	= $userRecord['isadmin'];
		return $usrObj;
	}
	
	public static function findUserByEmail($email)
	{
		global $database;
		$query = "select id, fname, lname, email, password, isadmin from users where email = '$email'"; 
		$userRs = $database->executeQuery($query);
		$userRecord = $userRs->fetch_assoc();
		$userRs->free_result();
		return $userRecord;
	}
	
	public static function findUserByID($id)
	{
		global $database;
		$query = "select id, fname, lname, email, password, isadmin from users where id = '$id'"; 		
		$userRs = $database->executeQuery($query);
		$userRecord = $userRs->fetch_assoc();
		$userRs->free_result();
		return $userRecord;
	}

	public function processPostRequest($postArray)
	{
		global $database;
		$query = "";
		$chkval = "";
		foreach ($postArray as $key => $value) 
		{
			if($key == "submit")
			{
				continue;
			}
			if(!is_string($key))
			{
				$query = $query . "('$this->userid', '$key', '$value', "; //create a bulk query string for insert
				$chkval = $key;
			}
			else
			{
				$key = substr($key, 1);
				if($chkval == $key) //additional check to ensure we append the correct googly prediction to the correct matchid
				$query = $query . "'$value'), ";
				unset($chkval);
			}
		}
		$query = "INSERT INTO predictions (userid, matchid, teamid, answer) VALUES " . $query;
		$query = substr($query, 0, -2);
		$query = $query . "ON DUPLICATE KEY UPDATE teamid=VALUES(teamid), answer=VALUES(answer)";
		$dbupdate = $database->executeQuery($query);
		if($dbupdate === true)
			return true;
		elseif(is_array($dbupdate))
		{
			$errdetails = ["other" => $dbupdate['errdesc']];
			return $errdetails;	
		}
	}
}

?>