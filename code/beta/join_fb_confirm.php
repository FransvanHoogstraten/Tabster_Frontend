<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
$timestamp = date("Y-m-d H:i:s");

$app_id     = "470536136358946";
$app_secret = "ab46c527fbdc2169db2866a7d2d23d25";
$site_url   = "http://www.yoombler.com/beta/join_fb_confirm.php"; 

include_once "./facebook_src/facebook.php";
include_once "./connect_to_mysql.php";

$facebook = new Facebook(array(
    'appId'     => $app_id,
    'secret'    => $app_secret,
    ));
	

$user = $facebook->getUser();

// if authenticated, this file creates the variables $facebook_email and $facebook_authenticated=True
if($user){ 
    try{
        $user_profile = $facebook->api('/me'); 
		$facebook_id=$user_profile['id'];
		$facebook_email= $user_profile['email'];
		$facebook_first_name= $user_profile['first_name'];
		$facebook_last_name= $user_profile['last_name'];
		$facebook_location= $user_profile['location']['name'];
		$facebook_birthday= $user_profile['birthday'];
		$facebook_gender= $user_profile['gender'];
		
		// Check DB for email address
		$sql_email_check = mysql_query("SELECT id FROM users WHERE email='$facebook_email' LIMIT 1");
		$email_check = mysql_num_rows($sql_email_check); 
		if ($email_check > 0){ 
			$message_inque=True;
			$header_target='./index.php';
			$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
			<b><br />The email address linked to this Facebook account is already present in our database.<br /><br />
			You will be redirected momentarily....</b>";
			}
		
// Success!!!! Create account.

	}catch(FacebookApiException $e){
// No Success!!! foutmelding: "could not log into facebook"
     	$user=NULL;	
		$message_inque=True;
		$header_target='./index.php';
		$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
		<b><br />Unable to connect to Facebook.<br /><br />
		You will be redirected momentarily....</b>";
		//message inque

    }
}else{
// No Success!!! foutmelding: "could not log into facebook"
		$message_inque=True;
		$header_target='./index.php';
		$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
		<b><br />Unable to connect to Facebook.<br /><br />
		You will be redirected momentarily....</b>";
} 
 

if (isset($_POST['confirm'])){
		// unset facebook cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		// Set hashed password initially to 'facebook_login'
		$hashedPass = "facebook_login"; 
		// Add user info into the database table, claim your fields then values 
		$facebook_birthday_mysql= date('Y-m-d', strtotime($facebook_birthday));
		$sql = mysql_query("INSERT INTO users (timestamp_create, timestamp_update, email, password, first_name, last_name, hometown, date_of_birth, sex, phonenumber, facebook_id) 
		VALUES('$timestamp','$timestamp','$facebook_email','$hashedPass','$facebook_first_name','$facebook_last_name','$facebook_location','$facebook_birthday_mysql', '$facebook_gender', '$phonenumber', '$facebook_id')") or die (mysql_error());
		// Get the inserted ID here to use in the activation email
		$id = mysql_insert_id();
		// Create directory(folder) to hold each user files(pics, MP3s, etc.) 
		mkdir("memberFiles/$id", 0755); 
		// Put picture inside of from Facebook Graph
		copy("https://graph.facebook.com/$facebook_id/picture?type=large", "./memberFiles/$id/pic1.jpg");


		// Start assembly of Email Member the activation link
		$to = "$facebook_email";
		$from = "info@yoombler.com";
		$subject = "Complete your registration";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = '<html>
		<body bgcolor="#FFFFFF">
		Hi ' . $facebook_first_name . ',
		<br /><br /> 
		You must complete this step to activate your account with us.
		<br /><br />
		Please click here to activate now &gt;&gt;
		<a href="http://www.yoombler.com/beta/activation.php?id=' . $id . '">
		ACTIVATE NOW</a>
		<br /><br />
		Your Login Data is as follows: 
		<br /><br />
		E-mail Address: ' . $facebook_email . ' <br />
		<b>Because you logged in with facebook, no password has been set. Please use Facebook for future logins as well. </b>
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
		$facebook_email . '<br />'.
		$facebook_first_name . '<br />'.
		$facebook_last_name . '<br />'.
		$facebook_location . '<br />'.
		$facebook_gender . '<br />'.
		$facebook_birthday . '<br />'.
		'<b>Deze persoon heeft zijn Facebook account gebruikt voor deze gegevens</b><br /><br />'.
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
		$text="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><h4>OK $facebook_first_name, one last step to verify your email identity:</h4><br />
		We just sent an Activation link to: $facebook_email<br /><br />
		<strong>Please check your email inbox in a moment</strong> and click on the Activation Link to verify <br />
		your e-mail address. Because we are still in Beta testing, your information will be screened before gaining access.<br /><br />
		<strong>If the email does not show in your inbox, please check your spamfilter!</strong>";
	
	}


?>


<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Create Account with Facebook</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Create Account with Facebook"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Create Account with Facebook"> 
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
	<div class="LR-bg-img LR-site-bg-image-container LR-site-show-on-bg-image" style="background-image: url(./pics/restaurant_background2.jpg);"></div>
	<div class="LR-bg-img"></div>
		
		<!-- AnnouncementBar -->
		<div class="LR-announcement-bar LR-announcement-bg-color-container" style="background-color: rgba(0, 0, 0, 0.8); background-position: initial initial; background-repeat: initial initial;">
			<h4 class="LR-announcement">
			<?php if ($message_inque == True){print $text; header("Refresh: 5; '$header_target'"); exit();}?>
			
			</h4> 
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<div class="LR-sign-up"> 
							
						<div class="LR-sign-up-container">
						<div class="LR-sign-up-container-inner black">

						<font size=4>Confirm User Info:</font><br><br>
						
						<div align="left" style="margin: 5px;">
						<font size=2>
						<table>
						<tr>
							<td width="35%">Email Address:</td>
							<td width="65%"><i><b><?php echo $facebook_email.'<br>'; ?></b></i></td>
						</tr>
						<tr>
							<td>Name:</td>
							<td><i><b><?php echo $facebook_first_name.' '.$facebook_last_name; ?></b></i></td>
						</tr>
						<tr>
							<td>Location:</td>
							<td><i><b><?php echo $facebook_location.'<br>'; ?></b></i></td>
						</tr>
						<tr>
							<td>Birthday:</td>
							<td><i><b><?php echo $facebook_birthday.'<br>'; ?></b></i></td>
						</tr>
						<tr>
							<td>Gender:</td>
							<td><i><b><?php echo $facebook_gender.'<br>'; ?></b></i></td>
						</tr>
						</table> 
						</font>
						</div>
						
							<div class="LR-extra-fields">
								<form action="./join_fb_confirm.php" method="post" enctype="multipart/form-data" name="confirm" id="confirm">
									<input type="hidden" name="confirm" value="yes">
									<input type="submit" name="Submit" value="Confirm" class="LR-sign-up-submit"/>
								</form>
							
							</div>
							<!-- message that info can be changed in profile -->
							<div style="margin: 15px auto;"><font size=2><i>Except for the email address, all user info can be reviewed and changed at a later stage at your personal profile page.</i></font></div>
								
						</div>
						</div><!-- .sign-up-container -->
							
							
						
						
						
						</div><!-- .sign-up -->			
					</div><!-- .box-inner -->	
				</div><!-- .box-container -->
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
		</div><!-- #content -->	

</div>  

 
 