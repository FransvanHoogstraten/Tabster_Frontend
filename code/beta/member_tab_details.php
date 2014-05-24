	<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');


//Connect to the database through our include 
include_once "./connect_to_mysql.php";

$toplinks = "";
if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
	$toplinks = '<font size="2"><a href="./member_profile.php">' . $email . '</a> &bull; 
	<a href="./logout.php">Log Out</a></font>';
} else {
	header("location: ./index.php"); 	
}

$level = $_GET['level'];
$tab_id = $_SESSION['tab_id'];
// Only if GET tab_id set, must it be loaded
if (isset($_GET['id'])){
	$tab_id = $_GET['id'];
}
// Renew session variable
$_SESSION['tab_id']=$tab_id;

// Query if this user may see this tab
$sql = mysql_query("SELECT * FROM tabs_LT_users WHERE tab_id='$tab_id' AND user_id='$userid'"); 
$login_check = mysql_num_rows($sql);
if($login_check == 0){
	$header_target='./tabs.php';
	$message_inque = True;
	$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
		<b><br />You are not authorized to view this tab.<br /><br />
		You will be redirected momentarily....</b>";
}

// Query for tab_name
$sql = mysql_query("SELECT name, location_id FROM tabs WHERE id='$tab_id'"); 
while($row = mysql_fetch_array($sql)){
$tab_name = $row["name"];
$location_id = $row["location_id"];
}
$_SESSION['tab_name']=$tab_name;

// Query for location_name
$sql = mysql_query("SELECT name FROM locations WHERE id='$location_id'"); 
while($row = mysql_fetch_array($sql)){
$location_name = $row["name"];
}
$_SESSION['location_name']=$location_name;
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Tab Details</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Tab Details"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Tab Details"> 
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
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">

					<!-- Button for leaving tabs -->
					<div style="float: right;"><!-- Hier staat de margin EXPRES niet op -100, anders gaat de text er doorheen lopen -->
						<?php $sql = mysql_query("
							SELECT * 
							FROM tabs_LT_users
							WHERE tab_id='$tab_id' AND user_id='$userid' and status='closed'
							");
							$check = mysql_num_rows($sql); 
							
							$sql = mysql_query("
							SELECT * 
							FROM tabs
							WHERE id='$tab_id' AND status='active'
							");
							$check2 = mysql_num_rows($sql); 
						?>
						<?php if (($check > 0) and ($check2 > 0)){ echo "<a href='./tab_join2.php'><img src='./pics/add_button.png' width='40px' /></a>";}?>
						<?php if (($check == 0) and ($check2 > 0)){echo "<a href='./tab_leave.php'><img src='./pics/leave_tab.png' width='40px' /></a>";}?>
							
						
						
					</div>

					<!-- Button for members overview -->
					<div style="float: left; "><!-- Hier staat de margin EXPRES niet op -100, anders gaat de text er doorheen lopen -->
						<a href="./member_tab_members_overview.php"><img src="./pics/members2.png" width='40px' /></a>
					</div>

				
					<!-- TabName & Location -->
					<div style='margin-bottom: 10px;'>
						<b><font size=6><?php echo $tab_name; ?></font></b><br/>
						<font size=3><i><?php echo $location_name; ?></i></font>
					</div>
					
					<!-- To reset the 'float' commands -->
					<div style="clear: both;">
					</div>
					
					<!-- Dropdown for Total/Personal tab -->
					<div>
						<form action="./member_tab_details.php" method="get" enctype="multipart/form-data" name="form" id="form">
							<select name="level" class="LR-sign-up-input tab_level" onchange="this.form.submit();">			<!-- This will reload the current page -->
								<option  value="">Total Tab Details:</option>	
								<option <?php if ($level == 'personal') print 'selected '; ?> value="personal">Personal Tab Details:</option>	
							</select>
						</form>
					</div>
					<!-- Body -->
					<div class="LR-sign-up"> 
						<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner black">
								<div class="LR-extra-fields">
									<?php $total_amount = 0; $total_amount = (float) $total_amount;
									if ($level == 'personal'){
									// Personal Tab overview
									$sql = mysql_query("
										(SELECT _events_sales_personal.SALE_id AS sale_id, _events_sales_personal.timestamp, consumptions.name, 
										_events_sales_personal.consumption_nr, _events_sales_personal.amount, _events_sales_personal.members_nr, _events_sales_personal.id, 1 AS tbl
										FROM `_events_sales_personal` 
										LEFT JOIN `consumptions` on _events_sales_personal.consumption_id = consumptions.id 
										WHERE _events_sales_personal.tab_id='$tab_id' AND _events_sales_personal.user_id='$userid' AND _events_sales_personal.status='active')

										UNION

										(SELECT _events_tabs.id, _events_tabs.timestamp,  event_type.description, users.first_name, users.last_name, '', '', 2 AS tbl
										FROM _events_tabs 
										LEFT JOIN event_type ON _events_tabs.type_id=event_type.id
										LEFT JOIN users ON _events_tabs.user_id=users.id
										WHERE _events_tabs.tab_id='$tab_id')

										ORDER BY timestamp ASC;");     
									}else{
									// Total Tab overview
									$sql = mysql_query("
										(SELECT _events_sales.id AS sale_id, _events_sales.timestamp,  consumptions.name, _events_sales.consumption_nr, _events_sales.amount, 1 AS tbl
										FROM `_events_sales` 
										LEFT JOIN `consumptions` on _events_sales.consumption_id = consumptions.id 
										WHERE _events_sales.tab_id='$tab_id' AND _events_sales.status='active')

										UNION

										(SELECT _events_tabs.id, _events_tabs.timestamp,  event_type.description, users.first_name, users.last_name, 2 AS tbl
										FROM _events_tabs 
										LEFT JOIN event_type ON _events_tabs.type_id=event_type.id
										LEFT JOIN users ON _events_tabs.user_id=users.id
										WHERE _events_tabs.tab_id='$tab_id') 
										ORDER BY timestamp ASC;");     
									}
									while($row = mysql_fetch_array($sql)){
									$sale_id = $row["sale_id"];
									$timestamp = $row["timestamp"];
									$consumption_name = $row["name"];				#in case of TABS message, this is the DESCRIPTION
									$consumption_raw = $row["consumption_nr"];		#in case of TABS message, this is the FIRST NAME							
									$amount = $row["amount"];						#in case of TABS message, this is the LAST NAME
									$members_nr = $row["members_nr"];				
									$tbl = $row["tbl"];
									
									if ($level == 'personal'){
										$consumption_nr=($consumption_raw/$members_nr);
										$consumption_nr=round($consumption_nr, 1);
									} else {
									$consumption_nr=$consumption_raw;
									}
									
									$date=explode(' ', $timestamp);
									$date= $date[0];
									$date= date('jS F Y', strtotime($date));
									if ($date!=$date_old){
										echo "	<div>
													<font class='tab_details date'>$date</font>
												</div>";
									} 
									$date_old=$date;	
									
									$timestamp=explode(' ', $timestamp);
									$timestamp= $timestamp[1];
									$timestamp= substr($timestamp,0,-3);
									
									if ($tbl == '1'){
									#it is a SALES entry:
									$amount = money_format('%.2n', $amount);
									$total_amount = $total_amount + $amount;
									?> 
									
									<!-- CONSUMPTIONS -->
									<form id="myform" name="myform" method="get" action="./member_cons_details.php">
										<div class="tab_details">
											<!-- timestamp -->
											<font class="tab_details timestamp"><?php echo $timestamp; ?></font>
											<!-- button -->
											<input id="sale" name="sale" value="<?php echo $sale_id;?>" type="hidden" />
											<button type="link" class="LR-sign-up-submit consumption_button">
												<?php echo $consumption_nr.' '.$consumption_name; ?>
											</button>
											<!-- amount -->
											<font class="tab_details amount" size=4><b>&euro;<?php echo $amount; ?></b></font>
										</div>
									</form>									
									
									<?php } if ($tbl == '2'){
									#renaming of variables, for easier understanding
									$entry_description  = $consumption_name;
									$entry_first_name = $consumption_raw;
									$entry_last_name = $amount;		
									
									#no longer used, but no need to delete yet
									if ($entry_description == 'Created:'){$plus_min = 'plus';}
									if ($entry_description == 'Closed:'){$plus_min = 'min';}
									if ($entry_description == 'Joined:'){$plus_min = 'plus';}
									if ($entry_description == 'Left:'){$plus_min = 'min';}
									
									?> 
									
									<!-- SYSTEM MESSAGES -->
									<div class="tab_details system_messages">
										<font class="tab_details timestamp"><?php echo $timestamp.'  '.$entry_description.' '.$entry_first_name.' '.$entry_last_name;; ?></font>
									</div>
									
									
									<?php }} ?>
									
									<div style='margin: 10px;'>
										<div style='float: left'>
											<font size="5" style='color: white;'><strong>Total Amount:</strong></font>
										</div>
										<div style='float: right'>
											<font size="5" style='color: white;'><strong>&euro;<?php echo money_format('%.2n', $total_amount); ?></strong></font>
										</div>
										<div style='clear: both;'>
										</div>
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








