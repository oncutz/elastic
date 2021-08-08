<!-- HTML STARTS HERE -->
<!DOCTYPE>
<html>  
	<head>
		<meta charset="utf-8">
		<title>Search Elasticsearch</title>
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
		<link rel="stylesheet" href="src/css/main.css">
	</head>
	<body>
		<div class="container">
			<form action="index.php" method="get" autocomplete="off">
				<label>
					Search for Something
					<input id="source" type="text" name="q">
				</label>
			</form>
							
			
				<div id="response" class="container">
					
				</div>
		</div>
	</body>
</html>

<script>

document.addEventListener("DOMContentLoaded", function() {

	var element = document.getElementById('source');
	element.addEventListener("keyup", function(event) {
		if(element.value.length == 0) {
			var target = document.getElementById('response');
			target.innerHTML = "";
		}
    	httpGetAsync('/getData.php?q='+element.value, element.value, processResponse);
 	})
});



function httpGetAsync(theUrl, data, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
		processResponse(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true);
    xmlHttp.send(null);
}

function processResponse(responseText) {
	
	if(responseText) {
		var target = document.getElementById('response');
	target.innerHTML = "<div class='row'><div class='col-xs-2'><h3>Author</h3></div><div class='col-xs-2'><h3>Book</h3></div></div>"
		var obj = JSON.parse(responseText);

		var target = document.getElementById('response');

		obj.books.forEach((element)=>{
			target.innerHTML += "<div class='row'><div class='col-xs-2'><h4>" 
									+ obj.name + "</h4></div><div class='col-xs-4'><h4>" 
									+ element + "</h4></div></div>";
		})

	}

}
</script>