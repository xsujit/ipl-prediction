<?php

require_once("includes/session.php");
require_once("includes/user.php");
require_once("includes/match.php");
require_once("includes/functions.php");

$user = $session->createUser();
if(!is_object($user))
{
	if($user === "timedout")
	{
		header("Location:index.php?err=" . urlencode("Your session has ended as there was no activity for more than 30 minutes. Please login again"));
		exit();
	}
	elseif($user === "nologin")
	{
		header("Location:index.php?err=" . urlencode("You must be logged in to view this page"));
		exit();
	}
}
opHTMLHeader("Overall Points");
opHTMLNavHeader();
opHTMLNavbar("points");
?>
<div class="points-header">
	<h3>Overall Points Table<br>
	<small>Below you will see the overall standings of all the players</small></h3>
</div>
<?php
$resultsArray = Match::getOverallPoints();
if($resultsArray === false)
{
	echo '<div class="alert alert-danger">' . 'Sorry, no results to display at this time' . '</div>' . PHP_EOL;
}
else
{	
	$userid = $user->getUserid();
	$rank=1;
	echo '<table class="table table-striped">' . PHP_EOL;
		echo '<thead>' . PHP_EOL;
			echo '<tr>' . PHP_EOL;
				echo '<th>Rank</th>' . PHP_EOL;
				echo '<th>Name</th>' . PHP_EOL;
				echo '<th>Match Points</th>' . PHP_EOL;
				echo '<th>Googly Points</th>' . PHP_EOL;
				echo '<th>Total ' . '<span class="glyphicon glyphicon-triangle-bottom"></span>' . '</th>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		echo '</thead>' . PHP_EOL;
		echo '<tbody>' . PHP_EOL;
	foreach($resultsArray as $row)
	{
		//store the team ids in variables
		$name = ucfirst($row['fname']) . " " . strtoupper(substr($row['lname'], 0,1));
		$mpoints = $row['mpoints'];
		$gpoints = $row['gpoints'];
		$total = $row['total'];
		$dbuserid = $row['uid'];
		if($dbuserid == $userid)
			echo '<tr class="success">' . PHP_EOL;
		else	
			echo '<tr>' . PHP_EOL;
				echo "<td>$rank</td>" . PHP_EOL;
				echo "<td>$name</td>" . PHP_EOL;
				echo "<td>$mpoints</td>" . PHP_EOL;
				echo "<td>$gpoints</td>" . PHP_EOL;
				echo "<td>$total</td>" . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		$rank++;
	}
	echo '</tbody>' . PHP_EOL;
	echo '</table>' . PHP_EOL;
}
?>
<hr>
<?php
	opHTMLFooter();
?>