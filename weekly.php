<?php
require_once("includes/user.php");
require_once("includes/match.php");
require_once("includes/session.php");
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

opHTMLHeader("Weekly Results");
opHTMLNavHeader();
opHTMLNavbar("weekly");
?>
<div class="points-header">
	<h3>Weekly Points Table<br>
	<small>Below you will see the weekly standings of all the players</small></h3>
</div>
<?php
$resultsArray = Match::getWeeklyResults();
if($resultsArray === false)
{
	echo '<div class="alert alert-danger">' . 'Sorry, no results to display at this time' . '</div>' . PHP_EOL;
}
else
{
	$weeks = array_column($resultsArray, 'week');
	$weeks = array_unique($weeks);
	foreach($weeks as $week)
	{
		$weekArr = [];
		foreach($resultsArray as $arr)
		{
			if($week == $arr['week'])
				$weekArr[] = $arr;
		}
		if(count($weekArr) > 0)
		{
			echo '<h3><span class="label label-primary">Week ' . $week . '</span></h3>' . PHP_EOL;
			
			$rank=1; //initialize the rank variable
			$totalrecords = count($weekArr);
			
			$reminder = $totalrecords%3;
			$parts = floor($totalrecords/3);
			$green = $amber = $red = $parts;
			
			if($reminder == 1)
			{
				$amber += $green;
				$red += $amber + 1;
			}
			elseif($reminder == 2)
			{
				$amber += $green + 1;
				$red += $amber + 1;
			}
			else
			{
				$amber += $green;
				$red += $amber;
			}
			
			$count = 1;
			
			echo '<table class="table">' . PHP_EOL;
				echo '<thead>' . PHP_EOL;
					echo '<tr>' . PHP_EOL;
						echo '<th>Rank</th>' . PHP_EOL;
						echo '<th>Name</th>' . PHP_EOL;
						echo '<th>Match Points</th>' . PHP_EOL;
						echo '<th>Googly Points</th>' . PHP_EOL;
						echo '<th>Total ' . '<span class="glyphicon glyphicon-triangle-bottom"></span>' . '</th>' . PHP_EOL;
						echo '<th>Amount</th>' . PHP_EOL;
						echo '<th>Paid?</th>' . PHP_EOL;
					echo '</tr>' . PHP_EOL;
				echo '</thead>' . PHP_EOL;
				echo '<tbody>' . PHP_EOL;
			foreach($weekArr as $row)
			{
				//store the database columns in variables
				$name = ucfirst($row['fname']) . " " . strtoupper(substr($row['lname'], 0,1));
				$mpoints = $row['mpoints']; //match points
				$gpoints = $row['gpoints']; // googly points
				$total = $row['total']; // match plus googly score
				$wpoints = $row['wpoints'];
				$amttopay = $row['amt'];
				$pstatus = $row['pstatus'];
				
				if($count <= $green)
				{
					echo '<tr class="success">' . PHP_EOL;
				}
				elseif($count <= $amber)
				{
					echo '<tr class="warning">' . PHP_EOL;
				}
				elseif($count <= $red)
				{
					echo '<tr class="danger">' . PHP_EOL;
				}
				echo "<td>$rank</td>" . PHP_EOL;
				if($wpoints < $total)
					echo '<td>' . $name  . ' <span class="glyphicon glyphicon-chevron-down"></span>' . '</td>' . PHP_EOL;
				elseif($wpoints > $total)
					echo '<td>' . $name  . ' <span class="glyphicon glyphicon-chevron-up"></span>' . '</td>' . PHP_EOL;
				else
					echo "<td>$name</td>" . PHP_EOL;
				echo "<td>$mpoints</td>" . PHP_EOL;
				if(is_null($gpoints))
					echo "<td>**</td>" . PHP_EOL;
				else
					echo "<td>$gpoints</td>" . PHP_EOL;
				echo "<td>$total</td>" . PHP_EOL;
				
				if(is_null($amttopay))
					echo "<td>--</td>" . PHP_EOL;
				else
					echo "<td>$amttopay" . ".00</td>" . PHP_EOL;
				if(is_null($pstatus))
					echo "<td>--</td>" . PHP_EOL;
				elseif($pstatus == 2 || $pstatus == 1) //2 or 1 indicates na or paid 
					echo '<td><span class="glyphicon glyphicon-ok"></span></td>' . PHP_EOL;
				elseif($pstatus == 0) // 0 is unpaid
					echo '<td><span class="glyphicon glyphicon-exclamation-sign"></span></td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
				$rank++;
				$count++;
			}
			echo '</tbody>' . PHP_EOL;
			echo '</table>' . PHP_EOL;
			echo '<p class="text-muted"><span class="glyphicon glyphicon-chevron-up"></span> Promotion | ' . PHP_EOL;
			echo '<span class="glyphicon glyphicon-chevron-down"></span> Demotion | -- Pending Calculation ' . PHP_EOL;
			echo '| <span class="glyphicon glyphicon-exclamation-sign"></span> Unpaid | <span class="glyphicon glyphicon-ok"></span> Paid </p>' . PHP_EOL;
		}
	}
}
?>
<hr>
<?php
	opHTMLFooter();
?>