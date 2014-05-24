<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
 
$timestamp = date("Y-m-d H:i:s");
$errorMsg = "";
// First we check to see if the form has been submitted 
if (isset($_POST['hospitality_name'])){
	//Connect to the database through our include 
	include_once "./connect_to_mysql.php";
	// Filter the posted variables
	$hospitality_name = ereg_replace("[^A-Za-z0-9]", "", $_POST['hospitality_name']); // filter everything but spaces, numbers, and letters
	$hospitality_address = ereg_replace("[^A-Z a-z0-9]", "", $_POST['hospitality_address']); // filter everything but spaces, numbers, and letters
	$hospitality_location = ereg_replace("[^A-Z a-z0-9]", "", $_POST['hospitality_location']); // filter everything but spaces, numbers, and letters
	$contact_person = ereg_replace("[^A-Z a-z0-9]", "", $_POST['contact_person']); // filter everything but spaces, numbers, and letters
	$contact_email = stripslashes($_POST['contact_email']);
	$contact_email = strip_tags($contact_email);
	$contact_email = mysql_real_escape_string($contact_email);
	$POS = ereg_replace("[^A-Z a-z0-9]", "", $_POST['POS']); // filter everything but spaces, numbers, and letters
	
	if((!$hospitality_name) || (!$hospitality_address) || (!$hospitality_location) || (!$contact_person) || (!$contact_email) || (!$POS)){
		
		$errorMsg .= "You did not submit the following required information:<br /><br />";

		if(!$hospitality_name){ 
	       $errorMsg .= "--- Naam Cafe/Restaurant"; 
		} 
		if(!$hospitality_address){
			$errorMsg .= "--- Adres";
		} 
		if(!$hospitality_location){
			$errorMsg .= "--- Plaats"; 
		} 
		if(!$contact_person){ 
		    $errorMsg .= "--- Contactpersoon"; 
		}
		if(!$contact_email){ 
		    $errorMsg .= "--- Emailadres Contactpersoon"; 
		}		
		if(!$POS){ 
	       $errorMsg .= "--- Merk POS/Kassasysteem"; 
		} 
	   
	   
	} else {
	
		// Email webmaster that someone is interested
		$to = "fransvanhoogstraten@gmail.com";
		$from = "info@tabster.nl";
		$subject = "Horeca toont interesse!";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = 'Een horecaexploitant heeft zijn gegevens achtergelaten: <br /><br />'. 
		'Naam: '.$hospitality_name . '<br />'.
		'Adres: '.$hospitality_address . '<br />'.
		'Plaats: '.$hospitality_location . '<br />'.
		'Contactpersoon: '.$contact_person . '<br />'.
		'Email: '.$contact_email . '<br />'.
		'Merk POS: '.$POS . '<br /><br />'.
		'Hierbij bevestig ik als horecaexploitant interesse te hebben in het Tabster Horecaconcept (ik wordt nergens toe verplicht). 
		Tabster mag dit bericht gebruiken om met de POS/Kassasysteem fabrikanten zo snel mogelijk tot een Tabster update voor ons POS systeem te komen.';
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
		<br /><h4>OK $contact_person, dank voor je inzending!<br><br>
		Wij zullen je op de hoogte houden over de Tabster vorderingen en gaan bij jullie samen met jullie POS/Kassafabrikant een 'Tabster update' ontwikkelen";
	
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

	<!-- Left & Right Info-float -->
	
		<div class="infobox-container left">
			<div class="infobox">
				
				<h3>Horeca</h3>
				<br><b>De Horeca is aan verandering onderhevig, zowel voor consumenten als horecaexploitanten. 
				Vijftien jaar geleden was het nog ondenkbaar om met PIN te betalen voor een 
				kopje koffie op een terras, nu is dat doodgewoon. <br/><br/>
				Het fysieke betaalproces is arbeidsintensief en daarmee duur. Daarnaast zijn de fooien 
				met de introductie van de PIN afgenomen. Zeker nu cashgeld steeds minder populair wordt, 
				nemen de (PIN) transactiekosten toe. Groepen die gescheiden pinnen leiden tot  
				ergernis bij horecapersoneel en tot extra kosten voor de ondernemer.</b><br/><br/>
				<img src="./pics/cafe.jpg" width="100%">
				
			
			</div>
		</div>
	
	
		<div class="infobox-container right">
			<div class="infobox">
				<h3>Consument</h3>
				<br><b>Tegelijk is de consument steeds nieuwsgieriger! Hoeveel geld heb ik deze maand uitgegeven 
				in de horeca? Hoe staat het met mijn alcoholgebruik? <br/><br/>
				Er is vaak frustratie te herkennen bij consumenten die geregeld in groepen naar het cafe/restaurant gaan. 
				Aangezien vrijwel niemand nog cash bij zich heeft is het wel zo gemakkelijk als één iemand 
				het volledige bedrag voor zijn rekening neemt. De volgende dag moet dan via de email een berekening
				worden gemaakt wie hoeveel verschuldigd is. 
				Een frustratie voor velen, omdat het altijd 
				dezelfde zijn die te laat terug betalen. <br/><br/>
				De introductie van Tabster doet al deze problemen als sneeuw voor de zon verdwijnen. Interesse als horecaondernemer? 
				Laat uw gegevens op deze pagina achter, dan gaan wij voor u aan de slag. </b><br/><br/>
				<img src="./pics/terras_zon.jpeg" width="100%">

			</div>
		</div>
	
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
					<div class="LR-box-inner">
						<h1 class="LR-site-title" style="color: rgb(0,0,0);">Toon uw interesse in Tabster:</h1>
						<div class="LR-sign-up"> 
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner black">
							<!-- other fields -->
							<div class="LR-extra-fields">
								<form action="./hospitality_info.php" method="post" name="form" id="form" autocomplete="off">
									<input name="hospitality_name" id="hospitality_name" type="text" class="LR-sign-up-input" value="<?php echo "$hospitality_name"; ?>" placeholder="Naam Cafe/Restaurant" />
									<input name="hospitality_address" id="hospitality_address" type="text" class="LR-sign-up-input" value="<?php echo "$hospitality_address"; ?>" placeholder="Straat + huisnummer" />
									<input name="hospitality_location" id="hospitality_location" type="text" class="LR-sign-up-input" value="<?php echo "$hospitality_location"; ?>" placeholder="Plaats" />
									<input name="contact_person" id="contact_person" type="text" class="LR-sign-up-input" value="<?php echo "$contact_person"; ?>" placeholder="Contactpersoon" /> 
									<input name="contact_email" id="contact_email" type="email" class="LR-sign-up-input" value="<?php echo "$contact_email"; ?>" placeholder="Emailadres Contactpersoon" />
									<input name="POS" id="POS" type="text" class="LR-sign-up-input" value="<?php echo "$POS"; ?>" placeholder="Merk/Type Kassasysteem (POS systeem)" />
									<font color="#FFFFFF" style="font-size: 11px;"><i>Hierbij bevestigt u als horecaexploitant interesse te hebben in het Tabster Horecaconcept (u wordt nergens toe verplicht). 
									Wij zullen dit bericht o.a. gebruiken om met de POS/Kassasysteem fabrikanten zo snel mogelijk tot een 'Tabster update' voor uw systeem te komen.</i></font>
									<input type="submit" name="submit" title="Bevestigen" value="Bevestigen" class="LR-sign-up-submit">
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

 
 