<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
 
$timestamp = date("Y-m-d H:i:s");
$errorMsg = "";
// First we check to see if the form has been submitted 
if (isset($_POST['name'])){
	//Connect to the database through our include 
	include_once "./connect_to_mysql.php";
	// Filter the posted variables
	$name = ereg_replace("[^A-Za-z0-9]", "", $_POST['name']); // filter everything but spaces, numbers, and letters
	$email = ereg_replace("[^A-Z a-z0-9]", "", $_POST['email']); // filter everything but spaces, numbers, and letters
	$tel = ereg_replace("[^A-Z a-z0-9]", "", $_POST['tel']); // filter everything but spaces, numbers, and letters
	$feedback = ereg_replace("[^A-Z a-z0-9]", "", $_POST['feedback']); // filter everything but spaces, numbers, and letters
	$email = stripslashes($_POST['email']);
	$email = strip_tags($email);
	$email = mysql_real_escape_string($email);
		
	if((!name) || (!$email) || (!$tel) || (!$feedback)) {
		
		$errorMsg .= "U moet de volgende onderdelen nog invullen:<br /><br />";

		if(!$hospitality_name){ 
	       $errorMsg .= "--- Naam"; 
		} 
		if(!$email){
			$errorMsg .= "--- Email Adres";
		} 
		if(!$tel){
			$errorMsg .= "--- Telefoonnummer"; 
		} 
		if(!$feedback){ 
		    $errorMsg .= "--- Bericht"; 
		}
		
	   
	} else {
	
		// Email webmaster that someone is interested
		$to = "fransvanhoogstraten@gmail.com";
		$from = "info@tabster.nl";
		$subject = "Contactformulier Tabster";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = 'Iemand wil contact opnemen met Tabster: <br /><br />'. 
		'Naam: '.$name . '<br />'.
		'Email: '.$email . '<br />'.
		'telnr: '.$tel . '<br /><br />'.
		'Bericht: '.$feedback . '<br /><br />'.
		'Gelieve even contact op te nemen';
		// end of message
		$headers= "From: Tabster Horeca Admin <".$from.">\r\n";
		$headers.= "Reply-To: Tabster Horeca Admin <".$from.">\r\n";
		$headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
		$headers.= "MIME-Version: 1.0" . "\r\n";
		$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$to = "$to";
		// Finally send the activation email to the member
		mail($to, $subject, $message, $headers);



		// Then print a message to the browser for the joiner 
		$message_inque=True;
		$header_target='./index.php';
		$SuccessMsg="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><h4>OK $contact_person, dank voor uw bericht. Wij nemen z.s.m. contact op!<br><br>";
	
	} // Close else after database duplicate field value checks
  } // Close else after missing vars check

?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tabster | Hospitality Info</title> 
 
<!-- Google -->
<meta name="title" content="Tabster | Hospitality Info"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Tabster"> 
<meta property="og:title" content="Tabster | Hospitality Info"> 
<meta property="og:description" content="Your Digital Hospitality Experience.">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./pics/icon.ico"> 
 
<style type="text/css"></style><link rel="stylesheet" type="text/css" href="./beta/css/classic.css"><link rel="stylesheet" type="text/css" href="./beta/css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./beta/css/css"><link rel="shortcut icon" href="./beta/css/icon.ico">

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
			<!-- Show Error Messages -->
			<?php if ($message_inque==True){echo $SuccessMsg; header("Refresh: 5; '$header_target'"); exit();}?> 
			<?php echo $errorMsg; ?> 
		
			</h4>
		</div>
				
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner" style="margin-top: 75px;">
						<h1 class="LR-site-title" style="color: rgb(0,0,0)">Neem contact op met Tabster:</h1>
						<div class="LR-sign-up"> 
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner black">
							<!-- other fields -->
							<div class="LR-extra-fields">
								<form action="./contact_form.php" method="post" name="form" id="form" autocomplete="off">
									<font color="#FFFFFF" style="font-size: 13px;"><i>Als u hieronder uw contactgegevens en bericht achterlaat nemen wij zo snel mogelijk contact op!</i></font><br><br>
									<input name="name" id="name" type="text" class="LR-sign-up-input" value="<?php echo "$name"; ?>" placeholder="Naam" /> 
									<input name="email" id="email" type="email" class="LR-sign-up-input" value="<?php echo "$email"; ?>" placeholder="Emailadres" />
									<input name="tel" id="tel" type="tel" class="LR-sign-up-input" value="<?php echo "$tel"; ?>" placeholder="Telefoonnummer" />
									<br><br>
									<textarea name="feedback" rows="12" cols="48" maxlength="5000" placeholder="Bericht..."></textarea>									
									<input type="submit" name="submit" title="Verzenden" value="Verzenden" class="LR-sign-up-submit">
								</form>
							</div><!-- .extra-fields -->
							</div><!-- .sign-up-container-inner -->
							</div><!-- .sign-up-container -->
						</div><!-- .sign-up -->			
					</div><!-- .box-inner -->	
				</div><!-- .box-container -->
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
	</div><!-- #content -->	
</div>  

 
 