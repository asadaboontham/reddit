<?php
$servername = "localhost";
$username = "root";
$password = "root";
$database = "agricultural";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset("utf8");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.dailynews.co.th/agriculture');
curl_setopt($ch, CURLOPT_URL, 'https://www.dailynews.co.th/agriculture/');

//html
$data = curl_exec($ch);
curl_close($ch);

//include html dom
require('simple_html_dom.php');

//array
$output = array();

//convert html to string
$html = str_get_html($data);


for($page = 1; $page <= 10; $page++){
	$html->load_file('https://www.dailynews.co.th/agriculture/?page='.$page);

//id=sitTable
$bahan = $html->find('section[id=top-section]', 0);
//
$kotak = $bahan->find('article[class=content]');
//echo $bahan;
//foreach item
foreach($kotak as $key => $val){
	//title&link tag div class entry unvoted
	 $entry = $val->find('div[class=media-body]', 0);///

	  if (!empty($entry->find('h[class=media-heading]'))) {  ///value มั่ว
		    //title&link tag a class title may-blank array
		      $news_head = $entry->find('h3[class=media-heading]', 0)->innertext;
		        //$link = $entry->find('a[class=media-heading]', 0)->href;
	         } else {
		           //title&link tag a class title may-blank array
		             $news_head = $entry->find('h3[class=media-heading]', 0)->innertext;
	              }

		$news_date =  $entry->find('span[class=media-date]',0)->innertext;
		$news_des =  $entry->find('p[class=media-desc]', 0)->innertext;
    $news_ref = 'ข่าวเดลินิวส์';


    $news_pics = '';
    if (!empty ($val->find('div[class=media-left]'))) {
      //tag a tag img
      if (!empty ($val->find('img[class=media-object]', 0))) {
          $news_pics = $val->find('img[class=media-object]', 0)->src;
      }
    }

    $news_link = '';
    if (!empty ($val->find('a[class=media]'))) {
      //tag a tag  link
      if (!empty ($val->find('a[class=media]', 0))) {
          $news_link = $val->find('a[class=media]', 0)->href;
      }
    }

    $output[]= array(
    				'news_head' => $news_head,
    				'news_link' => $news_link,
    				'news_pics' => 'https://www.dailynews.co.th/'. $news_pics,
    				'news_date' => $news_date,
    				'news_des' => $news_des,
            'news_ref' => $news_ref
    		);
}
}
foreach ($output as $code){
  // $query = "SELECT `news_date` FROM `agri_news` WHERE news_date=?";
  //
  // if ($stmt = $conn->prepare($query)){
  //
  //         $stmt->bind_param("s", $email);
  //
  //         if($stmt->execute()){
  //             $stmt->store_result();
  //
  //             $news_date= "";
  //             $stmt->bind_result($news_date);
  //             $stmt->fetch();
  //
  //             if ($stmt->num_rows == 1){
  //
  //             echo "That field already exists.";
  //             exit;
  //
  //             }
  //         }
  //     }

  $query = mysqli_query($conn, "SELECT * FROM agri_news WHERE news_date = '".$news_date."'");

      if (!$query)
      {
          die('Error: ' . mysqli_error($conn));
      }

  if(mysqli_num_rows($query) > 0){

      echo "already exists" ;

  }else{
      $query1 = "INSERT INTO agri_news (news_head ,news_des ,news_date , news_ref ,news_pics ,news_link )
      VALUES ('". $code['news_head']."','". $code['news_des']."','". $code['news_date']."','". $code['news_ref']."','". $code['news_pics']."','". $code['news_link']."')";

      $q = mysqli_query($conn, $query1) or die (mysqli_error($conn));
    // $query1 = "INSERT INTO agri_news (news_head ,news_des ,news_date , news_ref ,news_pics ,news_link )
    // VALUES ('". $code['title']."','". $code['descr']."','". $code['times']."','". $code['ref']."','". $code['img']."','". $code['link']."')
    // ON DUPLICATE KEY UPDATE news_date = VALUES(news_date)";
    //
    // $q = mysqli_query($conn, $query1) or die (mysqli_error($conn));
    }
}


?>
