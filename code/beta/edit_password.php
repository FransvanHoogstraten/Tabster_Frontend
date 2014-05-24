<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
$timestamp = date("Y-m-d H:i:s");
//Connect to the database through our include 
include_once "./connect_to_mysql.php";

#this is to enable password reset via email, the ID and MD5 code are included via GET

$md5=$_SESSION['md5'];

if ((isset($_GET['id'])) && (isset($_GET['md5']))) {
	$userid = $_GET['id'];
	$md5 = $_GET['md5'];
		
	
	// Query member data from the database and ready it for display
	$sql = mysql_query("SELECT * FROM users WHERE id='$userid' AND emailactivated='1' LIMIT 1");
	while($row = mysql_fetch_array($sql)){
	$email = $row['email'];
	$md5_from_mysql = $row["password"];
	
	if ($md5_from_mysql == $md5){
		$_SESSION['id'] = $userid;
		$_SESSION['email'] = $email;
		
		$password_reset = True;
		$_SESSION['md5']=$md5;	//so it can be filled in at the next load$
		
	} else{
		header("location: ./index.php"); 	
		$password_reset = False;
		exit();
		}
	}
} else {
	$password_reset = False;
	$_SESSION['md5']='';
}

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}

$errorMsg="";
if (isset($_POST['password'])){ 
	// Filter the posted variables
	$passwordc = ereg_replace("[^A-Za-z0-9]", "", $_POST['passwordc']); // filter everything but numbers and letters
	$password = ereg_replace("[^A-Za-z0-9]", "", $_POST['password']); // filter everything but numbers and letters
	$password2 = ereg_replace("[^A-Za-z0-9]", "", $_POST['password2']); // filter everything but numbers and letters

	// Check if password is correct
	$hashedPass = md5($passwordc);

	// Normally $md5 will be empty (because session[md5] does not exist). But in case of 2nd load of password reset, $md5 is filled.
	if ($md5 !=''){
		$hashedPass=$md5;
	}

	$sql_check = mysql_query("SELECT id FROM users WHERE password='$hashedPass' AND id='$userid' LIMIT 1");
	$sql_check = mysql_num_rows($sql_check); 
	if ($sql_check > 0){
		$password_correct=True;
	} else {
		$password_correct=False;
		$errorMsg .= "You didn't enter the correct password.<br/>";
	}
	}
		
	
if ($password_correct == True){

	if ($password != $password2){	
		$errorMsg .= "The passwords don't match. Please try again.<br/>";
		$password = ''; $password2 = '';	
		
	}else {
		// Change password
		
		$hashedPass = md5($password);
		$sql = mysql_query("UPDATE users SET timestamp_update='$timestamp', password='$hashedPass' WHERE id='$userid'") or die (mysql_error());
		
		// Then print a message to the browser for the joiner 
		$message_inque=True;
		$header_target='./profile.php';
		$SuccessMsg.="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><h4>OK $first_name, your password has been updated!</h4><br />";
	
	}
}

// Query member data from the database and ready it for display


?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Edit Password</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Edit Password"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Edit Password"> 
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
			<?php echo $errorMsg; ?>			
			<?php if ($message_inque==True){echo $SuccessMsg; header("Refresh: 5; $header_target"); exit();}?>			
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<h1 class="LR-site-title" style="">Change Password</h1>
						<div class="LR-sign-up"> 
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
									<font size="-1" color="#FFFFFF">letters or numbers only:</font>
									<form action="./edit_password.php" method="post" name="form" id="form" autocomplete="off">	
									<input name="passwordc" id="passwordc" type="password" class="LR-sign-up-input password" placeholder="Current Password" <?php if ($password_reset == True) {echo "style=display:none;";}?> />
									<input name="password" id="password" type="password" class="LR-sign-up-input password" placeholder="New Password" />
									<input name="password2" id="password2" type="password" class="LR-sign-up-input password" placeholder="Repeat New Password" />
									
									<input type="submit" name="submit" title="Change Password" value="Change Password" class="LR-sign-up-submit">		
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

