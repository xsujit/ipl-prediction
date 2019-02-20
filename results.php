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

opHTMLHeader("Match Results");
opHTMLNavHeader();
opHTMLNavbar("results");
?>
<div class="points-header">
	<h3>Weekly Match Results<br>
	<small>Here you will see the actual match results and your predictions</small></h3>
</div>

<?php
	$userid = $user->getUserid();
	$resultsArray = Match::getWeeklyMatches($userid);
	if($resultsArray === false)
	{
		echo '<div class="alert alert-danger">' . 'Sorry, no match results to display at this time' . '</div>';
	}
	else
	{
		$weeks = array_column($resultsArray, 'week');
		$weeks = array_unique($weeks);
		arsort($weeks);
		$weeks = array_values($weeks);
		foreach($weeks as $week)
		{
			//echo '<button type="button" class="btn btn-primary">Week  <span class="badge">' . $week . '</span></button>' . PHP_EOL;
			echo '<h3><span class="label label-primary">Week ' . $week . '</span></h3>';
			echo '<table class="table table-striped">' . PHP_EOL;
				echo '<thead>' . PHP_EOL;
					echo '<tr>' . PHP_EOL;
						echo '<th class="text-center">Match Date ' . '<span class="glyphicon glyphicon-triangle-top"></span></th>' . PHP_EOL;
						echo '<th class="text-center">Match</th>' . PHP_EOL;
						echo '<th class="text-center">Prediction</th>' . PHP_EOL;
						echo '<th class="text-center">Winner</th>' . PHP_EOL;
						echo '<th class="text-center">Googly Question</th>' . PHP_EOL;
						echo '<th class="text-center">Prediction</th>' . PHP_EOL;
						echo '<th class="text-center">Answer</th>' . PHP_EOL;
					echo '</tr>' . PHP_EOL;
				echo '</thead>' . PHP_EOL;
				echo '<tbody>' . PHP_EOL;
				foreach($resultsArray as $row)
				{
					if($row['week'] == $week)
					{
						//store the team ids in variables
						$matchdate = $row['matchdate'];
						$team1       = $row['team1'];
						$t1sname     = $row['t1sname'];
						$team2       = $row['team2'];
						$t2sname     = $row['t2sname'];
						$winner      = $row['mresult'];
						$winsname    = $row['t3sname'];
						$mprediction = $row['mprediction'];
						$mpsname     = $row['t4sname'];
						$jackpot     = $row['jackpot'];
						$googlyq     = $row['question'];
						$googlya     = $row['answer'];
						$gprediction = $row['gprediction'];
						echo '<tr>' . PHP_EOL;
							echo '<td class="text-center">' . $matchdate . '</td>' . PHP_EOL;
							echo '<td class="text-center"><span data-toggle="tooltip" title="' . $team1 . '">' . $t1sname . '</span> vs <span data-toggle="tooltip" title="' . $team2 . '">' . $t2sname . '</span></td>' . PHP_EOL;
							if ($mprediction == $winner && $winner != NULL)
								echo '<td class="text-center"><span data-toggle="tooltip" title="' . $mprediction . '">' . $mpsname . '</span> <span class="glyphicon glyphicon-ok"></span></td></td>' . PHP_EOL;
							else
								echo '<td class="text-center"><span data-toggle="tooltip" title="' . $mprediction . '">' . $mpsname . '</span></td>' . PHP_EOL;
							echo '<td class="text-center"><span data-toggle="tooltip" title="' . $winner . '">' . $winsname . '</span></td>' . PHP_EOL;
							if ($jackpot == 1)
								echo '<td><p class="para-nopadding" data-toggle="tooltip" title="Jackpot Question">' . $googlyq . '</p></td>' . PHP_EOL;
								//echo '<td><span class="glyphicon glyphicon-star" data-toggle="tooltip" title="Jackpot Question"></span> ' . $googlyq . '</td>' . PHP_EOL;
							else
								echo '<td>' . $googlyq . '</td>' . PHP_EOL;
							if (is_null($gprediction) || is_null($googlya))
							{
								if (is_null($gprediction) && is_null($googlya))
								{
									echo '<td class="text-center"></td>' . PHP_EOL;
									echo '<td class="text-center"></td>' . PHP_EOL;
								}
								elseif (is_null($gprediction))
								{
									echo '<td class="text-center"></td>' . PHP_EOL;
									if ($googlya == 1)
										echo '<td class="text-center">Yes</td>' . PHP_EOL;
									elseif ($googlya == 2)
										echo '<td class="text-center">No</td>' . PHP_EOL;
								}
								elseif (is_null($googlya))
								{
									if ($gprediction == 1)
										echo '<td class="text-center">Yes</td>' . PHP_EOL;
									elseif ($gprediction == 2)
										echo '<td class="text-center">No</td>' . PHP_EOL;
									elseif ($gprediction == 3)
										echo '<td class="text-center">Skip</td>' . PHP_EOL;
									echo '<td class="text-center"></td>' . PHP_EOL;
								}
							}
							elseif ($gprediction == $googlya)
							{
								if ($gprediction == 1)
								{
									echo '<td class="text-center">Yes <span class="glyphicon glyphicon-ok"></span></td>' . PHP_EOL;
									echo '<td class="text-center">Yes</td>' . PHP_EOL;
								}
								elseif ($gprediction == 2)
								{
									echo '<td class="text-center">No <span class="glyphicon glyphicon-ok"></span></td>' . PHP_EOL;
									echo '<td class="text-center">No</td>' . PHP_EOL;
								}
							}
							else
							{
								if ($gprediction == 1)
									echo '<td class="text-center">Yes <span class="glyphicon glyphicon-remove"></span></td>' . PHP_EOL;
								elseif ($gprediction == 2)
									echo '<td class="text-center">No  <span class="glyphicon glyphicon-remove"></span></td>' . PHP_EOL;
								elseif ($gprediction == 3)
									echo '<td class="text-center">Skip</td>' . PHP_EOL;
								if ($googlya == 1)
									echo '<td class="text-center">Yes</td>' . PHP_EOL;
								elseif ($googlya == 2)
									echo '<td class="text-center">No</td>' . PHP_EOL;
							}
						echo '</tr>' . PHP_EOL;
					}
					else
						continue;
				}
				echo '</tbody>' . PHP_EOL;
			echo '</table>' . PHP_EOL;
		}
	}
	?>		
<hr>
<?php
	opHTMLFooter();
?>