<?php
session_start();
//获取前段同步方式提交的数据
$userName = $_POST['userName'];
$password = $_POST['password'];
include_once "conn.php";
$sql = "select * from staff where userName = '$userName' and password = '". md5($password) ."' and isAdopt = 1";
$result = mysqli_query($root,$sql);
$num = mysqli_num_rows($result);
if(!$num){
    echo "<script>alert('登录失败!该用户不存在或账号密码错误');history.back();</script>";
    exit;
}
else{
    $info = mysqli_fetch_array($result,1);
    $_SESSION['currentUser'] = $info;
    $_SESSION['isAdmin'] = $info['admin'];
    if($info['admin'] === '1'){
        echo "<script>alert('登录成功');location.href='index.php?currentPage=staffAdopt'</script>";
    }
    else{
        echo "<script>alert('登录成功');location.href='index.php?currentPage=staffInfoChange'</script>";
    }
    exit;
}