<?php
$video=$_GET['v'];
$image="images/" . basename($video) . ".jpg";
?>

<?php ?>
<html>
  <head>
    <title>Titel der Seite</title>
  </head>
  <body>
    <!--<div id="video-jwplayer_wrapper" style="position: relative; display: block; width: 1280px; height: 720px;">-->
    <div id="video-jwplayer_wrapper" >
      <object type="application/x-shockwave-flash" data="jwplayer/jwplayer.flash.swf" width="100%" height="100%" bgcolor="#000000" id="video-jwplayer" name="video-jwplayer" tabindex="0">
        <param name="allowfullscreen" value="true">
        <param name="allowscriptaccess" value="always">
        <param name="seamlesstabbing" value="true">
        <param name="wmode" value="opaque">
      </object>
      <div id="video-jwplayer_aspect" style="display: none;"></div>
      <div id="video-jwplayer_jwpsrv" style="position: absolute; top: 0px; z-index: 10;"></div>
    </div>

    <script src="jwplayer/jwplayer.js"></script>
    <script>jwplayer.key="tuPjKBSnqV9rzJqHUmCpmbr1/7oITWSz5cgdfA==";</script>
    <script type="text/javascript">
    jwplayer('video-jwplayer').setup({
    playlist: [{
        image: "<?php echo "$image"; ?>",
        sources: [{ 
            file: "<?php echo $_GET['v'] . "/index.m3u8"; ?>"
        },{
            file: "<?php echo $_GET['v']; ?>"
        }]
    }]	  

    });
    </script>
  </body>
</html>
