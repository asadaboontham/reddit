
<?php
session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '165391177377803',
  'app_secret' => '72f90cb583aedf7100729a45a0b4e75b',
  'default_graph_version' => 'v2.4',
  ]);
$helper = $fb->getRedirectLoginHelper();

try {
 if (isset($_SESSION['facebook_access_token'])) {
  $accessToken = $_SESSION['facebook_access_token'];
 } else {
    $accessToken = $helper->getAccessToken();
 }
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
   exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
 echo 'Facebook SDK returned an error: ' . $e->getMessage();
   exit;
 }


if (isset($accessToken)) {
if(isset($_SESSION['facebook_access_token'])) {
  $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
 } else {
    // Logged in!
    $_SESSION['facebook_access_token'] = (string) $accessToken;

    // OAuth 2.0 client handler
  $oAuth2Client = $fb->getOAuth2Client();

  // Exchanges a short-lived access token for a long-lived one
  $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

  $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
 }
 $output = array();
  $url = "https://graph.facebook.com/Kasettakonthai?fields=feed{attachments{title,description,media,url},caption,created_time}&access_token={$accessToken}";
  		$headers = array("Content-type: application/json");

  		 $ch = curl_init();
  		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  		 curl_setopt($ch, CURLOPT_URL, $url);
  	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  		 curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
  		 curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
  		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		 curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
  		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

       $st=curl_exec($ch);
       $result=json_decode($st,TRUE);

      //  //echo $result['feed']['data'][0]['attachments']['data'][0];

 // $result['feed']['data'][0]['attachments']['data'][0]['title'];die;
       foreach ($result['feed']['data'] as $data) {
         if (isset($data['caption'])) {
          //  echo $data['caption']."<br>";
          //  echo $data['created_time']."<br>";
          foreach ($data['attachments']['data'] as $data2) {

          //  echo $data2['title']."<br>";
          //  echo $data2['url']."<br>";
          //  echo $data2['description']."<br>";
        }


    $output['data'][] = array(
            'title' => $data2['title'],
            'link' => $data2['url'],
            'img' => $data2['media']['image']['src'],
            'time ' => $data['created_time'],
            'description' => $data2['description'],
            'ref' => $data['caption']
        );
}
}
    header("Content-type: application/json", true);
    echo json_encode($output, JSON_PRETTY_PRINT);


 } else {

 $permissions = ['email']; // optional
 $loginUrl = $helper->getLoginUrl('http://localhost/graph/', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
}
