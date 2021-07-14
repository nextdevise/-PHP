/*
applyLog为操作请求会议室申请日志
 */
var g_cId = 0;
   function requestLog(currentPage,nodeNum,cId = 0) {
       $.ajax({
           data:{
               currentPage:currentPage,
               nodeNum:nodeNum,
               cId:cId
           },
           url:"getApplyLog.php",
           dataType:"JSON",
           type:"POST",
           success:function (data) {
               if(!data.error){
                   //创建UI界面
                   console.log(data);
                   createLogUi(data.log,data.totalNum,currentPage,nodeNum,data.stateCId);
                   //log 总数 当前页面 分页数量
               }
               else{
                   alert("错误!"+data.error);
               }
           },
           error:function (_,error) {
               alert(error);
           }
       });
   }
   $(function () {
       requestLog(1,8);
   });
//创建元素
var createEle = ele =>document.createElement(ele);
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
        g_CurrentPage < maxPage && ( g_CurrentPage +=1) && requestLog(g_CurrentPage,nodeNum,g_cId);
    }
    else if(howToPage ===-1){
        g_CurrentPage > 1 && (g_CurrentPage -= 1) && requestLog(g_CurrentPage,nodeNum,g_cId);
    }
    else {
        g_CurrentPage !== howToPage && (g_CurrentPage = howToPage) && requestLog(g_CurrentPage,nodeNum,g_cId);
    }
};
var $q = q =>document.querySelector(q);
var mainContent = $q(".mainContent");
//立即执行，返回我们需要操作的函数和元素
var logManage = (function(){
    var logInfo = ['序号','申请人','开始时间','结束时间','时长/分钟','会议室','会议主题','参会人员','申请时间',"状态",'操作'];
    //切换页面
    return {
        createTableHead:function (parTable) {
            var tr = createEle("tr");
            createCell("td",tr,logInfo);
            parTable.appendChild(tr);
        },
        deleteLog:function (aId,nodeNum) {
            if(!confirm("确认删除？")){
                return false;
            }
            $.ajax({
                data:{
                    aId:aId
                },
                url:"deleteLog.php",
                dataType:"JSON",
                type:"POST",
                success:function (data) {
                    if(!data.error){
                        alert("删除成功!");
                        var futureNum = data.futureNum;
                        //判断删除之后的最后一页
                        if(futureNum){
                            var lastPage = Math.ceil(futureNum/nodeNum);
                            g_CurrentPage = lastPage>=g_CurrentPage?g_CurrentPage:lastPage;
                            requestLog(lastPage,nodeNum);
                        }
                        else{
                            requestLog(1,nodeNum);
                        }

                    }
                    else{
                        alert("错误"+data.error);
                    }
                },
                error:function (_,error) {
                    alert(error);
                }
            })
        },
        //分页功能的实现
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
            requestLog(g_CurrentPage,nodeNum,g_cId);
        });
        div.appendChild(selectPage);
        var span = createEle("span");
        span.innerHTML = ' 共 '+ lastPage +'页';
        div.appendChild(span);
    },
        backTwo:function (str) {
            return (('0'+str).slice(-2));
        }
}
})();

//展示会议室申请日志UI
function createLogUi(log,nodeTotalNum,currentPage,nodeNum,stateCId) {
    //创建表格和表头
    console.log(stateCId);
    mainContent.innerHTML = '';
    var logTable = createEle("table");
    logTable.classList.add("logTable");
    logManage.createTableHead(logTable);

    //创建表体
    //log为一个数组，且每一条记录都是一个对象
    log.forEach(function (v,index) {
        var tr = createEle("tr");
        tr.innerHTML =
            '<td>'+ logManage.backTwo(((index+1)+((currentPage-1)*nodeNum))) +'</td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.userName +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.startTime +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.endTime +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.useTime +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.cName +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.cTitle +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.cNames +'</div></td>' +
            '<td class="logInfoTd"><div class="logInfo">'+ v.aTime +'</div></td>' +
            '<td class="logInfoTd">'+ v.state +'</td>' +
            '<td class="logInfoTd"><a onclick="logManage.deleteLog('+ v.aId +','+ nodeNum +')" class="a2">删除</a></td>';
        logTable.appendChild(tr);
    });
    mainContent.appendChild(logTable);

    //实现记录的分页
    var div = createEle("div");
    div.className = "pageDiv";
    var div1 = createEle("div");
    div.appendChild(div1);
    mainContent.appendChild(div);
    logManage.page(nodeTotalNum,currentPage,nodeNum,div1);


    function onlyLookUp(e,nodeNum) {
        var cId = e.target.value;
        g_cId = cId;
        requestLog(1,nodeNum,cId);
        g_CurrentPage = 1;
    }

    (function () {
        //实现申请日志会议室的筛选
        var div2 = createEle("div");
        var select = createEle("select");
        select.className = "changeSelect";
        var span = createEle("span");
        div2.className = "changConference";
        $.ajax({
            url:"getConference.php",
            type:"POST",
            dataType:"JSON",
            success:function (data) {
                var str = '';
                for(var i=0;i<=data.conferenceroom.length;i++){
                    if(i===0){
                        str+='<option value=0>全部会议室</option>';
                    }
                    else{
                        str +='<option value='+data.conferenceroom[i-1].cId+'>'+ data.conferenceroom[i-1].cName +'</option>'
                    }
                }
                span.innerHTML = "<span>只看 </span>";
                select.innerHTML =str;
                div2.appendChild(span);
                div2.appendChild(select);
                console.log(stateCId);
                console.log(select.children);
                for(var t=0;t<select.children.length;t++){
                    console.log(stateCId);
                    if(select.children[t].value*1 === stateCId*1){
                        select.children[t].selected = true;
                    }
                }
                div.appendChild(div2);
                select.addEventListener("change",function (e) {
                    onlyLookUp(e,nodeNum);
                });
            },
            error:function (_,error) {
                alert(error)
            }
        });



    })()

}