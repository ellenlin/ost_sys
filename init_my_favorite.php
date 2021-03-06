<?php
/**
 * 我的收藏管理
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
 * 操作我的收藏内容
 * @since 1
 */
$message = '';
$message_bool = false;


/**
 * 删除我的收藏
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {
        if ($del_view['post_status'] == 'private' && $del_view['post_type'] == 'favorite') {
            if ($oapost->del($_GET['del'])) {
                $message = '删除我的收藏成功！';
                $message_bool = true;
            } else {
                $message = '无法删除该我的收藏，删除失败。';
                $message_bool = false;
            }
        } else {
            $message = '无法删除该我的收藏，该我的收藏不存在。';
            $message_bool = false;
        }
    } else {
        $message = '无法删除该我的收藏，该我的收藏不存在。';
        $message_bool = false;
    }
}

/**
 * 获取我的收藏列表记录数
 * @since 3
 */
$favorite_list_row = $oapost->view_list_row(null, null, null, 'private', 'favorite', null, '');

/**
 * 计算页码
 * @since 1
 */
$page_max = ceil($favorite_list_row / $max);
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
 * 获取我的收藏列表
 * @since 3
 */
$favorite_list = $oapost->view_list(null, null, null, 'private', 'favorite', $page, $max, $sort, $desc, null, '');
?>
<!-- 管理表格 -->
<h2>我的收藏</h2>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-calendar"></i>时间</th>
            <th><i class="icon-comment"></i> 题目名</th>
            <th><i class="icon-tag"></i> 答案</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
    <tbody id="favorite_list">
        <?php if($favorite_list){ foreach($favorite_list as $v){ ?>
        <tr>
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