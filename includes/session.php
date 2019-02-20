<?php
require_once('user.php');
class Session
{
	private $loggedIn=false;
	private $timedOut=false;
	public $lastactivity;
	public $userid;
	
	function __construct()
	{
		session_start();
		$this->authSession();
	}
	private function authSession() // initialize class variables based on session
	{
		if(isset($_SESSION['userid']))
		{
			$this->lastactivity = $_SESSION['lastactivity'];
			$this->userid       = $_SESSION['userid'];
			$this->loggedIn     = true;
			if($this->isSessionActive())
			{
				$this->updateLastActivity();
			}
			else
			{
				$this->timedOut = true;
			}
		}
	}
	public function isSessionActive()
	{
		if((time() - $this->lastactivity) > 3600)
			return false;
		else
			return true;
	}	
	public function login($user) // used when a user logs in from the login page
	{
		$this->userid       = $_SESSION['userid']       = $user->getUserid();
		$this->lastactivity = $_SESSION['lastactivity'] = time();
		$this->loggedIn     = true;
	}
	public function logout()
	{
		unset($this->userid);
		unset($this->lastactivity);
		session_unset();
		session_destroy();
	}
	public function getLogin()
	{
		return $this->loggedIn;
	}
	public function getTimedOut()
	{
		return $this->timedOut;
	}
	private function updateLastActivity()
	{
		$this->lastactivity = $_SESSION['lastactivity'] = time();
	}
	public function createUser()
	{
		if($this->loggedIn)
		{
			if(!$this->timedOut)
			{
				$userRecord  = User::findUserByID($this->userid);
				$user        = User::instantiate($userRecord);
				return $user;	
			}
			else
			{
				$this->logout();
				return "timedout";
			}
		}
		else
		{
			$this->logout();
			return "nologin";
		}
	}
}

$session = new Session;

?>