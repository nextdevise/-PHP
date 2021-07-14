<?php
$uId = $_POST['uId'];
include_once "conn.php";
$sql = "delete from staff where uId = $uId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 2;
}
else{
    $a['error'] = 10;
    $sql = "select * from staff where isAdopt = 0";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 3;
    }
    else{
        $a['futureNum'] = mysqli_num_rows($result);
    }
}
echo json_encode($a);
exit;
?>