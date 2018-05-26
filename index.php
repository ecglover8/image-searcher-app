<!DOCTYPE html>
<html lang='en-US'>
	<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
		<style>
			* {
				box-sizing: border-box;
				margin: 0px;
				padding: 0px;
			}
			a {
				border-bottom: 1px dashed #D9D9D9;
				color: #D9D9D9;
				text-decoration: none;
				transition: all 0.2s;
			}
			a:hover {
				border-bottom: 1px solid #FFFFFF;
				color: #FFFFFF;
			}
			body {
				background-color: #004D9A;
				color: #FCFCFC;
				font-family: courier, arial;
				font-size: 24px;
				margin: auto;
				width: 94%;
			}
			button {
				font-size: 1.6em;
				font-weight: 600;
				padding: 5px 8px;
			}
			footer {
				background-color: #F7F8FA;
				background-color: rgba(247, 248, 250, 0.8);
				color: #004D9A;
				display: grid;
				font-weight: 600;
				grid-template-columns: 225px 125px auto;
				grid-gap: 8px;
			}
			footer div {
				padding: 4px;
			}
			form, p {
				margin: 15px;
				padding: 12px;
			}
			h1 {
				font-size: 2.8em;
				text-align: center;
			}
			h2 {
				font-size: 2.5em;
				margin: 8px;
			}
			input[type=text], select {
				display: inline-block;
				font-size: 2em;
				margin: 3px;
				padding: 3px 12px;
			}
			table {
				border: 1px solid #000000;
				border-collapse: collapse;
				color: #000000;
				margin: 30px auto;
			}
			td, th {
				border: 1px solid #FFFFFF;
				padding: 15px;
			}
			th {
				font-size: 1.1em;
			}
			tr:nth-of-type(even) {
				background-color: #DFE3EB;
			}
			tr:nth-of-type(odd) {
				background-color: #D3D8E3;
			}
			.footlink {
				border-bottom: 1px dashed #294D71;
				color: #294D71;
				text-decoration: none;
				transition: all 0.2s;
			}
			.footlink:hover {
				border-bottom: 1px solid #004D9A;
				color: #004D9A;
			}
			.nohide {
				font-size: 0.8em;
			}
			.warning {
				background-color: #F7F8FA;
				border-left: 5px solid #E4E5E6;
				color: #FF1A1A;
				font-size: 20px;
				font-weight: 700;
			}
			@media only screen and (max-width: 768px) {
				body { font-size:16px;width:100% }
				footer { display:block }
				form, p { margin:8px;padding:6px }
				.hide { display:none }
				.nohide { display:block;font-size:1em;padding:5px }
			}
		</style>
    <title>FCC Getty Image Search Microservice</title>
  </head>
	<body>
<?php
include 'includes/conn.php'
?>
		<h1>FCC Getty Image Search Microservice</h1>
		<p>Type a search term into the box below and get a JSON list of results from the <a href="http://developers.gettyimages.com/en/">Getty Images API</a></p>
		<form action="results.php" method="get" name="mainform" onsubmit="return val()">
			<input name="q" type="text" size="20" />
			<select name="lim" size="1">
				<option value="10" selected>10</option>
				<option value="20">20</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<button type="submit">Search</button>
		</form>
		<p>You can also use the microservice directly by typing into your browser<br /> <em>https://image-searcher-app.herokuapp.com/results.php?q={your_search_term}&limit={number_results_to_return}</em><br />Where <em>your_search_term</em> is a string and <em>number_results_to_return</em> is an integer.</p>
		<h2>Last ten search results:</h2>
<?php
$stmt = $establish->prepare('SELECT Term, Time, First, Thumb, URL FROM tblQuery ORDER BY Time DESC LIMIT 10');
$stmt->execute();
// print out results of query to page
if ($stmt->rowCount() === 0) {
	echo '<p>We are not able to print out previous search results at this time.</p>';
} else {
	echo '<table>';
	echo '<tr>';
	echo '<th>SEARCH</th><th>DATE / TIME</th><th>TOP RESULT</th>';
	echo '</tr>';
	while ($row = $stmt->fetch()) {
		echo '<tr>';
		echo '<td>' . $row["Term"] . '</td><td>' . $row["Time"] . '</td><td><a href="' . $row["URL"] . '"><img src="' . $row["Thumb"] . '" title="' . $row["First"] . '" /></a></td>';
		echo '</tr>';
	}
	echo '</table>';
}
$stmt = null;
?>
		<footer>
			<div class="hide">
				Lovingly designed and painstakingly maintained by
			</div>
			<div class="hide">
				<a href="https://ecglover8.github.io"><img alt="Gigi Web and Graphic Design" height="125" src="includes/logo.png" title="Expectorating the Unexpected" width="125" /></a>
			</div>
			<div class="nohide">
				Thank you for using this microservice. This microservice is written using HTML, Javascript, and PHP with a mySQL database to store search queries. Please check out the <a class="footlink" href="https://github.com/ecglover8/image-searcher-app" target="_blank">Github repository</a> to review and/or contribute to this code.
			</div>
		</footer>
		<script type="text/javascript">
			function val() {
				var q = document.forms["mainform"]["q"].value;
				if (q == "") {
					alert("Please enter a search term before pressing the submit button.");
					return false;
				}
			}
		</script>
	</body>
</html>