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
	$first_name = $_SESSION['first_name'];
	$toplinks = '<font size="2"><a href="./member_profile.php">' . $email . '</a> &bull; 
	<a href="./logout.php">Log Out</a></font>';
} else {
	header("location: ./index.php"); 
}
 
$timestamp = date("Y-m-d H:i:s");
$errorMsg = "";

if (isset($_POST['location'])){
	
	// Filter the posted variables
	$location_id = ereg_replace("[^0-9]", "", $_POST['location']); // filter everything but numbers
	} 

if ($_POST['tab'] != ''){
	// Filter the posted variables
	$tab_id = ereg_replace("[^0-9]", "", $_POST['tab']); // filter everything but numbers
	
	//retrieve tab-name
	$sql =mysql_query("SELECT name FROM tabs WHERE id='$tab_id' LIMIT 1"); 
		while($row = mysql_fetch_array($sql)){
			$tab_name = $row["name"];
		}
	//check if user is already tab-member
	$sql =mysql_query("SELECT * FROM tabs_LT_users WHERE tab_id='$tab_id' AND user_id='$userid' AND status='active'"); 
	$check = mysql_num_rows($sql); 
	// If the user is already a member of this tab:
	if ($check > 0){
		$header_target="./tabs.php";
		$text =  "<a href=$header_target><img src=\"./pics/failed.png\"; class=\"img-confirmation\"></a>
		<b><br />$first_name, you just were already a member of Tab <strong><font color=\"#FF6600\">'$tab_name'</strong></font>. <br />
		Nothing was changed in our administration.<br /><br />
		You will be redirected momentarily...</b>";
		$message_inque = True;
		
	// If the user is NOT already a member of this tab:
	}else{
		// Add user info into the database table, claim your fields then values 
		$sql = mysql_query("
		INSERT INTO __events_RAW (timestamp, type_id, user_id, tab_id) 
		VALUES('$timestamp','4','$userid','$tab_id')") 
		or die (mysql_error());	
		$message_inque = True;

		$header_target="./tabs.php";
		$text = "<a href='$header_target'><img src=\"./pics/ok.png\"; class=\"img-confirmation\"></a>
		<b><br />$first_name, you just joined Tab <strong><font color=\"#FF6600\">'$tab_name'</strong></font>.><br /><br />
		You will be redirected momentarily...</b>";
						
	
		} 
	}  
?>

<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Join Tab</title> 
<script type="text/javascript">
<!-- Form Validation -->
function validate_form ( ) { 
valid = true; 
if ( document.form.location.value == "" ) { 
alert ( "Location must not be blank." ); 
valid = false;
}
if ( document.form2.tab.value == "" ) { 
alert ( "Tab Name must not be blank." ); 
valid = false;
}
return valid;
}
<!-- Form Validation -->
</script>
 
<!-- Google -->
<meta name="title" content="Yoombler | Join Tab"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Join Tab"> 
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
					<h1 class="LR-site-title" style="">Join Tab:</h1>
					
					<div class="LR-sign-up"> 
							
						<div class="LR-sign-up-container">
						<div class="LR-sign-up-container-inner LR-clearfix">
							<div class="LR-extra-fields">
								
								<form action="./tab_join.php" method="post" enctype="multipart/form-data" name="form" id="form" onsubmit="return validate_form ( );">
									<select name="location" class="LR-sign-up-input location" onchange="this.form.submit();">			<!-- This will reload the current page -->
										<option disabled selected value="">Please Select Location...</option>
										<option <?php if ($location_id == '1') print 'selected '; ?> value="1">Figurantenbar</option>	
										<option <?php if ($location_id == '2') print 'selected '; ?> value="2">GoldenBrown bar</option>	
									</select>
								</form>
								<form action="./tab_join.php" method="post" enctype="multipart/form-data" name="form2" id="form2" onsubmit="return validate_form ( );">
									<select name="tab" class="LR-sign-up-input location">		
										<option disabled selected value="">Please Select a Tab...</option>	
										
										<?php $sql="SELECT id,name FROM tabs WHERE location_id='$location_id' AND status='Active'"; $result =mysql_query($sql); while ($data=mysql_fetch_assoc($result)){ ?>
										<option value ="<?php echo $data['id'] ?>" ><?php echo $data['name'] ?></option>
										<?php } ?>
										
									</select>
									<input name="submit" id="submit" type="submit" value="Join" class="LR-sign-up-submit" />
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


