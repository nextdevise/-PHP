//员工注册审核请求
//查找元素函数
var $1 = q => document.querySelector(q);
var mainContent = $1(".mainContent");
//创建元素函数
var createEle = ele =>document.createElement(ele);
//创建表头函数
var g_staffChoose = [],g_CurrentPage = 1;
var staff = ['','序号','用户名','真实姓名','邮箱','联系电话','所属部门','申请时间','操作'];
function createCell(eles,parent,values,className = '') {
    values.forEach(value=>{
        var ele = createEle(eles);
    ele.innerHTML = value;
    ele.className = className;
    parent.appendChild(ele);
})
};
function backTwo(str) {
    return (('0'+str).slice(-2));
}
//发送一部请求函数
/*
默认我们每次获取10条记录
 */
function requestStaffNode(table,nodeNum = 10,page = 1) {
    $.ajax({
        data:{
            nodeNum:nodeNum,
            page:page
        },
        url:"getStaffNode.php",
        type:"POST",
        dataType:"JSON",
        success:function (data) {
            if(!data.error){
                console.log(data);
                data.staffNode.forEach((value,index)=>{
                    var tr = createEle("tr");
                var staffInfo = [
                    '<input type="checkbox"  value='+ value.uId +' class="isChoose">',
                    backTwo((page-1)*nodeNum + index+1),
                    '<div class="staffInfo">'+ value.userName +'</div>',
                    '<div class="staffInfo">'+ value.staffName +'</div>',
                    '<div class="staffInfo">'+ value.email +'</div>',
                    '<div class="staffInfo">'+ value.phoneNumber +'</div>',
                    value.dName,data.applyTime[index],
                    '<div><a onclick="StaffManage('+ value.uId +',0,'+ nodeNum +')" class="a2">审核通过</a>|' +
                    '<a onclick="StaffManage('+ value.uId +',1,'+ nodeNum +')" class="a2">删除</a></div>'
                ];
                createCell("td",tr,staffInfo,"staffInfoBody");
                //根据dId来选择部门
                table.appendChild(tr);
            });
                console.log(g_staffChoose);
                var isChooses = document.querySelectorAll(".isChoose");
                for(var i =0;i<isChooses.length;i++){
                    if(g_staffChoose.some(p=>p===isChooses[i].value*1)){
                        isChooses[i].checked = true;
                    }
                }
                mainContent.appendChild(table);
                //创建多选按钮选项
                staffManyManage(data.nodeTotalNum,page,nodeNum);
            }
            else{
                alert("员工查找失败!"+data.error);
            }
        },
        error:function (_,error) {
            alert(error);
        }
    })
}
//员工操作
function getStaffNode(nodeNum = 8,page = 1) {
    //创建table
    //初始化
    mainContent.innerHTML = '';
    var table = createEle("table");
    table.classList.add("mainTable");
    table.addEventListener("change",function (e) {
        if(e.target.tagName === "INPUT") {
            chooseCurrentStaff(e.target.value*1);
        }
    });
    var tr = createEle("tr");
    createCell("td", tr, staff,"staffInfoHead");
    table.appendChild(tr);
    //发送请求，获取数据
    requestStaffNode(table,nodeNum,page);
    mainContent.appendChild(table);
}
//员工的删除和审核操作
function StaffManage(uId,whatUrl,nodeNum) {
    if(!confirm("确认操作?")){
        return;
    }
    $.ajax({
        data:{
            uId:uId
        },
        url:["adoptStaff.php","deleteStaff.php"][whatUrl],
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            if(data.error === 10){
                alert("删除成功!");
            }
            else if(data.error === 11){
                alert("操作成功!");
            }
            else{
                alert("操作失败!"+data.error);
                return false;
            }
            var futureNum = data.futureNum;
            //判断删除之后的最后一页
            if(futureNum){
                var lastPage = Math.ceil(futureNum/nodeNum);
                g_CurrentPage = lastPage>=g_CurrentPage?g_CurrentPage:lastPage;
                getStaffNode(nodeNum,g_CurrentPage);
            }
            else{
                getStaffNode(nodeNum,1);
            }

        },
        error: function (_, error) {
            alert(error);
        }
    })
}
//员工的删除和审核操作的多选操作
function staffManyManage(nodeTotalNum,currentPage,nodeNum){
    //创建多选确认按钮
    var manyBtn = [
        '<div class="control" id="control-del"><img src="img/delete.png" class="controlIcon"><span>删除所选</span></div>',
        '<div class="control" id="control-edit"><img src="img/edit.png" class="controlIcon"><span>通过所选</span></div>'
    ];
    var div = createEle("div");
    div.className = "btnBox";
    div.addEventListener("click",function (e) {
        if(e.target.parentElement.id === "control-del"){
            controlMoreStaff(g_staffChoose,"deleteManyStaff.php",nodeNum);
        }
        else if(e.target.parentElement.id === "control-edit"){
            controlMoreStaff(g_staffChoose,"adoptManyStaff.php",nodeNum);
        }
    });
    createCell("div",div,manyBtn);
    mainContent.appendChild(div);
    //这里其实没必要，有点多余

    //创建分页，分页功能的实现
    page(nodeTotalNum,currentPage,nodeNum,div);
}
//控制多选操作的具体实现
function controlMoreStaff(allChooseStaff,url,nodeNum){
    if(!allChooseStaff.length){
        alert('还没有选中任何对象!');
        return;
    }
    if(!confirm("确认操作已选中!")){
        return;
    }
    $.ajax({
        data:{
            staffs:allChooseStaff
        },
        url:url,
        dataType:"JSON",
        type:"POST",
        success:function (data) {
            if(data.error === 12){
                alert("删除成功!");
            }
            else if(data.error === 13){
                alert("操作成功!");
            }
            else{
                alert("操作失败!"+data.error);
                return false;
            }
            var futureNum = data.futureNum;
            if(futureNum){
                var lastPage = Math.ceil(futureNum/nodeNum);
                g_CurrentPage = lastPage>=g_CurrentPage?g_CurrentPage:lastPage;
                getStaffNode(nodeNum,g_CurrentPage);
            }
            else{
                getStaffNode(nodeNum,1);
            }
        },
        error:function (_,error) {
            alert(error);
        }
    })
}
//多选按钮的切换和判断是否选中当前员工
function chooseCurrentStaff(currentStaffUId) {
    var currentIndex = g_staffChoose.findIndex(value=>value===currentStaffUId);
    if(currentIndex === -1){
        g_staffChoose.push(currentStaffUId);
    }
    else{
        g_staffChoose.splice(currentIndex,1);
    }
    console.log(g_staffChoose);
}
//分页功能的实现
function page(nodeTotalNum,currentPage,nodeNum,div) {
    //参数1：获取到的员工总数。参数2：请求而得到的当前位置页。参数3：要求每页的数量
    //js分页
    //1、创建前端ui
    var lastPage = Math.ceil(nodeTotalNum/nodeNum);
    var divPage = createEle("div");
    divPage.classList.add("pageDiv");
    var str = '';
    divPage.innerHTML = '' +
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
        getStaffNode(nodeNum,g_CurrentPage);
    });
    divPage.appendChild(selectPage);
    var span = createEle("span");
    span.innerHTML = '共 '+ lastPage +'页';
    divPage.appendChild(span);
    div.appendChild(divPage);
}
//切换页面
function changePage(nodeNum,howToPage,maxPage) {
    if(howToPage === 0){
        g_CurrentPage < maxPage && ( g_CurrentPage +=1) && getStaffNode(nodeNum,g_CurrentPage);
    }
    else if(howToPage ===-1){
        g_CurrentPage > 1 && (g_CurrentPage -= 1) && getStaffNode(nodeNum,g_CurrentPage);
    }
    else {
        g_CurrentPage !== howToPage && (g_CurrentPage = howToPage) && getStaffNode(nodeNum,g_CurrentPage);
    }
}

////////////////////////////
//调用、初始化
getStaffNode(8,1);
