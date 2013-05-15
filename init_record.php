<?php
/**
 * 考试记录管理
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
 * 操作考试记录内容
 * @since 1
 */
$message = '';
$message_bool = false;


/**
 * 删除考试记录
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {
        if ($del_view['post_status'] == 'public' && $del_view['post_type'] == 'record_b') {
            if ($oapost->del($_GET['del'])) {
                $message = '删除考试记录成功！';
                $message_bool = true;
            } else {
                $message = '无法删除该考试记录，删除失败。';
                $message_bool = false;
            }
        } else {
            $message = '无法删除该考试记录，该考试记录不存在。';
            $message_bool = false;
        }
    } else {
        $message = '无法删除该考试记录，该考试记录不存在。';
        $message_bool = false;
    }
}

/**
 * 获取考试记录列表记录数
 * @since 3
 */
$message_list_row = $oapost->view_list_row(null, null, null, 'public', 'record_b', null, '');

/**
 * 计算页码
 * @since 1
 */
$page_max = ceil($message_list_row / $max);
if ($page < 1) {
    $page = 1;
} else {
    if ($page > $page_max) {
        $page = $page_max;
    }
}
$page_prev = $page - 1;
$page_next = $page + 1;

/**
 * 获取考试记录列表
 * @since 3
 */
$message_list = $oapost->view_list(null, null, null, 'public', 'record_b', $page, $max, $sort, $desc, null, '');
?>
<!-- 管理表格 -->
<h2>考试记录中心</h2>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-user"></i> 用户名</th>
            <th><i class="icon-calendar"></i>时间</th>
            <th><i class="icon-comment"></i> 题库名</th>
            <th><i class="icon-tag"></i> 分数</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
    <tbody id="message_list">
        <?php if($message_list){ foreach($message_list as $v){ ?>
        <tr>
            <td><?php 
            $user_list= $oauser->view_user($v['post_user']);
            if($user_list){  ?>
            <?php echo $user_list['user_username']; ?><?php } ?>
            <td><?php echo $v['post_date']; ?></td>
            <td><?php 
                $bank_list= $oapost->view($v['post_parent']);
            if($bank_list){  ?>
            <?php echo $bank_list['post_title']; ?><?php }  ?></td>
            <td><?php echo $v['post_url']; ?></td>
            <td><div class="btn-group"><a href="<?php echo $page_url;?>&del=<?php echo $v['id']; ?>" class="btn btn-danger"><i class="icon-trash icon-white"></i> 删除</a></div></td>
        </tr>
        <?php }  }?>
    </tbody>
</table>

<!-- 页码 -->
<ul class="pager">
    <li class="previous<?php if($page<=1){ echo ' disabled'; } ?>">
        <a href="<?php echo $page_url.'&page='.$page_prev; ?>">&larr; 上一页</a>
    </li>
    <li class="next<?php if($page>=$page_max){ echo ' disabled'; } ?>">
        <a href="<?php echo $page_url.'&page='.$page_next; ?>">下一页 &rarr;</a>
    </li>
</ul>

<?php
if (isset($_GET['view']) == false && isset($_GET['edit']) == false) {
    $send_user = '';
    if(isset($_GET['user']) == true){
        $send_user_view = $oauser->view_user((int)$_GET['user']);
        if($send_user_view){
            $send_user = $send_user_view['user_username'];
        }
    }
    ?>
    
        <?php
}
        if (isset($_GET['view']) == true) {
            $view_message = $oapost->view($_GET['view']);
            if ($view_message) {
                ?>
    <!-- 查看考试记录信息 -->
            <div id="view" class="form-actions">
                <p>
                    <strong><?php echo $view_message['post_title']; ?></strong>
                    <em>&nbsp;<?php echo $view_message['post_date']; ?> - <?php $message_user = $oauser->view_user($view_message['post_user']); if($message_user){ echo '<a href="init.php?init=4&user='.$message_user['id'].'" target="_self">'.$message_user['user_name'].'</a>'; unset($message_user); } ?></em>
                </p>
                <p>&nbsp;</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $view_message['post_content']; ?></p>
                <p>&nbsp;</p>
                <p><a href="<?php echo $page_url; ?>" role="button" class="btn"><i class="icon-arrow-left"></i> 返回</a></p>
            </div>
                <?php
            }
        }
        ?>

        <!-- Javascript -->
        <script>
            $(document).ready(function(){
                var message = "<?php echo $message; ?>";
                var message_bool = "<?php echo $message_bool ? '2' : '1'; ?>";
                if(message != ""){
                    msg(message_bool,message,message);
                }
            });
        </script>