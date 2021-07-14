<?php
//该页面的作用是根据现在的时间和会议室申请记录中的记录进行判断，依次得出当前会议室是否可以使用
date_default_timezone_set("PRC");
$nowTime  = time();
//获取现在的时间戳，以服务器的时间为准
include_once "conn.php";
//三个会议室，不同的结果
//分三次进行查询
$sql = "select * from applylog where $nowTime >=startTime and $nowTime <endTime";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    $a['bigRoom'] = 0;
    $a['centerRoom'] = 0;
    $a['littleRoom'] = 0;
    $a['isUserRoom'] = [];
    while($info = @mysqli_fetch_array($result,1)){
         if($info['cId'] === '1'){
             $a['bigRoom'] = 1;
             //我也是真的醉了，命名数据库里是int的，获取到之后想要输出却得到的是字符串类型的
         }
        else if($info['cId'] === '2'){
            $a['centerRoom'] = 2;
        }
        else if($info['cId'] === '3'){
            $a['littleRoom'] = 3;
        }
        else if($info['cId'] === '4'){
            $a['littleRoom'] = 4;
        }
    }
    array_push($a['isUserRoom'],$a['bigRoom']);
    array_push($a['isUserRoom'],$a['centerRoom']);
    array_push($a['isUserRoom'],$a['littleRoom']);
    array_push($a['isUserRoom'],$a['littleRoom']);
    echo json_encode($a);
    exit;
}
?>