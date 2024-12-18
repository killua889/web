<?php
define("HOSTNAME","localhost");
define("USERNAME","root");
define("PASSWORD","");
define("DATABASE","online_market");
$connect = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);
if(!$connect){
    die("connect failed");
}
$query="SELECT * FROM products";
$result=mysqli_query($connect,$query);
$output=[];
while($row = mysqli_fetch_assoc($result)){
    $output[]=$row;
}
echo json_encode($output);
?>