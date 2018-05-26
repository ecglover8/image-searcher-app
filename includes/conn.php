<?php
// database connection parameters
$char = 'utf8';
$db = 'epiz_21927992_imagesearchdb';
$host = 'sql106.epizy.com';
$opts = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];
$pass = '5rL6q6uHOk2K';
$port = 3306;
$user = 'epiz_21927992';

try {
	$establish = new PDO("mysql:host=$host;dbname=$db;port=$port;", $user, $pass, $opts);
} catch(PDOException $e) {
		echo '<p class="warning">Error establishing connection to external database. Your search query will not be saved.</p>';
}

?>