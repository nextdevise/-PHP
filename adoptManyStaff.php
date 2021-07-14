<?php
$uIds = $_POST['staffs'];
include_once "conn.php";
$a = [];
foreach ($uIds as $uId){
    $sql = "update staff set isAdopt = 1 where uId = $uId";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 4;
        echo json_encode($a);
        exit;
    }
    else{
        $a['error'] = 13;
        $sql = "select * from staff where isAdopt = 0";
        $result = mysqli_query($root,$sql);
        if(!$result){
            $a['error'] = 15;
        }
        else{
            $a['futureNum'] = mysqli_num_rows($result);
        }
    }
}
echo json_encode($a);
exit;
?>