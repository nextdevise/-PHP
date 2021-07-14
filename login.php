</<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/login.css" type="text/css" rel="stylesheet" >
    <link href="css/UIHeader.css" type="text/css" rel="stylesheet" >
    <title>用户登陆-模板盒子会议室管理系统 V1.0 </title>
    <style>
        *{
            padding:0;
            margin:0;
        }
        a{
            text-decoration:none;
        }
        .a1{
            color:white;
        }
        .a2{
            color:black;
        }
        a:hover{
           color:red;
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
                <div class="leftUi">
                    <ul>
                        <li class="li1">
                            <h1 class="hInfo">欢迎来到</h1>
                            <img src="img/direction.png" class="directionIcon">
                        </li class="li1">
                        <li class="li1">
                            <h1 class="hInfo">模板盒子会议室管理系统</h1>
                        </li>
                    </ul>
                </div>
                <div class="rightUi">
                    <form method="post" action="loginProving.php">
                        <h1 class="hInfo2">LOGIN</h1>
                        <table>
                            <tr>
                                <td class="row">
                                    <img src="img/user.png" class="icon">
                                </td>
                                <td class="row">
                                    <label>
                                        <input class="input" placeholder="      请输入用户名" name="userName" id="userName">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="row">
                                    <img src="img/key.png" class="icon">
                                </td>
                                <td class="row">
                                    <label>
                                        <input class="input" type="password" placeholder="      请输入密码" align="center" name="password" id="password">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="row">
                                    <label>
                                        <input type="submit" value="登录" id="submit" onclick="return checkInput()">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                   <div class="lastTd">
                                       <label><input type="checkbox">记住密码</label>
                                       <span><a href="signup.php" class="a2">注册账号</a></span>
                                   </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/jquery-1.9.1.min.js"></script>
<script>
    function checkInput() {
        var userName = $("#userName").val().trim(),
            password = $("#password").val().trim();
        var error = 0,errorMsg = '';
        if(userName === ''){
            error = 1;
            errorMsg +='用户名不能为空!';
        }
        else if(password === ''){
            error = 1;
            errorMsg +='密码不能为空!';
        }
        if(error === 1){
            alert(errorMsg);
            return false;
        }
        else{
            return true;
        }
    }
</script>
</html>