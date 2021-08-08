<!-- HTML STARTS HERE -->
<!DOCTYPE>
<html>  
	<head>
		<meta charset="utf-8">
		<title>Search Elasticsearch</title>
		<link rel="stylesheet" href="css/main.css">
	</head>
	<body>
		<form action="index.php" method="get" autocomplete="off">
			<label>
				Search for Something
				<input id="source" type="text" name="q">
			</label>
			<input type="submit" value="search">
		</form>
						
		<div class="res">
			<a href="#id">Name</a>
		</div>
		<div class="res">Attributes</div>
	</body>
</html>
<script>

document.addEventListener("DOMContentLoaded", function() {
	var element = document.getElementById('')
	element.addEventListener("keydown", function(event) {
    	var character = keysight(event).char
 	})
});



function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}
</script>