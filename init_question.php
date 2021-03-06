<?php
/**
 * 题目管理
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
$bank = isset($_POST['select_bank'])?$_POST['select_bank']: 0;



/**
 * 引入题目操作模块
 * @since 4
 */
require_once(DIR_LIB . DS . 'oa-post.php');
require_once(DIR_LIB . DS . 'plug-substrutf8.php');
require_once(DIR_LIB . DS . 'plug-subject.php');



/**
 * 操作题目内容
 * @since 1
 */
$message = '';
$message_bool = false;

/**
 * 题目类型数组
 * @since 2
 */
$question_type = array('radio', 'check', 'boolean', 'content');
/**
 * 添加新的题目
 * @since 2
 */
if (isset($_GET['new']) == true && isset($_POST['select_bank']) == true && isset($_POST['select_type']) == true&& isset($_POST['add_content']) == true&& isset($_POST['add_ch_an']) == true && isset($_POST['add_score']) == true) {
    if ($_POST['add_content']) {
        $title = '';
        $title = $_POST['add_content'];
        $post_name = $_POST['select_type'];
        $post_parent = (int) $_POST['select_bank'];
        $post_content=$_POST['add_ch_an'];
        $post_order=$_POST['add_score'];
       
        if ($oapost->add($title, $post_content, 'question', $post_parent , null, null, $post_name, $post_order, 'public', null)) {
            $message = '添加题目成功！';
            $message_bool = true;
        } else {
            $message = '无法添加新的题目。';
            $message_bool = false;
        }
    } else {
        $message = '题目名称不能为空。';
        $message_bool = false;
    }
    }


/**
 * 编辑题目
 * @since 2
 */
if (isset($_GET['edit']) == true && isset($_POST['edit_bank']) == true && isset($_POST['edit_type']) == true&& isset($_POST['edit_content']) == true&& isset($_POST['edit_ch_an']) == true && isset($_POST['edit_score']) == true) {
    $edit_res = $oapost->view($_GET['edit']);
    if ($edit_res) {
        if ($_POST['edit_content']) {
            $title = '';
            $title = $_POST['edit_content'];
            $post_name = $_POST['edit_type'];
            $post_parent = (int) $_POST['edit_bank'];
            $post_content=$_POST['edit_ch_an'];
            $post_order=$_POST['edit_score'];
            if ($oapost->edit($edit_res['id'], $title, $post_content, 'question', $post_parent , null, null, $post_name, $post_order, 'public', null)) {
                $message = '编辑题目成功！';
                $message_bool = true;
            } else {
                $message = '无法编辑题目。';
                $message_bool = false;
            }
        } else {
            $message = '题目名称不能为空。';
            $message_bool = false;
        }
    } else {
        $message = '题目不存在。';
        $message_bool = false;
    }
}


/**
 * 删除题目
 * @since 3
 */
if (isset($_GET['del']) == true) {
    $del_view = $oapost->view($_GET['del']);
    if ($del_view) {
        if ($del_view['post_status'] == 'public' && $del_view['post_type'] == 'question') {
            if ($oapost->del($_GET['del'])) {
                $message = '删除题目成功！';
                $message_bool = true;
            } else {
                $message = '无法删除该题目，删除失败。';
                $message_bool = false;
            }
        } else {
            $message = '无法删除该题目，该题目不存在。';
            $message_bool = false;
        }
   
    }
}

/**
 * 获取题目列表记录数
 * @since 3
 */
$question_list_row = $oapost->view_list_row(null, null, null, 'public', 'question', null, '');

/**
 * 计算页码
 * @since 1
 */
$page_max = ceil($question_list_row / $max);
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
 * 获取题目列表
 * @since 3
 */
//$question_list = $oapost->view_list(null, null, null, 'public', 'question', $page, $max, $sort, $desc, null, '');


/**
 * 获取所有题库列表
 */
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');


?>
<!-- 管理表格 -->
<h2>题目中心</h2>
<h5>选择题库</h5>
<form action="" method="post" name="form1">
<select name="select_bank" class="input-medium">
    <?php if($bank_list){ foreach($bank_list as $v){ ?><option value="<?php echo $v['id']; ?>"><?php echo $v['post_title']; ?></option><?php } } ?>
</select>
    <button class="btn btn-primary" id="button_view" onclick="javascript:query_order('form1');"><i class="icon-ok icon-white"></i> 查看该题库下题目</button>
</form>
<?php 
    $plugsubject = new plugsubject($bank, $db, $ip_arr['id'], $post_user);
    $question_list = $plugsubject->view_subject_list($page , $max , $sort, $desc, null);?>
<table class="table table-hover table-bordered table-striped">
    <thead>
        <tr>
            <th><i class="icon-calendar"></i> 时间</th>
            <th><i class="icon-pencil"></i>所属题库</th>
            <th><i class="icon-tag"></i>题目</th>
            <th><i class="icon-comment"></i> 答案</th>
            <th><i class="icon-asterisk"></i> 操作</th>
        </tr>
    </thead>
    <tbody id="question_list">
        <?php if($question_list){ foreach($question_list as $v){ ?>
        <tr>
            <td><?php echo $v['post_date']; ?></td>
            <td><?php 
                $bank_list1= $oapost->view($v['post_parent']);
            if($bank_list1){  ?>
            <?php echo $bank_list1['post_title']; ?><?php }  ?></td>
            <td><?php $a = explode('||',$v['post_content']);
                        echo $v['post_title'];?>
                <br>
                <?php
                        $choose = $plugsubject->view($v['id']);
                            echo $choose;
                        ?></td>
            <td><?php 
                       $answer_put=$plugsubject->view_answer($a[1],$v['post_name']); 
                       echo $answer_put;?></td>
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
if (isset($_GET['view']) == false && isset($_GET['edit']) == false) {
    $send_user = '';
    if(isset($_GET['user']) == true){
        $send_user_view = $oauser->view_user((int)$_GET['user']);
        if($send_user_view){
            $send_user = $send_user_view['user_username'];
        }
    }
    ?>
    <!-- 添加题目 -->
    <h2 id="send">添加题目</h2>
    <form action="<?php echo $page_url; ?>&new=1" method="post" class="form-actions">
        <div class="control-group">
            <label class="control-label" for="select_bank">选择题库</label>
            <div class="bs-docs-example">
               <select name="select_bank" class="input-medium">
                <?php if($bank_list){ foreach($bank_list as $v){ ?>
                   <option value="<?php echo $v['id']; ?>"><?php echo $v['post_title']; ?></option><?php } } ?>
               </select>
            <label class="control-label" for="select_tyoe">选择题目类型</label>
            <div class="bs-docs-example">
               <select name="select_type" class="input-medium">
                <?php $question_type_ch=array('单选题','多选题','判断题','问答题');
                    foreach($question_type_ch as $k=>$v){ ?>
                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                    <?php } ?>
               </select>
            </div>
            <div class="control-group">
            <label class="control-label" for="add_score">分值</label>
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="1" id="new_question" name="add_score" placeholder="分值"></textarea>
                </div>
            </div>
        </div>
         <div class="control-group">
            <label class="control-label" for="add_content">题目内容</label>
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="3" id="edit_content" name="add_content" placeholder="题目内容"></textarea>
                </div>
            </div>
        </div>
             <div class="control-group">
            <label class="control-label" for="add_ch_an">选项及答案</label>
            <label>格式：单选：选项1|选项2|选项3|选项4||答案（数字表示）</label>
            <label>多选：选项1|选项2|选项3|选项4||答案1（数字表示）|答案2|..</label>
           <!-- <label>判断：1（正确） 0（错误）</label>
            <label>问答：答案</label>-->
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="3" id="edit_content" name="add_ch_an" placeholder="选项及答案"></textarea>
                </div>
            </div>
        </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 添加</button>
            </div>
        </div>
    </form>

        <?php
}
if (isset($_GET['edit']) == true && isset($_GET['view']) == false && isset($_GET['new']) == false) {
    $edit_message = $oapost->view($_GET['edit']);
    if ($edit_message) {
        ?>
    <!-- 编辑题目信息 -->
            <div id="edit">
                <h2>编辑题目信息</h2>
                <p>编辑题目信息。</p>
                <form action="<?php echo $page_url.'&edit='.$edit_message['id']; ?>" method="post" class="form-actions">
                    <div class="control-group">
            <label class="control-label" for="edit_bank">选择题库</label>
            <div class="bs-docs-example">
               <select name="edit_bank" class="input-medium">
                <?php if($bank_list){ foreach($bank_list as $v){ ?>
                      <option value="<?php echo $v['id']; ?>"<?php if($v['post_title']== $edit_message['post_parent']){ echo 'selected'; }?>><?php echo $v['post_title']; ?></option>
                <?php } } ?>
               </select>
            <label class="control-label" for="edit_tyoe">选择题目类型</label>
            <div class="bs-docs-example">
               <select name="edit_type" class="input-medium">
                <?php $question_type_ch=array('单选题','多选题','判断题','问答题');
                        foreach($question_type_ch as $k=>$v){ ?>
                                <option value="<?php echo $k; ?>" <?php if($k == $edit_message['post_name']){ echo 'selected'; } ?>><?php echo $v; ?></option>
                                <?php } ?>
               </select>
            </div>
            <div class="control-group">
            <label class="control-label" for="edit_score">分值</label>
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="1" id="new_question" name="edit_score" placeholder="分值"><?php echo $edit_message['post_url']; ?></textarea>
                </div>
            </div>
        </div>
         <div class="control-group">
            <label class="control-label" for="edit_content">题目内容</label>
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="3" id="edit_content" name="edit_content" placeholder="题目内容"><?php echo $edit_message['post_title']; ?></textarea>
                </div>
            </div>
        </div>
             <div class="control-group">
            <label class="control-label" for="edit_ch_an">选项及答案</label>
            <label>格式：单选：选项1|选项2|选项3|选项4||答案（数字表示）</label>
            <label>多选：选项1|选项2|选项3|选项4||答案1（数字表示）|答案2|..</label>
           <!-- <label>判断：1（正确） 0（错误）</label>
            <label>问答：答案</label>-->
            <div class="controls">
                <div class="input-prepend">
                    <textarea rows="3" id="edit_content" name="edit_ch_an" placeholder="选项及答案"><?php echo $edit_message['post_content']; ?></textarea>
                </div>
            </div>
        </div>
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> 修改</button>
                            <a href="<?php echo $page_url; ?>" role="button" class="btn"><i class="icon-remove"></i> 取消</a>
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
    <!-- 查看题目信息 -->
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
  
   function query_order(form1)
   {
       $('form[name="'+form1+'"]').submit();
   }

       </script>