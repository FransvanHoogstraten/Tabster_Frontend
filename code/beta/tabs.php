<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');

//Connect to the database through our include 
include_once "./connect_to_mysql.php";

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Tab Overview</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Tab Overview"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Tab Overview"> 
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
						
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<!-- Float right -->
					<div style='float: right; margin-left: -100%;'>
						<a href="./tab_add.php"><img src="./pics/add_button.png" width='40px'/></a>
					</div>
					<!-- Page Title -->
					<h1 class="LR-site-title">My Tabs</h1>
					<!-- Reset Float -->
					<div style="clear: both;"></div>
					
					<div class="LR-sign-up"> 
						<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
										
									<?php $sql = mysql_query("
									SELECT tabs.id, tabs.timestamp_update, tabs.name AS name_tab, tabs.status as tab_status, tabs_LT_users.status AS member_status, locations.name AS name_location
									FROM tabs 
									JOIN tabs_LT_users ON tabs.id = tabs_LT_users.tab_id 
									JOIN locations ON tabs.location_id = locations.id
									WHERE tabs_LT_users.user_id='$userid' 
									ORDER BY tabs.timestamp_update DESC 
									");
									
									while($row = mysql_fetch_array($sql)){$tab_id = $row["id"];$timestamp_update = $row["timestamp_update"];$tab_name = $row["name_tab"];
										$member_status = $row["member_status"];$tab_status = $row["tab_status"];$location_name = $row["name_location"];
										$timestamp_update=explode(' ', $timestamp_update);
										$date_update= $timestamp_update[0];
										$date_update= date('jS F Y', strtotime($date_update));

										if ($member_status == 'active'){
											$status_attr='';
										}else{
											$status_attr='not_active';
										}if ($tab_status == 'active'){
											$color='#FFFFFF';
										}else{
											$color='#777777';
									}?>
																			
								<form id="myform" name="myform" method="get" action="./member_tab_details.php">
									<input id="id" name="id" value="<?php echo $tab_id;?>" type="hidden" />
									<button type="link" class="LR-sign-up-submit tab_button <?php echo $status_attr; ?>">
										<font color = "<?php echo $color;?>"><b><?php echo $tab_name; ?></b><br/><font size=1><?php echo $location_name; ?> - <?php echo $date_update; ?></font></font>
									</button>
								</form>
								
								
							
								<?php } ?> 
									
								</div>
								
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
 
 