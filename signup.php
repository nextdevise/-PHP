</<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/signup.css" type="text/css" rel="stylesheet">
    <link href="css/UIHeader.css" type="text/css" rel="stylesheet">
    <title>用户注册-模板盒子会议室管理系统 V1.0</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        .a1 {
            color: white;
        }

        a:hover {
            color: red;
        }

    </style>
</head>
<body>
<div id="container">
    <header>
        <div id="top">
            <div class="logo">
                <img src="img/logo.png" id="logo">
                <h1 class="title">会议室管理系统</h1>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="login.php" class="a1">登录</a></li>
                    <li><a href="signup.php" class="a1">注册</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div id="content">
        <div class="menuUi">
            <p class="toInfo">有账号?去<a style="color:red" href="login.php">登陆</a></p>
            <h1 id="signUpTitle"><span>SIGN UP</span></h1>
                <table id="signInfoInput">
                    <tr>
                        <td class="td1">*用户名</td>
                        <td><label><input class="input" placeholder="   用户名" id="userName"></label></td>
                        <td><span class="userNameInfo" style="width:100px;height:100%;display:block;"></span></td>
                    </tr>
                    <tr>
                        <td class="td1">*密码</td>
                        <td><label><input class="input" placeholder="   密码" type="password" id="password"></label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="td1">*确认密码</td>
                        <td><label><input class="input" placeholder="   确认密码" type="password" id="surePassword"></label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="td1">*真实姓名</td>
                        <td><label><input class="input" placeholder="   真实姓名" id="staffName"></label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="td1">*邮箱</td>
                        <td><label><input class="input" placeholder="   邮箱" id="email"></label></td>
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td class="td1">*个人电话</td>
                        <td><label><input class="input" placeholder="   个人电话" id="phoneNumber"></label></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="td1">*所属部门</td>
                        <td>
                            <label>
                                <select id="department"></select>
                            </label>
                        </td>
                        <td></td>
                    </tr>
                   
                    <tr>
                        <td class="td1">*验证码</td>
                        <td>
                         <div id="codeInput">
                             <label><input placeholder="   验证码" id="code"></label>
                             <img src="code.php" id="codeImg" onclick="this.src='code.php? ' + new Date().getTime()" width="100px" height="30px">
                         </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <label>
                                <input type="submit" value="注册" id="submit" onclick="toSignup()">
                            </label>
                        </td>
                    </tr>
                </table>
        </div>
    </div>
</div>
</body>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/ajaxfileupload.js"></script>
<script>
    // 获取所有部门
    $.ajax({
        url:"getDepartment.php",
        type:"POST",
        dataType:"JSON",
        success:function (data) {
            console.log(data);
           var str = '';
           for(var i=0;i<data.department.length;i++){
               str +='<option value='+data.department[i].dId+'>'+ data.department[i].dName +'</option>'
           }
           $("#department").append(str);
        },
        error:function (_,error) {
            alert(error)
        }
    });

    //设置全局标志变量
    var g_isUse = 1;
    //异步验证用户名是否已被占用
    $("#userName").blur(function () {
        if(!$("#userName").val().trim()){
            $(".userNameInfo").html("<span></span>");
            return false;
        }
        $.ajax({
            data:{
                userName:$("#userName").val().trim()
            },
            url:"checkUserName.php",
            type:"POST",
            dataType:"JSON",
            success:function (data) {
                if(data.error){
                    $(".userNameInfo").html("<span style='color:red'>不能使用</span>");
                    g_isUse = 0;
                }
                else{
                    $(".userNameInfo").html("<span style='color:green'>可以使用</span>");
                    g_isUse = 1;
                }
            },
            error:function (_,error) {
                alert(error);
            }
        });
    });

    //异步上传文件，并使用异步方式完成注册
    var isUploaded = 0,fileName = '';
        $("#upload").click(function () {
            ajaxFileUpload();
        });

    function ajaxFileUpload(){
        $.ajaxFileUpload
        (
            {
                url: 'upload.php', //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'pic', //文件上传域的ID
                type: "post",
                dataType: 'content', //返回值类型 一般设置为json
                success: function (data)  //服务器成功响应处理函数
                {
                    var d = eval('(' + data + ')');
                    if (d.error == 0) {
                        $(".uploadInfo").text("上传成功");
                        isUploaded = 1;
                        fileName = d.path;
                    } else {
                        $(".uploadInfo").text("上传失败");
                    }
                },
                error: function (_,error)//服务器响应失败处理函数
                {
                    alert(error);
                }
            }
        );
    }

    //完成员工的注册及验证

    //验证数据正确性

    //获取数据写入数据库
    function toSignup(){
        var userName = $("#userName").val().trim(),password = $("#password").val().trim(),
            surePassword = $("#surePassword").val().trim(),staffName = $("#staffName").val().trim(),email = $("#email").val().trim(),
            phoneNumber = $("#phoneNumber").val().trim(),
            department = $("#department").val().trim(),code = $("#code").val().trim();
        var errorLog = 0,errorMsg = '';
        if (userName == "") {
            errorLog = 1;
            errorMsg += "请输入你的用户名！\n";
        }
        if (password == "") {
            errorLog = 1;
            errorMsg += "请输入你的密码！\n";
        }
        else if (surePassword == "") {
            errorLog = 1;
            errorMsg += "请再次输入你的密码！\n";
        }
        else if(password!=surePassword){
            errorLog = 1;
            errorMsg += "请保持再次输入密码时的一致！\n";
        }
        if (staffName == "") {
            errorLog = 1;
            errorMsg += "请输入你的真实姓名！\n";
        }
        if (email == "") {
            errorLog = 1;
            errorMsg += "请输入你的邮箱！\n";
        }
        if (phoneNumber == "") {
            errorLog = 1;
            errorMsg += "请输入你的个人电话！\n";
        }
        if (department == "") {
            errorLog = 1;
            errorMsg += "请选择你的部门！\n";
        }
        if (code == "") {
            errorLog = 1;
            errorMsg += "请输入验证码！\n";
        }
        if(errorLog){
            alert(errorMsg);
            return false;
        }
        if(!g_isUse){
            alert("用户名已被占用，请重新输入!");
            return false;
        }
        //对于用户头像是可选择上传的项目
        $.ajax({
            data:{
                userName:userName,
                password:password,
                staffName:staffName,
                email:email,
                code:code,
                department:department,
                isUploaded:isUploaded,
                fileName:fileName,
                phoneNumber:phoneNumber
            },
            url:"signupProving.php",
            type:"POST",
            dataType:"JSON",
            success:function (data) {
                if(data.error){
                    alert(data.errorMsg);
                }
                else{
                    alert("注册申请成功，请等待管理员审核!");
                    location.reload();
                }
            },
            error:function (_,error) {
                alert(error);
            }
        })

    }
</script>

</html>