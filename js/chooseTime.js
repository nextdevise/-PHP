//使用requestAnimation来显示当前的时间
//该控件会在员工选择时间的时候才会显示出来，刚开始是隐藏掉的
var $1 = q => document.querySelector(q);
var timeChoose = $1(".timeChoose"), hours = $1(".hours"), minutes = $1(".minutes"), myChooseTime = $1(".myChooseTime");
var startTime = $1("#startTime"),endTime = $1("#endTime"),howTime = $1("#howTime");
var backBeforTwo = hour => hour.slice(-2);
var days = ["日", "一", "二", "三", "四", "五", "六"];
//定义我的日期对象，将现在的时间放入到其中
var currentTime = {};//当前的时间
var g_showTime = '',g_timespan = 0;
var selectEle = {}, selectTime = {};
var currentDate = '';//记录当前date，刷新的一个判断条件
var currentHour = '';//记录当前的Hour，刷新分钟
const currentCSS = ['currentDate', 'currentHour', 'currentHour'];
var allHoursEle = [], allMinutesEle = [];
//创建并添加元素
var toAppend = (ele, par, text, datasetName = null, datasetText = null, className = null, all = []) => {
    text.forEach(value => {
        var element = document.createElement(ele);
    element.innerText = value;
    element.classList.add(className);
    element.dataset[datasetName] = datasetText;
    par.appendChild(element);
    all.push(element);
})
}
//返回当前时间正常的时间
var getShowTime = () => selectTime.selectDate.concat(selectTime.selectHour).concat(selectTime.selectMinute);
//格式化当前的时间
function forMatTimes(now) {
    return {
        nowYear: now.getFullYear(),
        nowMonth: backBeforTwo('0' + (now.getMonth() + 1)),
        nowDate: backBeforTwo('0' + now.getDate()),
        nowHour: backBeforTwo('0' + now.getHours()),
        nowMin: backBeforTwo('0' + now.getMinutes()),
        nowSec: backBeforTwo('0' + now.getSeconds()),
        nowDay: now.getDay()
    }
};

//小时和分钟界面管理
const TimeManger = (() => {
    return {
        dateChoose() {
            timeChoose.innerHTML = '';
            currentDate = currentTime.nowDate;
            //将以当前时间开始的未来7天，包括了今天
            for (var i = 0; i < 7; i++) {
                timeed = new Date();
                timeed.setDate(timeed.getDate() + i);
                var year = timeed.getFullYear(),
                    month = timeed.getMonth(),
                    dates = timeed.getDate(),
                    day = timeed.getDay();
                var li = document.createElement("li");
                li.dataset.time = [year, month + 1, dates];
                li.className = "li1";
                toAppend("p", li, [`${year}/${month + 1}/${dates}`, `星期${days[day]}`]);
                timeChoose.appendChild(li);
            }
            selectEle.currentEle = timeChoose.children[0];
        },
        setHours(howHour = 0) {
            howHour = howHour === 24?23:howHour;
            allHoursEle = [];
            hours.innerHTML = '';
            for (var i = howHour; i <= 23; i++) {
                toAppend('li', hours, [backBeforTwo('0' + i)], 'time', i, 'HD', allHoursEle);
            }
            //每次刷新后因为保留不了当前选中的元素，所以要在变化之后自动更新
            selectEle.currentHour = allHoursEle[0];
            selectTime.selectHour = selectEle.currentHour && selectEle.currentHour.dataset.time * 1;
        },
        setminutes(howMinute = 0) {
            allMinutesEle = [];
            minutes.innerHTML = '';
            for (var i = howMinute; i <= 59; i++) {
                toAppend('li', minutes, [backBeforTwo('0' + i)], 'time', i, 'HD', allMinutesEle);
            }
            selectEle.currentMinute = allMinutesEle[0];
            selectTime.selectMinute = selectEle.currentMinute && selectEle.currentMinute.dataset.time * 1;
        }
    }
})();

//请注意，这几个函数是为了自动刷新而存在的
//输出并显示可选hour
function hourChoose(date, hour) {
    date === currentDate ? TimeManger.setHours(hour + 1) : TimeManger.setHours();
}
//输出并显示可选分钟
function minutesChoose(date, hour, minutes) {
    if (date === currentDate) {
        if(currentHour === 23){
            TimeManger.setminutes(minutes)
        }
        else{
            hour === currentHour ? TimeManger.setminutes(minutes) : TimeManger.setminutes();
        }
    }
    else {
        TimeManger.setminutes();
    }
}
var freshTime = (nowTimes, current) => {
    /*
    刷新当前时间：
    如果选中的是当前的时间，那么我们将设置，日期会在0点之后更新，Hours会在每一个小时之后更新，
    而分钟也会在每一分钟之后更新
    现在的时间是通过定时器实时传递过来的，如果就像我们说的，肯定是要先记录第一次的时间，然后判断是否符合我们
    改变的条件，然后在进行改变
    */
    //初始化操作
    currentHour = nowTimes.nowHour;
    if (!current) {
        //获取当前时分秒
        Object.keys(nowTimes).forEach(v => currentTime[v] = nowTimes[v]);
        //刷新可选日期
        TimeManger.dateChoose(currentTime.nowDate);
        //刷新可选Hour
        hourChoose(currentTime.nowDate, currentTime.nowHour);
        //刷新可选分钟
        minutesChoose(currentTime.nowDate, currentTime.nowHour, currentTime.nowMin);
        //记录当前选中的是谁
        selectEle = {
            currentEle: $1(".timeChoose").children[0],
            currentHour: $1(".hours").children[0],
            currentMinute: $1(".minutes").children[0]
        };
        changeTime();
        //刷新当前元素状态
    }
    else {
        //判断各个时间点并刷新
        Object.keys(nowTimes).forEach((v, index) => {
            if (nowTimes[v] !== currentTime[v]) {
            currentTime[v] = nowTimes[v];
            [TimeManger.dateChoose, hourChoose, minutesChoose][index](selectTime.selectDate[2] * 1, selectTime.selectHour-1, currentTime.nowMin);
            changeTime();
        }
    })
    }
}

//获取当前的时间并输出
(function getNowTime() {
    var now = new Date(),
        nowTimes = forMatTimes(now);//获取到的格式化时间
    //时钟显示在屏幕上
    $1(".nowTime").innerText = `${nowTimes.nowYear}/${nowTimes.nowMonth}/${nowTimes.nowDate} ${nowTimes.nowHour}:${nowTimes.nowMin}:${nowTimes.nowSec}   星期${days[nowTimes.nowDay]}`;
    //时间管理函数
    freshTime({ nowDate: nowTimes.nowDate * 1, nowHour: nowTimes.nowHour * 1, nowMin: nowTimes.nowMin * 1 }, currentDate);
    setTimeout(getNowTime, 1000);
})();

//选择日期功能的实现
timeChoose.addEventListener("click", e => {
    //当前元素的判断
    switch (e.target.tagName) {
case "LI": selectEle.currentEle = e.target; break;
case "P": selectEle.currentEle = e.target.parentElement; break;
}
//和获取当前元素的date
selectTime.selectDate = selectEle.currentEle.dataset.time.split(",");
hourChoose(selectTime.selectDate[2] * 1, currentHour);
minutesChoose(selectTime.selectDate[2] * 1, currentHour, currentTime.nowMin);
selectEle.currentHour = $1(".hours").children[0];
selectEle.currentMinute = $1(".minutes").children[0];
changeTime();
});

//选择hours功能实现
hoursChoose.addEventListener("click", e => {
    if (e.target.tagName === 'LI') {
    selectEle.currentHour = e.target;
    selectTime.selectHour = selectEle.currentHour.dataset.time * 1;
    minutesChoose(selectTime.selectDate[2] * 1, selectTime.selectHour - 1, currentTime.nowMin);
}
switchButton(e.target, allHoursEle, 'currentHour');
fresh(selectEle);
})
//选择minutes功能实现
minuteChoose.addEventListener("click", e => {
    if (e.target.tagName === 'LI') {
    selectEle.currentMinute = e.target;
    hours.selectHour = selectEle.currentMinute.dataset.minute * 1;
}
switchButton(e.target, allMinutesEle, 'currentMinute');
fresh(selectEle);
})

//点击按钮切换当前元素功能实现
function switchButton(target, all, current) {
    if (target.tagName === 'IMG') {
        var nowHourEleIndex = all.indexOf(selectEle[current]);//找到当前选中的元素
        //判断当前是否有选中，如果不选中，那么就默认为第一个
        selectEle[current] = nowHourEleIndex === -1 ? all[0] : all[nowHourEleIndex];
        if (target.dataset.direction === 'directionTop' && nowHourEleIndex >= 1) {
            selectEle[current] = all[nowHourEleIndex - 1];
        }
        else if (target.dataset.direction === 'directionBottom' && (nowHourEleIndex < all.length - 1 && nowHourEleIndex > -1)) {
            selectEle[current] = all[nowHourEleIndex + 1];
        }
    }
    changeTime();
}


function fresh(curEle) {
    Object.keys(curEle).forEach((ele, index) => {
        if (curEle['old' + ele] !== curEle[ele] && index < 3) {
        curEle[ele].classList.add(currentCSS[index]);
        curEle['old' + ele] && curEle['old' + ele].classList.remove(currentCSS[index]);
        curEle['old' + ele] = curEle[ele];
    }
})
}



function changeTime() {
    //当前选中的时间
    selectTime = {
        selectDate: selectEle.currentEle && selectEle.currentEle.dataset.time.split(","),
        selectHour: selectEle.currentHour && selectEle.currentHour.dataset.time * 1,
        selectMinute: selectEle.currentMinute && selectEle.currentMinute.dataset.time * 1
    };
    fresh(selectEle);
    var [year,month,date,hour,minute] = getShowTime();//ES6的数组解构
    g_showTime = `${year}/${backBeforTwo('0' +month)}/${backBeforTwo('0' +date)}  ${backBeforTwo('0' +hour)}:${backBeforTwo('0' +minute)}`;
    myChooseTime.innerText = `当前选择时间：${g_showTime}`;
}
//最重要的部分，获取时间戳和控制时间面板出现和隐藏
$1("#submitBtn").addEventListener("click", e => {
var [year,month,date,hour,minute] = getShowTime();//ES6的数组解构
console.log([year,month,date,hour,minute]);
g_timespan = new Date(year*1,(month*1)-1,date*1,hour,minute);
startTime.value = g_showTime;
startTime.dataset.time = g_timespan.getTime()/1000;
//转化为php的时间戳
//改变结束时间
toEndTime(g_timespan);
g_isClick = true;
timeCheckedAnimat();
});
var g_isClick = false;
var chooseTime = $1("#chooseTime"),chooseTimes = $1("#chooseTimes"),container_time = $1("#container-time");
chooseTime.addEventListener("click",e=>{
    timeCheckedAnimat();
});
function timeCheckedAnimat() {
    chooseTimes.style.height = g_isClick?0+'px':container_time.offsetHeight + 'px';
    chooseTimes.style.transition = "height 1s";
    g_isClick = g_isClick?false:true;
}

//当选择时间改变时
howTime.addEventListener("change",function () {
   if(startTime.value){
       toEndTime(g_timespan);
   }
});

//结束时间的设置
function toEndTime(nowTimeSpan) {
    var endDate = new Date();
    endDate.setTime(nowTimeSpan.getTime() + howTime.selectedOptions[0].value*1*60*1000);
    var endInfo = forMatTimes(endDate);
    endTime.value = `${endInfo.nowYear}/${endInfo.nowMonth}/${endInfo.nowDate} ${endInfo.nowHour}:${endInfo.nowMin}`;
    endTime.dataset.time = endDate.getTime()/1000;
}

//申请数据提交
(()=>{
    //申请会议室使用提交
    var applySubmit = $1("#applySubmit");
    applySubmit.addEventListener("click",function (e) {
        var conferenceTitle = $1("#conferenceTitle"),conferenceNames = $1("#conferenceNames"),chooseConference = $1("#chooseConference"),error = 0,errorMsg = '',userId = $1("#userName").dataset.userid;
        console.log(startTime.value.trim());
        if(conferenceTitle.value.trim()===""){
            error = 1;
            errorMsg += "请输入会议主题";
        }
		else if(conferenceNames.value.trim()===""){
            error = 1;
            errorMsg += "请输入参会人员";
        }
        else if(chooseConference.selectedOptions[0].value===''){
            error = 1;
            errorMsg += "请选择会议室";
        }
        else if(startTime.value.trim() === ""){
            error = 1;
            errorMsg +="请选择开始时间";
        }
        else if(howTime.value.trim()===""){
            error = 1;
            errorMsg +="请选择使用时间";
        }
        if(error === 1){
            alert(errorMsg);
            return false;
        }
        //数据审核通过，合法数据进入操作
        else{
           $.ajax({
               data:{
                   userId:userId,
                   startTime:startTime.dataset.time,
                   endTime:endTime.dataset.time,
                   useTime:howTime.value,
                   cId:chooseConference.selectedOptions[0].value,
                   cTitle:conferenceTitle.value,
				   cNames:conferenceNames.value
        },
               url:"apply.php",
               dataType:"JSON",
               type:"POST",
               success:function (data) {
                    if(data.error === 0){
                        alert("恭喜，预约成功!");
                        location.href='index.php?currentPage=conferenceApply&H1='+ g_state[0].state +'&H2='+ g_state[1].state;                    }
                    else{
                       if(data.error === 2){
                           alert("抱歉，该时间段该会议室已被人占用，请重新选择时间或会议室！");
                       }
                       else{
                           alert("错误"+data.error);
                       }
                    }
               },
               error:function (_,error) {
                   alert(error);
               }
           })
        }

});

})()


