<?php

//$encoded = substr($encoded, 0, strlen($encoded)-1);

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'https://www.kasetkaoklai.com/home/category/%E0%B8%82%E0%B9%88%E0%B8%B2%E0%B8%A7%E0%B9%80%E0%B8%81%E0%B8%A9%E0%B8%95%E0%B8%A3%E0%B9%81%E0%B8%99%E0%B8%B0%E0%B8%99%E0%B8%B3/');
curl_setopt($ch, CURLOPT_URL, 'https://www.kasetkaoklai.com/home/category/%E0%B8%82%E0%B9%88%E0%B8%B2%E0%B8%A7%E0%B9%80%E0%B8%81%E0%B8%A9%E0%B8%95%E0%B8%A3%E0%B9%81%E0%B8%99%E0%B8%B0%E0%B8%99%E0%B8%B3/');

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
$wrap_content = $html->find('div[class=td-ss-main-content]', 0);
//
$item = $wrap_content->find('div[class=td_module_10]');
//echo "$wrap_content";

//foreach item
foreach($item as $key => $val){
	//title&link tag div class entry unvoted
	$entry = $val->find('div[class=item-details]', 0);///

	if (!empty($entry->find('a'))) {  ///value มั่ว
		//title&link tag a class title may-blank array
		$title = $entry->find('a', 0)->innertext;
		//$link = $entry->find('a[class=media-heading]', 0)->href;
	} else {
		//title&link tag a class title may-blank array
		$title = $entry->find('a', 0)->innertext;
	}

	$img = '';
	if (!empty ($val->find('div[class=td-module-meta-info]'))) {
		//tag a tag img
		if (!empty ($val->find('img[class=entry-thumb]', 0))) {
				$img = $val->find('img[class=entry-thumb]', 0)->src;
		}
	}
		//$link =  $entry->find('[class=td-post-author-name]',0)->innertext;
	    $link = '';
	if (!empty ($val->find('div[class=td-module-meta-info]'))) {
		//tag a tag  link
		if (!empty ($val->find('a', 0))) {
				$link = $val->find('a', 0)->href;
		}
}
	$time =  $entry->find('time',0)->innertext;
$output[]= array(
				'title' => $title,
				'link' => $link,
				'img' => $img,
				'time' => $time,
				'desc' => ''
		);

}
header("Content-type: application/json", true);
echo json_encode($output, JSON_PRETTY_PRINT);
// print'<pre>';
// print_r($output);
// print'</pre>';

?>
