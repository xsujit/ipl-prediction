<?php
require_once("includes/functions.php");
require_once("includes/session.php");

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

opHTMLHeader("Help");
opHTMLNavHeader();
opHTMLNavbar("help");
?>
<div class="points-header">
	<h3>FAQs<br>
	<small>Important instructions - please read before playing</small></h3>
</div>
<div>
<h4>When does the SIMS IPL2017 start?</h4>
<p class="text-primary">Registration for SIMS IPL 2017 will be open from 30 Mar to 31 Mar 2017</p>
<p class="text-primary">Registration will close after 31 Mar 2017</p>

<h4>What are the timelines?</h4>
<p class="text-primary">Voting window will be open every Monday and Tuesday (till 12:00 PM)</p>
<p class="text-primary">User will need to login during this time period to submit their predictions for the comming week</p>

<h4>What are predictions and questions?</h4>
<p class="text-primary">Each IPL match will have a prediction and a question</p>
<p class="text-primary">Prediction is to guess which team will be the winner of that match</p>
<p class="text-primary">There can be two types of questions - Googly and Jackpot</p>
<p class="text-primary">There will be only one Jackpot question in a week</p>
<p class="text-primary">Match predictions are mandatory. Questions are optional</p>

<h4>How many points do predictions and questions carry?</h4>
<p class="text-primary">Each correct match prediction gets you +10 points. There are no negative points for match predictions</p>
<p class="text-primary">Each Googly question gets you +5 points if correct and -5 points if incorrect</p>
<p class="text-primary">Each Jackpot question gets you +20 points if correct and -20 points if incorrect</p>

<h4>How to play?</h4>
<p class="text-primary">In the web application, panels are color coded for easy identification</p>
<p class="text-primary">Each match is represented as a panel. It will contain a prediction and a question</p>
<p class="text-primary">A red panel indicates that it contains the Jackpot question of the week</p>
<img src="img/red.jpg" class="img-rounded" alt="Red Panel" width="522" height="158">
<br><br>
<p class="text-primary">A blue panel indicates that it contains a Googly question (Tooltips are also present to make it more user friendly)</p>
<img src="img/blue.jpg" class="img-rounded" alt="Blue Panel" width="551" height="157">
<br><br>
<h4>Weekly winners</h4>
<p class="text-primary">SIMS IPL Players will be divided in 3 categories based on the points they have acquired - Green, Blue and Red</p>
<p class="text-primary">Green - These players do not have pay for that week</p>
<p class="text-primary">Blue - These players have pay Rs. 25 for that week</p>
<p class="text-primary">Red - These players have pay Rs. 50 for that week</p>
<p class="text-primary">No player will have to play more than Rs. 200 for the entire season. The maximum amount per player is capped at Rs. 200</p>
<p class="text-primary">In case of a tie, there would be a lucky draw held</p>
<p class="text-primary">Weekly payments are to be made to Gaurav (SIMS 7) and Vijayavel (SIMS Primary and non-SIMS)</p>
<h4>Prizes</h4>
<p class="text-primary">After 7 weeks action packed games, predictions, googlies and jackpots its time to chill! 
We will be having an in-house lunch party where we have delicious food and also award the winners. 
There would also be some on-the-spot lucky draw prizes on the last day</p>
<p class="text-primary">Top 3 players with highest overall points would be given prizes</p>
<p class="text-primary">There would also be a special prize for the player who ends up with the lowest overall points</p>
<h4>How many players in SIMS IPL 2017</h4>
<p class="text-primary">This year we have 33 players</p>
</div>
<hr>
<?php
	opHTMLFooter();
?>