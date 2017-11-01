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
		      $title = $entry->find('h3[class=media-heading]', 0)->innertext;
		        //$link = $entry->find('a[class=media-heading]', 0)->href;
	         } else {
		           //title&link tag a class title may-blank array
		             $title = $entry->find('h3[class=media-heading]', 0)->innertext;
	              }

		$times =  $entry->find('span[class=media-date]',0)->innertext;
		$descr =  $entry->find('p[class=media-desc]', 0)->innertext;
    $ref = 'ข่าวเดลินิวส์';


    $img = '';
    if (!empty ($val->find('div[class=media-left]'))) {
      //tag a tag img
      if (!empty ($val->find('img[class=media-object]', 0))) {
          $img = $val->find('img[class=media-object]', 0)->src;
      }
    }

    $link = '';
    if (!empty ($val->find('a[class=media]'))) {
      //tag a tag  link
      if (!empty ($val->find('a[class=media]', 0))) {
          $link = $val->find('a[class=media]', 0)->href;
      }
    }

    $output[]= array(
    				'title' => $title,
    				'link' => $link,
    				'img' => 'https://www.dailynews.co.th/'. $img,
    				'times' => $times,
    				'descr' => $descr,
            'ref' => $ref
    		);
}
foreach ($output as $code){

    $query1 = "INSERT INTO agri_news (news_head ,news_des ,news_date , news_ref ,news_pics ,news_link )
    VALUES ('". $code['title']."','". $code['descr']."','". $code['times']."','". $code['ref']."','". $code['img']."','". $code['link']."')";

    $q = mysqli_query($conn, $query1) or die (mysqli_error($conn));

}    // header("Content-type: application/json", true);
    // echo json_encode($output, JSON_PRETTY_PRINT);

    //$sql = "INSERT INTO database_name (id, title, times, descr) VALUES";





//Insert to database
// $sql = "INSERT INTO database_name (id, title, times, descr) VALUES";
// $sql .= implode(",",$output);
// $debugquery = mysql_query($sql);
// if (!$debugquery)
// {
// 	die(mysql_error());
// }





?>
