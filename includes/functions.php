<?php
	function opHTMLHeader($title) //outputs HTML headers and body - Bootstrap template
	{
		echo '<!DOCTYPE html>' . PHP_EOL;
		echo '<html lang="en">' . PHP_EOL;
		echo '<head>' . PHP_EOL;
		echo '<meta charset="utf-8">' . PHP_EOL;
		echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . PHP_EOL;
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
		echo '<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->' . PHP_EOL;
		echo '<meta name="description" content="">' . PHP_EOL;
		echo '<meta name="author" content="">' . PHP_EOL;
		echo '<link rel="icon" href="img/favicon.ico">' . PHP_EOL;
		echo "<title>$title</title>" . PHP_EOL;
		echo '<!-- Bootstrap core CSS -->' . PHP_EOL;
		echo '<link href="css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
		echo '<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">' . PHP_EOL;
		echo '<link href="css/sticky-footer.css" rel="stylesheet">' . PHP_EOL;
		echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>' . PHP_EOL;
		echo '<script>' . PHP_EOL;
		echo '$(document).ready(function(){' . PHP_EOL;
		echo '$(\'[data-toggle="tooltip"]\').tooltip();' . PHP_EOL;
		echo '});' . PHP_EOL;
		echo '</script>' . PHP_EOL;
		echo '<style>' . PHP_EOL;
		echo '.carousel-inner > .item > img,' . PHP_EOL;
		echo '.carousel-inner > .item > a > img {' . PHP_EOL;
		echo 'width: 80%;' . PHP_EOL;
		echo 'margin: auto;' . PHP_EOL;
		echo '}' . PHP_EOL;
		echo '</style>' . PHP_EOL;
		echo "</head>" . PHP_EOL;
	}
	
	function opHTMLNavHeader() //outputs HTML headers and body - Bootstrap template
	{
		echo '<body>' . PHP_EOL;
		echo '<nav class="navbar navbar-inverse navbar-fixed-top">' . PHP_EOL;
		echo '<div class="container">' . PHP_EOL;
		echo '<div class="navbar-header">' . PHP_EOL;
		echo '<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">' . PHP_EOL;
		echo '<span class="sr-only">Toggle navigation</span>' . PHP_EOL;
		echo '<span class="icon-bar"></span>' . PHP_EOL;
		echo '<span class="icon-bar"></span>' . PHP_EOL;
		echo '<span class="icon-bar"></span>' . PHP_EOL;
		echo '</button>' . PHP_EOL;
		echo '<a class="navbar-brand" href="#">SIMS IPL 2017</a>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
	}
	function opHTMLNavbar($active) //outputs HTML headers and body - Bootstrap template
	{
		echo '<div id="navbar" class="collapse navbar-collapse">' . PHP_EOL;
        echo '<ul class="nav navbar-nav">' . PHP_EOL;
		echo ($active == "predictions") ? '<li class="active"><a href="predictions.php">Match Predictions</a></li>' . PHP_EOL : '<li><a href="predictions.php">Match Predictions</a></li>' . PHP_EOL;
		echo ($active == "results")     ? '<li class="active"><a href="results.php">Match Results</a></li>' . PHP_EOL         : '<li><a href="results.php">Match Results</a></li>' . PHP_EOL;
		echo ($active == "weekly")      ? '<li class="active"><a href="weekly.php">Weekly Points</a></li>' . PHP_EOL          : '<li><a href="weekly.php">Weekly Points</a></li>' . PHP_EOL;
		echo ($active == "points")      ? '<li class="active"><a href="points.php">Overall Points</a></li>' . PHP_EOL         : '<li><a href="points.php">Overall Points</a></li>' . PHP_EOL;
		echo ($active == "help")        ? '<li class="active"><a href="help.php">Help</a></li>' . PHP_EOL                     : '<li><a href="help.php">Help</a></li>' . PHP_EOL;
		echo '<li><a href="logout.php">Logout</a></li>' . PHP_EOL;
        echo '</ul>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		echo '</nav>' . PHP_EOL;
		echo '<div class="container">' . PHP_EOL;
	}
	function opHTMLFooter() //outputs HTML headers and body - Bootstrap template
	{
		echo '</div><!-- /.container -->' . PHP_EOL;
		echo '<footer class="footer">' . PHP_EOL;
		echo '<div class="container">' . PHP_EOL;
		echo '<p class="text-muted">&copy; Copyright 2016-2017</p>' . PHP_EOL;
		echo '</div>' . PHP_EOL;
		echo '</footer>' . PHP_EOL;
		echo '<!-- Bootstrap core JavaScript' . PHP_EOL;
		echo '================================================== -->' . PHP_EOL;
		echo '<!-- Placed at the end of the document so the pages load faster -->' . PHP_EOL;
		echo '<script>window.jQuery || document.write(\'<script src="js/jquery.min.js"><\/script>\')</script>' . PHP_EOL;
		echo '<script src="js/bootstrap.min.js"></script>' . PHP_EOL;
		echo '<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->' . PHP_EOL;
		echo '<script src="js/ie10-viewport-bug-workaround.js"></script>' . PHP_EOL;
		echo '</body>' . PHP_EOL;
		echo '</html>' . PHP_EOL;
	}
?>