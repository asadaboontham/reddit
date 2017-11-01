	<?php

	//$encoded = substr($encoded, 0, strlen($encoded)-1);

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
	// $html->load_file('https://www.dailynews.co.th/agriculture/');

	for($page = 1; $page <= 5; $page++){
		$html->load_file('https://www.dailynews.co.th/agriculture/?page='.$page);
		//$getHTML = file_get_html('https://www.dailynews.co.th/agriculture/?page='.$page);
	//id=sitTable
	$bahan = $html->find('section[id=top-section]', 0);
	//
	$kotak = $bahan->find('article[class=content]');
	//echo "$bahan";


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
			$time =  $entry->find('span[class=media-date]',0)->innertext;
			$author =  $entry->find('p[class=media-desc]', 0)->innertext;
			$ref = 'ข่าวเดลินิวส์';


	//make class คล้ายๆ meta-body
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


	$output['data'] [] = array(
					'title' => $title,
					'link' => $link,
					'img' => 'https://www.dailynews.co.th/'. $img,
					'time' => $time,
					'author' => $author,
					'ref' => $ref
			);
	}
}
	//
	header("Content-type: application/json", true);
	echo json_encode($output, JSON_PRETTY_PRINT);
	// print'<pre>';
	// print_r($output);
	// print'</pre>';
	?>
