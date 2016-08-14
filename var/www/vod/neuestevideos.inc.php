<?php
if(isset($_GET['k']) && $_GET['k']!="") {
	$directory=$_GET['k'];
} else {
	$directory="";
}


// dirlist ohne erneuten Aufruf enthält hier lediglich die Kategorien bzw. content/
//$dirlist = getFileList("content/$directory/", true, $voddata);

 ?>
<!-- NEUESTE VIDEOS -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="main-grids">
				<div class="top-grids">
					<div class="recommended">
						<div class="recommended-grids">
							<div class="recommended-info">
								<h3>Neueste Videos: <?php echo "$directory"; ?></h3>
							</div>
<?php

// vod: für iphone/vlc
// direct: download, iphone (mit mp4-direktive in nginx vod.conf)
// player: jwplayer, für pc
// player-vod: iphone, pc
	foreach($dirlist as $item => $row) {
		$datum[$item] = $row['lastmod'];
	}
	array_multisort($datum, SORT_DESC, $dirlist);


	foreach($dirlist as $file) {
		// nur mp4-Videos
		if(!preg_match("~video/mp4~", $file['type'])) continue;

		if(!file_exists("{$file['image']}")) {
			echo "<!-- {$file['image']} -->";
			$image = '../images/missing-image.png';
			//$videofile = "/var/www/vod/{$file['name']}";
			//$tmppath = "/var/www/vod/images/tmp";
			//$tmpimages = "{$tmppath}/%1d{$file['plain']}";
			//
			//// ffmpeg: -ss ZUERST, dann sehr schnell!
			//// ffmpeg -ss 00:06:00 -i medical16.mp4 -vf select="eq(pict_type\,I)" -s "360x240" -vframes 1 -f image2 "/var/www/vod/images/medical16.mp4.jpg"
			//// ffmpeg -ss 00:05:30 -i medical16.mp4 -vf "select='eq(pict_type,PICT_TYPE_I)'" -s "180x120" -vf fps=1/30 -vframes 4 -f image2 "/var/www/vod/images/medical16.mp4.%03d.jpg"
			//// gm montage -tile 2x2 -geometry +0+0 /var/www/vod/images/medical16.mp4.*.jpg /var/www/vod/images/medical16-overview.jpg
			//$cmdff = "ffmpeg -ss 00:05:30 -i $videofile -vf \"select='eq(pict_type,PICT_TYPE_I)'\" -s '180x120' -vf fps=1/30 -vframes 4 -f image2 $tmpimages.jpg";
			//$cmdgm = "gm montage -tile 2x2 -geometry +0+0 $tmpimages* /var/www/vod/images/{$file['plain']}.jpg";
			//$cmdrm = "rm {$tmppath}/*";
			//exec($cmdff, $ffout);
			//exec($cmdgm, $gmout);
			//exec($cmdrm, $rmout);
			//echo "<!--";
			//print_r($ffout);
			//print_r($gmout);
			//print_r($rmout);
			//echo "-->";
		
		} else {
			$image = "{$file['image']}";
		}
?>

							<div class="col-md-3 resent-grid recommended-grid">
							<div class="resent-grid-img recommended-grid-img">
								<!-- <a href="<?php echo "{$file['name']}/index.m3u8"; ?>"><img src="<?php echo "$image"; ?>" alt="" /></a> -->
								<a href="javascript:void(0)" onClick="updateId('<?php echo "{$file['id']}"; ?>','<?php echo "{$file['name']}/index.m3u8"; ?>')"><img src="<?php echo "$image"; ?>" alt="" /></a>
								<div class="time small-time">
									<p><?php echo "{$file['duration']}"; ?></p>
								</div>
								<div class="clck small-clck">
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span>
								</div>
							</div>
							<div class="resent-grid-info recommended-grid-info video-info-grid">
								<h5><a href="single.php?v=<?php echo "{$file['name']}"; ?>" class="title">
								<?php echo "{$file['titel']}"; ?>
								</a></h5>
								<ul>
									<li class="details"><p class="author author-info"><a href="single.php?v=<?php echo "{$file['name']}"; ?>" class="author"><?php echo "{$file['sender']}, {$file['sendedatum']}, {$file['sendezeit']} {$file['quality']} {$file['cut']}"; ?></a></p></li>
<?php
	$fileviews=$file['views'] . "x angesehen";
	if($file['views']!=0) {
		$fileviews = "<span style='color: #ff7d31 !important; font-weight: bold;'>{$file['views']}x angesehen</span>";
	}
?>
									<li class="right-list"><p class="views views-info"><?php echo "$fileviews" ?></p></li>
								</ul>
							</div>
						</div>
<?php

  } //foreach

?>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
			</div>
		</div>
