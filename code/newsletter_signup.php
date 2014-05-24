<?php

$timestamp = date("Y-m-d H:i:s");

if ($_POST['email']) {
	$email = stripslashes($_POST['email']);
	$email = strip_tags($email);
	$email = mysql_real_escape_string($email);

	$to = "fransvanhoogstraten@gmail.com";
	$from = "info@tabster.nl";
	$subject = "Tabster Newsletter Signup";
	
	$message = 'Het volgende e-mail address heeft interesse getoond in Tabster: ' . $email;
	// end of message
	$headers= "From: Tabster Admin <".$from.">\r\n";
	$headers.= "Reply-To: Tabster Newsletter Signup <".$from.">\r\n";
	$headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
	$headers.= "MIME-Version: 1.0" . "\r\n";
	$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
	$to = "$to";
	// Finally send the email
	mail($to, $subject, $message, $headers);



	// Success!!!
	$message_inque=True;
	$header_target='./';
	$text="<a href='$header_target'><img src=\"/beta/pics/ok.png\" alt=\"Success\" width=\"300\"></a>
	<br /><h4>Thank you for your submission. The e-mail address $email has been added to our database </h4><br />";
			
	}
else{

		// Failure
		$message_inque=True;
		$header_target='index.htm';
		$text="No e-mail address detected";
		}
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tabster | Logout</title> 
 
<meta name="title" content="Tabster"> 
<meta name="description" content="Sign up for updates"> 
<meta name="keywords" content=""> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<link rel="icon" href="./pics/icon.ico">
 
<style type="text/css"></style><link rel="stylesheet" type="text/css" href="./beta/css/classic.css"><link rel="stylesheet" type="text/css" href="./beta/css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./beta/css/css"><link rel="shortcut icon" href="./css/icon.ico">
</head> 
<body> 
<?php include_once("analyticstracking.php") ?>

<div id="lr-widget" rel="7KBI93HI">
<div id="content" class="LR-content LR-site-bg-color-container" style="background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: initial initial;"><!-- LR-sharing-page LR-stats LR-lx -->
		
			
	<!-- BG image -->
	<div class="LR-bg-img LR-site-bg-image-container LR-site-show-on-bg-image" style="background-image: url(./pics/background1.png);"></div>
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
