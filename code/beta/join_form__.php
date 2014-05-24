<?php
session_start(); // Must start session first thing
$app_id     = "470536136358946";
$app_secret = "ab46c527fbdc2169db2866a7d2d23d25";
$site_url   = "http://www.yoombler.com/beta/join_fb_confirm.php"; 

include_once "./facebook_src/facebook.php";

$facebook = new Facebook(array(
    'appId'     => $app_id,
    'secret'    => $app_secret,
    ));
	

    
$loginUrl = $facebook->getLoginUrl(array(
	#'scope'         => 'email , user_location, user_birthday',
	'scope'         => 'user_location',
	'redirect_uri'  => $site_url,
));

date_default_timezone_set('Europe/Amsterdam');
 
$timestamp = date("Y-m-d H:i:s");
$errorMsg = "";
// First we check to see if the form has been submitted 
if (isset($_POST['email'])){
	//Connect to the database through our include 
	include_once "./connect_to_mysql.php";
	// Filter the posted variables
	$first_name = ereg_replace("[^A-Za-z0-9]", "", $_POST['first_name']); // filter everything but spaces, numbers, and letters
	$last_name = ereg_replace("[^A-Z a-z0-9]", "", $_POST['last_name']); // filter everything but spaces, numbers, and letters
	$hometown = ereg_replace("[^A-Z a-z0-9]", "", $_POST['hometown']); // filter everything but spaces, numbers, and letters
	$phonenumber = ereg_replace("[^0-9]", "", $_POST['phonenumber']); // filter everything but numbers
	//$date_of_birth = ereg_replace("[^0-9]", "", $_POST['date_of_birth']); // filter everything but numbers
	$date_of_birth = $_POST['date_of_birth']; 
	$sex = ereg_replace("[^a-z]", "", $_POST['sex']); // filter everything but lowercase letters
	$email = stripslashes($_POST['email']);
	$email = strip_tags($email);
	$email = mysql_real_escape_string($email);
	$password = ereg_replace("[^A-Za-z0-9]", "", $_POST['password']); // filter everything but numbers and letters
	$password2 = ereg_replace("[^A-Za-z0-9]", "", $_POST['password2']); // filter everything but numbers and letters

	if((!$first_name) || (!$last_name) || (!$hometown) || (!$date_of_birth) || (!$sex) || (!$email) || (!$phonenumber) || (!$password) || (!$password2)){
		
		$errorMsg .= "You did not submit the following required information:<br /><br />";

		if(!$email){ 
	       $errorMsg .= "--- Email Address"; 
		} 
		if(!$first_name){
			$errorMsg .= "--- First Name";
		} 
		if(!$last_name){
			$errorMsg .= "--- Last Name"; 
		} 
		if(!$hometown){ 
		    $errorMsg .= "--- Hometown"; 
		}
		if(!$phonenumber){ 
		    $errorMsg .= "--- Phonenumber"; 
		}		
		if(!$date_of_birth){ 
	       $errorMsg .= "--- Date of Birth"; 
		} 
		if(!$sex){ 
	       $errorMsg .= "--- Sex"; 
		} 
		if((!$password) or (!$password2)){ 
		   $errorMsg .= "--- Password"; 
		}
	   
	   
	} else {
	// Database duplicate Fields Check
	$sql_email_check = mysql_query("SELECT id FROM users WHERE email='$email' LIMIT 1");
	$email_check = mysql_num_rows($sql_email_check); 
	if ($email_check > 0){ 
		$errorMsg .= "Your Email address is already in use. Please use another.";
	} 
	else if ($password != $password2){ 
		$errorMsg .= "The passwords don't match. Please try again.";
	} 
	else {
		// Add MD5 Hash to the password variable
       $hashedPass = md5($password); 
		// Add user info into the database table, claim your fields then values 
		$date_of_birth_mysql= date('Y-m-d', strtotime($date_of_birth));
		$sql = mysql_query("INSERT INTO users (timestamp_create, timestamp_update, email, password, first_name, last_name, hometown, date_of_birth, sex, phonenumber) 
		VALUES('$timestamp','$timestamp','$email','$hashedPass','$first_name','$last_name','$hometown','$date_of_birth_mysql', '$sex', '$phonenumber')") or die (mysql_error());
		// Get the inserted ID here to use in the activation email
		$id = mysql_insert_id();
		// Create directory(folder) to hold each user files(pics, MP3s, etc.) 
		mkdir("memberFiles/$id", 0755); 
		// Put picture inside of unknown person
		copy("./pics/unknown.jpg", "./memberFiles/$id/pic1.jpg");


		// Start assembly of Email Member the activation link
		$to = "$email";
		$from = "info@yoombler.com";
		$subject = "Complete your registration";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = '<html>
		<body bgcolor="#FFFFFF">
		Hi ' . $first_name . ',
		<br /><br /> 
		You must complete this step to activate your account with us.
		<br /><br />
		Please click here to activate now &gt;&gt;
		<a href="http://www.yoombler.com/beta/activation.php?id=' . $id . '">
		ACTIVATE NOW</a>
		<br /><br />
		Your Login Data is as follows: 
		<br /><br />
		E-mail Address: ' . $email . ' <br />
		Password: ' . $password . ' 
		<br /><br /> 
		<b> Because we are still in Beta testing, your information will be screened before gaining access.</b><br /><br /> 
		Thanks! <br /><br /> 
		<strong>Team Yoombler</font></strong><br /><br />
		<img src="http://www.yoombler.com/beta/pics/logo_new_noBG.jpg" alt="Yoombler Logo" width="120">
		</body>
		</html>';
		// end of message
		$headers= "From: Yoombler Activation <".$from.">\r\n";
		$headers.= "Reply-To: Yoombler Activation <".$from.">\r\n";
		$headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
		$headers.= "MIME-Version: 1.0" . "\r\n";
		$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$to = "$to";
		// Finally send the activation email to the member
		mail($to, $subject, $message, $headers);



		// Email webmaster that someone requested Beta access
		$to = "fransvanhoogstraten@gmail.com";
		$from = "info@yoombler.com";
		$subject = "Someone requests Beta access";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = 'Er is iemand die Beta toegang wil: <br /><br />'. 
		$email . '<br />'.
		$first_name . '<br />'. 
		$last_name . '<br />'.
		$hometown . '<br />'.
		$phonenumber . '<br />'.
		$date_of_birth . '<br />'.
		$sex . '<br /><br />'.
		'Klik hier als je deze toegang wil verlenen: <br /><br />
		<a href="http://www.yoombler.com/beta/activation_beta.php?id=' . $id . '">
		ACTIVATE NOW</a>';
		// end of message
		$headers= "From: Yoombler Beta Admin <".$from.">\r\n";
		$headers.= "Reply-To: Yoombler Beta Admin <".$from.">\r\n";
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
		<br /><h4>OK $first_name, one last step to verify your email identity:</h4><br />
		We just sent an Activation link to: $email<br /><br />
		<strong>Please check your email inbox in a moment</strong> and click on the Activation Link to verify <br />
		your e-mail address. Because we are still in Beta testing, your information will be screened before gaining access.<br /><br />
		<strong>If the email does not show in your inbox, please check your spamfilter!</strong>";
	
	} // Close else after database duplicate field value checks
  } // Close else after missing vars check
} //Close if $_POST
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Create Account</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Create Account"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Create Account"> 
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
			
			<!-- Show Error Messages -->
			<?php if ($message_inque==True){echo $SuccessMsg; exit();}?> 
			<?php echo $errorMsg; ?> 
			
			
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
						<h1 class="LR-site-title" style="">Beta Sign Up</h1>
						<div class="LR-sign-up"> 
							<form action="./join_form__.php" method="post" name="form" id="form" autocomplete="off">
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
							<!-- facebook login -->
							<div align="center" style="">
							<a href="<?php echo $loginUrl; ?>">
								<img src="./pics/facebook_use_info.png" width="220px">
							</a>
							</div>
							<!-- other fields -->
							<div class="LR-extra-fields">
								<input name="email" id="email" type="email" class="LR-sign-up-input signup-email" value="<?php echo "$email"; ?>" placeholder="E-mail Address" />
								<input name="first_name" id="first_name" type="text" class="LR-sign-up-input first-name" value="<?php echo "$first_name"; ?>" placeholder="First Name" />
								<input name="last_name" id="last_name" type="text" class="LR-sign-up-input last-name" value="<?php echo "$last_name"; ?>" placeholder="Last Name" />
								<input name="hometown" id="hometown" type="text" class="LR-sign-up-input hometown" value="<?php echo "$hometown"; ?>" placeholder="Hometown" /> 
								<input name="phonenumber" id="phonenumber" type="tel" class="LR-sign-up-input phonenumber" value="<?php echo "$phonenumber"; ?>" placeholder="Phonenumber" />
								<input name="date_of_birth" id="date_of_birth" type="text" class="LR-sign-up-input date_of_birth" value="<?php echo "$date_of_birth"; ?>" placeholder="Date of Birth (dd-mm-yyyy)" />
								
								<select name="sex" class="LR-sign-up-input sex" placeholder="Sex">
									<option disabled <?php if ($sex==''){echo 'selected';}?>>Please Select...</option>
									<option value="male" <?php if ($sex=='male'){echo 'selected';}?>>male</option>
									<option value="female" <?php if ($sex=='female'){echo 'selected';}?>>female</option>
									
								  </select>
								
								<font size="-1" color="#FFFFFF">letters or numbers only:</font>
								<input name="password" id="password" type="password" class="LR-sign-up-input password" value="<?php echo "$password"; ?>" placeholder="Password" />
								<input name="password2" id="password2" type="password" class="LR-sign-up-input password" value="<?php echo "$password2"; ?>" placeholder="Repeat Password" />
								
								<input type="submit" name="submit" title="Join" value="Join" class="LR-sign-up-submit">
							</div><!-- .extra-fields -->
							</div><!-- .sign-up-container-inner -->
							</div><!-- .sign-up-container -->
							</form>
							
						</div><!-- .sign-up -->			
					</div><!-- .box-inner -->	
				</div><!-- .box-container -->
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
		</div><!-- #content -->	

</div>  
  <!-- End LaunchRock Widget -->
 
 