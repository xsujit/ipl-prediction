<?php 
require_once("includes/session.php");

$session->logout();
header("Location:index.php?success=" . urlencode("You have been logged out successfully"));

?>
