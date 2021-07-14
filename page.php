<?php
//为了避免重复包含文件而造成错误，加了判断函数是否存在的条件：
if(!function_exists("pageft")){
    //定义函数pageft(),三个参数的含义为：
    //$totle：信息总数；
    //$displaypg：每页显示信息数，这里设置为默认是20；
    //$url：分页导航中的链接，除了加入不同的查询信息“page”外的部分都与这个URL相同。
    //　　　默认值本该设为本页URL（即$_SERVER["REQUEST_URI"]），但设置默认值的右边只能为常量，所以该默认值设为空字符串，在函数内部再设置为本页URL。
    function pageft($totle,$displaypg=20,$realtotal,$url=''){

        //定义几个全局变量：
        //$page：当前页码；
        //$firstcount：（数据库）查询的起始项；
        //$pagenav：页面导航条代码，函数内部并没有将它输出；
        //$_SERVER：读取本页URL“$_SERVER["REQUEST_URI"]”所必须。
        global $page,$firstcount,$pagenav,$_SERVER;

        //为使函数外部可以访问这里的“$displaypg”，将它也设为全局变量。注意一个变量重新定义为全局变量后，原值被覆盖，所以这里给它重新赋值。
        $GLOBALS["displaypg"]=$displaypg;
        $page=isset($_GET['page'])?$_GET['page']:1;  //判断是否存在
        //echo "page:".$page."<br>";
        //如果$url使用默认，即空值，则赋值为本页URL：
        if(!$url){
            $url=$_SERVER["REQUEST_URI"];
            $thisurl = $_SERVER["REQUEST_URI"];
        }
        //echo "url:".$url."<br>";
        //URL分析：
        $parse_url=parse_url($url);
        //echo "parse_url:".$parse_url."<br>";

        $url_query=@$parse_url["query"]; //单独取出URL的查询字串
        //echo "url_query:".$url_query."<br>";

        if($url_query){
            //因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
            //这里用到了正则表达式，请参考“PHP中的正规表达式”（http://www.pconline.com.cn/pcedu/empolder/wz/php/10111/15058.html）
            $url_query=ereg_replace("(^|&)page=$page","",$url_query);
            //echo "url_query1:".$url_query."<br>";

            //将处理后的URL的查询字串替换原来的URL的查询字串：
            $url=str_replace($parse_url["query"],$url_query,$url);
            //echo "url:".$url."<br>";
            //在URL后加page查询信息，但待赋值：
            if($url_query) $url.="&page"; else $url.="page";
        } else {
            $url.="?page";
        }
        //echo "url:".$url."<br>";

        $lastpg=ceil($totle/$displaypg); //最后页，也是总页数
        $page=min($lastpg,$page);
        $prepg=$page-1; //上一页
        $nextpg=($page==$lastpg ? 0 : $page+1); //下一页
        $firstcount=($page-1)*$displaypg;

        //开始分页导航条代码：
        $pagenav="第 <B>".($totle?($firstcount+1):0)."</B>-<B>".min($firstcount+$displaypg,$totle)."</B> 条，共<B> $realtotal </B>条记录";

        //如果只有一页则跳出函数：
        if($lastpg<=1) return false;

        $pagenav.=" <a href=$url=1 mce_href=$url=1>首页</a> ";
        if($prepg) $pagenav.=" <a href=$url=$prepg mce_href=$url=$prepg>上页</a> "; else $pagenav.=" 上页 ";
        if($nextpg) $pagenav.=" <a href=$url=$nextpg mce_href=$url=$nextpg>下页</a> "; else $pagenav.=" 下页 ";
        $pagenav.=" <a href=$url=$lastpg mce_href=$url=$lastpg>尾页</a> ";

        //下拉跳转列表，循环列出所有页码：
        $pagenav.="  到第 <select name='topage' size='1'  style='font-size:12px' mce_style='font-size:12px' onchange='window.location=\"$url=\"+this.value'>\n";
        for($i=1;$i<=$lastpg;$i++){
            if($i==$page) $pagenav.="<option value='$i' selected>$i</option>\n";
            else $pagenav.="<option value='$i'>$i</option>\n";
        }
        $pagenav.="</select> 页，共 $lastpg 页";
    }

}

?>