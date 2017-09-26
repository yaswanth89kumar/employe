<?php
header('Access-Control-Allow-Origin: *');  
error_reporting(0);
include('includes/libraries/sql.php');
echo $db->getAchievements();
?>

