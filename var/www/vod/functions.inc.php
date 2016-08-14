<?php


// db-verbindung
function dbcon() {
	// datenbank
	$link = mysqli_connect("localhost", "databaseuser", "databasepassword", "vodportal");
	
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	
	return $link;	
}


// string in multidimensionalem array suchen
function in_array_r($needle, $haystack, $strict = true) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}

/////
// OBSOLETE; SEE /usr/bin/uploadwatch.sh and incrontab -e
/////
//function fileintodb($file) {
//	// $file = content/Tatort...
//	$path = $file;
//	$file = basename($file);
//	$link = dbcon();	
//	$cmd = "/usr/bin/mediainfo /var/www/vod/$path | grep 'Duration' | head -n 1 | cut -d':' -f 2";
//	echo "<!-- $cmd -->\n";
//	exec($cmd, $mediainfo);
//	$mediainfo = $mediainfo[0];
//
//	$sql = "INSERT INTO plainlist (videoname,duration) VALUES ('".$file."','".$mediainfo."');";
//	$result = mysqli_query($link, $sql) or die(mysqli_error($link));
//	/* close connection */
//	mysqli_close($link);	
//
//	return $mediainfo;
//}
//
//function screenshot($file) {
//	// $dirlist['plain'] sollte ausreichen
//	$path = $file;
//	$file = basename($file);
//	$tmppath="/var/www/vod/images/tmp";
//	
//	set_time_limit(120);
//	@ini_set('implicit_flush', 1);
//	@ini_set('output_buffering', 0);	
//	ini_set('implicit_flush', 'on');
//	ini_set('output_buffering', 'off');	
//
//	// auf viermal aufteilen, sonst timeout
//	// siehe auch: /etc/php/fpm/php.ini => max_execution_time
//	// siehe auch: /etc/php/fpm/pool.d/www.conf => -"-
//	// siehe auch: /usr/local/nginx-vod/sites-available/vod.conf => timeout
//	// 330 = ab 5min 30s alle 30 s
//	for($i=330; $i<=420; $i+=30) {
//		if (ob_get_level() == 0) ob_start();
//		$cmd = "/usr/local/bin/ffmpeg -ss $i -i $path -vf \"select='eq(pict_type,PICT_TYPE_I)'\" -s '180x120' -vf fps=1/30 -vframes 4 -f image2 $tmppath/$i.jpg";
//		echo "<!-- $cmd -->\n";
//		$last = system($cmd, $retval); 
//		echo "<!-- ffmpeg: $last -- $retval";
//		ob_flush();
//		flush();
//	}
//	//$cmd = "/usr/local/bin/ffmpeg -ss 00:05:30 -i $path -vf \"select='eq(pict_type,PICT_TYPE_I)'\" -s '180x120' -vf fps=1/30 -vframes 4 -f image2 $tmppath/%02d.jpg";
//	//echo "<!-- $cmd -->\n";
//	//$last = system($cmd, $retval); 
//	//echo "<!-- ffmpeg: $last -- $retval";
//
//	
//	$cmd="/usr/bin/gm montage -tile 2x2 -geometry +0+0 $tmppath/* /var/www/vod/images/$file.jpg";
//	echo "<!-- $cmd -->\n";
//	$last=system($cmd, $retval);
//	echo "<!-- gm montage: $retval -- $last -->\n";
//	
//	$cmd="rm $tmppath/*";
//	echo "<!-- $cmd -->\n";
//	$last=system($cmd, $retval);
//	echo "<!-- rm: $retval -- $last -->\n";
//	
//	ob_end_flush();
//	
//}
//
//function movetofolder($file) {
//	$fullpath = $file;
//	$dir = dirname($fullpath);
//	$file = basename($fullpath);
//	
//	echo "<!-- $fullpath, $file -->\n";
//	if(preg_match("/Autopsie/",$file)) {
//		$movetodir='Autopsie';
//	} elseif(preg_match("/Medical/",$file)) {
//		$movetodir='Medical_Detectives';
//	} elseif(preg_match("/Tatort/",$file)) {
//		$movetodir='Tatort';
//	} elseif(preg_match("/Sherlock/",$file)) {
//		$movetodir='Sherlock';
//	} else {
//		$movetodir='Andere';
//	}
//	
//	$moveto = "$dir/$movetodir/$file";
//	echo "<!-- rename $fullpath to $moveto -->\n";
//	rename($fullpath, $moveto);
//
//	
//}


// DIRECTORY LESEN
function getFileList($dir, $recurse=false, $voddata) {

	// array to hold return value
	$retval = array();
	$filecount = array();
	
	// add trailing slash if missing
	if(substr($dir, -1) != "/") $dir .= "/";
	
	// open pointer to directory and read list of files
	$d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
	while(false !== ($entry = $d->read())) {
		// skip hidden files
		if($entry[0] == ".") continue;
		// nur videos
		// funktioniert nicht (leere Seite) -- warum?
		//if(!preg_match("/(\.mp4$|\.avi$)/", $entry)) continue; 
		// wenn Directory
		if(is_dir("$dir$entry")) {
			$retval[] = array(
				"name" => "$dir$entry/",
				"plain" => "$dir$entry",
				"dirname" => "$entry",
				"catimage" => "images/categories/$entry.jpg",
				"type" => filetype("$dir$entry"),
				"size" => 0,
				"lastmod" => filemtime("$dir$entry"),
				"filecount" => count(glob("$dir$entry/*mp4")), // nur mp4s zählen
				"titel" => preg_replace("/_/", " ", $entry)
			);
			
			if($recurse && is_readable("$dir$entry/")) {
				$retval = array_merge($retval, getFileList("$dir$entry/", true, $voddata));
			}
		
		// wenn File
		} else {
			$arr = explode('_', $entry);
			$arr = array_reverse($arr);
			//Array
			//(
			//    [0] => DE.mpg.mp4
			//    [1] => TVOON
			//    [2] => 45
			//    [3] => vox
			//    [4] => 03-25
			//    [5] => 16.07.17
			//    [6] => Gerichtsmedizin
			//    [7] => der
			//    [8] => Geheimnisse
			//    [9] => Detectives
			//    [10] => Medical
			//)
			// 0: Kodierung
			// 1: Aufnahmeprogramm
			// 2: Kanal
			// 3: Sender
			// 4: Uhrzeit
			// 5: Datum
			// Rest: Titel
			$kodierung = array_shift($arr);
			$quality = "";
			$cut = "";
			if(preg_match("/HQ/",$kodierung)) $quality = "HQ";
			if(preg_match("/cut/", $kodierung)) $cut = "cut";
			$programm = array_shift($arr);
			$kanal = array_shift($arr);
			$sender = strtoupper(array_shift($arr));
			$sendezeit = preg_replace("/-/",":",array_shift($arr));
			$sendedatum = "20" . preg_replace("/\./","-",array_shift($arr));
			$titel = implode(" ", array_reverse($arr));
			$fstitel = preg_replace("/ /","_",$titel);
			
			/// SUCHFUNKTION
			// aktuelles video in array aus db finden	
			if(in_array_r($entry, $voddata)) {
				$duration = $voddata[$entry]['duration'];
				$views = $voddata[$entry]['views'];
				$id = $voddata[$entry]['id'];

			} else {
				// sollte eigentlich nicht mehr vorkommen dank incron und /usr/local/bin/uploadwatch.sh
				echo "<!-- adding $entry to database -->";
				$duration = fileintodb("$dir$entry");
				$views = 0;
				$id = 0;
			}
			
			//Array
			//(
			//    [57] => Array
			//        (
			//            [name] => content/Medical/Medical_Detectives_Geheimnisse_der_Gerichtsmedizin_16.07.08_04-00_vox_45_TVOON_DE.mpg.mp4
			//            [plain] => Medical_Detectives_Geheimnisse_der_Gerichtsmedizin_16.07.08_04-00_vox_45_TVOON_DE.mpg.mp4
			//            [image] => Gerichtsmedizin.jpg
			//            [type] => video/mp4
			//            [size] => 131.75
			//            [duration] =>  1h 0mn
			//            [lastmod] => 1467993022
			//            [sender] => VOX
			//            [sendezeit] => 04:00
			//            [sendedatum] => 2016-07-08
			//            [titel] => Medical Detectives Geheimnisse der Gerichtsmedizin
			//            [fstitel] => Medical_Detectives_Geheimnisse_der_Gerichtsmedizin
			//        )		
			$retval[] = array(
				"name" => "$dir$entry",
				"plain" => "$entry",
				"image" => "images/{$entry}.jpg",
				"type" => mime_content_type("$dir$entry"),
				"size" => round(filesize("$dir$entry")/1024/1024,2),
				"duration" => $duration,
				"lastmod" => filemtime("$dir$entry"),
				"sender" => $sender,
				"sendezeit" => $sendezeit,
				"sendedatum" => $sendedatum,
				"titel" => $titel,
				"fstitel" => $fstitel,
				"views" => $views,
				"id" => $id,
				"kodierung" => $kodierung,
				"quality" => $quality,
				"cut" => $cut
			);						
			
			
			$imagename = "images/" . basename($entry) . ".jpg";
			if(!file_exists($imagename)) {
			// sollte eigentlich nicht mehr vorkommen dank incron und /usr/local/bin/uploadwatch.sh
				//echo "<!-- screenshotting $imagename from $dir$entry -->";
				//screenshot("$dir$entry");
				//echo "<!-- moving $entry to folder -->";
				//movetofolder("$dir$entry");
			}

			//} // else
		


			//if(in_array($entry,$voddata)) {
			//	$retval[]['duration'] = $voddata['duration'][$entry];
			//	//echo "dur: {$voddata['duration'][$entry]}";
			//}
		} // elseif is_readable dir
    } // while
    $d->close();

	asort($retval);

    return $retval;
  } // function
  
  
?>
