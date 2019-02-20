<?php

require_once('db.php');

class Match
{
	public static function findActiveMatches()
	{
		global $database;
		$query = "SELECT m.week AS week, m.id AS mid, m.visible, m.matchdate AS matchdate, t1.id AS t1id, t1.name AS t1name, t1.shortname AS t1sname, 
					t2.id AS t2id, t2.name AS t2name, t2.shortname AS t2sname,
					m.question AS question, s.name AS stadium, s.city AS city, m.daynight AS daynight, m.jackpot
					FROM matches AS m
					INNER JOIN teams AS t1 ON m.team1 = t1.id
					INNER JOIN teams AS t2 ON m.team2 = t2.id
					INNER JOIN stadiums AS s ON m.stadium = s.id
                    ORDER BY matchdate, daynight
					"
					;
		$resultObject = $database->executeQuery($query);
		if(is_object($resultObject))
		{
			if($resultObject->num_rows > 0)
			{
				$resultsArray = [];
				foreach($resultObject as $record)
				{
					$resultsArray[] = $record;
				}
				$resultObject->free_result();
				$resultsArray = array_combine(range(1, count($resultsArray)), array_values($resultsArray)); // index of the array will start from 1
				foreach($resultsArray as $key=>$arr) // keep only the visible matches in the array
				{
					if($arr['visible'] != 1)
						unset($resultsArray[$key]);
				}
				if(count($resultsArray) === 0)
				{
					return false;
				}
				return $resultsArray;
			}
			else
				return false;
		}
		else
			return false;
	}
	public static function getWeeklyMatches($userid)
	{
		global $database;
		$query = "SELECT m.week, m.matchdate, t1.name AS team1, t1.shortname AS t1sname, t2.name AS team2, t2.shortname AS t2sname,
					t3.name AS mresult, t3.shortname AS t3sname, t4.name AS mprediction, t4.shortname AS t4sname,
					m.jackpot AS jackpot, m.question AS question, r.qresult AS answer, p.answer AS gprediction
					FROM matches AS m
					LEFT JOIN results AS r ON r.mid = m.id
					LEFT JOIN predictions AS p ON p.matchid = m.id AND p.userid = $userid
					LEFT JOIN teams as t1 ON t1.id = m.team1
					LEFT JOIN teams as t2 ON t2.id = m.team2
					LEFT JOIN teams as t3 ON t3.id = r.mresult
					LEFT JOIN teams as t4 ON t4.id = p.teamid
					ORDER BY m.matchdate
					";
		$resultObject = $database->executeQuery($query);
		if(is_object($resultObject))
		{
			if($resultObject->num_rows > 0)
			{
				$resultsArray = [];
				foreach($resultObject as $record) //convert result object to array
				{
					$resultsArray[] = $record;
				}
				$resultObject->free_result();
				return $resultsArray;
			}
			return false;
		}
		else
			return false;
	}
	public static function getWeeklyResults()
	{
		global $database;
		$query = "SELECT m.week, u.fname AS fname, u.lname as lname, SUM(s.mpoints) AS mpoints, SUM(s.gpoints) AS gpoints, SUM(s.total) AS total, 
						SUM(s.wpoints) AS wpoints, a.amount AS amt, a.pstatus as pstatus
						FROM standings AS s
						INNER JOIN users AS u ON u.id=s.userid
						INNER JOIN matches AS m ON s.mid=m.id
                        LEFT JOIN amount AS a on a.userid=s.userid AND a.week=m.week
						GROUP BY m.week, u.id
                        ORDER by m.week DESC, wpoints DESC, fname
						";
		$resultObject = $database->executeQuery($query);
		if(is_object($resultObject))
		{
			if($resultObject->num_rows > 0)
			{
				$resultsArray = [];
				foreach($resultObject as $record) //convert result object to array
				{
					$resultsArray[] = $record;
				}
				$resultObject->free_result();
				return $resultsArray;
			}
			else
				return false;
		}
		else
			return false;
	}
	public static function getOverallPoints()
	{
		global $database;
		$query = "SELECT u.id as uid, u.fname AS fname, u.lname AS lname, SUM(s.mpoints) AS mpoints, SUM(s.gpoints) AS gpoints, SUM(s.total) AS total
					FROM standings AS s
					INNER JOIN users AS u ON u.id=s.userid
					INNER JOIN matches AS m ON s.mid=m.id
					GROUP BY s.userid
					ORDER BY total DESC, u.fname
					";
		$resultObject = $database->executeQuery($query);
		if(is_object($resultObject))
		{
			if($resultObject->num_rows > 0)
			{
				$resultsArray = [];
				foreach($resultObject as $record) //convert result object to array
				{
					$resultsArray[] = $record;
				}
				$resultObject->free_result();
				return $resultsArray;
			}
			else
				return false;
		}
		else
			return false;
	}
}

?>