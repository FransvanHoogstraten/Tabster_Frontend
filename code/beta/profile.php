<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
$timestamp = date("Y-m-d H:i:s");

// Browser Cashe Flush (because of picture upload)
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}
//Connect to the database through our include 
include_once "./connect_to_mysql.php";

$errorMsg="";
if (isset($_POST['sex'])){ // Sex variable cannot be 'unset'
	// Filter the posted variables
	$first_name = ereg_replace("[^A-Za-z0-9]", "", $_POST['first_name']); // filter everything but spaces, numbers, and letters
	$last_name = ereg_replace("[^A-Z a-z0-9]", "", $_POST['last_name']); // filter everything but spaces, numbers, and letters
	$hometown = ereg_replace("[^A-Z a-z0-9]", "", $_POST['hometown']); // filter everything but spaces, numbers, and letters
	$phonenumber = ereg_replace("[^0-9]", "", $_POST['phonenumber']); // filter everything but numbers
	$date_of_birth = $_POST['date_of_birth']; // filter everything but numbers
	$sex = ereg_replace("[^a-z]", "", $_POST['sex']); // filter everything but lowercase letters
		
	if((!$first_name) || (!$last_name) || (!$hometown) || (!$date_of_birth) || (!$sex) || (!$email) || (!$phonenumber)){
		
		$errorMsg .= "You did not submit the following required information:<br />";

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
		$errorMsg .= '<br>'; 
		
	}else {
				
		// Add user info into the database table, claim your fields then values 
		$date_of_birth_mysql= date('Y-m-d', strtotime($date_of_birth));
		$sql = mysql_query("UPDATE users SET timestamp_update='$timestamp', first_name='$first_name', last_name='$last_name', 
		hometown='$hometown', phonenumber='$phonenumber', date_of_birth='$date_of_birth_mysql', sex='$sex' WHERE id='$userid'") or die (mysql_error());

		// Then print a message to the browser for the joiner 
		$message_inque=True;
		$header_target='./profile.php';
		$SuccessMsg.="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><h4>OK $first_name, your profile info has been updated!</h4><br />";
	
	} // Close else after database duplicate field value checks
} // Close else after missing vars check

// Query member data from the database and ready it for display
$sql = mysql_query("SELECT * FROM users WHERE id='$userid'"); 
while($row = mysql_fetch_array($sql)){
$role_id = $row["role_id"];
$first_name = $row["first_name"];
$last_name = $row["last_name"];
$hometown = $row["hometown"];
$phonenumber = $row["phonenumber"];
$date_of_birth = $row["date_of_birth"];
$date_of_birth= date('d-m-Y', strtotime($date_of_birth));
$sex = $row["sex"];
$status = $row["status"];
$signupdate = strftime("%b %d, %Y", strtotime($row['timestamp_create']));
}

?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | My Profile</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | My Profile"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | My Profile"> 
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
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner" align="center">
					<h1 class="LR-site-title" style="">My Profile</h1>
					<a href="./edit_pic.php"><img src="./memberFiles/<?php echo "$userid"; ?>/pic1.jpg" alt="Ad" width="100" /></a>
					<div class="LR-sign-up"> 
							
						<div class="LR-sign-up-container">
						<div class="LR-sign-up-container-inner LR-clearfix">
							<div class="LR-extra-fields">
								<form action="./profile.php" method="post" name="form" id="form" autocomplete="off">	
									<input name="first_name" id="first_name" type="text" class="LR-sign-up-input" value="<?php echo "$first_name"; ?>" placeholder="First Name" />
									<input name="last_name" id="last_name" type="text" class="LR-sign-up-input" value="<?php echo "$last_name"; ?>" placeholder="Last Name" />
									<input name="hometown" id="hometown" type="text" class="LR-sign-up-input" value="<?php echo "$hometown"; ?>" placeholder="Hometown" /> 
									<input name="phonenumber" id="phonenumber" type="tel" class="LR-sign-up-input" value="<?php echo "$phonenumber"; ?>" placeholder="Phonenumber" />
									<input name="date_of_birth" id="date_of_birth" type="text" class="LR-sign-up-input date_of_birth" value="<?php echo "$date_of_birth"; ?>" placeholder="Date of Birth (yyyy/mm/dd)" />
									
									<select name="sex" class="LR-sign-up-input" placeholder="Sex">
										<option disabled <?php if ($sex==''){echo 'selected';}?>>Please Select...</option>
										<option value="male" <?php if ($sex=='male'){echo 'selected';}?>>male</option>
										<option value="female" <?php if ($sex=='female'){echo 'selected';}?>>female</option>
									</select>
									<input type="submit" name="submit" title="Save Profile" value="Save Profile" class="LR-sign-up-submit">
								</form>
								<form action="./edit_password.php" method="link">
									<input type="submit" value="Change Password" class="LR-sign-up-submit">
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

 
