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
$page = 1;
$max = 10;
$sort = 7;
$desc = true;


/**
 * 获取所有题库列表
 */
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');

$bank = isset($_POST['select_bank'])?$_POST['select_bank']:0;
$ace_list = $oapost->view_list(null, null,null, 'private', 'record_b', $page, $max, $sort, $desc, $bank,  ''); 


?>
<h2>高手榜</h2>
<h4>选择题库</h4>
<form action=""  method="post" name="form1">
        <select name="select_bank" class="input-medium">
            <?php if($bank_list){ foreach($bank_list as $v){ ?><option value="<?php echo $v['id']; ?>"><?php echo $v['post_title']; ?></option><?php } } ?>
        </select>      
       <button class="btn btn-primary" id="button_view" onclick="javascript:query_order('form1');"><i class="icon-ok icon-white"></i> 查看榜单</button>
 </form>
 <table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-tag"></i>排名</th>
            <th><i class="icon-user"></i> 用户名</th>
            <th><i class="icon-comment"></i> 分数</th>
        </tr>
    </thead>
    <tbody id="ace_list">
        <?php $t=1;
        if($ace_list){ foreach($ace_list as $v){ ?>
        <tr>
            <td><?php echo $t++; ?></td>
            <td><?php 
            $user_list= $oauser->view_user($v['post_user']);
            if($user_list){  ?>
            <?php echo $user_list['user_username']; ?><?php } ?></td>
            <td><?php echo $v['post_order']; ?></td>
        </tr>
        <?php } } ?>
    </tbody>
</table>
 
   

<script>
   function query_order(form1)
   {
       $('form[name="'+form1+'"]').submit();
   }
</script>
    