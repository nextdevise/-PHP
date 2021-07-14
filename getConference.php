<?php
//该文件是用来获取所有的部门并返回去以提供给员工选择
include_once "conn.php";
$sql = "select * from conferenceroom";
$result = mysqli_query($root,$sql);
$b = [];
$a['conferenceroom'] = [];
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    while($info = mysqli_fetch_array($result,1)){
        $b['cId'] = $info['cId'];
        $b['cName'] = $info['cName'];
        array_push($a['conferenceroom'],$b);
    }
}
echo json_encode($a);
exit;