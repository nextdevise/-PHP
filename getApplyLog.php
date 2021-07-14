<?php
//该页面为获取会议室申请日志
//Now：增加管理员和用户查看日志时，判断是否为管理员，不是的话，只能查看将来的记录
session_start();
$nowTime = time();
$currentPage = $_POST['currentPage'];
$nodeNum = $_POST['nodeNum'];
$cId = $_POST['cId'];
$isAdmin = $_SESSION['isAdmin'];
$offsetNum = ($currentPage - 1) * $nodeNum;
include_once "conn.php";
date_default_timezone_set("PRC");

//先查询到所有记录的条数，不加llimt
$sql = "select * from applylog a";
//如果是筛选的话
if($cId){
    $sql .=" where a.cId = $cId";
    if(!$isAdmin){
        $sql .=" and a.endTime > $nowTime";
    }
}
else if(!$isAdmin){
    $sql .=" where a.endTime > $nowTime";
}

$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 2;
    echo json_encode($a);
    exit;
}
else{
    $a['error'] = 0;
    $a['totalNum'] = mysqli_num_rows($result);
}
$sql = "SELECT
	a.*, s.userName,
	c.cName
FROM
	applylog a,
	staff s,
	conferenceroom c
WHERE
	s.uId = a.userId
AND c.cId = a.cId";
if($cId){
    $sql .=" and a.cId = $cId";
}
//如果不是管理员
if(!$isAdmin){
    $sql .=" and a.endTime > $nowTime";
}
$sql.=" ORDER by a.aId asc limit $offsetNum,$nodeNum";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    $a['log'] = [];
    while($info=@mysqli_fetch_array($result,1)){
        $b['aId'] = $info['aId'];
        $b['userName'] = $info['userName'];
        $b['startTime'] = date("Y-m-d H:i:s",$info['startTime']);
        $b['endTime'] = date("Y-m-d H:i:s",$info['endTime']);
        $b['useTime'] = $info['useTime'];
        $b['cName'] = $info['cName'];
        $b['cTitle'] = $info['cTitle'];
		$b['cNames'] = $info['cNames'];
        if($nowTime>=$info['startTime']&&$nowTime<$info['endTime']){
            $b['state'] = '<span style="color:rgba(255,0,0,0.9);">正在进行</span>';
        }
        else{
            if($nowTime<$info['startTime']){
                $b['state'] = '<span style="color:rgba(0,128,0,0.9);">未开始</span>';
            }
            else{
                $b['state'] = '<span style="color:gray">已结束</span>';
            }
        }
        $b['aTime'] = date("Y-m-d H:i:s",$info['aTime']);
        array_push($a['log'],$b);
    }
    $a['stateCId'] = $cId;
}
echo json_encode($a);
exit;
?>