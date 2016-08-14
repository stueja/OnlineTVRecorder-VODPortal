<?php
include_once 'functions.inc.php';

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
// OBSOLETE; $dirlist will be filled by index.php
////
//if(isset($_GET['k']) && $_GET['k']!='') {
//	$directory=$_GET['k'];
//	// dir1: für die Videos dieser Kategorie
//	$dir1 = getFileList("content/$directory/", false, $voddata);
//} else {
//	$dir1 = [];
//}
//
//// dir2: für die Kategorien-Links in der Seitennavigation
//$dir2 = getFileList("content/", false, $voddata);
//$dirlist = array_merge($dir1,$dir2);




?>
<!-- SEITENNAVIGATION -->
        <div class="col-sm-3 col-md-2 sidebar">
			<div class="top-navigation">
				<div class="t-menu">MENU</div>
				<div class="t-img">
					<img src="images/lines.png" alt="" />
				</div>
				<div class="clearfix"> </div>
			</div>
				<div class="drop-navigation drop-navigation">
				  <ul class="nav nav-sidebar">
					<li class="active"><a href="index.php" class="home-icon"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
					<li><a href="shows.html" class="user-icon"><span class="glyphicon glyphicon-home glyphicon-blackboard" aria-hidden="true"></span>TV Shows</a></li>
					<li><a href="history.php" class="sub-icon"><span class="glyphicon glyphicon-home glyphicon-hourglass" aria-hidden="true"></span>History</a></li>
					<li><a href="#" class="menu1"><span class="glyphicon glyphicon-film" aria-hidden="true"></span>Kategorien<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></a></li>
						<ul class="cl-effect-2">
<?php
	// Kategorien-Menü füllen
	foreach($dirlist as $file) {
		if (preg_match("/^dir$/", $file['type'])) {

?>									
							<li><a href="movies.php?k=<?php echo "{$file['dirname']}"; ?>"><?php echo "{$file['titel']} ({$file['filecount']})"; ?></a></li>                                             
<?php
		} // if
	} // foreach
?>			 
						</ul>
						<!-- script-for-menu -->
						<script>
							$( "li a.menu1" ).click(function() {
							$( "ul.cl-effect-2" ).slideToggle( 300, function() {
							// Animation complete.
							});
							});
						</script>
					<li><a href="#" class="menu"><span class="glyphicon glyphicon-film glyphicon-king" aria-hidden="true"></span>Sports<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></a></li>
						<ul class="cl-effect-1">
							<li><a href="sports.html">Football</a></li>                                             
							<li><a href="sports.html">Cricket</a></li>
							<li><a href="sports.html">Tennis</a></li> 
							<li><a href="sports.html">Shattil</a></li>  
						</ul>
						<!-- script-for-menu -->
						<script>
							$( "li a.menu" ).click(function() {
							$( "ul.cl-effect-1" ).slideToggle( 300, function() {
							// Animation complete.
							});
							});
						</script>
					<li><a href="movies.html" class="song-icon"><span class="glyphicon glyphicon-music" aria-hidden="true"></span>Songs</a></li>
					<li><a href="news.html" class="news-icon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>News</a></li>
				  </ul>
				  <!-- script-for-menu -->
						<script>
							$( ".top-navigation" ).click(function() {
							$( ".drop-navigation" ).slideToggle( 300, function() {
							// Animation complete.
							});
							});
						</script>
					<div class="side-bottom">
						<div class="side-bottom-icons">
							<ul class="nav2">
								<li><a href="#" class="facebook"> </a></li>
								<li><a href="#" class="facebook twitter"> </a></li>
								<li><a href="#" class="facebook chrome"> </a></li>
								<li><a href="#" class="facebook dribbble"> </a></li>
							</ul>
						</div>
						<div class="copyright">
							<p>Copyright © 2015 My Play. All Rights Reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
						</div>
					</div>
				</div>
        </div>
 
<!-- // SEITENNAVIGATION -->
