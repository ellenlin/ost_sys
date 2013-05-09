<?php
/**
 * 题库页面
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
 * 添加新的题库
 * @since 1
 */
if (isset($_POST['select']) == true) {
    $post_name='';
    if($_POST['select']==1){
         $post_name='计算机';
    }else if($_POST['select']==2){
         $post_name='英语';
    }else if($_POST['select']==3){
         $post_name='政治';
    }else{
         $post_name='数学';
    }
}
if (isset($_POST['new_message']) == true&&$_POST['new_message']) {
    $title = '';
    //引入截取字符串模块
    require_once(DIR_LIB . DS . 'plug-substrutf8.php');
    if (isset($_POST['new_title']) == true && $_POST['new_title']) {
        $title = plugsubstrutf8($_POST['new_title'], 15);
    } else {
        $title = plugsubstrutf8($_POST['new_message'], 30);
    }
    
    if ($oapost->add($title, null, 'bank', 0, null, null, $post_name, null, 'public', null)) {
        $message = '添加题库成功！';
        $message_bool = true;
    } else {
        $message = '无法添加新的题库。';
        $message_bool = false;
    }
}else{
     $message = '题库名称不能为空。';
     $message_bool = false;
}


/**
 * 删除题库
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {
        if ($del_view['post_status'] == 'public' && $del_view['post_type'] == 'bank' ) {
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
$message_list_row = $oapost->view_list_row(null, null, null, 'public', 'bank',null,'');

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
<h2>题库中心</h2>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-calendar"></i> 时间</th>
            <th><i class="icon-tag"></i>专业科目</th>
            <th><i class="icon-comment"></i> 名称</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
    <tbody id="message_list">
        <?php if($message_list){ foreach($message_list as $v){ ?>
        <tr>
            <td><?php echo $v['post_date']; ?></td>
            <td><?php echo $v['post_name']; ?></td>
            <td><?php echo $v['post_title']; ?></td>
            <td><div class="btn-group"><a href="<?php echo $page_url;?>&edit=<?php echo $v['id']; ?>#edit" role="button" class="btn"><i class="icon-pencil"></i> 编辑</a><a href="<?php echo $page_url;?>&del=<?php echo $v['id']; ?>" class="btn btn-danger"><i class="icon-trash icon-white"></i> 删除</a></div></td>
            
        </tr>
        <?php } } ?>
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
if (isset($_GET['view']) == false) {
    $send_user = '';
    if(isset($_GET['user']) == true){
        $send_user_view = $oauser->view_user((int)$_GET['user']);
        if($send_user_view){
            $send_user = $send_user_view['user_username'];
        }
    }
    ?>
    <!-- 添加题库 -->
    <h2 id="send">添加题库</h2>
    <form action="<?php echo $page_url; ?>" method="post" class="form-actions">
        <div class="control-group">
            <label class="control-label" for="new_name">专业科目</label>
            <div class="bs-docs-example">
                <select name="select">
                    <option value="1" selected>计算机</option>
                    <option value="2">英语</option>
                    <option value="3">政治</option>
                    <option value="4">数学</option>
                </select>
            </div>
            <label class="control-label" for="new_message">名称</label>
            <div class="controls">
                <textarea rows="2" id="new_message" name="new_message" placeholder="名称"></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 添加</button>
            </div>
        </div>
    </form>

        <?php
}
if (isset($_GET['edit']) == true && isset($_GET['view']) == false) {
    $edit_message = $oapost->view($_GET['edit']);
    if ($edit_message) {
        ?>
        <!-- 编辑题库信息 -->
        <div id="edit">
            <h2>编辑题库信息</h2>
            <p>编辑题库信息。</p>
             <form action="<?php echo $page_url; ?>" method="post" class="form-actions">
        <div class="control-group">
            <label class="control-label" for="new_name">专业科目</label>
            <div class="bs-docs-example">
                <select name="select">
                    <option value="1" selected>计算机</option>
                    <option value="2">英语</option>
                    <option value="3">政治</option>
                    <option value="4">数学</option>
                </select>
            </div>
            <label class="control-label" for="new_message">名称</label>
            <div class="controls">
                <textarea rows="2" id="new_message" name="new_message" placeholder="名称"></textarea>
            </div>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 修改</button>
                        <a href="<?php echo $page_url.'&edit='.$_GET['edit']; ?>" role="button" class="btn"><i class="icon-remove"></i> 取消</a>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }
}
        if (isset($_GET['view']) == true) {
            $view_message = $oapost->view($_GET['view']);
            if ($view_message) {
                ?>
                <!-- 编辑题库信息 -->
                <div id="view" class="form-actions">
                    <p><strong><?php echo $view_message['post_title']; ?></strong><em>&nbsp;<?php echo $view_message['post_date']; ?> - <?php $message_user = $oauser->view_user($view_message['post_user']); if($message_user){ echo '<a href="init.php?init=4&user='.$message_user['id'].'" target="_self">'.$message_user['user_name'].'</a>'; unset($message_user); } ?></em></p>
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