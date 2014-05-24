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

$tab_id = $_SESSION['tab_id'];
$tab_name = $_SESSION['tab_name'];
$location_name = $_SESSION['location_name'];
$sale_id = $_GET['sale'];

?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Consumption Details</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Consumption Details"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Consumption Details"> 
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

					<!-- TabName & Location -->
					<div style='margin-bottom: 10px;'>
						<b><font size=6><?php echo $tab_name; ?></font></b><br/>
						<font size=3><i><?php echo $location_name; ?></i></font>
					</div>
					
					<!-- Body -->
					<div class="LR-sign-up"> 
						<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner black">
								<div class="LR-extra-fields">
									<?php 
									$sql = mysql_query("
										SELECT _events_sales.timestamp,  consumptions.name AS con_name , _events_sales.consumption_nr, _events_sales.amount, tabs.name AS tab_name, locations.name AS loc_name
										FROM _events_sales 
										LEFT JOIN consumptions on _events_sales.consumption_id = consumptions.id 
										LEFT JOIN tabs on _events_sales.tab_id = tabs.id 
										LEFT JOIN locations on tabs.location_id = locations.id 
										WHERE _events_sales.id='$sale_id'
										");     
									$row = mysql_fetch_array($sql);
									$timestamp = $row["timestamp"];
									$consumption_name = $row["con_name"];		
									$consumption_nr = $row["consumption_nr"];	
									$amount = $row["amount"];					
									$tab_name = $row["tab_name"];				
									$loc_name = $row["loc_name"];
									
									$timestamp=explode(' ', $timestamp);
									$date= $timestamp[0];
									$timestamp= $timestamp[1];
									?> 
														

									<!-- Consumption Desciption -->									
									<div class="tab_details">
										<font class="tab_details timestamp"><b>
											Ordered on <?php echo $date; ?> at <?php echo $timestamp; ?>:
										</b></font><br/><br/>
									</div>
									<div style="float: left;">
										<font class="tab_details amount" size=4><b><?php echo $consumption_nr.'x '.$consumption_name; ?></b></font>
									</div>
									<div style="float: right;">
										<font class="tab_details amount" size=4><b>&euro;<?php echo $amount; ?></b></font>
									</div>
									<div style="clear: both;">
									</div>
									<br/>
									<br/>
									<!-- Members Overview -->
									<div class="tab_details">
										<font class="tab_details timestamp"><b>
											Participating at that moment:
										</b></font><br/>
									</div>									
									
									<table >
									
										<?php 
										$sql = mysql_query("
											SELECT users.id, users.first_name, users.last_name, _events_sales_personal.amount
											FROM _events_sales_personal
											LEFT JOIN users ON _events_sales_personal.user_id=users.id
											WHERE _events_sales_personal.SALE_id='$sale_id'
											");     
										
										$position = 'left';
										while($row = mysql_fetch_array($sql)){
											$member_id = $row["id"];
											$first_name = $row["first_name"];
											$last_name = $row["last_name"];	
											$amount = $row["amount"];
											$amount = money_format('%.2n', $amount);
											
											$cell_content="	<div>
															<img src='./memberFiles/$member_id/pic1.jpg' style='height:100px; max-width: 95%;' /><br/>
															$first_name $last_name<br/>
															<font class='tab_details amount' size=4><b>&euro;$amount</b></font>
															</div>";
											
											
											if ($position == 'left'){
												echo "<tr width='100%' valign='bottom'><td width='50%'>".$cell_content."<td>";
											} else {
												echo "<td width='50%'>".$cell_content."<td></tr>";
											}
												
											// flip the position tracker
											if ($position == 'left'){
												$position = 'right';
												$position = 'right';
											} else {
												$position = 'left';
											}
										}
										?> 
									</table>
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

