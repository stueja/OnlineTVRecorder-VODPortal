<?php
include 'functions.inc.php';

//TODO:
// - sql begrenzen auf die Einträge, die gesucht werden (z. B. nur Autopsie%)
//	ACHTUNG: Kategorie "Andere" ist nicht in Spalte 'videoname' => Kategorie
//	in DB hinzufügen?


$link=dbcon();
$query = "SELECT id, videoname, duration, views FROM plainlist";
$voddata = array();
if ($result = mysqli_query($link, $query)) {
	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {
		// $voddata['duration']['medical.mp4']
		$voddata[$row['videoname']] = $row;
	}

    /* free result set */
    mysqli_free_result($result);
}

/* close connection */
mysqli_close($link);


////
// movies.php = newly loaded page (i. e. no included file), needs array filled
////
if(isset($_GET['k']) && $_GET['k']!='') {
	$directory=$_GET['k'];
	// dir1: für die Videos dieser Kategorie
	$dir1 = getFileList("content/$directory/", false, $voddata);
} else {
	$dir1 = "";
}

// dir2: für die Kategorien-Links in der Seitennavigation
$dir2 = getFileList("content/", false, $voddata);
$dirlist = array_merge($dir1,$dir2);


?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Jans Videoportal</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="My Play Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' media="all" />
<!-- //bootstrap -->
<link href="css/dashboard.css" rel="stylesheet">
<!-- Custom Theme files -->
<link href="css/style.css" rel='stylesheet' type='text/css' media="all" />
<script src="js/jquery-1.11.1.min.js"></script>
<!--start-smoth-scrolling-->
<!-- fonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<!-- //fonts -->
</head>
<body>

<?php
// OBERE NAVIGATION
include 'topnav.inc.php';
?>
	
<?php
// SEITENNAVIGATION
include 'sidenav.inc.php';
?> 
	 
<?php
// NEUESTE VIDEOS
include 'neuestevideos.inc.php';
?> 
			<!-- footer -->

	<!-- </div> -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script>
function updateId(id,href)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
        {
            //alert(xmlhttp.responseText);
        } else {
	   	console.log("fehler: xmlhttp.readyState: "+xmlhttp.readyState+" -- xmlhttp:status: "+xmlhttp.status);
	   }
    };
    //dritter Parameter muß auf false gesetzt werden
    xmlhttp.open("GET", "updatecounter.php?id=" +id, false);
    xmlhttp.send();
    location.href = href;
}
</script>
</body>
</html>
