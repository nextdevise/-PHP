//该页面为获取已经通过注册验证员工的信息
    function requestStaffInfo(currentPage,nodeNum) {
        $.ajax({
            data:{
              currentPage:currentPage,
              nodeNum:nodeNum
            },
            url:"getStaffInfo.php",
            dataType:"JSON",
            type:"POST",
            success:function (data) {
                if(!data.error){
                    console.log(data);
                    controlInfo(data.staffInfo,data.totalNum,currentPage,nodeNum);
                }
                else{
                    alert("错误!"+data.error);
                }
            },
            error:function (_,error) {
                alert(error);
            }
        })
    }

$(function () {
    requestStaffInfo(1,8);
});




//创建元素
var createEle = ele =>document.createElement(ele);
var $q = q =>document.querySelector(q);
var mainContent = $q(".mainContent");
var g_CurrentPage = 1;
//主要用于创建表格中的元素
function createCell(eles,parent,values) {
    values.forEach(function(value){
        var ele = createEle(eles);
        ele.innerHTML = value;
        //注意这里的是innerHTML，区别于innerText
        parent.appendChild(ele);
    });
};
function changePage(nodeNum,howToPage,maxPage) {
    if(howToPage === 0){
        g_CurrentPage < maxPage && ( g_CurrentPage +=1) && requestStaffInfo(g_CurrentPage,nodeNum);
    }
    else if(howToPage ===-1){
        g_CurrentPage > 1 && (g_CurrentPage -= 1) && requestStaffInfo(g_CurrentPage,nodeNum);
    }
    else {
        g_CurrentPage !== howToPage && (g_CurrentPage = howToPage) && requestStaffInfo(g_CurrentPage,nodeNum);
    }
};
//
var infoManage = (function () {
    var staffInfoHead = ['序号','用户名','真实姓名','邮箱','电话','部门','是否管理','操作'];
    return {
        createTableHead:function (parTable) {
            var tr = createEle("tr");
            createCell("td",tr,staffInfoHead);
            parTable.appendChild(tr);
        },
        backTwo:function (str) {
            return (('0'+str).slice(-2));
        },
        page:function (nodeTotalNum,currentPage,nodeNum,div){
            /*
            参数1：获取到的总数。
            参数2：请求而得到的当前位置页。
            参数3：要求每页的数量
            参数4：分页ui显示的位置
             */
            //创建前端ui
            var lastPage = Math.ceil(nodeTotalNum/nodeNum);
            div.innerHTML = '' +
                '第'+ currentPage +'页 共'+ nodeTotalNum +'条记录' +
                '<a onclick="changePage('+ nodeNum +',1,'+ lastPage +')" class="a2"> 首页 </a>' +
                '<a onclick="changePage('+ nodeNum +',-1,'+ lastPage +')" class="a2"> 上一页 </a>' +
                '<a onclick="changePage('+ nodeNum +',0,'+ lastPage +')" class="a2"> 下一页 </a>' +
                '<a onclick="changePage('+ nodeNum +','+ lastPage +','+ lastPage +')" class="a2"> 尾页 </a>';
            var selectPage = createEle("select");
            for(var i=1;i<=lastPage;i++){
                var option = createEle("option");
                i===g_CurrentPage && (option.selected = true);
                option.value = i;
                option.innerText = i;
                selectPage.appendChild(option);
            }
            selectPage.addEventListener("change",function (e) {
                g_CurrentPage = e.target.value*1;
                //注意这里的数字格式，value是文本类型的，我们需要的是Int类型的
                requestStaffInfo(g_CurrentPage,nodeNum);
            });
            div.appendChild(selectPage);
            var span = createEle("span");
            span.innerHTML = ' 共 '+ lastPage +'页';
            div.appendChild(span);
        },
        setStaffAdmin:function (nowIsAdmin,uId,nodeNum) {
            if(!confirm("确认操作？")){
                return false;
            }
            //设置管理员，如果现在为管理员,传0，否则传1
            var action = nowIsAdmin?0:1;
            console.log(action);
            $.ajax({
                data:{
                    action:action,
                    uId:uId
                },
                dataType:"JSON",
                type:"POST",
                url:"setStaffAdmin.php",
                success:function (data) {
                    if(!data.error){
                        alert("操作成功");
                        requestStaffInfo(g_CurrentPage,nodeNum);
                    }
                    else{
                        alert("错误!"+data.error);
                    }
                },
                error:function (_,error) {
                   alert(error);
                }
            })
        },
        deleteStaffInfo:function (uId,nodeNum) {
            if(!confirm("确认删除？")){
                return false;
            }
            $.ajax({
                data:{
                    uId:uId
                },
                url:"deleteStaffInfo.php",
                dataType:"JSON",
                type:"POST",
                success:function (data) {
                    if(!data.error){
                        alert("删除成功!");
                        /*
                        根据以前的总数减去现在删掉的数量，来获取现在的分页情况，如果最大分页数量大于了
                        删除之前的分页数量，那么现在保持current，否则以最大分页为准
                        */
                        //获取到的当前还存在的记录的条数
                        var futureNum = data.futureNum;
                        if(futureNum){
                            //判断删除之后的最后一页
                            var lastPage = Math.ceil(futureNum/nodeNum);
                            g_CurrentPage = lastPage>=g_CurrentPage?g_CurrentPage:lastPage;
                            requestStaffInfo(g_CurrentPage,nodeNum);
                        }
                        else{
                            requestStaffInfo(1,nodeNum);
                        }


                    }
                    else{
                        alert("错误!"+data.error);
                    }
                },
                error:function (_,error) {
                    alert(error);
                }
            })
        }
    }
})();



function controlInfo(staffInfo,nodeTotalNum,currentPage,nodeNum) {
    //在mainContent里面存放数据
    //创建表格
    mainContent.innerHTML = '';
    var table = createEle("table");
    table.className = "staffInfoTable";
    infoManage.createTableHead(table);

    //创建表体
    staffInfo.forEach(function (staff,index) {
        var tr = createEle("tr");
        tr.className = "staffInfoTr";
        tr.innerHTML =
            '<td>'+ infoManage.backTwo((((currentPage - 1)*nodeNum)+(index+1))) +'</td>' +
            '<td><div class="staffInfoDiv">'+ staff.userName +'</div></td>' +
            '<td><div class="staffInfoDiv">'+ staff.staffName +'</div></td>' +
            '<td><div class="staffInfoDiv">'+ staff.email +'</div></td>' +
            '<td><div class="staffInfoDiv">'+ staff.phoneNumber +'</div></td>' +
            '<td>'+ staff.dName +'</td>' +
            '<td>'+ staff.adminInfo +'</td>' +
            '<td><a class="a2" onclick="infoManage.setStaffAdmin('+ staff.admin +','+ staff.uId +','+ nodeNum +')">'+ staff.setAdminInfo +'</a> ' +
            '<a class="a2" onclick="infoManage.deleteStaffInfo('+ staff.uId +','+ nodeNum +','+ nodeTotalNum +')">'+ staff.isDelete +'</a></td>';
        table.appendChild(tr);
    });
    mainContent.appendChild(table);
    //进行分页操作
    var div = createEle("div");
    div.className = "pageDiv";
    var div1 = createEle("div");
    div.appendChild(div1);
    mainContent.appendChild(div);
    infoManage.page(nodeTotalNum,currentPage,nodeNum,div1);
}