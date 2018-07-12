# Note
despite being a paying customer on onlinetvrecorder for ~7 years, a case of videos not being sent to me was handled with such unprofessional impudence and in such an unfriendly manner that meanwhile I canceled my "membership" there.

# OnlineTVRecorder-VODPortal
Solution to receive files from onlinetvrecorder.com, process them, display them in a php portal and stream them to e. g. iphone.

![Preview 1](http://imgur.com/Kow4BCY.png)
![Preview 2](http://imgur.com/2Sv7s5J.png)

Note. The whole thing is badly hacked. And it works, at least for the upload/display/stream parts. Search, login and other features provided by the "My Play" W3 portal are not functional, since I do not need them.


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


#### nginx configuration (configure) with nginx-vod-module et. al.:
```
#!/bin/bash
# https://github.com/kaltura/nginx-vod-module
# without rtmp module: --add-module=/home/jan/nginx-rtmp-module/nginx-rtmp-module-master \
./configure \
--prefix=/usr/local/share/nginx-vod \
--sbin-path=/usr/local/nginx-vod/nginx-vod \
--conf-path=/usr/local/nginx-vod/nginx-vod.conf \
--pid-path=/usr/local/nginx-vod/nginx-vod.pid \
--http-log-path=/var/log/nginx-vod/access.log \
--error-log-path=/var/log/nginx-vod/error.log \
--lock-path=/var/lock/nginx-vod.lock \
--http-client-body-temp-path=/var/lib/nginx-vod/body \
--http-fastcgi-temp-path=/var/lib/nginx-vod/fastcgi \
--http-proxy-temp-path=/var/lib/nginx-vod/proxy \
--user=www-data \
--group=www-data \
--http-scgi-temp-path=/var/lib/nginx-vod/scgi \
--http-uwsgi-temp-path=/var/lib/nginx-vod/uwsgi \
--with-pcre=../pcre-8.39 \
--with-pcre-jit \
--with-zlib=../zlib-1.2.8 \
--with-http_ssl_module \
--with-http_stub_status_module \
--with-http_realip_module \
--with-http_auth_request_module \
--with-http_addition_module \
--with-http_dav_module \
--with-http_flv_module \
--with-http_gzip_static_module \
--with-http_mp4_module \
--with-http_perl_module \
--with-http_random_index_module \
--with-http_secure_link_module \
--with-http_sub_module \
--with-mail_ssl_module \
--with-stream \
--with-mail \
--with-http_geoip_module \
--with-http_image_filter_module \
--with-http_xslt_module \
--with-file-aio \
--with-threads \
--with-cc-opt="-O3" \
--with-debug \
--add-module=../nginx-vod-module-master \
--add-module=../ngx-more-set-headers
```

