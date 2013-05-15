<?php
/**
 *我的错题页面
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
 * 操作题库内容
 * @since 1
 */
$message = '';
$message_bool = false;

/**
 * 题库类型数组
 * @since 2
 */
$select_bank_arr = array('计算机','英语','政治','数学');

/**
 * 添加新的题库
 * @since 2
 */
if (isset($_POST['new_message']) == true && isset($_GET['new']) == true && isset($_POST['select']) == true) {
    if ($_POST['new_message']) {
        $title = '';
        //引入截取字符串模块
        require_once(DIR_LIB . DS . 'plug-substrutf8.php');
        $title = plugsubstrutf8($_POST['new_message'], 15);
        $post_name = 0;
        if (isset($select_bank_arr[(int) $_POST['select']]) == true) {
            $post_name = (int) $_POST['select'];
        }
        if ($oapost->add($title, null, 'bank', 0, null, null, $post_name, null, 'public', null)) {
            $message = '添加题库成功！';
            $message_bool = true;
        } else {
            $message = '无法添加新的题库。';
            $message_bool = false;
        }
    } else {
        $message = '题库名称不能为空。';
        $message_bool = false;
    }
}

/**
 * 编辑题库
 * @since 2
 */
if (isset($_POST['edit_title']) == true && isset($_POST['edit_name']) == true && isset($_GET['edit']) == true) {
    $edit_res = $oapost->view($_GET['edit']);
    if ($edit_res) {
        if ($_POST['edit_title']) {
            $title = '';
            //引入截取字符串模块
            require_once(DIR_LIB . DS . 'plug-substrutf8.php');
            $title = plugsubstrutf8($_POST['edit_title'], 15);
            $post_name = 0;
            if (isset($select_bank_arr[(int) $_POST['edit_name']]) == true) {
                $post_name = (int) $_POST['edit_name'];
            }
            if ($oapost->edit($edit_res['id'], $title, null, 'bank', 0, null, null, $post_name, null, 'public', null)) {
                $message = '编辑题库成功！';
                $message_bool = true;
            } else {
                $message = '无法编辑题库。';
                $message_bool = false;
            }
        } else {
            $message = '题库名称不能为空。';
            $message_bool = false;
        }
    } else {
        $message = '题库不存在。';
        $message_bool = false;
    }
}


/**
 * 删除题库
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {
        if ($del_view['post_status'] == 'public' && $del_view['post_type'] == 'bank') {
            if ($oapost->del($_GET['del'])) {
                $message = '删除题库成功！';
                $message_bool = true;
            } else {
                $message = '无法删除该题库，删除失败。';
                $message_bool = false;
            }
        } else {
            $message = '无法删除该题库，该题库不存在。';
            $message_bool = false;
        }
    } else {
        $message = '无法删除该题库，该题库不存在。';
        $message_bool = false;
    }
}

/**
 * 获取题库列表记录数
 * @since 3
 */
$message_list_row = $oapost->view_list_row(null, null, null, 'public', 'bank', null, '');

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
 * 获取题库列表
 * @since 3
 */
$message_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');
?>
<!-- 管理表格 -->
<h2>我的收藏</h2>


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

        if (isset($_GET['view']) == true) {
            $view_message = $oapost->view($_GET['view']);
            if ($view_message) {
                ?>
    <!-- 查看错题信息 -->
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
