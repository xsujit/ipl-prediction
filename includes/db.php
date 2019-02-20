<?php
require_once('config.php');

class Database
{
	private $dbconn;
	function __construct()
	{
		$this->dbconn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->dbconn->connect_error)
			die("Error connecting to the database: " . $this->dbconn->connect_error);
	}
	public function getConnection()
	{
		return $this->dbconn;
	}
	public function executeQuery($query)
	{
		$resultSet = $this->dbconn->query($query);
		if(is_object($resultSet))
		{
			return $resultSet;	
		}
		elseif($resultSet === true)
		{
			return $resultSet;
		}
		else
		{
			$errdetails = ["sqlstate" => $this->getSQLState(), "errno" => $this->getSQLerrno(), "errdesc" => $this->getSQLerrdesc()];
			return $errdetails;
		}
	}
	public function getSQLState()
	{
		return $this->dbconn->sqlstate;
	}
	public function closeConnection()
	{
		$this->dbconn->close();
	}
	public function getSQLerrno()
	{
		return $this->dbconn->errno;
	}
	public function getSQLerrdesc()
	{
		return $this->dbconn->error;
	}
}

$database = new Database;
?>