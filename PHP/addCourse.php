<?php
//not currently being used as we perform this through ngphp-post.php
header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
echo "REACHED";
$postdata = Null;
if(isset($postdata)){
    echo "yes";
    $request = json_decode($postdata);
    echo $request;
}
$postdata = file_get_contents("php://input");
if(isset($postdata)){
    echo "yes";
    $request = json_decode($postdata);
    echo $request;
}
else{
    echo "no";
}
echo "this is the post data received by php: " . "'" . $postdata . "";

?>