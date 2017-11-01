<?php

//$encoded = substr($encoded, 0, strlen($encoded)-1);

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'http://news.thaipbs.or.th/other/farmland');
curl_setopt($ch, CURLOPT_URL, 'http://news.thaipbs.or.th/other/farmland');

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
//
$wrap_content = $html->find('div[id=dropArea]', 0);
$item = $wrap_content->find('div[class=row news-wrap]');
//echo "$wrap_content";

//foreach item
foreach($item as $key => $val){
	//title&link tag div class entry unvoted
	$entry = $val->find('div[class=item-content]', 0);///

	if (!empty($entry->find('div[class=item-meta ]'))) {  ///value มั่ว
		//title&link tag a class title may-blank array
		$title = $entry->find('h2', 0)->innertext;
	} else {
		//title&link tag a class title may-blank array
		$title = $entry->find('h2', 0)->innertext;
	}

	//$time =  $entry->find('time',0)->innertext;
	$desc =  $entry->find('p[class=summary]', 0)->innertext;
	$img = '';
	if (!empty ($val->find('div[class=media-wrapper]'))) {
		//tag a tag img
		if (!empty ($val->find('img', 0))) {
				$img = $val->find('img', 0)->src;
		}
	}

		//$link =  $entry->find('[class=td-post-author-name]',0)->innertext;
	  $link = '';
		if (!empty ($val->find('div[class=clearfix]'))) {
		//tag a tag  link
		if (!empty ($val->find('a', 0))) {
				$link = $val->find('a', 0)->href;
		}
}
$time = '';
if (!empty ($val->find('div[class=clearfix]'))) {
//tag a tag  link
if (!empty ($val->find('a', 0))) {
		$link = $val->find('a', 0)->href;
}
}
	//$time =  $entry->find('time',0)->innertext;
$output[]= array(
				'title' => $title,
				'link' => $link,
				'img' => $img,
				'time' => $time,//$time,
				'desc' => $desc
		);

}
header("Content-type: application/json", true);
echo json_encode($output, JSON_PRETTY_PRINT);
// print'<pre>';
// print_r($output);
// print'</pre>';

?>
