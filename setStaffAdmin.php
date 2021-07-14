<?php
//该文件为设置管理功能
$action = $_POST['action'];
$uId = $_POST['uId'];
include_once "conn.php";
$sql = "update staff set admin = $action where uId = $uId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
}
echo json_encode($a);
exit;
?>