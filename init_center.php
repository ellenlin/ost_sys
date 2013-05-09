<?php
/**
 * 个人首页
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
if (isset($init_page) == false) {
    die();
}

/**
 * 操作消息内容
 * @since 1
 */
$message = '';
$message_bool = false;

/**
 * 获取消息列表
 * @since 1
 */
$message_list = $oapost->view_list(null, null, null, 'private', 'message', 1, 6, 0, true, null, $post_user);

/**
 * 获取系统消息
 * @since 3
 */
$system_message_list = $oapost->view_list(null, null, null, 'public', 'message', 1, 1, 0, true, null, null);
$system_message_view = null;
if ($system_message_list) {
    $system_message_view = $oapost->view($system_message_list[0]['id']);
}
unset($system_message_list);

?>
<!-- 欢迎界面 -->
<?php if($system_message_view){ ?>
<h3>系统消息</h3>
<div class="hero-unit">
    <h1><?php echo $system_message_view['post_title']; ?></h1>
    <p><?php echo $system_message_view['post_content']; ?></p>
    <p><a class="btn btn-primary btn-large">了解详情</a></p>
</div>
<?php } ?>



<!-- Javascript -->
<script>
    $(document).ready(function() {
        var message = "<?php echo $message; ?>";
        var message_bool = "<?php echo $message_bool ? '2' : '1'; ?>";
        if (message != "") {
            msg(message_bool, message, message);
        }
    });
</script>