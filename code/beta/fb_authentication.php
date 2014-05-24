<?php
$app_id     = "470536136358946";
$app_secret = "ab46c527fbdc2169db2866a7d2d23d25";
$site_url   = "http://www.yoombler.com/fb_authentication.php"; 
$facebook_authenticated = False;

include_once "./src/facebook.php";

$facebook = new Facebook(array(
    'appId'     => $app_id,
    'secret'    => $app_secret,
    ));
	
$user = $facebook->getUser();

// if authenticated, this file creates the variables $facebook_email and $facebook_authenticated=True
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
// if not authenticated, this file creates the variables $loginUrl
    $loginUrl = $facebook->getLoginUrl(array(
        'scope'         => 'email',
        'redirect_uri'  => $site_url,
        ));
	echo "<a href='$loginUrl'>Login</a>";
}

?>
