<?php

require 'Curl.php';

use \Curl\Curl;

$videolist = NULL;
$loop = 0;
$uploadplaylist = NULL;
$curl = new Curl();
$curl->get('https://www.googleapis.com/youtube/v3/channels', array(
    'part' => 'contentDetails',
	'forUsername' => 'meea5',
	'key' => 'AIzaSyCBc5h3ZqU5soRSjtgOIeZIipJJTPMzUKM'
	
));

if ($curl->error) {
    echo 'Error: ' . $curl->error_code . ': ' . $curl->error_message;
}
else {
    //print '<pre>';
	//print_r($curl->response);
	$account = $curl->response;
	echo '<br/><br/>';
	
	$uploadplaylist = $account->items[0]->contentDetails->relatedPlaylists->uploads;
	//echo $uploadplaylist;
	
	$curl = new Curl();
	$curl->get('https://www.googleapis.com/youtube/v3/playlistItems', array(
    'part' => 'snippet',
	'playlistId' => $uploadplaylist,
	'key' => 'AIzaSyCBc5h3ZqU5soRSjtgOIeZIipJJTPMzUKM'
	
));

if ($curl->error) {
    echo 'Error: ' . $curl->error_code . ': ' . $curl->error_message;
}
else {
	//print_r($curl->response);
	$page = $curl->response;
	$nextPageToken = $page->nextPageToken;
	echo '<ul id="gallery">';
	foreach($page->items as $item)	
	{	
			
			$thumb = $item->snippet->thumbnails->default->url;
			$title = $item->snippet->title;
			$description = $item->snippet->description;
			$vid = $item->snippet->resourceId->videoId;
			
			echo sprintf('<li><a target="_blank" href="https://www.youtube.com/watch?v=%s"><img src="%s"/><p class="title">%s </p> </a></li>',$vid,$thumb,$title);
			
	}
	/*ok we got the first 4 so let's get about 50 more*/
	//https://www.googleapis.com/youtube/v3/playlistItems?pageToken=CBkQAA&part=snippet&playlistId=UUb5IRY45UkbX9KcC8kKgrHQ&key=AIzaSyCBc5h3ZqU5soRSjtgOIeZIipJJTPMzUKM&nextPageToken=CAUQAA
	$max = 0;
	fetchmore($nextPageToken,$uploadplaylist,$max);
}


}

function fetchmore($token,$uploadplaylist,$max) {
	$curl = new Curl();
	$curl->get('https://www.googleapis.com/youtube/v3/playlistItems', array(
    'pageToken'=>$token,
	'part' => 'snippet',
	'playlistId' => $uploadplaylist,
	'key' => 'AIzaSyCBc5h3ZqU5soRSjtgOIeZIipJJTPMzUKM'
	));
	
	if ($curl->error) {
    //echo '';
}
else {
	//print_r($curl->response);
	$page = $curl->response;
	@$token = $page->nextPageToken;
	//echo $token.'<br/>';
	foreach($page->items as $item)	
	{	
			
			$thumb = $item->snippet->thumbnails->default->url;
			$title = $item->snippet->title;
			$description = $item->snippet->description;
			$vid = $item->snippet->resourceId->videoId;
			
			echo sprintf('<li><a target="_blank" href="https://www.youtube.com/watch?v=%s"><img src="%s"/><br/><p class="title">%s </p></a></li>',$vid,$thumb,$title);
			
	}
	if(isset($token) && $max < 30)
	{
		$max++;
		fetchmore($token,$uploadplaylist,$max);
	}
	
	
	

} //end else
}
echo '</ul>';
?>


<style>

ul, p{margin:0; padding:0;}
#gallery li {float:left;list-style-type:none; 
  padding:2px; min-width:22%;max-width:100%;width:22%}
#gallery li img{display:block;width:100%;max-width:150px}
p{    word-wrap: break-word;max-width:150px;}

li img {
	
  border: 5px solid #fff; 
  -webkit-transition: box-shadow 0.5s ease;
  -moz-transition: box-shadow 0.5s ease;
  -o-transition: box-shadow 0.5s ease;
  -ms-transition: box-shadow 0.5s ease;
  transition: box-shadow 0.5s ease;
}
 
li img:hover {
  -webkit-box-shadow: 0px 0px 7px rgba(255,255,255,0.9);
  box-shadow: 0px 0px 7px rgba(255,255,255,0.9);
}
</style>