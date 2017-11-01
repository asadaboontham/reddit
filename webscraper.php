<?php

// error_reporting(E_ALL);
// $cookie_file = "cookies/cookiejar.txt";
require('simple_html_dom.php');
$html = new simple_html_dom();
//  $page = "http://publicaccess.chelmsford.gov.uk/online-applications/advancedSearchResults.do?action=firstPage";

// Form fields are the fields being posted by the search form.
// $form_fields = "searchCriteria.reference=&searchCriteria.planningPortalReference=&searchCriteria.alternativeReference=&searchCriteria.description=&searchCriteria.applicantName=&searchCriteria.caseType=&searchCriteria.ward=&searchCriteria.parish=&searchCriteria.agent=&searchCriteria.caseStatus=&searchCriteria.caseDecision=&searchCriteria.appealStatus=&searchCriteria.appealDecision=&searchCriteria.developmentType=&caseAddressType=Application&searchCriteria.address=&date%28applicationReceivedStart%29=08%2F11%2F2013&date%28applicationReceivedEnd%29=18%2F11%2F2013&date%28applicationValidatedStart%29=&date%28applicationValidatedEnd%29=&date%28applicationCommitteeStart%29=&date%28applicationCommitteeEnd%29=&date%28applicationDecisionStart%29=&date%28applicationDecisionEnd%29=&date%28appealDecisionStart%29=&date%28appealDecisionEnd%29=&date%28applicationDeterminedStart%29=&date%28applicationDeterminedEnd%29=&searchType=Application";

$ch = curl_init();
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)');
//curl_setopt($ch, CURLOPT_URL, $page);
curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $form_fields);
curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
//echo 'Curl error: ' . curl_error($ch); //no errrors
curl_setopt($ch, CURLOPT_REFERER, 'https://www.dailynews.co.th/agriculture');
curl_setopt($ch, CURLOPT_URL, 'https://www.dailynews.co.th/agriculture/');
$str = curl_exec($ch);
curl_close($ch);

//Simple HTMLDOM Here....
$html = str_get_html($str);
$bahan = $html->find('section[id=top-section]', 0);
//
$kotak = $bahan->find('article[class=content]');

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
  if ($next = $html->find('a[class=next]', 0)) {
      //We apend the next HREF to the base referer
      $base = "http://publicaccess.chelmsford.gov.uk";
      $url  = $base . $next->href;

      // Display the Url to see if we are going to the right address.
      echo $url;
// echo $title;
}


// $html = str_get_html($str);
// $ret  = $html->find('ul li.searchresult');
// foreach ($ret as $list) {
//     echo $list;
// }
// //If we find a NEXT button
// if ($next = $html->find('a[class=next]', 0)) {
//     //We apend the next HREF to the base referer
//     $base = "http://publicaccess.chelmsford.gov.uk";
//     $url  = $base . $next->href;
//
//     // Display the Url to see if we are going to the right address.
//     echo $url;
//
//     //If we find a next link, we call the get_data function to follow the link
//     //and search for the same type of data to display.
//     get_data($url);
// }
?>






?>
