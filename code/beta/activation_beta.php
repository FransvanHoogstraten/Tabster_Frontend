<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
include_once "./connect_to_mysql.php";

$toplinks = "";
if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
	$toplinks = '<font size="2"><a href="./member_profile.php">' . $email . '</a> &bull; 
	<a href="./logout.php">Log Out</a></font>';
} else {
	$toplinks = '<font size="2"><a href="./join_form.php">Register</a> &bull; <a href="./login.php">Login</a></font>';
}

// Get the member id from the URL variable
$id = $_REQUEST['id'];
$id = ereg_replace("[^0-9]", "", $id); // filter everything but numbers for security
if (!$id) {
	echo "Missing Data to Run";
	exit();	
}
// Update the database field named 'status' to active
$sql = mysql_query("UPDATE users SET status='active' WHERE id='$id'"); 
// Check the database to see if all is right now 
$sql_doublecheck = mysql_query("SELECT * FROM users WHERE id='$id' AND status='active'"); 
$doublecheck = mysql_num_rows($sql_doublecheck); 
if($doublecheck == 0){ 
// Print message to the browser saying we could not activate them

	$message_inque=True;
	$header_target='./index.php';
	$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
	<b><br />The account could not be activated! <br/>
	If this you keep running into this problem, please email us.<br /><br />
	You will be redirected momentarily....</b>";


} elseif ($doublecheck > 0) {

	$message_inque=True;
	$header_target='./index.php';
	$text="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
	<br /><b>Congratulations, the account has been activated!<br /><br />
	You will be redirected momentarily....</b>";

} 
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Beta Account Activation</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Beta Account Activation"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Beta Account Activation"> 
<meta property="og:description" content="Yoombler | Your Digital Hospitality Experience.">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./pics/icon.ico"> 

 
<style type="text/css"></style><link rel="stylesheet" type="text/css" href="./css/classic.css"><link rel="stylesheet" type="text/css" href="./css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./css/css"><link rel="shortcut icon" href="./css/icon.ico">

</head> 
<body> 
<?php include_once("analyticstracking.php") ?>
<div id="lr-widget" rel="7KBI93HI">
<div id="content" class="LR-content LR-site-bg-color-container" style="background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: initial initial;"><!-- LR-sharing-page LR-stats LR-lx -->
		
			
	<!-- BG image -->
	<div class="LR-bg-img LR-site-bg-image-container LR-site-show-on-bg-image" style="background-image: url(./pics/restaurant_background.jpg);"></div>
	<div class="LR-bg-img"></div>
		
		<!-- AnnouncementBar -->
		<div class="LR-announcement-bar LR-announcement-bg-color-container" style="background-color: rgba(0, 0, 0, 0.8); background-position: initial initial; background-repeat: initial initial;">
			<h4 class="LR-announcement">			
			<?php if ($message_inque==True){echo $text; header("Refresh: 5; $header_target"); exit();}?> 
			</h4>
		</div>
		
		<!-- Body - No body is filled in -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
		</div><!-- #content -->	

</div>  
