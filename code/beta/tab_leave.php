<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');
$timestamp = date("Y-m-d H:i:s");

//Connect to the database through our include 
include_once "./connect_to_mysql.php";
	
$toplinks = "";
if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
	$first_name = $_SESSION['first_name'];
	$toplinks = '<font size="2"><a href="./.php">' . $email . '</a> &bull; 
	<a href="./logout.php">Log Out</a></font>';
} else {
	header("location: ./index.php"); 
}

$tab_id=$_SESSION['tab_id'];

$sql = mysql_query("SELECT * FROM tabs WHERE id='$tab_id'"); 
while($row = mysql_fetch_array($sql)){
	$tab_name = $row["name"];
	}
				
if ($_POST['leave']=='yes'){
	$sql = mysql_query("
	INSERT INTO __events_RAW (timestamp, type_id, user_id, tab_id) 
	VALUES('$timestamp','5','$userid','$tab_id')") 
	or die (mysql_error());
	
	$message_inque = True;	//Boolean, to stop the page from loading furthur in the HTML part
	$header_target = './tabs.php';
	$text = "<a href=$header_target><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
	<h4><br />$first_name, you just left Tab <strong><font color=\"#FF6600\">'$tab_name'</strong></font>.</h4><br />
	You will be redirected momentarily...";
	
}


?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Leave Tab</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Leave Tab"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Leave Tab"> 
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
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<font size=3><i>Leaving Tab:</i></font>
					<h1 class="LR-site-title"><?php echo $tab_name; ?></h1>
					<div class="LR-sign-up"> 
							
						<div class="LR-sign-up-container">
						<div class="LR-sign-up-container-inner LR-clearfix">
							<div class="LR-extra-fields">
								<form action="./tab_leave.php" method="post" enctype="multipart/form-data" name="form" id="form">
									<input type="hidden" name="leave" value="yes">
									<input type="submit" name="Submit" value="Confirm" class="LR-sign-up-submit"/>
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
