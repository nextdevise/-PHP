//获取当前非管理员信息，修改界面
//完成员工年龄选择的功能

var $q1 = q =>document.querySelector(q);
var staffAge = $q1("#staffAge"),changeSubmit = $q1("#changeSubmit"),userName = $q1("#userName"),staffName = $q1("#staffName"),
    email = $q1("#email"),phoneNumber = $q1("#phoneNumber"),department = $q1("#department"),code = $q1("#code");
var currentAge = $q1("#currentAge"),
    currentDepartment = $q1("#currentDepartment");
var createEle = ele =>document.createElement(ele);
function toAdd(par,ele,value,text,wantValue) {
    var option = createEle(ele);
    option.innerText = text;
    option.value = value;
    par.appendChild(option);
    value === wantValue && (option.selected = true);
}
//员工年龄限制18-55岁
//method:使用循环添加的方式


(function () {
    // 获取所有部门
    $.ajax({
        url:"getDepartment.php",
        type:"POST",
        dataType:"JSON",
        success:function (data) {
            console.log(data);
            for(var i=0;i<data.department.length;i++){
                toAdd(department,"option",data.department[i].dId,data.department[i].dName,currentDepartment.value);
            }
        },
        error:function (_,error) {
            alert(error)
        }
    });
})();
//设置全局标志变量
var g_isUse = 1;
//异步验证用户名是否已被占用
$("#userName").blur(function () {
    if(!$("#userName").val().trim() || $("#nowUserName").val().trim()===$("#userName").val().trim()){
        $(".userNameInfo").html("<span></span>");
        g_isUse = 1;
        console.log(g_isUse);
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
                $(".userNameInfo").html("<span style='color:red;padding-left:20px;'>不能使用</span>");
                g_isUse = 0;
            }
            else{
                $(".userNameInfo").html("<span style='color:green;padding-left:20px;'>可以使用</span>");
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
changeSubmit.addEventListener("click",function (e) {
   var errorLog = 0,errorMsg = '';
   var userName1 = userName.value.trim(),staffName1 = staffName.value.trim(),email1 = email.value.trim(),phoneNumber1 = phoneNumber.value.trim(),
       code1 = code.value.trim(),staffAge1 = $("#staffAge").val().trim(),department1 = $("#department").val().trim(),nowUserUId = $("#nowUserUId").val().trim();
    if (userName1 == "") {
        errorLog = 1;
        errorMsg += "请输入你的用户名！\n";
    }
    if (staffName1 == "") {
        errorLog = 1;
        errorMsg += "请输入你的真实姓名！\n";
    }
    if (email1 == "") {
        errorLog = 1;
        errorMsg += "请输入你的邮箱！\n";
    }
    if (phoneNumber1 == "") {
        errorLog = 1;
        errorMsg += "请输入你的联系电话！\n";
    }
    if (code1 == "") {
        errorLog = 1;
        errorMsg += "请输入你的验证码！\n";
    }
    if(errorLog){
        alert(errorMsg);
        return false;
    }
    else{
        if(!g_isUse){
            alert("用户名已被占用，请重新输入!");
            return false;
        }
        //条件都满足
        $.ajax({
            data:{
                userName:userName1,
                staffName:staffName1,
                staffAge:staffAge1,
                email:email1,
                code:code1,
                department:department1,
                isUploaded:isUploaded,
                fileName:fileName,
                phoneNumber:phoneNumber1,
                nowUserUId:nowUserUId
            },
            url:"changeStaffInfo.php",
            type:"POST",
            dataType:"JSON",
            success:function (data) {
                if(!data.error){
                    alert("恭喜,修改成功!");
                    location.href='index.php?currentPage=staffInfoChange&H1='+ g_state[0].state +'&H2='+ g_state[1].state;
                }
                else{
                    if(data.error === 1){
                        alert("验证码输入错误!");
                        return false;
                    }
                    else{
                        alert("错误!"+data.error);
                    }
                }
            },
            error:function (_,error) {
                alert(error);
            }
        })
    }

});