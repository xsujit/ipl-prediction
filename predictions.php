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

if(isset($_POST['submit']))
{
	$result = $user->processPostRequest($_POST);
	if($result === true)
	{
		header("Location:predictions.php?success=" . urlencode("Success! Your predictions have been saved/updated. You can see your new predictions on the \"Match Results\" page"));
	}
	elseif(key($result) === "other")
	{
		header("Location:predictions.php?err=" . urlencode("Sorry, Your predictions could not be saved/updated: " . $result['other']));
	}
	else
	{
		header("Location:predictions.php?err=" . urlencode("Sorry, Your predictions could not be saved/updated, unknown error occurred"));
	}
}

opHTMLHeader("Match Predictions");
opHTMLNavHeader();
opHTMLNavbar("predictions");

?>

<div class="prediction-header">
<h4>Welcome <?php echo $user->getFirstName(); ?>,<small> Here you can submit your weekly predictions. </small></h4>
</div>
<?php
	if(isset($_GET['err']))
	{
		echo '<div class="alert alert-danger">' . $_GET['err'] . '</div>' . PHP_EOL;
	} 
	if(isset($_GET['success']))
	{
		echo '<div class="alert alert-success">' .  $_GET['success'] . '</div>' . PHP_EOL;
	}
?>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="img/01.jpg" alt="IPL 2017" width="460" height="345">
      </div>

      <div class="item">
        <img src="img/02.jpg" alt="IPL 2017" width="460" height="345">
      </div>
    
      <div class="item">
        <img src="img/03.jpg" alt="IPL 2017" width="460" height="345">
      </div>

      <div class="item">
        <img src="img/04.jpg" alt="IPL 2017" width="460" height="345">
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <br>
<?php
	$activematches = Match::findActiveMatches();
	if($activematches === false)
	{
		$errmsg = "Sorry, the voting window is currently closed. Voting window is open every Monday and Tuesday till 12:00 PM";
		echo '<div class="alert alert-danger">' . $errmsg . '</div>' . PHP_EOL;
	}
	else
	{
		echo '<form class="form-inline" method="post" action="predictions.php" onsubmit="return confirm(\'Are you sure you want to submit?\');">' . PHP_EOL;
		foreach($activematches as $key=>$match)
		{
			//store the team ids in variables
			$matchid      = $match['mid'];
			$questionid   = "Q" . $match['mid'];
			$date         = new DateTime($match['matchdate']);
			$matchdate    = $date->format('D d F');
			$team1id      = $match['t1id'];
			$team1name    = $match['t1name'];
			$t1sname      = $match['t1sname'];
			$team2id      = $match['t2id'];
			$team2name    = $match['t2name'];
			$t2sname      = $match['t2sname'];
			$stadium      = $match['stadium'];
			$city         = $match['city'];
			$daynight     = $match['daynight'];
			$question     = $match['question'];
			$jackpot      = $match['jackpot'];
			$matchseq     = 'Match ' . $key;
			
			if ($jackpot == 1)
				echo '<div class="panel panel-danger">' . PHP_EOL;
			else
				echo '<div class="panel panel-info">' . PHP_EOL;
			if ($jackpot == 1)
				echo '<div class="panel-heading" data-toggle="tooltip" data-placement="left" title="Jackpot question match">' . PHP_EOL;
			else
				echo '<div class="panel-heading">' . PHP_EOL;
			echo '<strong>' . $matchseq . '</strong> | ' . $matchdate . ' | ' . $stadium . ' | ' . $city . ' | ';
			switch ($daynight)
			{
				case 1:
					echo ' Day' . PHP_EOL;
					break;
				case 2:
					echo ' Day-Night' . PHP_EOL;
					break;
				case 3:
					echo ' Night' . PHP_EOL;
					break;
				default:
					echo ' Unknown' . PHP_EOL;
					break;
			}
			
			echo '</div>' . PHP_EOL; //for panel heading
			echo '<div class="panel-body">' . PHP_EOL;
			echo '<div class="form-group">' . PHP_EOL;
			echo '<label class="radio-inline" data-toggle="tooltip" data-placement="left" title="Predict a winner for this match">';
			echo '<input type="radio"' . ' name="' . $matchid . '" value="' . $team1id . '" required>' . $team1name;
			echo '</label>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			echo '<div class="form-group">' . PHP_EOL;
			echo '<label class="radio-inline" data-toggle="tooltip" data-placement="right" title="Predict a winner for this match">';
			echo '<input type="radio"' . ' name="' . $matchid . '" value="' . $team2id . '" required>' . $team2name;
			echo '</label>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			if ($jackpot == 1)
				echo '<h5 data-toggle="tooltip" data-placement="left" title="Jackpot question of the week!"><em>' . $question . '</em></h5>' . PHP_EOL;
			else
				echo '<h5 data-toggle="tooltip" data-placement="left" title="Googly question for this match"><em>' . $question . '</em></h5>' . PHP_EOL;
			echo '<div class="form-group">' . PHP_EOL;
			echo '<label class="radio-inline">' . PHP_EOL;
			echo '<input type="radio"' . ' name="' . $questionid . '" value="1" required>Yes' . PHP_EOL;
			echo '</label>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			echo '<div class="form-group">' . PHP_EOL;
			echo '<label class="radio-inline">' . PHP_EOL;
			echo '<input type="radio"' . ' name="' . $questionid . '" value="2" required>No' . PHP_EOL;
			echo '</label>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			echo '<div class="form-group">' . PHP_EOL;
			echo '<label class="radio-inline">' . PHP_EOL;
			echo '<input type="radio"' . ' name="' . $questionid . '" value="3" required>Skip' . PHP_EOL;
			echo '</label>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
			echo '</div>' . PHP_EOL;
		}
		echo '<button type="submit" name = "submit" class="btn btn-primary">Submit / Update My Predictions</button>' . PHP_EOL;
		echo '</form>' . PHP_EOL;
	}
	?>
<hr>
<?php
	opHTMLFooter();
?>