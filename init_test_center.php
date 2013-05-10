 <?php 
/**
 * 考试中心
 * @author suhuiling
 * @version 1
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
$select_bank_arr = array('计算机','英语','政治','数学');
require_once(DIR_LIB . DS . 'oa-post.php');
require_once (DIR.LIB . DS .'plug-substr8.php' );
//require_once(DIR_LIB . DS . 'plug-subject.php');
 
$plugsubject = new plug-subject($sort, $db, $max, $_POST[$user]);

if (isset($_POST[$select_subject]) == true){
        $post_type = $select_bank_arr['$select_subject'];
}
 ?>
<h2>考试中心</h2>
<label class="control-label" for="new_name"><h4>选择专业科目　　　　　　选择题库　　　　　　　　　　　　　　　　　　　考试剩余时间：45:32</h4></label>
             <div class="bs-docs-example">
                <select name="select_subject">
                    <option>计算机</option>
                </select>
           
                <select name="select_bank">
                    <option>2009年综合408</option>
                </select>
                 <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 开始考试</button>　
                 
            </div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th>一、单选题（每题2分，共40题）</th>
        </tr>
       
    </thead>
</table>
