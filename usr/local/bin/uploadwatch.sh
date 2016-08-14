#!/bin/bash
# for use with incron / incrontab -e

VIDEOFILE=$1
SOURCEFOLDER=/var/www/upload/otr
IMAGEFOLDER=/var/www/vod/images
TMPPATH=/tmp/$VIDEOFILE/


logger -t uploadwatch.sh started, videofile $VIDEOFILE

if [[ $VIDEOFILE != *".mp4" ]] && [[ $VIDEOFILE != *".avi" ]]
then
  logger -t uploadwatch.sh $VIDEOFILE is not an mp4 or avi file, exiting
  exit;
fi


logger -t uploadwatch.sh making temporary path $TMPPATH
mkdir $TMPPATH

logger -t uploadwatch.sh ffmpeg: creating snapshots
/usr/local/bin/ffmpeg -ss 00:05:30 -i $SOURCEFOLDER/$VIDEOFILE -vf \"select='eq(pict_type,PICT_TYPE_I)'\" -s '180x120' -vf fps=1/30 -vframes 4 -f image2 $TMPPATH/%02d.jpg

logger -t uploadwatch.sh gm montage: combining snapshots
/usr/bin/gm montage -tile 2x2 -geometry +0+0 $TMPPATH/* $IMAGEFOLDER/$VIDEOFILE.jpg

logger -t uploadwatch.sh removing $TMPPATH
rm -rf $TMPPATH

logger -t uploadwatch.sh getting mediainfo
MEDIAINFO="$(/usr/bin/mediainfo /var/www/upload/otr/$VIDEOFILE | grep 'Duration' | head -n 1 | cut -d':' -f 2)"

logger -t uploadwatch.sh writing to database
QUERY="INSERT INTO plainlist (videoname,duration) VALUES ('$VIDEOFILE','$MEDIAINFO');"
echo $QUERY | mysql -u databaseuser -p databasepassword vodportal -h localhost

# http://www.thegeekstuff.com/2010/07/bash-case-statement/
if   [[ $VIDEOFILE =~ ^Tatort.* ]] ; then TARGETFOLDER=$SOURCEFOLDER/Tatort
elif [[ $VIDEOFILE =~ ^Autopsie.* ]] ; then TARGETFOLDER=$SOURCEFOLDER/Autopsie
elif [[ $VIDEOFILE =~ ^Medical.* ]] ; then TARGETFOLDER=$SOURCEFOLDER/Medical_Detectives
elif [[ $VIDEOFILE =~ ^Sherlock.* ]] ; then TARGETFOLDER=$SOURCEFOLDER/Sherlock
else TARGETFOLDER=$SOURCEFOLDER/Andere
fi
logger -t uploadwatch.sh moving $VIDEOFILE to target folder $TARGETFOLDER
mv $SOURCEFOLDER/$VIDEOFILE $TARGETFOLDER/$VIDEOFILE
logger -t uploadwatch.sh finished

