<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
/* 
Created By Adam Khoury @ www.flashbuilding.com 
-----------------------June 20, 2008----------------------- 
*/
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

$timestamp = date("Y-m-d H:i:s");

if ($_POST['email']) {
//Connect to the database through our include 
include_once "./connect_to_mysql.php";
$email = stripslashes($_POST['email']);
$email = strip_tags($email);
$email = mysql_real_escape_string($email);
// Make query and then register all database data that -
// cannot be changed by member into SESSION variables.
// Data that you want member to be able to change -
// should never be set into a SESSION variable.
$sql = mysql_query("SELECT * FROM users WHERE email='$email' AND emailactivated='1' AND status='active'"); 
$login_check = mysql_num_rows($sql);
if($login_check > 0){ 
	while($row = mysql_fetch_array($sql)){

		$userid = $row["id"];
		$first_name = $row["first_name"];
		$md5_from_mysql = $row["password"];

		// Start assembly of Email Member the activation link
		$to = "$email";
		$from = "info@yoombler.com";
		$subject = "Password Reset Link";
		//Begin HTML Email Message where you need to change the activation URL inside
		$message = '<html>
		<body bgcolor="#FFFFFF">
		Hi ' . $first_name . ',
		<br /><br /> 
		You requested a link to reset the password of your Yoombler account. 
		<br /><br />
		Please click here to reset the password now &gt;&gt;
		<a href="http://www.yoombler.com/beta/edit_password.php?id=' . $userid . '&md5='. $md5_from_mysql .'">
		RESET NOW</a>
		<br /><br />
		This message was sent to: 
		<br /><br />
		E-mail Address: ' . $email . ' <br />
		<br /><br /> 
		If you did not request this password request link, please contact us at info@yoombler.com.
		<br /><br /> 
		Thanks! <br /><br /> 
		<strong>Team Yoombler</font></strong><br /><br />
		<img src="http://www.yoombler.com/beta/pics/logo_new_noBG.jpg" alt="Yoombler Logo" width="120">
		</body>
		</html>';
		// end of message
		$headers= "From: Yoombler Password Reset <".$from.">\r\n";
		$headers.= "Reply-To: Yoombler Password Reset <".$from.">\r\n";
		$headers.= "X-Mailer: PHP/" . phpversion()."\r\n";
		$headers.= "MIME-Version: 1.0" . "\r\n";
		$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";
		$to = "$to";
		// Finally send the password reset email to the member
		mail($to, $subject, $message, $headers);



		// Success!!!
		$message_inque=True;
		$header_target='./index.php';
		$text="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><h4>OK $first_name, we are almost there:</h4><br />
		We just sent a Password Reset Link to: $email<br /><br />
		<strong><font color=\"#FF6600\">Please check your email inbox in a moment</font></strong> to click on the Password Reset Link <br />
		inside the message. <br /><br />
		<strong><font color=\"#FF6600\">If the email does not show in your inbox, please check your spamfilter!</font></strong>";
				
	}
}else{

		// Failure
		$message_inque=True;
		$header_target='./forgot_password.php';
		$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
		<b><br />That email address is not known to our system. Please try again.<br /><br />
		You will be redirected momentarily....</b>";
		}
	}
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler</title> 

<script type="text/javascript">
<!-- Form Validation -->
function validate_form ( ) { 
valid = true; 
if ( document.logform.email.value == "" ) { 
alert ( "Please enter your Email Address" ); 
valid = false;
}
return valid;
}
<!-- Form Validation -->
</script>

<!-- Google -->
<meta name="title" content="Yoombler | Forgot Password"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Forgot Password"> 
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
			<?php if ($message_inque == True){print $text; header("Refresh: 5; '$header_target'"); exit();}?>			
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<h1 class="LR-site-title" style="">Password Reset Request:</h1>
					<div class="LR-sign-up"> 
							
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
									<form action="./forgot_password.php" method="post" enctype="multipart/form-data" name="logform" id="logform" onsubmit="return validate_form ( );">
										<input name="email" id="email" type="email"  class="LR-sign-up-input email" placeholder="Email Address" /></td>
										<input name="submit" name="submit" type="submit" value="Submit" class="LR-sign-up-submit"/>
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




