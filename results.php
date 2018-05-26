<?php
include 'includes/conn.php'
?>
<?php
// error handling of query
if (($_GET["q"] == '') || (!ctype_digit($_GET["lim"]))) {
	$errmsg = [ "message" => "Invalid search parameters." ];
	echo json_encode($errmsg);
	exit();
}
// store search term as variable
$term = $_GET["q"];
// setup the CURL API request
$apkey = 'eabvqrw6pqbecejdduwnswn7';
$ch = curl_init('https://api.gettyimages.com/v3/search/images?fields=id,referral_destinations,thumb,title&phrase=' . str_replace(' ','+',$term) . '&page_size=' . $_GET["lim"]);
// returns the API results as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Image Searcher Microservice (ecg8@yahoo.com)');
//set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Api-Key: ' . $apkey
));
// get stringified output, as specified in CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);
//convert data into standard object
$objdata = json_decode($data);
// extract images array from data object
$imagearray = $objdata->images;
// get info about the request
$info = curl_getinfo($ch);
// close CURL resource to free up system resources 
curl_close($ch)
?>
<?php
header('Content-Type: application/json');
if ($imagearray) {
	$x = 0;
	$json = new ArrayObject(array("message" => "Query was successful."));
	foreach ($imagearray as $image) {
		$thumb = $image->display_sizes[0]->uri;
		$title = $image->title;
		$uri = $image->referral_destinations[0]->uri;
		if ($x == 0) {
			$stmt = $establish->prepare('INSERT INTO tblQuery (Term, First, Thumb, URL) VALUES (?,?,?,?)');
			$stmt->execute([$term, $title, $thumb, $uri]);
		}
		$x++;
		$push = array("search term" => $term, "title" => $title, "thumbnail URL" => $thumb);
		$json->append($push);
	};
	unset($image);
	echo json_encode($json);
} else {
	// must use double quotes to create valid JSON
	$noresult = [ "message" => "No results found." ];
	echo json_encode($noresult);
}
?>