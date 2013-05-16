<?php
/**
 * 考试中心
 * @author suhuiling
 * @version 6
 * @package ost_sys
 */
if (isset($init_page) == false) {
    die();
}

/**
 * 初始化变量
 * @since 6
 */
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$max = 9999;
$sort = 0;
$desc = true;

/**
 * 题库类型数组
 * @since 2
 */
require(DIR_LIB.DS.'plug-banktype.php');
$bank_type = pluggetbank($oaconfig);
?>
<!-- HTML -->
<h2>考试中心</h2>
<div class="row">
    <div class="span4 offset1" id="time_id">
        <h4>选择专业科目 | 选择题库</h4>
    </div>
</div>
<div class="row">
    <div class="span4 offset1" id="select_input">
        <select name="select_bank_type" class="input-small">
            <?php
            foreach($bank_type as $k=>$v){
                //判断科目下是否存在题库
                $bank_list = $oapost->view_list(null, null, null, 'public', 'bank', 1, 1, 0, false, null, $k);
                if($bank_list){
             ?>
            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
            <?php
                }
                $bank_list = null;
            }
            ?>
        </select>
        <select name="select_bank" class="input-medium"></select>
        <button type="submit" class="btn btn-primary btn-large" id="button_start"><i class="icon-ok icon-white"></i> 开始考试</button>
    </div>
</div>
<hr>
<form action="<?php echo $page_url; ?>" method="post"></form>

<!-- Javascript -->
<script>
    //提交答卷
    function test_submit(){
         //初始化相关数据
        $("form").data("submit","2");
        $("form").data("submit-data",new Array());
        //遍历所有input[class=hidden]组件
        $("form").find("input[class='hidden']").each(function(){
            if($(this).attr("value") === ""){
                //如果该题目没有填写内容
                $("form").data("submit","1");
                return;
            }else{
                var arr = new Array($(this).attr("name").substr(6),$(this).attr("value"));
                var data_arr = $("form").data("submit-data");
                data_arr.push(arr);
                $("form").data("submit-data",data_arr);
            }
        });
        if($("form").data("submit") === "2" && $("form").data("submit-data") !== ""){
            if($("form").data("ajax") == "1"){
                $("form").data("ajax","0");
                $.post($("form").data("url"),{
                    "subject":$("form").data("submit-data")
                },function(data){
                    if(data){
                        $("form").html("<p>试卷总分值："+data['status'][0]+"</p><p>本次考试得分："+data['status'][1]+"</p>");
                    }
                    //复位相关数据
                    $("form").data("ajax","1");
                    $("form").data("submit-data","");
                    //删除顶部选单
                    clearTimeout(time_handle);
                    $("#select_input").remove();
                    $("#time_id").remove();
                },"json");
            }
        }
        msg($("form").data("submit"),"试卷提交成功。","答案填写不完整，无法交卷。");
    }
    //计时器
    var time_i;
    var time_handle;
    var time_start;
    function time_next() {
        time_i.setTime(time_i.getTime()-1000);
        $("#time_id").html("<h4>考试剩余时间：" + time_i.getHours()+":"+time_i.getMinutes()+":"+time_i.getSeconds()+ "</h4>");
        clearTimeout(time_handle);
        if(time_start - time_i.getTime() >= Math.abs($("#time_id").data("length"))){
            test_submit();
        }else{
            time_handle = setTimeout("time_next()", 1000);
        }
    }
    //选择题库类型动作
    function select_bank_type(v){
        if($("select[name='select_bank_type']").data("ajax") == "1"){
            $("select[name='select_bank_type']").data("ajax","0");
            var url = "ajax_bank_list.php?type="+v;
            $.get(url,function(data){
                $("select[name='select_bank']").html("");
                if(data){
                    $(data["status"]).each(function(v){
                        $("select[name='select_bank']").append('<option value="'+data["status"][v]["id"]+'">'+data["status"][v]["post_title"]+'</option>');
                    });
                    $("select[name='select_bank_type']").data("ajax","1");
                }
            },"json");
        }
    }
    //开始执行脚本
    $(document).ready(function() {
        //试卷处理ajax-url
        $("form").data("url","ajax_subject_html.php");
        //试卷处理ajax锁定
        $("form").data("ajax","1");
        //考试状态标识
        $("form").data("test","0");
        //考试时长(秒)
        $("#time_id").data("length", <?php echo 3600*2000; ?>);
        //开始考试按钮
        $("#button_start").click(function() {
            if($("form").data("test") == "0"){
                $("form").html("正在加载试卷...");
                $("form").data("ajax","0");
                $.get($("form").data("url")+"?bank="+$("select[name='select_bank']").attr("value"),function(data){
                    $("form").data("test","1");
                    var html = "";
                    if(data["error"] == "success"){
                        html = data["status"];
                        //执行计时器
                        time_i = new Date("0","0","0","0","0","0");
                        time_i.setMilliseconds(Math.abs($("#time_id").data("length")));
                        time_start = time_i.getTime();
                        clearTimeout(time_handle);
                        time_next();
                        //替换掉相关内容
                        $("#button_start").html('<i class="icon-ok icon-white"></i> 交卷');
                        $("#select_input > select").fadeOut();
                        //替换HTML
                        $("form").html(html);
                        //绑定组建事件
                        $("form").find("input[type='radio']").change(function(){
                            $("form > input[name='"+$(this).attr("name")+"'][class='hidden']").attr("value",$(this).attr("value"));
                        });
                        $("form").find("input[type='checkbox']").change(function(){
                            $("form").data("ls","");
                            $("form").find("input[name='"+$(this).attr("name")+"'][type='checkbox']:checked").each(function(){
                                $("form").data("ls",$("form").data("ls")+"|"+$(this).attr("value"));
                            });
                            $("form > input[name='"+$(this).attr("name")+"'][class='hidden']").attr("value",$("form").data("ls").substr(1));
                        });
                        $("form").find("textarea").keyup(function(){
                            $("form > input[name='"+$(this).attr("name")+"'][class='hidden']").attr("value",$(this).attr("value"));
                        });
                    }else{
                        html = "试卷没有考题，请等待老师录入信息。";
                        $("form").html(html);
                        $("form").data("test","0");
                    }
                    $("form").data("ajax","1");
                },"json");
            }else{
                test_submit();
            }
        });
        //选择题库ajax锁定
        $("select[name='select_bank_type']").data("ajax","1");
        //选择题库列表动作绑定
        $("select[name='select_bank_type']").change(function(){
            select_bank_type($(this).attr("value"));
        });
        //初始化题库列表
        if($("select[name='select_bank_type']").html() != ""){
            select_bank_type($("select[name='select_bank_type']").children("option").first().attr("value"));
        }
    });
</script>
