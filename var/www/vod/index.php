<?PHP

include 'functions.inc.php';

// TODO
// - code cleanup
// # thumbnails 10min in Sendung (5? 6?)
// - Abstand Uhr/Videosymbol
// TODO: sidenav: kategorien-dropdown füllt sich nur, wenn seite nicht mit ?k=... aufgerufen wird,
//       weil sich in k keine Unterordner befinden
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


$dirlist = getFileList("content/", true, $voddata);

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Jans Videoportal</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
// KATEGORIEN
include 'kategorien.inc.php';
?>


<?php
// NEUESTE VIDEOS
include 'neuestevideos.inc.php';
?>


	</div>
</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->		

</body>
</html>
