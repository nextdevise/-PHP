<?php
//该文件为获取所有等待审核员工信息
date_default_timezone_set("PRC");
session_start();
header("content-type:text/html;charset=utf-8");
$page = $_POST['page']-1;
$nodeNum = $_POST['nodeNum'];
$offsetNum = $nodeNum*$page;
//
include_once "conn.php";
$sql = "select * from staff where isAdopt = 0";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
}
else{
    $a['error'] = 0;
    $a['nodeTotalNum'] = mysqli_num_rows($result);
}
$sql = "select * from staff s,department d where s.isAdopt = 0 and s.dId = d.dId limit $offsetNum,$nodeNum";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 2;
}
else{
    $a['error'] = 0;
    $a['staffNode'] = mysqli_fetch_all($result,1);
    $b = [];
    foreach ($a['staffNode'] as $current){
        array_push($b,date("Y-m-d H:i:s",$current['applyTime']));
    }
    $a['applyTime'] = $b;
}
echo json_encode($a);
exit;
?>