<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
	$first_name = $_SESSION['first_name'];
	$last_name = $_SESSION['last_name'];
} else {
	header("location: ./index.php"); 	

}
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Personal Homepage</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Personal Homepage"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Personal Homepage"> 
<meta property="og:description" content="Your Digital Hospitality Experience.">
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
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">

					<!-- Button for logging out -->
					<div style="float: left; margin-right: -100%;">
						<a href="./logout.php"><img src="./pics/logout.png" width='40px' style="border-width: 0;"/></a>
					</div>
					<div style="float: right";></div>

					<h1 class="LR-site-title" style="">Menu</h1>

					<!-- To reset the 'float' commands -->
					<div style="clear: both;">
					</div>

					<div class="LR-sign-up"> 
							
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
									<form action="./profile.php" method="link">
										<input type="submit" value="My Profile" class="LR-sign-up-submit">
									</form>
									<form action="./tabs.php" method="link">
										<input type="submit" value="My Tabs" class="LR-sign-up-submit">
									</form>
									<form action="./feedback.php" method="link">
										<input type="submit" value="Submit Feedback" class="LR-sign-up-submit">
									</form>
									
									<!-- Easy for pre-integration testing -->
									<?php if ($userid=='1'){ ?>
										<form action="./create_consumption.php" method="link">
											<input type="submit" value="Create Consumption" class="LR-sign-up-submit">
										</form>
									<?php } ?>
									
									
									
								</div>
								
							</div>
							</div><!-- .sign-up-container -->
							
							
						</div><!-- .sign-up -->			
					</div><!-- .box-inner -->	
				</div><!-- .box-container -->
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
		</div><!-- #content -->	

</div>  

 
 