<?php
//该文件是为了删除会议室申请日志的
$aId = $_POST['aId'];
include_once "conn.php";
$sql = "delete from applylog where aId = $aId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    $sql = "select * from applylog";
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