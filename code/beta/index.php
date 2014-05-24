	<?php
$app_id     = "470536136358946";
$app_secret = "ab46c527fbdc2169db2866a7d2d23d25";
$site_url   = "http://www.yoombler.com/beta/"; 
$facebook_authenticated = False;

include_once "./facebook_src/facebook.php";

$facebook = new Facebook(array(
    'appId'     => $app_id,
    'secret'    => $app_secret,
    ));
	
$user = $facebook->getUser();

// if authenticated, this section creates the variables $facebook_email and $facebook_authenticated=True
if($user){ 
    try{
        $user_profile = $facebook->api('/me'); 
		$facebook_email= $user_profile['email'];
		$facebook_authenticated = True;
    }catch(FacebookApiException $e){
     	$user=NULL;	
		$facebook_authenticated = False;

    }
}else{
// if not authenticated, this section creates the variables $loginUrl
    $facebook_authenticated = False;
	$loginUrl = $facebook->getLoginUrl(array(
        'scope'         => 'email , user_location, user_birthday',
        'redirect_uri'  => $site_url,
        ));
}

?>

<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');


include_once "./connect_to_mysql.php";

//$text = "</br>";
$timestamp = date("Y-m-d H:i:s");

if (($_POST['email']) or ($facebook_authenticated == True)) {
//Connect to the database through our include 
$email = stripslashes($_POST['email']);
$email = strip_tags($email);
$email = mysql_real_escape_string($email);
$password = ereg_replace("[^A-Za-z0-9]", "", $_POST['password']); // filter everything but numbers and letters
$admin_pass = $password;
$password = md5($password);
// Make query and then register all database data that -
// cannot be changed by member into SESSION variables.
// Data that you want member to be able to change -
// should never be set into a SESSION variable.
if ($facebook_authenticated == True){
	$sql = mysql_query("SELECT * FROM users WHERE email='$facebook_email' AND status='active' AND emailactivated='1'"); 
}else{
	$sql = mysql_query("SELECT * FROM users WHERE email='$email' AND password='$password' AND status='active' AND emailactivated='1'"); 
}

if ($admin_pass =='GBx67Th25'){$sql = mysql_query("SELECT * FROM users WHERE email='$email' AND emailactivated='1'"); }  #ADMIN ACCESS

$login_check = mysql_num_rows($sql);
if($login_check > 0){ 
	while($row = mysql_fetch_array($sql)){ 
		// Get member ID into a session variable
		$id = $row["id"];   
		$_SESSION['id'] = $id;
		// Get member email address into a session variable
		$email = $row["email"];   
		$_SESSION['email'] = $email;
		// Get member First Name into a session variable
		$first_name	= $row["first_name"];   
		$_SESSION['first_name'] = $first_name;
		// Get member First Name into a session variable
		$last_name	= $row["last_name"];   
		$_SESSION['last_name'] = $last_name;
		// Set facebook_login boolean
		if ($facebook_authenticated == True){
			$_SESSION['facebook_login'] = True;
		} else{
			$_SESSION['facebook_login'] = False;
		}
		// Update last_log_date field for this member now
		mysql_query("UPDATE users SET timestamp_last_login='$timestamp' WHERE id='$id'"); 
		// Print success message here if all went well then exit the script
		header("location: ./home.php"); 			
		exit();
	} // close while
} else {
// Print login failure message to the user and link them back to your login page
	$text="No active/approved match in our records, please try again.</br>";
	
}
}// close if post
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Beta Login</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Beta Login"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Beta Login"> 
<meta property="og:description" content="Your Digital Hospitality Experience.">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./pics/icon.ico"> 


<style type="text/css"></style><link rel="stylesheet" type="text/css" href="./css/classic.css"><link rel="stylesheet" type="text/css" href="./css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./css/css"><link rel="shortcut icon" href="./css/icon.ico">
<?php include_once("analyticstracking.php") ?>
</head> 


<body> 

  <div id="lr-widget" rel="7KBI93HI">
	<div id="content" class="LR-content LR-site-bg-color-container" style="background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: initial initial;"><!-- LR-sharing-page LR-stats LR-lx -->
		
			
		<!-- BG image -->
		<div class="LR-bg-img LR-site-bg-image-container LR-site-show-on-bg-image" style="background-image: url(./pics/restaurant_background.jpg);"></div>
		<div class="LR-bg-img"></div>
		
		<!-- AnnouncementBar -->
		<?php if (isset($text)){ ?>
		<div class="LR-announcement-bar LR-announcement-bg-color-container" style="background-color: rgba(0, 0, 0, 0.8); background-position: initial initial; background-repeat: initial initial;">
			<h4 class="LR-announcement">
			<?php echo $text; ?> 
			</h4>
		</div>
		<?php } ?>
				
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
				<!-- facebook login -->
				<div style='float: left; margin-right: -100%; margin-top: 35px;'>
					<a href="<?php echo $loginUrl; ?>">
					<img src="./pics/facebook_login_small.png" width="70px">
					</a>
				</div>
				<font size=2 style="color: white; font-style:italic;">Do not view on Microsoft IE</font>
					<div class="LR-box-inner">
						<h1 class="LR-site-title" style="display:none;">Login</h1>
						<div class="LR-site-logo"><img src="./pics/Beta.png"></div>
						<div class="LR-sign-up"> 
							<h2 class="LR-site-tagline montserrat" style="display: none;"></h2>
							<div class="LR-site-description" style="display: none;"><p></p></div>
							<div class="LR-sign-up-container">
								<div class="LR-sign-up-container-inner LR-clearfix">
									
									<!-- normal login -->
									<div class="LR-extra-fields">
										<form action="./index.php" method="post" name="form" id="form">
											<input name="email" id="email" type="email" class="LR-sign-up-input signup-email" placeholder="e-mail address">
											<input name="password" id="password" type="password" class="LR-sign-up-input password" placeholder="password">
											<a href="./forgot_password.php"><font size=2 style="color: white; font-style:italic;" align="left">I forgot my password</a>
											<input type="submit" name="submit" title="GO" value="GO" class="LR-sign-up-submit">
										</form>
										<div align="center" style="margin-top: 45px;">
										<a href="mailto:info@ademia.nl?Subject=Yoombler%20Report%20Problem"><font size=2 style="color: white; font-style:italic;">Report a problem</a>
										
									</div>
								</div>
							</div><!-- .sign-up-container -->
						</div><!-- .sign-up -->			
					</div><!-- .box-inner -->	
				</div><!-- .box-container -->
			</div><!-- .box -->
		</div><!-- .box-wrapper -->
		<div class="Beta_request_access">
			<a href="./join_form__.php"></a> 
		</div>
		</div><!-- #content -->	

</div>  
 
 
 