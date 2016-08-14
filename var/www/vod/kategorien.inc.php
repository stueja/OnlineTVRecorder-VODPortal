<?php ?>

<!-- KATEGORIEN -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="main-grids">
				<div class="top-grids">
					<div class="recommended">
						<div class="recommended-grids">
							<div class="recommended-info">
								<h3>Kategorien</h3>
							</div>

<?php
	foreach($dirlist as $file) {
		if (preg_match("/^dir$/", $file['type'])) {


?>							

							<div class="col-md-3 resent-grid recommended-grid">
							<div class="resent-grid-img recommended-grid-img">
								<a href="movies.php?k=<?php echo "{$file['dirname']}"; ?>"><img src="<?php echo "{$file['catimage']}"; ?>" alt="" /></a>
								<div class="time small-time">
									<p><?php echo "{$file['filecount']}"; ?></p>
								</div>
								<div class="clck small-clck">
									<span class="glyphicon glyphicon-film" aria-hidden="true"></span>
								</div>
							</div>
							<div class="resent-grid-info recommended-grid-info video-info-grid">
								<h5><a href="movies.php?k=<?php echo "{$file['dirname']}"; ?>" class="title">
								<?php echo "{$file['titel']}"; ?>
								</a></h5>
								<ul>
									<li><p class="author author-info"><a href="movies.php?k=<?php echo "{$file['dirname']}"; ?>" class="author"><?php echo "{$file['titel']}"; ?></a></p></li>
									<li class="right-list"><p class="views views-info"><?php echo "{$file['filecount']}"; ?> Videos</p></li>
								</ul>
							</div>
						</div>

<?php
		} // if
	} // foreach
?>						
						
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
			</div>
		</div>							
<!-- // KATEGORIEN -->