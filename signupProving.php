<?php
session_start();
$userName = $_POST['userName'];
$password = $_POST['password'];
$staffName = $_POST['staffName'];
$staffAge = $_POST['staffAge'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$code = $_POST['code'];
$department = $_POST['department'];
$isUploaded = $_POST['isUploaded'];
$fileName = $_POST['fileName'];

//首先判断验证码是否正确
if(strtolower($code) <> strtolower($_SESSION["captcha"]))
{
    $a['error']=1;
    $a['errorMsg'] = "验证码错误!";
    echo json_encode($a);
    exit;
}

//写入数据库
include_once "conn.php";
$sql = "INSERT INTO staff (
	userName,
	password,
	userPic,
	staffName,
	staffAge,
	email,
	phoneNumber,
	dId,
	applyTime
)
VALUE
	(
		'$userName',
		'". md5($password) ."',
		'$fileName',
		'$staffName',
		'$staffAge',
		'$email',
		'$phoneNumber',
		'$department',
		'".time()."'
	)
";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
    $a['errorMsg'] = "注册失败!";
}
else{
    $a['error'] = 0;
}
echo json_encode($a);
exit;