<?php

/**
 * 题目操作类
 * <p>需要：oa-post类、plug-substrutf8模块</p>
 * @author fotomxq <fotomxq.me>
 * @version 6
 * @package PlugSubject
 */
class plugsubject extends oapost {

    /**
     * 题目标识
     * @since 1
     * @var string 
     */
    private $post_type = 'question';

    /**
     * 用户ID
     * @since 1
     * @var int 
     */
    private $user_id;

    /**
     * 题目类型标识数组
     * <p>0-radio-单选 ; 1-check-多选 ; 2-boolean-判断 ; 3-content-问答</p>
     * @since 1
     * @var array 
     */
    public $question_type = array('radio', 'check', 'boolean', 'content');

    /**
     * 选项匹配数组
     * @since 1
     * @var array
     */
    public $question_select_arr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    /**
     * 选项分隔符
     * @since 1
     * @var string 
     */
    public $delimiter = '|';

    /**
     * 内容分隔符(内容,答案)
     * @since 1
     * @var string 
     */
    public $delimiter_content = '||';

    /**
     * 题库ID
     * @since 1
     * @var int 
     */
    public $bank_id;

    /**
     * 构建
     * @since 1
     * @param int $bank_id 所属题库ID
     * @param coredb $db 数据库操作句柄
     * @param int $ip_id IP ID
     * @param int $user_id 用户ID
     */
    public function __construct($bank_id, &$db, $ip_id, $user_id) {
        parent::__construct($db, $ip_id);
        $this->user_id = $user_id;
        if ($this->check_bank_type($bank_id) == true) {
            $this->bank_id = $bank_id;
        } else {
            $this->bank_id = 0;
        }
    }

    /**
     * 查询题目列表
     * @since 2
     * @param int $page 页数
     * @param type $max 页长
     * @param int $sort 排序字段
     * @param boolean $desc 排序方式
     * @param int $user 筛选用户ID
     * @return null|array
     */
    public function view_subject_list($page = 1, $max = 10, $sort = 7, $desc = true, $user = null) {
        return parent::view_list($user, null, null, 'public', $this->post_type, $page, $max, $sort, $desc, $this->bank_id);
    }

    /**
     * 输出当前题库所有题目HTML
     * <p>需要配合bootstrap才能达到最佳效果</p>
     * <p>仅生成表单组件，表单需要提前在页面中定义</p>
     * @since 5
     * @return string
     */
    public function html_get() {
        $return = '';
        $res = $this->view_subject_list(1, 999, 7, true, null);
        if ($res) {
            $question_id = 1;
            foreach ($res as $v) {
                $v_res = parent::view($v['id']);
                $content_arr = $this->question_get_arr($v_res['post_content']);
                $html_name = 'input_' . $v['id'];
                $input_html = '<input class="hidden" name="' . $html_name . '" value="">';
                $return .= $input_html . '<dl><dt>第' . $question_id . '题 - ' . $v_res['post_title'] . '</dt><dd>';
                switch ($v_res['post_name']) {
                    case 'radio':
                        $v_q_arr = $this->question_select_get_arr($content_arr[0]);
                        foreach ($v_q_arr as $k => $v) {
                            $return .= '<label class="radio"><input type="radio" name="' . $html_name . '" value="' . $k . '">' . $this->question_select_arr[$k] . '、' . $v . '</label>';
                        }
                        break;
                    case 'check':
                        $v_q_arr = $this->question_select_get_arr($content_arr[0]);
                        foreach ($v_q_arr as $k => $v) {
                            $return .= '<label class="checkbox"><input type="checkbox" name="' . $html_name . '" value="' . $k . '">' . $this->question_select_arr[$k] . '、' . $v . '</label>';
                        }
                        $return .= '';
                        break;
                    case 'boolean':
                        $return .= '<label class="radio"><input type="radio" name="' . $html_name . '" value="1">正确</label><label class="radio"><input type="radio" name="' . $html_name . '" value="0">错误</label>';
                        break;
                    case 'content':
                    default:
                        $return .= '<p>' . $content_arr[0] . '</p><p><textarea name="' . $html_name . '" rows="3" placeholder="答案..."></textarea></p>';
                        break;
                }
                $return .= '</dd></dl>';
                $question_id++;
                $v_res = null;
            }
        }
        return $return;
    }

    /**
     * 分析并记录考试结果
     * @since 6
     * @param array $data 结果数据数组
     * @return int 考试得分
     */
    public function html_put($data) {
        $return = 0;
        $bank_fraction = 0;
        $record_question_type = 'record_q';
        $record_bank_type = 'record_b';
        $post_status = 'private';
        //添加试卷记录
        $bank_res = parent::view($this->bank_id);
        $record_bank_id = $this->add($bank_res['post_title'], $bank_res['post_content'], $record_bank_type, $this->bank_id, $this->user_id, null, null, null, $post_status, null);
        if ($record_bank_id > 0) {
            foreach ($data as $v) {
                $v_res = parent::view($v[0]);
                if ($v_res) {
                    $ls_contents = $this->question_get_arr($v_res['post_content']);
                    //该题目得分分值，0表明没有得分
                    $post_order = 0;
                    //如果回答正确
                    if ($ls_contents[1] == $v[1]) {
                        $bank_fraction += $v_res['post_url'];
                        $post_order = $v_res['post_url'];
                    }
                    //添加题目记录
                    $record_question_id = $this->add($v_res['post_title'], $v_res['post_content'], $record_question_type, $record_bank_id, $this->user_id, null, $v_res['post_name'], $v[1], $post_status, null);
                    if ($record_question_id > 0) {
                        $this->edit_order($record_question_id, $post_order);
                    }
                }
            }
            //修改试卷得分
            $this->edit_order($record_bank_id, $bank_fraction);
            $return = $bank_fraction;
        }
        return $return;
    }

    /**
     * 获取题目内容
     * @since 5
     * @param int $id ID
     * @return null
     */
    public function view($id) {
        $return = null;
        $res = parent::view($id);
        $contents = $this->question_get_arr($res['post_content']);
        $content = '';
        $answer = '';
        switch ($res['post_type']) {
            case 'radio':
                $ls_contents = $this->question_select_get_arr($contents[0]);
                foreach ($ls_contents as $v) {
                    $content .= '<p>' . $v . '</p>';
                }
                break;
            case 'check':
                $ls_contents = $this->question_select_get_arr($contents[0]);
                foreach ($ls_contents as $v) {
                    $content .= '<p>' . $v . '</p>';
                }
                break;
            case 'boolean':
                $content = $res['post_title'];
                break;
            default:
                $content = $contents[0];
                break;
        }
        $answer = $this->view_answer($contents[1], $res['post_name']);
        $res['content_html'] = '<p>' . $content . '</p><p>答案：' . $answer . '</p>';
        $return = $res;
        return $return;
    }

    /**
     * 显示答案部分
     * @since 1
     * @param string $answer 答案原始数据
     * @param string $type 题目类型
     * @return string
     */
    function view_answer($answer, $type) {
        switch ($type) {
            case 'radio':
                return $this->question_select_arr[$answer];
                break;
            case 'check':
                $answers = $this->question_select_get_arr($answer);
                $return = '';
                foreach ($answers as $v) {
                    $return .= ',' . $v;
                }
                return substr($return, 1);
                break;
            case 'boolean':
                if ($answer) {
                    return '正确';
                } else {
                    return '错误';
                }
                break;
            default:
                return $answer;
                break;
        }
    }

    /**
     * 计算试卷总分值
     * @since 5
     * @return int
     */
    public function sum_bank_fraction() {
        $return = 0;
        $questions = $this->view_subject_list(1, 9999, 0, false);
        if ($questions) {
            foreach ($questions as $v) {
                $return += (int) $v['post_url'];
            }
        }
        return $return;
    }

    /**
     * 添加新的题目
     * <p>单选题实例：$plugsubject->add_subject('单选题标题',array('选项A','选项B','选项C'),0,0,5);</p>
     * <p>多选题实例：$plugsubject->add_subject('多选题标题',array('选项A','选项B','选项C'),array(0,1),1,5);</p>
     * <p>判断题实例：$plugsubject->add_subject('判断题标题','判断题内容',1,2,5);</p>
     * <p>问答题实例：$plugsubject->add_subject('问答题标题','问答题内容','问答题答案',3,5);</p>
     * @since 2
     * @param string $title 标题
     * @param string|array $content 内容
     * <p>选择题必须提交数组</p>
     * @param string|array $answer 答案
     * <p>*选择题对应$content数组键值，如上例中正确答案为A，则参数为0</p>
     * <p>*多选题必须提交数组，如上例中正确答案为A和B，则参数为array(0,1)</p>
     * <p>*问答题提交1表明正确答案为正确，反之提交0</p>
     * @param string $type 题目类型
     * <p>*0-单选题</p>
     * <p>*1-多选题</p>
     * <p>*2-判断题</p>
     * <p>*3-问答题</p>
     * @param int $fraction 分数
     * @return int 记录ID
     */
    public function add_subject($title, $content, $answer, $type, $fraction) {
        $post_content = $this->get_content($content, $answer, $type);
        return parent::add($title, $post_content, $this->post_type, $this->bank_id, $this->user_id, null, $this->question_type($type), $fraction, 'public', null);
    }

    /**
     * 编辑题目
     * <p>具体参考添加新题目方法，除$id参数外其他参数均相同。</p>
     * @since 2
     * @param int $id 题目ID
     * @param string $title 标题
     * @param string $content 内容
     * @param string $answer 答案
     * @param int $type 题目类型
     * @param int $fraction 分数
     * @return boolean
     */
    public function edit_subject($id, $title, $content, $answer, $type, $fraction) {
        $return = false;
        $view_res = parent::view($id);
        if ($view_res) {
            if ($view_res['post_type'] == $this->post_type) {
                $post_content = $this->get_content($content, $answer, $type);
                $return = parent::edit($view_res['id'], $title, $post_content, $this->post_type, $view_res['post_parent'], $view_res['post_user'], null, $this->question_type($type), $fraction, $view_res['post_status'], null);
            }
        }
        return $return;
    }

    /**
     * 删除题库下所有题目
     * @since 1
     * @param int $bank_id 题库ID
     * @return boolean
     */
    public function del_all($bank_id) {
        return parent::del_parent($bank_id);
    }

    /**
     * post_content生成器
     * @since 2
     * @param string|array $content 题目内容
     * @param string $answer 答案
     * @param int $type 题目类型
     * @return string
     */
    private function get_content($content, $answer, $type) {
        $return = '';
        $post_content = '';
        $post_answer = '';
        switch ($type) {
            case 0:
                //单选题
                if (is_array($content) == true) {
                    $post_content = implode($this->delimiter, $content);
                    $post_answer = $answer;
                }
                break;
            case 1:
                //复选题
                if (is_array($content) == true) {
                    $post_content = implode($this->delimiter, $content);
                    if (is_array($answer) == true) {
                        $post_answer = implode($this->delimiter, $answer);
                    }
                }
                break;
            case 2:
                //判断题
                $post_content = $content;
                $post_answer = $answer ? 1 : 0;
                break;
            case 3:
            default:
                //问答题
                $post_content = $content;
                $post_answer = $answer;
                break;
        }
        $return = $post_content . $this->delimiter_content . $post_answer;
        return $return;
    }

    /**
     * 获取选择题数组
     * <p>不能包含答案数据</p>
     * @since 1
     * @param string $content 题目内容部分
     * @return array
     */
    private function question_select_get_arr($content) {
        return explode($this->delimiter, $content);
    }

    /**
     * 获取题目内容分割数组
     * @since 1
     * @param string $content
     * @return array
     */
    private function question_get_arr($content) {
        return explode($this->delimiter_content, $content);
    }

    /**
     * 获取题目类型标识
     * @since 1
     * @param int $type
     * @return string
     */
    private function question_type($type) {
        if (isset($this->question_type[$type]) == true) {
            return $this->question_type[$type];
        }
        return $this->question_type[3];
    }

    /**
     * 判断题库类型
     * @since 1
     * @param int $bank_id
     * @return boolean
     */
    private function check_bank_type($bank_id) {
        $bank_res = parent::view($bank_id);
        if ($bank_res['post_type'] == 'bank') {
            return true;
        }
        return false;
    }

}

?>
