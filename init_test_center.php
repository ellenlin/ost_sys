 <?php 
/**
 * 考试中心
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
 
 require_once(DIR_LIB . DS. 'oa-post.php');


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

require(DIR_LIB . DS . 'plug-subject.php');

html_put();


 ?>
<h2>考试中心</h2>
<label class="control-label" for="new_name"><h4>选择专业科目　　　　　　选择题库</h4></label>
             <div class="bs-docs-example">
                <select name="select">
                    <?php foreach($select_bank_arr as $k=>$v){ ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                    <?php } ?>
                </select>
           
                <select>
                    
                </select>
            </div>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-calendar"></i> 时间</th>
            <th><i class="icon-tag"></i>专业科目</th>
            <th><i class="icon-comment"></i> 分数</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
</table>
