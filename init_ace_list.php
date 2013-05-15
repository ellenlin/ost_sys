<?php

/**
 * 高手榜
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
$sort = 12;
$desc = true;


/**
 * 获取所有题库列表
 */
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');


?>
<h2>高手榜</h2>
<h4>选择题库</h4>
    <div>
        <select name="select_bank" class="input-medium">
            <?php if($bank_list){ foreach($bank_list as $v){ ?><option value="<?php echo $v['id']; ?>"><?php echo $v['post_title']; ?></option><?php } } ?>
        </select>
        <button class="btn btn-primary" id="button_view"><i class="icon-ok icon-white"></i> 查看榜单</button>
        <?php if(isset($_POST['select_bank']) == true){?>
            <?php $ace_list=$oapost->view_list(null,null , null, 'public', 'record_b', 1, $max, $sort, $desc , (int)$_POST['select_bank'], '') ;?>
        <?php }?>  
            <?php if($ace_list){ foreach($ace_list as $v){ ?><?php echo $v['post_title']; ?><?php }}?>
    </div>

