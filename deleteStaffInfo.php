<?php
//该页面为删除已经通过验证了的员工
$uId = $_POST['uId'];
include_once "conn.php";
$sql = "delete from staff where uId = $uId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    $sql = "select * from staff where isAdopt = 1";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 2;
    }
    else{
        $a['futureNum'] = mysqli_num_rows($result);
    }
}
echo json_encode($a);
exit;
?>