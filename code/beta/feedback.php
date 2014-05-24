<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}

// Process the form if it is submitted
if ($_POST['feedback']) {
	$feedback = $_POST['feedback'];
	
	$to = "fransvanhoogstraten@gmail.com";
	$from = "info@yoombler.com";
	$subject = "Yoombler Feedback";
	
	$message = $email.' heeft feedback achtergelaten:<br/><br/><b>'.$feedback.'</b>';
	// end of message
	$headers= "From: Yoombler Admin <".$from.">\r\n";
	$headers.= "Reply-To: Yoombler Feedback <".$from.">\r\n";
	$headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0" . "\r\n";
	$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
	$to = "$to";
	// Finally send the email
	mail($to, $subject, $message, $headers);



	// Success!!!
	$message_inque=True;
	$header_target='./home.php';
	$text="<a href='$header_target'><img src=\"/beta/pics/ok.png\" class=\"img-confirmation\"></a>
	<br /><h4>Thank you for your submission. The comment has been sent to our webmaster </h4><br />";
			
	}
 
?>


<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | User Feedback</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | User Feedback"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | User Feedback"> 
<meta property="og:description" content="Your Digital Hospitality Experience.">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./pics/icon.ico"> 

 
<style type="text/css">

</style><link rel="stylesheet" type="text/css" href="./css/classic.css"><link rel="stylesheet" type="text/css" href="./css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./css/css"><link rel="shortcut icon" href="./css/icon.ico">

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
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<h1 class="LR-site-title" style="">Submit Feedback</h1>
					<div class="LR-sign-up"> 
							
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
									<form action="feedback.php" method="post" name="form" id="form"> 
										<textarea name="feedback" rows="12" cols="35" maxlength="5000"></textarea>
										<input name="Submit" type="submit" class="LR-sign-up-submit" value="Submit" />
									</form>
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


