//这里做的是申请会议室的时候，请求需要的数据
var g_conferenceInfo;
(function () {
    $.ajax({
        url:"getConference.php",
        type:"POST",
        dataType:"JSON",
        success:function (data) {
            console.log(data);
            var str = '';
            for(var i=0;i<data.conferenceroom.length;i++){
                str +='<option value='+data.conferenceroom[i].cId+'>'+ data.conferenceroom[i].cName +'</option>'
            }
            $("#chooseConference").append(str);
            g_conferenceInfo = data.conferenceroom;
            console.log(g_conferenceInfo);
        },
        error:function (_,error) {
            alert(error)
        }
    });
})();
var howTime = document.getElementById("howTime");
var str1 = '';
for(var i=20;i<=180;i+=5){
    str1+=i===60?'<option value='+ i +' selected>'+ i +'</option>':'<option value='+ i +'>'+ i +'</option>'
}
howTime.innerHTML = str1;


//实现申请会议室和查看会议室之间的切换
(function () {
    var applyMenu = document.querySelector(".applyMenu"),
        mainContents = document.querySelector(".mainContent"),
        useRooms = document.querySelectorAll(".useRoom"),
        lookUpConference = document.querySelector("#lookUpConference"),
        chooseConference = document.querySelector("#chooseConference");
    applyMenu.addEventListener("click",function (e) {
       if(e.target.className === "useRoom") {
          var room = e.target.dataset.room*1;
          var length = mainContents.children.length;
          for(var i=0;i<length;i++){
              if(i===room){
                  mainContents.children[i].style.display = "block";
                  useRooms[i].parentElement.classList.add("currentMenu");
              }
              else{
                  mainContents.children[i].style.display = "none";
                  useRooms[i].parentElement.classList.remove("currentMenu");
              }
          }
       }
    });
    lookUpConference.addEventListener("click",function (e) {
        if(e.target.tagName === "SPAN"){
            //?小写？大写
            // alert(e.target.dataset.roomvalue);
            //影藏
            mainContents.children[0].style.display = "none";
            mainContents.children[1].style.display = "block";
            useRooms[1].parentElement.classList.add("currentMenu");
            useRooms[0].parentElement.classList.remove("currentMenu");
            //获取会议室下拉列表，选中我们想要的
            for(var i=0;i<chooseConference.children.length;i++){
                //都是字符类型的数字
                if(e.target.dataset.roomvalue === chooseConference.children[i].value){
                    chooseConference.children[i].selected = true;
                }
            }
        }
    })

})();

$(function () {
    //实现判断当前会议室是否可以使用功能
    var meetRooms = document.querySelectorAll(".meetRoom");
    function useRoom(isNum,parent,roomValue) {
        var div = document.createElement("div");
        div.className = "isUseRoom";
        if(isNum){
            div.classList.add("notUseRoom");
            div.innerHTML = '<span data-roomValue='+ roomValue +'>该时段已被占用</span>';
        }
        else{
            div.classList.add("canUseRoom");
            div.innerHTML = '<span data-roomValue='+ roomValue +'>当前可以使用</span>';
        }
        parent.appendChild(div);
    }
    $.ajax({
        url:"judgeRoomIsUse.php",
        dataType:"JSON",
        type:"POST",
        success:function (data) {
            if(!data.error){
                console.log(data);
                console.log(g_conferenceInfo);
                //根据返回的结果来添加上相应的元素
                data.isUserRoom.forEach(function (v,index) {
                    useRoom(v,meetRooms[index],g_conferenceInfo[index].cId);
                })
            }
        },
        error:function (_,error) {
            alert(error);
        }
    })
});
