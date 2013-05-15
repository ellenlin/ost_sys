<?php
/**
 * 考试中心
 * @author suhuiling
 * @version 4
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
 * 获取所有题库列表
 * @since 1
 */
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');


/**
 * 题库类型数组
 * @since 2
 */
require(DIR_LIB.DS.'plug-banktype.php');
$bank_type = pluggetbank($oaconfig);

/**
 * 政治题库题目说明数组
 */
$politics_explain_arr=array('一、单项选择题：1～16小题，每小题1分，共16分。下列每题给出的四个选项中，只有一个选项是符合题目要求的。请在答题卡上将所选项的字母涂黑。',
                           '二、多项选择题：17～33题，每小题2分，共34分。下列每题给出的四个选项中，至少有两个选项是符合题目要求的。请在答题卡上将所选项的字母涂黑。多选或少选均不得分。',
                           '三、分析题：34～38小题，每小题10分，共50分。要求结合所学知识分析材料并回答问题。将答案写在答题纸指定位置上。',
    );

/**
 * 引入题目操作模块
 * @since 4
 */
require_once(DIR_LIB . DS . 'oa-post.php');
require_once(DIR_LIB . DS . 'plug-substrutf8.php');
require_once(DIR_LIB . DS . 'plug-subject.php');

/**
 * 获取题目数据
 * @since 4
 */
$plugsubject = new plugsubject(3, $db, $ip_arr['id'], $post_user);
//$plugsubject->add_subject('题目测试2', '题目2内容', 0, 5);
//$plugsubject->edit_subject(6, '题目测试1修改', '判断题测试1', 1, 2, 15);
$question_html = $plugsubject->html_get();
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
        <select name="select_subject" class="input-small">
            <?php foreach($bank_type as $k=>$v){ ?>
            <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
            <?php } ?>
        </select>
        <select name="select_bank" class="input-medium">
            <?php if($bank_list){ foreach($bank_list as $v){ ?><option value="<?php echo $v['id']; ?>"><?php echo $v['post_title']; ?></option><?php } } ?>
        </select>
        <button type="submit" class="btn btn-primary btn-large" id="button_start"><i class="icon-ok icon-white"></i> 开始考试</button>
    </div>
</div>
<hr>
<form action="<?php echo $page_url; ?>" method="post">
    <?php echo $question_html; ?>
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
        $("#time_id").data("length", <?php echo 3600*2000; ?>);
        //开始考试按钮
        $("#button_start").click(function() {
            if(test_on == false){
                test_on = true;
                //执行计时器
                time_i = new Date("0","0","0","0","0","0");
                time_i.setMilliseconds(Math.abs($("#time_id").data("length")));
                time_start = time_i.getTime();
                clearTimeout(time_handle);
                time_next();
                //替换掉相关内容
                $("#button_start").html('<i class="icon-ok icon-white"></i> 交卷');
                $("#select_input > select").fadeOut();
            }else{
                test_submit();
            }
        });
    });
</script>
