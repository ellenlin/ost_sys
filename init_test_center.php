<?php
/**
 * 考试中心
 * @author suhuiling
 * @version 2
 * @package ost_sys
 */
if (isset($init_page) == false) {
    die();
}

/**
 * 初始化变量
 * @since 3
 */
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$max = 10;
$sort = 0;
$desc = true;


/**
 * 题库类型数组
 * @since 2
 */
$select_bank_arr = array('计算机', '英语', '政治', '数学');
require_once(DIR_LIB . DS . 'oa-post.php');
require_once(DIR_LIB . DS . 'plug-substrutf8.php');
//require_once(DIR_LIB . DS . 'plug-subject.php');
/*
  $plugsubject = new plugsubject($sort, $db, $max, $_POST[$user]);

  if (isset($_POST[$select_subject]) == true){
  $post_type = $select_bank_arr['$select_subject'];
  }
 */
?>
<!-- HTML -->
<h2>考试中心</h2>
<div class="row">
    <div class="span4" id="select_tip"><h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;选择专业科目&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;选择题库</h4></div>
    <div class="span3 offset2" id="time_id"></div>
</div>
<div class="bs-docs-example">
    <select name="select_subject">
        <option>计算机</option>
    </select>
    <select name="select_bank">
        <option>2009年综合408</option>
    </select>
    <button type="submit" class="btn btn-primary btn-large" id="button_start"><i class="icon-ok icon-white"></i> 开始考试</button>
</div>
<hr>
<form action="<?php echo $page_url; ?>" method="post">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>一、单选题（每题2分，共40题）</th>
            </tr>

        </thead>
    </table>
</form>

<!-- Javascript -->
<script>
    //考试状态标识
    var test_on = false;
    //提交答卷
    function test_submit(){
        $("form").submit();
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
    //开始执行脚本
    $(document).ready(function() {
        //考试时长(秒)
        $("#time_id").data("length", <?php echo 3600*1000; ?>);
        //开始考试按钮
        $("#button_start").click(function() {
            if(test_on == false){
                test_on = true;
                //执行计时器
                time_i = new Date("0","0","0","0","0","0");
                time_i.setMilliseconds(Math.abs($("#time_id").data("length")));
                time_start = time_i.getTime();
                clearTimeout(time_handle);
                time_handle = setTimeout("time_next()", 1000);
                //替换掉相关内容
                $("#button_start").html('<i class="icon-ok icon-white"></i> 交卷');
                $("#select_tip").html("");
                $("div[class='bs-docs-example'] > select").remove();
            }else{
                test_submit();
            }
        });
    });
</script>