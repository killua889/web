<?php
define("HOSTNAME","localhost");
define("USERNAME","root");
define("PASSWORD","");
define("DATABASE","online_market");
$connect = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
if(!$connect){
    die("connect failed");
}
?>