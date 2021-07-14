<?php
//获取用户名
$userName = $_POST['userName'];
//可以不用再进行用户名的格式判断
include_once "conn.php";
//从已经通过验证的staff表中查找用户名
$sql = "select * from staff where userName = '$userName'";
$result = mysqli_query($root,$sql);
$num = @mysqli_num_rows($result);
if($num){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
}
echo json_encode($a);
exit;