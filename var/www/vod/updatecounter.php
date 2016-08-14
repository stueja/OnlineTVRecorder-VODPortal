<?php

if(isset($_GET['id']) && !empty($_GET['id']))
{
    $id = $_GET['id'];
    include 'functions.inc.php';

    
    $xforwfor = getenv('HTTP_X_FORWARDED_FOR');
    $ip=getenv('REMOTE_ADDR');
    
    $browser=$_SERVER['HTTP_USER_AGENT'];    
    
    $update = "UPDATE plainlist SET views = views + 1 WHERE id = '".$id."'; INSERT INTO views (video_id,ip,x_forwarded_for,user_agent) VALUES (".$id.",'".$ip."','".$xforwfor."','".$browser."');";
    echo "<!-- $update -->";
    
    $link=dbcon();
    if (mysqli_multi_query($link, $update))
    {
        echo "<!-- Record updated successfully -->";
    } 
    else 
    {
        echo "<!-- Error updating record: " . mysqli_error($link) . " -->";
    }
    die;
}
?>
