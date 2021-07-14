<?php
//该页面是为了获取已通过注册验证的员工的信息
$currentPage = $_POST['currentPage'];
$nodeNum = $_POST['nodeNum'];
$offsetNum = ($currentPage - 1) * $nodeNum;
include_once "conn.php";
//为分页做准备,获取总数
$sql = "select * from staff where isAdopt = 1";
$result = mysqli_query($root,$sql);
if(!$result){
    $a['error'] = 1;
    echo json_encode($a);
    exit;
}
else{
    $a['totalNum'] = mysqli_num_rows($result);
    date_default_timezone_set("PRC");
    $sql = "select s.*,d.dName from staff s,department d where isAdopt = 1 and d.dId = s.dId order by s.uId asc limit $offsetNum,$nodeNum";
    $result = mysqli_query($root,$sql);
    if(!$result){
        $a['error'] = 2;
    }
    else{
        $a['error'] = 0;
        $a['staffInfo'] = [];
        while($info = @mysqli_fetch_array($result,1)){
            $b = [];
            $b['uId'] = $info['uId'];
            $b['userName'] = $info['userName'];
            $b['userPic'] = $info['userPic']?$info['userPic']:"defaultPic.png";
            $b['staffName'] = $info['staffName'];
            $b['staffAge'] = $info['staffAge'];
            $b['dName'] = $info['dName'];
            $b['email'] = $info['email'];
            $b['phoneNumber'] = $info['phoneNumber'];
            $b['admin'] = $info['admin'];
            $b['adminInfo'] = $info['admin']==='1'?"是":"否";
            $b['setAdminInfo'] = $info['admin']==='1'?$info['userName'] === 'admin'?'':"取消管理员 |":"设置管理员 |";
            $b['isDelete'] = $info['userName'] === 'admin'?'':"删除";
            array_push($a['staffInfo'],$b);
        }
    }
}
echo json_encode($a);
exit;
?>