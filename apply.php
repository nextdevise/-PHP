<?php
//该文件功能为实现从前端获取到申请会议室的数据，写入数据库中
$cId = $_POST['cId'];
$userId = $_POST['userId'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$useTime = $_POST['useTime'];
$cTitle = $_POST['cTitle'];
$cNames = $_POST['cNames'];
//先判断对应时间和会议室有没有和申请时间所矛盾的
include_once "conn.php";
$sql = "select * from applylog where cId = $cId";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    while($info = @mysqli_fetch_array($result,1)){
        //如果预约会议开始的时间大于了该条记录的开始时间，那么这时的开始时间一定要大于或等于该条记录的结束时间才满足条件
        //或者预约会议开始的时间小于了该条记录的开始时间，那么这时的结束时间也一定要小于或等于该条记录的开始时间才满足条件
        //取反操作
        if(!($startTime>$info['startTime'] && $startTime>=$info['endTime'] || $startTime<$info['startTime'] && $endTime<=$info['startTime'])){
            $a['error'] = 2;
            //只要一个不满足条件就直接退出
            echo json_encode($a);
            exit;
        }
    }
}
//满足条件，写入数据库中，写入数据库中的为时间戳
$sql = "insert into applylog (userId,startTime,endTime,useTime,cId,aTime,cTitle,cNames) value ('$userId','$startTime','$endTime','$useTime','$cId','".time()."','$cTitle','$cNames')";
$result1 = mysqli_query($root,$sql);
if(!$result1){
    $a['error'] = 3;
}
else{
    $a['error'] = 0;
}
echo json_encode($a);
exit;


?>