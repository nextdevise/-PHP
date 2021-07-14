<?php
//该页面为修改员工信息而存在的
session_start();
$userName = $_POST['userName'];
$staffName = $_POST['staffName'];
$staffAge = $_POST['staffAge'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$code = $_POST['code'];
$department = $_POST['department'];
$isUploaded = $_POST['isUploaded'];
$fileName = $_POST['fileName'];
$nowUserUId = $_POST['nowUserUId'];
//首先判断验证码是否正确
if(strtolower($code) <> strtolower($_SESSION["captcha"]))
{
    $a['error']=1;
    $a['errorMsg'] = "验证码错误!";
    echo json_encode($a);
    exit;
}
//更新数据库
include_once "conn.php";
$sql = "
UPDATE staff
SET userName = '$userName',
 staffName = '$staffName',
 staffAge = '$staffAge',
 email = '$email',
 phoneNumber = '$phoneNumber',
 dId = '$department'";
if($isUploaded){
    $sql .=",userPic = '$fileName'";
}
$sql .=" where uId = '$nowUserUId'";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 2;
}
else{
    $a['error'] = 0;
    $sql = "select * from staff where uId = $nowUserUId";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 3;
    }
    else{
        $info = mysqli_fetch_array($result,1);
        $_SESSION['currentUser'] = $info;
    }
}
echo json_encode($a);
exit;
?>