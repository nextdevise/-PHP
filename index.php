<?php
//该界面为员工和管理员进行该系统功能操作的核心，所有的功能都将集中在该页面
session_start();
if (!isset($_SESSION['currentUser']) or !$_SESSION['currentUser']) {
    echo "<script>alert('只有登录之后才能访问该页面');location.href='login.php'</script>";
    exit;
}
if(!isset($_GET['currentPage'])){
    if($_SESSION['isAdmin'] === '1'){
        echo "<script>location.href='index.php?currentPage=staffAdopt'</script>";
    }
    else{
        echo "<script>location.href='index.php?currentPage=staffInfoChange'</script>";
    }
    exit;
}

$currentPage = $_GET['currentPage'];
$H1 = isset($_GET['H1'])?$_GET['H1']*1?1:0:0;
$H2 = isset($_GET['H2'])?$_GET['H2']*1?1:0:0;
//echo "<script>alert('".$currentPage."')</script>";
?>
</<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理中心-模板盒子会议室管理系统 V1.0</title>
    <?php if($currentPage === "conferenceApply"){?>
    <link href="css/chooseTime.css" type="text/css" rel="stylesheet">
    <?php }?>
    <?php if($currentPage === "staffAdopt" && $_SESSION['isAdmin'] === '1' ){?>
        <link href="css/staffAdopt.css" type="text/css" rel="stylesheet">
    <?php }?>
    <?php if($currentPage === "applyLog"){?>
        <link href="css/applyLog.css" type="text/css" rel="stylesheet">
    <?php }?>
    <?php if($currentPage === "staffInfoAdmin" && $_SESSION['isAdmin'] === '1' ){?>
        <link href="css/staffInfoAdmin.css" type="text/css" rel="stylesheet">
    <?php }?>
    <?php if($currentPage === "staffInfoChange"){?>
       <link href="css/staffInfoChange.css" type="text/css" rel="stylesheet">
    <?php }?>
    <link href="css/index.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="container">
    <header>
        <div class="logoUi">
            <img src="img/logo.png" id="logo">
            <h1 class="title">会议室管理系统</h1>
        </div>
        <div class="menuUi">
            <ul>
                <li>当前用户:</li>
                <li><p style="font-size: 25px;font-weight: 300;"><?php echo $_SESSION['currentUser']['userName'] ?></p></li>
                <?php if (isset($_SESSION['currentUser']) and $_SESSION['currentUser']) { ?>
                    <li><a href="loginOut.php" class="a1">注销</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    <div id="content">
        <div class="leftOperationUi">
            <ul class="ul1">
                <li class="li">
                    <ul class="ul2">
                        <li class="li2">
                            <div class="menu">
                                <img src="img/staff.png" class="staffIcon">
                                <span class="span">员工管理中心</span>
                                <div class="arrow">
                                    <span style="display:block;float:right;">></span>
                                </div>
                            </div>
                        </li>
                        <?php if($_SESSION['isAdmin'] === '1'){?>
                            <li class="li1 li3"><span id="staffAdopt" class="controlMenu">员工注册审核 </span></li>
                            <li class="li1 li3"><span id="staffInfoAdmin" class="controlMenu">员工信息管理</span></li>
                        <?php }else{?>
                            <li class="li1 li3"><span id="staffInfoChange" class="controlMenu">员工信息修改 </span></li>
                        <?php }?>
                    </ul>
                </li>
                <li class="li">
                    <ul class="ul2">
                        <li class="li2">
                            <div class="menu">
                                <img src="img/conference.png" class="conferenceIcon">
                                <span class="span">会议室管理</span>
                                <div class="arrow">
                                    <span style="display:block;float:right;">></span>
                                </div>
                            </div>
                        </li>
                        <li class="li1 li3"><span id="applyLog" class="controlMenu">会议室申请记录</span></li>
                        <li class="li1 li3"><span id="meetApply" class="controlMenu">会议室申请</span></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="rightMainUi">
            <div class="mainUiContent">
                <div class="mainTop">
                <?php if($currentPage === "conferenceApply"){?>
                    <div class="applyMenu">
                        <ul>
                            <li><span class="useRoom" data-room="0">会议室使用情况</span></li>
                            <li><span>|</span></li>
                            <li><span class="useRoom" data-room="1">申请使用会议室</span></li>
                        </ul>
                    </div>
                <?php }
                else if($currentPage === "staffAdopt" && $_SESSION['isAdmin'] === '1'){?>
                        <h2 class="mainTitle"><span>员工注册审核</span></h2>
                        <h1 class="mainNode">员工注册列表</h1>
                <?php }else if($currentPage === "applyLog"){?>
                    <h2 class="mainTitle"><span>会议室申请日志</span></h2>
                    <h1 class="mainNode">会议室申请列表</h1>
                <?php }else if($currentPage === "staffInfoAdmin" && $_SESSION['isAdmin'] === '1'){?>
                    <h2 class="mainTitle"><span>员工信息管理</span></h2>
                    <h1 class="mainNode">员工信息列表</h1>
                <?php }else if($currentPage === "staffInfoChange"){?>
                    <div class="menuTop">
                        <ul>
                            <li><span class="menus">个人信息修改</span></li>
                        </ul>
                    </div>
                <?php }?>
                </div>
<!--                mainContent为公共显示内容的界面，需要在每次切换的时候清空-->
                <div class='mainContent'>
                    <?php if($currentPage === "conferenceApply"){?>
                    <div id="lookUpConference" class="conferenceChooseMenu">
                        <ul>
                            <li>
                                <div class="meetRoom">
                                    <img src="img/bigRoom.jpg" class="meetRoomIcon">
                                    <div class="showRoomName">
                                        <span>大会议室1</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meetRoom">
                                    <img src="img/centerRoom.jpg" class="meetRoomIcon">
                                    <div class="showRoomName">
                                        <span>大会议室2</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meetRoom">
                                    <img src="img/littleRoom.jpg" class="meetRoomIcon">
                                    <div class="showRoomName">
                                        <span>小会议室1</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="meetRoom">
                                    <img src="img/littleRoom.jpg" class="meetRoomIcon">
                                    <div class="showRoomName">
                                        <span>小会议室2</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="conferenceChooseMenu" style="display:none;">
                        <h1 class="applyTitle"><span>会议室使用申请</span></h1>
                        <table id="applyTable">
                            <tr>
                                <td class="applyInfo">用户名</td>
                                <td><label><input class="applyInput onlyReadInput" id="userName" value="<?php echo $_SESSION['currentUser']['userName']?>" readonly data-userid="<?php echo $_SESSION['currentUser']['uId']?>"></label></td>
                                <td class="helpInfo"> *当前登录用户名 不可修改!</td>
                            </tr>
                            <tr>
                                <td class="applyInfo">真实姓名</td>
                                <td><label><input class="applyInput onlyReadInput" value="<?php echo $_SESSION['currentUser']['staffName']?>" readonly></label></td>
                                <td class="helpInfo"> *当前登录用户真实姓名 不可修改!</td>
                            </tr>
                            <tr>
                                <td class="applyInfo">联系电话</td>
                                <td> <label><input class="applyInput onlyReadInput" value="<?php echo $_SESSION['currentUser']['phoneNumber']?>" readonly></label></td>
                                <td class="helpInfo">*当前登录用户联系电话 不可修改!</td>
                            </tr>
                            <tr>
                                <td class="applyInfo">会议主题</td>
                                <td><label><input class="applyInput" id="conferenceTitle"></label></td>
                                <td></td>
                            </tr>
							<tr>
                                <td class="applyInfo">参会人员</td>
                                <td><label><input class="applyInput" id="conferenceNames"></label></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="applyInfo">会议室选择</td>
                                <td>
                                    <label>
                                        <select id="chooseConference" class="selectInfo"></select>
                                    </label>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="applyInfo">开始时间</td>
                                <td><label><input class="applyInput" id="startTime" disabled></label></td>
                                <td class="chooseTime"><button id="chooseTime">选择时间</button></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <!--                        时间选择器-->
                                       <div id="chooseTimes">
                                           <div id='container-time'>
                                               <div id='content-time'>
                                                   <div id='DateChoose'>
                                                       <h2 style="color:##ff0000;">DATE</h2>
                                                       <p class='nowTime'></p>
                                                       <div class='Choose time'>
                                                           <ul class='timeChoose'></ul>
                                                       </div>
                                                   </div>
                                                   <div id='hoursChoose'>
                                                       <p class='chooseTitle'>HOUR</p>
                                                       <div class='directionBox'>
                                                           <img src='img/directionToTop.png' class='direction directionTop' data-direction='directionTop'>
                                                       </div>
                                                       <ul class='hours time'>

                                                       </ul>
                                                       <div class='directionBox'>
                                                           <img src='img/directionToBottom.png' class='directionBottom direction' data-direction='directionBottom'>
                                                       </div>
                                                   </div>
                                                   <div id='minuteChoose'>
                                                       <p class='chooseTitle'>MINUTE</p>
                                                       <div class='directionBox'>
                                                           <img src='img/directionToTop.png' class='directionTop direction' data-direction='directionTop'>
                                                       </div>
                                                       <ul class='minutes time'>

                                                       </ul>
                                                       <div class='directionBox'>
                                                           <img src='img/directionToBottom.png' class='directionBottom direction' data-direction='directionBottom'>
                                                       </div>
                                                   </div>
                                               </div>
                                               <div id='footer'>
                                                   <div class='myChooseTime'>

                                                   </div>
                                                   <div class='myBtn'>
                                                       <button class='btn' id='submitBtn'>确认选择</button>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="applyInfo">使用时间</td>
                                <td>
                                    <label>
                                        <select id="howTime" class="selectInfo">

                                        </select>
                                        <span>分钟</span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="applyInfo">结束时间</td>
                                <td><label><input class="applyInput" id="endTime" disabled></label> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="applySubmit">
                                    <label><button id="applySubmit">提交申请</button></label>
                                </td>
                                <td></td>
                            </tr>
                            <script src="js/chooseTime.js"></script>
                        </table>
                    </div>
                    <?php }?>
                    <?php if($currentPage === "staffInfoChange"){?>
                        <h1 class="changeTitle"><span>个人信息修改</span></h1>
                        <table id="changeInfoInput">
                            <tr>
                                <td class="td1">*用户名</td>
                                <td>
                                    <label>
                                        <input value="<?php echo $_SESSION['currentUser']['userName']?>" id="nowUserName" hidden>
                                        <input class="input" placeholder="   用户名" id="userName" value="<?php echo $_SESSION['currentUser']['userName']?>">
                                        <input value="<?php echo $_SESSION['currentUser']['uId']?>" id="nowUserUId" hidden>
                                    </label>
                                </td>
                                <td><span class="userNameInfo" style="width:100px;height:100%;display:block;"></span></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td class="td1">*姓名</td>
                                <td><label><input class="input" placeholder="   真实姓名" id="staffName" value="<?php echo $_SESSION['currentUser']['staffName']?>"></label></td>
                                <td><span class="helpInfo">*不修改请保持不变</span></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td class="td1">*邮箱</td>
                                <td><label><input class="input" placeholder="   邮箱" id="email" value="<?php echo $_SESSION['currentUser']['email']?>"></label></td>
                                <td><span class="helpInfo">*不修改请保持不变</span></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td class="td1">*电话</td>
                                <td><label><input class="input" placeholder="   个人电话" id="phoneNumber" value="<?php echo $_SESSION['currentUser']['phoneNumber']?>"></label></td>
                                <td><span class="helpInfo">*不修改请保持不变</span></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td class="td1">*部门</td>
                                <td>
                                    <label>
                                        <input value="<?php echo $_SESSION['currentUser']['dId']?>" hidden id="currentDepartment">
                                        <select id="department" class="select"></select>
                                    </label>
                                </td>
                                <td><span class="helpInfo">*不修改请保持不变</span></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td class="td1">*验证码</td>
                                <td>
                                    <label><input placeholder="   验证码" id="code" class="input"></label>
                                </td>
                                <td> <img src="code.php" id="codeImg" onclick="this.src='code.php? ' + new Date().getTime()" width="100px" height="28px"></td>
                                <td class="lastTd"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3">
                                    <label>
                                        <input type="submit" value="提交修改" id="changeSubmit">
                                    </label>
                                </td>
                            </tr>
                        </table>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery-1.9.1.min.js"></script>
<script>
    var ST = [<?php echo $H1?>,<?php echo $H2?>];
    var spans = document.querySelectorAll(".span");
    var g_state = Array.prototype.slice.call(spans).map(function (p,index) {
        return {eleName:p,state:ST[index]};
    });
    var ul1 = document.querySelector(".ul1");
    //菜单栏下拉列表的实现
    ul1.addEventListener("click",function (e) {
        if(e.target.className === 'span'){
            startHeight(g_state,e);
        }
    });
    (()=>{
        g_state.forEach((h,index)=>{
            if(h.state){
                h.eleName.parentElement.children[2].children[0].style.transform = "rotate(90deg)";
                h.eleName.parentElement.parentElement.parentElement.parentElement.style.height = h.eleName.parentElement.parentElement.parentElement.offsetHeight + 'px';
            }
            else{
                h.eleName.parentElement.children[2].children[0].style.transform = "rotate(0deg)";
                h.eleName.parentElement.parentElement.parentElement.parentElement.style.height = h.eleName.parentElement.parentElement.offsetHeight + 'px';
            }
    })
    })();
    function startHeight(currentState,e) {
        currentState.some(function (currentEle) {
            if(currentEle.eleName === e.target){
                if(currentEle.state){
                    e.target.parentElement.children[2].children[0].style.transform = "rotate(0deg)";
                    e.target.parentElement.parentElement.parentElement.parentElement.style.height = e.target.parentElement.parentElement.offsetHeight + 'px';
                }
                else{
                    e.target.parentElement.children[2].children[0].style.transform = "rotate(90deg)";
                    e.target.parentElement.parentElement.parentElement.parentElement.style.height = e.target.parentElement.parentElement.parentElement.offsetHeight + 'px';
                }
                currentEle.state = currentEle.state?0:1;
                e.target.parentElement.parentElement.parentElement.parentElement.style.transition = "height 1s";
            }
        });
    }
    ////////////////////////////
    /*
    菜单切换：在父级上添加点击根据目标来判断该执行发送什么请求
     */
    ul1.addEventListener("click",function (e) {
        if(e.target.id === "meetApply"){
            location.href='index.php?currentPage=conferenceApply&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
        }
        else if(e.target.id === "staffAdopt"){
            //要做到真正的初始化，必须将当前页面初始化为1
            location.href='index.php?currentPage=staffAdopt&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
        }
        else if(e.target.id === "applyLog"){
            location.href='index.php?currentPage=applyLog&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
        }
        else if(e.target.id === "staffInfoAdmin"){
            location.href='index.php?currentPage=staffInfoAdmin&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
        }
        else if(e.target.id === "staffInfoChange"){
            location.href='index.php?currentPage=staffInfoChange&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
        }
    });

</script>
<?php if($currentPage === "staffAdopt" && $_SESSION['isAdmin'] === '1' ){?>
    <script src="js/staffAdopt.js"></script>
<?php }?>
<?php if($currentPage === "conferenceApply"){?>
    <script src="js/conferenceApply.js"></script>
<?php }?>
<?php if($currentPage === "applyLog"){?>
    <script src="js/applyLog.js"></script>
<?php }?>
<?php if($currentPage === "staffInfoAdmin" && $_SESSION['isAdmin'] === '1' ){?>
    <script src="js/staffInfoAdmin.js"></script>
<?php }?>
<?php if($currentPage === "staffInfoChange"){?>
    <script src="js/staffInfoChange.js"></script>
    <script src="js/ajaxfileupload.js"></script>
<?php }?>
</body>
</html>