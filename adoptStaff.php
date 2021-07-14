<?php
$uId = $_POST['uId'];
include_once "conn.php";
$sql = "update staff set isAdopt = 1 where uId = $uId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 11;
    $sql = "select * from staff where isAdopt = 0";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 14;
    }
    else{
        $a['futureNum'] = mysqli_num_rows($result);
    }
}
echo json_encode($a);
exit;
?>