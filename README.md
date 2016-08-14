# OnlineTVRecorder-VODPortal
Solution to receive files from onlinetvrecorder.com, process them, display them in a php portal and stream them to e. g. iphone.

Note. The whole thing is badly hacked. And it works.


## Components needed:
 - a (paid) account on onlinetvrecorder.com (or any other platform which will send video files via FTP)
 - a Linux host (in my case: Banana Pi R1, 1GB RAM, ~1GHz CPU, 250GB SSD, Bananian Linux)
 - publicly reachable IP address with ddns hostname (for FTP access)
 - FTP server
 - incron daemon
 - ffmpeg, graphicsmagick/imagemagick, mediainfo
 - MySQL database (optional)
 - PHP
 - nginx compiled with nginx-vod-module and --with-http_mp4_module

### Process:
 - onlinetvrecorder.com sends requested video to FTP server behind ddns hostname
 - upon upload completion, incrond will run a script which will
  - determine the length (time) of the video
  - store the length in MySQL
  - create 4 screenshots at different timepoints of the video
  - combine those 4 screenshots to one preview image
 - upon calling the php portal, categories and videos will be displayed
 - upon click on a video, nginx-vod-module will update the views counter in the database and stream it to e. g. iphone

## Credits:
 - nginx-vod-module: https://github.com/kaltura/nginx-vod-module
 - the php portal used: https://w3layouts.com/my-play-a-video-content-portal-flat-bootstrap-responsive-web-template/
 - scanning a directory recursively in php: http://www.the-art-of-web.com/php/dirlist/
 - incron: http://inotify.aiken.cz/?section=incron&page=doc&lang=en
 - onlinetvrecorder.com: http//www.onlinetvrecorder.com/

