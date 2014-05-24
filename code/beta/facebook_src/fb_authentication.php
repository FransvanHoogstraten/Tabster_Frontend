<?php
$app_id     = "470536136358946";
$app_secret = "ab46c527fbdc2169db2866a7d2d23d25";
$site_url   = "http://www.yoombler.com/fb.php"; 
$facebook_authenticated = False;

include_once "./facebook.php";

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
		echo 'logged in';
    }catch(FacebookApiException $e){
     	$user=NULL;	
		$facebook_authenticated = False;

    }
}else{
// if not authenticated, this file creates the variables $loginUrl
    $facebook_authenticated = False;
	$loginUrl = $facebook->getLoginUrl(array(
        'scope'         => 'email',
        'redirect_uri'  => $site_url,
        ));

// THIS IS THE LINK WE NEED TO USE IN THE FACEBOOK BUTTON
		echo "<a href='$loginUrl'>Login</a>";
}

?>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <body>
	<pre>
	<?php print htmlspecialchars(print_r($user_profile, true)) ?>
	</pre>
  </body>
</html>
