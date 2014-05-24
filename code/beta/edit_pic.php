<?php
session_start(); // Must start session first thing
date_default_timezone_set('Europe/Amsterdam');

if (isset($_SESSION['id'])) {
	// Put stored session variables into local php variable
    $userid = $_SESSION['id'];
	$email = $_SESSION['email'];
} else {
	header("location: ./index.php"); 	
}

include('./SimpleImage.php');
$image = new SimpleImage();   

// Process the form if it is submitted
if ($_FILES['uploadedfile']['tmp_name'] != "") {
    // Run error handling on the file
    // Set Max file size limit to somewhere around 120kb
    $maxfilesize = 5000000;
    // Check file size, if too large exit and tell them why
    if($_FILES['uploadedfile']['size'] > $maxfilesize ) { 
        $message_inque=True;
		$header_target='./edit_pic.php';
		$text="<a href='$header_target'><img src=\"./pics/failed.png\" alt=\"Failed\" width=\"300\"></a>
		<b><br />Your image was too large. Must be 5Mb or less.<br /><br />
		You will be redirected momentarily....</b>";
        unlink($_FILES['uploadedfile']['tmp_name']); 
        
    // Check file extension to see if it is .jpg or .gif, if not exit and tell them why
    } else if (!preg_match("/\.(gif|jpg)$/i", $_FILES['uploadedfile']['name'] ) ) {
        $message_inque=True;
		$header_target='./edit_pic.php';
		$text="<a href='$header_target'><img src=\"./pics/failed.png\" class=\"img-confirmation\"></a>
		<b><br />Your image was not .gif or .jpg and it must be one of those two formats.<br /><br />
		You will be redirected momentarily....</b>";
		unlink($_FILES['uploadedfile']['tmp_name']);

        // If no errors on the file process it and upload to server 
    } else { 
        // Rename the pic
        $newname = "pic1.jpg";
		//resizing
		$image->load($_FILES['uploadedfile']['tmp_name']);
		$image->resizeToWidth(250);
				
        // Set the direntory for where to upload it, use the member id to hit their folder
        // Upload the file
        $image->save("memberFiles/$userid/".$newname);
		$message_inque=True;
		$header_target='./profile.php';
		$text="<a href='$header_target'><img src=\"./pics/ok.png\" class=\"img-confirmation\"></a>
		<br /><b>Success, the image has been uploaded and will displayed to visitors!<br /><br />
		You will be redirected momentarily....</b>";
		} 
} 
 
?>


<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Yoombler | Edit Profile Pic</title> 
 
<!-- Google -->
<meta name="title" content="Yoombler | Edit Profile Pic"> 
<meta name="description" content="Your Digital Hospitality Experience."> 
<meta name="keywords" content="hospitality, horeca, rekening delen, split the bill, paying, afrekenen, betalen, app, consumenten, consumers, social"> 
<!-- Facebook -->
<meta property="og:type" content="website"> 
<meta property="og:site_name" content="Yoombler"> 
<meta property="og:title" content="Yoombler | Edit Profile Pic"> 
<meta property="og:description" content="Your Digital Hospitality Experience.">
<meta property="og:image" content="./pics/logo_new_noBG.jpg">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./pics/icon.ico"> 

 


<link rel="stylesheet" type="text/css" href="./css/classic.css"><link rel="stylesheet" type="text/css" href="./css/classic-mobile.css" media="only screen and (min-device-width : 320px) and (max-device-width : 480px)"><link rel="stylesheet" type="text/css" href="./css/css"><link rel="shortcut icon" href="./css/icon.ico">

<script type="text/javascript">
<!-- Form Validation -->
function validate_form ( ) { 
valid = true; 
if ( document.form.uploadedfile.value == "" ) { 
alert ( "Please browse for a file on your PC and place it" ); 
valid = false;
}
return valid;
}
<!-- Form Validation -->
</script>
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
			<?php if ($message_inque==True){echo $text; header("Refresh: 5; $header_target"); exit();}?> 			
			<a href="./home.php">Logged in as: "<?php echo $email; ?>"</a>
			</h4>
		</div>
		
		<!-- Body -->
		<div class="LR-box-wrapper" align="center">	
			<div class="LR-box" style="background-color: rgba(0, 0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
				<div class="LR-box-container LR-clearfix">
					<div class="LR-box-inner">
					<h1 class="LR-site-title" style="">Edit your Profile Image</h1>
					<div class="LR-sign-up"> 
							
							<div class="LR-sign-up-container">
							<div class="LR-sign-up-container-inner LR-clearfix">
								<div class="LR-extra-fields">
									<img src="./memberFiles/<?php echo "$userid"; ?>/pic1.jpg" alt="Ad" width="200" />

									<form action="edit_pic.php" method="post" enctype="multipart/form-data" name="form" id="form" onsubmit="return validate_form ( );">
									 
									<!-- A trick is applied here. First button is hidden (see CSS) and second (formatable) button controls the first -->
									
									
									
									<input name="uploadedfile" id="uploadedfile" type="file" style="display: none;"/>
									<button type="button" class="LR-sign-up-submit" onclick="document.getElementById('uploadedfile').click();">Choose image</button>
									<input name="Submit" type="submit" class="LR-sign-up-submit" value="Upload Image" />
									
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


