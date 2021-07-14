<?php
$uIds = $_POST['staffs'];
include_once "conn.php";
$a = [];
foreach ($uIds as $uId){
    $sql = "delete from staff where uId = $uId";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 3;
        echo json_encode($a);
        exit;
    }
    else{
        $a['error'] = 12;
        $sql = "select * from staff where isAdopt = 0";
        $result = mysqli_query($root,$sql);
        if(!$result){
            $a['error'] = 16;
        }
        else{
            $a['futureNum'] = mysqli_num_rows($result);
        }
    }
}
echo json_encode($a);
exit;
?>