<?php
// to get timezone 
date_default_timezone_set("Africa/Cairo");
// to get current time in seconds
$Currenttime = time();
//to get date and time in a specific format
$datetime = strftime("%B-%d-%Y %H:%M:%S",$Currenttime);
echo $datetime;

?>