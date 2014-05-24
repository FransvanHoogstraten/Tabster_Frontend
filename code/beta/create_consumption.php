<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');

$timestamp = date("Y-m-d H:i:s");
//Connect to the database through our include 
include_once "./connect_to_mysql.php";

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}

// load tab display mode
$tab_display_mode = 'chronological';
if (isset($_SESSION["tab_display_mode"])){
	$tab_display_mode = $_SESSION["tab_display_mode"];
}
if (isset($_POST["tab_display_mode"])){
	$tab_display_mode = $_POST["tab_display_mode"];
	$_SESSION["tab_display_mode"] = $_POST["tab_display_mode"];	
}

// loading of session variables
if ($_POST['location']!=''){
	$_SESSION['location_id'] = $_POST['location']; 
	}
$location_id = $_SESSION['location_id'];

if ($_POST['temp_tab_id']!=''){
	$_SESSION['tab_id'] = $_POST['temp_tab_id']; 
	}
$tab_id=$_SESSION['tab_id'];	

// check if tab still active ==> if not, reset session&local variables 
$sql = mysql_query("SELECT * FROM tabs WHERE id='$tab_id' AND status='active'"); 
$check = mysql_num_rows($sql);
if ($check == 0){
	$_SESSION['tab_id']='';
	$tab_id=$_SESSION['tab_id']='';
	$_SESSION['consumption_nr']='';
	$consumption_nr=$_SESSION['consumption_nr']='';
	}

// check if tab is located at presently selected location
$sql = mysql_query("SELECT * FROM tabs WHERE id='$tab_id' AND location_id='$location_id' AND status='active'"); 
$check = mysql_num_rows($sql);
if ($check == 0){
	$_SESSION['tab_id']='';
	$tab_id=$_SESSION['tab_id']='';
	$_SESSION['consumption_nr']='';
	$consumption_nr=$_SESSION['consumption_nr']='';
	}

	
if ($_POST['consumption_nr']!=''){
	$_SESSION['consumption_nr'] = $_POST['consumption_nr']; 
	}
$consumption_nr=$_SESSION['consumption_nr'];


			
$final_tab_id=$_POST['final_tab_id'];
$final_consumption_id=$_POST['final_consumption_id'];
$final_consumption_nr=$_POST['final_consumption_nr'];
$final_price=$_POST['final_price'];

// Creation of MySQL event
if (($final_tab_id != '') and ($final_consumption_id != '') and ($final_consumption_nr != '') and ($final_price != '')){
	
	// Filter the posted variables
	$sql = mysql_query("INSERT INTO __events_RAW (timestamp, type_id, tab_id, consumption_id, consumption_nr, amount) 
	VALUES('$timestamp','1','$final_tab_id','$final_consumption_id', '$consumption_nr', '$final_price')") or die (mysql_error());
	
	//to refresh tab-info
	header('Refresh: 1; ./create_consumption.php' ) ;
	} 
	
// delete consumptions
if (isset ($_POST['delete_sale_id'])){
	$delete_sale_id = $_POST['delete_sale_id'];
	// Create RAW event
	$sql = mysql_query("INSERT INTO __events_RAW (timestamp, type_id, sale_id) VALUES('$timestamp','6','$delete_sale_id')") or die (mysql_error());
	
	//to refresh tab-info
	header('Refresh: 1; ./create_consumption.php' ) ;
	
	}
	
?>
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Register Consumptions</title> 

<script type="text/javascript">
	<!-- Form Validation -->
	function validate_form ( ) { 
	valid = true; 
	if ( document.form.location.value == "0" ) { 
	alert ( "Location must not be blank." ); 
	valid = false;
	}
	if ( document.form_tab.temp_tab_id.value == "" ) { 
	alert ( "Tab Name must not be blank." ); 
	valid = false;
	}
	return valid;
	}
	<!-- Confirmation Delete -->
	function confirmAction(){
      var confirmed = confirm("Consumptie verwijderen?");
      return confirmed;
	}
</script>

<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Register Consumptions"> 
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
	
	<table width="100%"><tr>
	<td width="50%" valign="top">
		
	<!-- Body -->
	<div class="LR-box-wrapper" >	
		<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
			<div class="LR-box-container LR-clearfix">
				<div class="LR-box-inner">
				<!-- Page Title -->
				<h1 class="LR-site-title">Register Consumptions</h1>
				
				<div class="LR-sign-up"> 
					<div class="LR-sign-up-container">
						<div class="LR-sign-up-container-inner LR-clearfix">
							<div class="LR-extra-fields">
								<!-- Section for selecting location-->
								<div>
									<form action="create_consumption.php" method="post" enctype="multipart/form-data" name="form" id="form" onsubmit="return validate_form ( );">
										<select name="location" class="LR-sign-up-input select-small" onchange="this.form.submit();">			<!-- This will reload the current page -->
											<option value="0">Please select location...</option>
											<option <?php if ($location_id == '1') print 'selected '; ?> value="1">Figurantenbar</option>	
											<option <?php if ($location_id == '2') print 'selected '; ?> value="2">GoldenBrown bar</option>	
										</select>
								
								
								
								<!-- Section for selecting tab-->
								
								
										<select name="temp_tab_id" class="LR-sign-up-input select-small" onchange="this.form.submit();">			<!-- This will reload the current page -->
											<option value="">Please select tab...</option>
											<?php $sql = mysql_query("SELECT id,name FROM tabs WHERE location_id='$location_id' AND status='Active' ORDER BY name DESC");
												while($row = mysql_fetch_array($sql)){$menu_tab_id = $row["id"];$name = $row["name"];?>
												<option <?php if ($menu_tab_id==$tab_id) print 'selected '; ?> value="<?php echo $menu_tab_id; ?>"><?php echo $name; ?></option>	
											<?php } ?>
										</select>
									</form>
								
								</div>
								
								
								<!-- Section for selecting number of consumptions-->
								<div>
									<?php $x = 1; while($x < 11){ ?>
											<form action="create_consumption.php" method="post" name="form" id="form" style="display: inline;">
												<input type="hidden" name="consumption_nr" value="<?php echo $x; ?>">
												<input type="submit" name="Submit" class="LR-sign-up-submit no_of_consumptions_button <?php if($consumption_nr==$x){echo "selected"; }?>" value="<?php echo $x; ?>">
											</form>
									<?php $x=$x + 1;} ?>
								</div>
								
									
								<!-- Section for selecting consumption-->
								<div>
									
									
									<?php $sql = mysql_query("SELECT id, name, price FROM consumptions WHERE location_id='$location_id' AND status='active' ORDER BY id ASC");
									while($row = mysql_fetch_array($sql)){$consumption_id = $row["id"];$name = $row["name"];$price = $row["price"];
									?>
									<form action="./create_consumption.php" method="post" enctype="multipart/form-data" name="form" id="form" style="display: inline;">
										<input type="hidden" name="final_tab_id" value="<?php echo $tab_id; ?>">
										<input type="hidden" name="final_consumption_id" value="<?php echo $consumption_id; ?>">
										<input type="hidden" name="final_consumption_nr" value="<?php echo $consumption_nr; ?>">
										<?php $final_price=($price*$consumption_nr);?>
										<input type="hidden" name="final_price" value="<?php echo $final_price; ?>">
										<input type="submit" name="Submit" class="LR-sign-up-submit create_consumption_button" value="<?php echo $name."\n&euro;".$price; ?>">
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
	
	</td>
	<td width="50%" valign="top" class="create-consumption-tab-extract">
	
	<!-- Start of Tab extract -->
	<div class="infobox-container" style="margin-top: 75px;">
		
		<div class="infobox">
		
			<h3>Tab Details:</h3>
			<table width="100%">
				<div>
					<!-- Selector for cumulative/chronological -->
					<div>
						<form action="create_consumption.php" method="post" enctype="multipart/form-data" name="form" id="form" onsubmit="return validate_form ( );">
							<select name="tab_display_mode" class="LR-sign-up-input select-small" onchange="this.form.submit();" >			<!-- This will reload the current page -->
								<option <?php if ($tab_display_mode == 'chronological') print 'selected '; ?> value="chronological">Chronological</option>	
								<option <?php if ($tab_display_mode == 'cumulative') print 'selected '; ?> value="cumulative">Cumulative</option>	
							</select>
						</form>
					</div>
					<!-- Tab Extract Chronological-->			
					<?php $total_amount = 0; $total_amount = (float) $total_amount;
					if ($tab_display_mode == 'chronological'){
					
						$sql = mysql_query("
						SELECT _events_sales.id, _events_sales.timestamp,  consumptions.name, _events_sales.consumption_nr, _events_sales.amount
						FROM _events_sales 
						LEFT JOIN consumptions on _events_sales.consumption_id = consumptions.id 
						WHERE _events_sales.tab_id='$tab_id' AND _events_sales.status='active'
						ORDER BY _events_sales.timestamp DESC
						");     
						
					while($row = mysql_fetch_array($sql)){
					$sale_id = $row["id"];
					$timestamp = $row["timestamp"];
					$timestamp=explode(' ', $timestamp);
					$date= $timestamp[0];
					$timestamp= $timestamp[1];
					$timestamp= substr($timestamp,0,-3);
					$consumption_name = $row["name"];			
					$consumption_number = $row["consumption_nr"];	
					$amount = $row["amount"];						
					$members_nr = $row["members_nr"];				
					$amount = money_format('%.2n', $amount);
					$total_amount = $total_amount + $amount;
					
					
					$date= date('jS F Y', strtotime($date));
					if ($date!=$date_old){
						echo "	<tr>
								<td align='left' valign='middle' colspan='5'>
									<font class='tab_details date' style='font-weight: normal;'>$date</font>
								</td></tr>";
					} 
					$date_old=$date;
					
					?> 
					<tr>
						<td width="15%" align="center" valign="middle"><strong><font size="2"><?php echo $timestamp; ?></font></strong></a></td> 
						<td width="10%" align="center" valign="middle"><strong><?php echo $consumption_number; ?></strong></td>
						<td width="60%" align="left" valign="middle"><strong><?php echo $consumption_name; ?></strong></td>
						<td width="10%" align="right" valign="middle"><strong>&euro;<?php echo $amount."<br />"; ?></strong></td>
						<!-- Delete button-->
						<td width="5%" align="right" valign="middle" >
							<form action="create_consumption.php" method="post" name="form2" id="form2" onsubmit="return confirmAction();">
								<input type="hidden" name="delete_sale_id" id="delete_sale_id" value="<?php echo $sale_id; ?>">
								<input type="image" src="/pics/leave_tab.png" width="20px"> 
							</form>
						</td>
					</tr>
					<?php }} ?>
					<!-- Tab Extract Cumulative-->
					<?php 
					if ($tab_display_mode == 'cumulative'){
					
						$sql = mysql_query("
						SELECT consumptions.name, SUM(_events_sales.consumption_nr), SUM(_events_sales.amount)
						FROM _events_sales 
						LEFT JOIN consumptions on _events_sales.consumption_id = consumptions.id 
						WHERE _events_sales.tab_id='$tab_id' AND _events_sales.status='active'
						GROUP BY consumption_id
						ORDER BY _events_sales.timestamp DESC
						");     
						
					while($row = mysql_fetch_array($sql)){
					$consumption_name = $row["name"];				
					$consumption_number = $row["SUM(_events_sales.consumption_nr)"];
					$amount = $row["SUM(_events_sales.amount)"];						
					$amount = money_format('%.2n', $amount);
					$total_amount = $total_amount + $amount;
					?> 
					<tr>
						<td width="10%" align="center" valign="middle"><strong><?php echo $consumption_number; ?></strong></td>
						<td width="70%" align="left" valign="middle"><strong><?php echo $consumption_name; ?></strong></td>
						<td width="20%" align="right" valign="middle"><strong>&euro;<?php echo $amount."<br />"; ?></strong></td>
						
					</tr>
					<?php }} ?>
					<!-- Total amount-->
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
			</table>

		</div>
	</div>
		
	</td>
	</tr>
	</table>
</div><!-- #content -->	
</div> 