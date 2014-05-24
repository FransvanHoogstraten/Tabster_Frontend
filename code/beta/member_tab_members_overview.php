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
	$toplinks = '<font size="2"><a href="./member_profile.php">' . $email . '</a> &bull; 
	<a href="./logout.php">Log Out</a></font>';
} else {
	header("location: ./index.php"); 	

}

$tab_id = $_SESSION['tab_id'];
$tab_name = $_SESSION['tab_name'];
$location_name = $_SESSION['location_name'];
$sale_id = $_GET['sale'];

$sql = mysql_query("
	SELECT users.first_name, users.last_name, tabs_LT_users.status, tabs_LT_users.seconds_in_memory, tabs_LT_users.timestamp_join, tabs_LT_users.timestamp_leave
	FROM tabs_LT_users
	LEFT JOIN users 
	ON tabs_LT_users.user_id=users.id
	WHERE tabs_LT_users.tab_id='$tab_id'
	");     

$arr = array( array('User', 'Seconds'));
	
while($row = mysql_fetch_array($sql)){
	$first_name = $row["first_name"];
	$last_name = $row["last_name"];	
	$status = $row["status"];
	$timestamp_join = $row["timestamp_join"];
	$timestamp_leave = $row["timestamp_leave"];
	$seconds_in_memory = $row["seconds_in_memory"];
	
	
	if ($status == 'active'){
		$diff_in_seconds = strtotime( $timestamp ) - strtotime( $timestamp_join );
		$seconds = $diff_in_seconds + $seconds_in_memory;
				
	} else{
		$diff_in_seconds = strtotime( $timestamp_leave ) - strtotime( $timestamp_join );
		$seconds = $diff_in_seconds + $seconds_in_memory;
	}

	#fill the array:
	array_push($arr, array($first_name.' '.$last_name, $seconds));
	
}
?>
<!DOCTYPE html> 
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Tab Members Overview</title> 
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  
  function drawChart() {
	var data = google.visualization.arrayToDataTable(
	<?php echo json_encode($arr); ?>
	);

	var options = {
		chartArea: {'width': '90%', 'height': '90%'},
		legend: {'position': 'none'},
		backgroundColor: 'none', 
		height: '250'
		
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
  }
</script>


<!-- Google -->
<meta name="title" content="Yoombler | Tab Members Overview"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Tab Members Overview"> 
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

									<!-- Members Overview -->
									<div class="tab_details">
										<font class="tab_details timestamp"><b>
											Current Tab Members:
										</b></font><br/>
									</div>
									
									<table >
										<!-- First line is to set the layout of the table (without we would get a fuckedup layout -->
										<tr width='100%' valign='bottom'><td width='50%'><td><td width='50%'><td></tr>
										<?php 
										$sql = mysql_query("
											SELECT users.id, users.first_name, users.last_name, tabs_LT_users.status
											FROM tabs_LT_users
											LEFT JOIN users 
											ON tabs_LT_users.user_id=users.id
											WHERE tabs_LT_users.tab_id='$tab_id' AND tabs_LT_users.status='active'
											");     
										
										$position = 'left';
																				
										while($row = mysql_fetch_array($sql)){
											$member_id = $row["id"];
											$first_name = $row["first_name"];
											$last_name = $row["last_name"];	
											$status = $row["status"];
																						
											$cell_content="	<div>
															<img src='./memberFiles/$member_id/pic1.jpg' style='height:100px; max-width: 95%; ' /><br/>
															$first_name $last_name
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
									
									<!-- Former Members Overview -->
									<?php
									$sql_check = mysql_query("SELECT * FROM tabs_LT_users WHERE tabs_LT_users.tab_id='$tab_id' AND tabs_LT_users.status='closed' LIMIT 1");
									$check = mysql_num_rows($sql_check); 
									if ($check > 0){ 
											echo 	"<div class='tab_details' style='margin-top: 25px;'>
														<font class='tab_details timestamp'><b>
														Former Tab Members:
														</b></font><br/>
													</div>";									
									}
									?>
									<table >
										<!-- First line is to set the layout of the table (without we would get a fuckedup layout -->
										<tr width='100%' valign='bottom'><td width='50%'><td><td width='50%'><td></tr>
										<?php 
										$sql = mysql_query("
											SELECT users.id, users.first_name, users.last_name, tabs_LT_users.status, tabs_LT_users.seconds_in_memory
											FROM tabs_LT_users
											LEFT JOIN users 
											ON tabs_LT_users.user_id=users.id
											WHERE tabs_LT_users.tab_id='$tab_id' AND tabs_LT_users.status='closed'
											");     
										
										$position = 'left';
										while($row = mysql_fetch_array($sql)){
											$member_id = $row["id"];
											$first_name = $row["first_name"];
											$last_name = $row["last_name"];	
											$status = $row["status"];
											
											$cell_content="	<div>
															<img src='./memberFiles/$member_id/pic1.jpg' style='height:100px; max-width: 95%;' /><br/>
															$first_name $last_name
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
									
									<!-- Graph -->
									<div class="tab_details" style="margin-top: 25px;">
										<font class="tab_details timestamp"><b>
											Division based on time (seconds):
										</b></font><br/>
									</div>
									
									<div id="chart_div" align="center">
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

