<?php

/**
 * IP操作类
 * @author suhuiling
 * @version 2
 * @package core
 */
class coreip {

    /**
     * IP定位数据库文件
     * @since 1
     * @var string 
     */
    private $locate_data_file;

    /**
     * 数据库表名称
     * @since 1
     * @var string
     */
    private $table_name;

    /**
     * 外部数据库操作句柄引用
     * @since 1
     * @var coredb 
     */
    private $db;

    /**
     * 初始化
     * @since 3
     * @param string $locate_data_file IP定位数据库文件
     * @param coredb $db 数据库操作句柄
     */
    public function __construct($locate_data_file, &$db) {
        $this->locate_data_file = $locate_data_file;
        $this->db = $db;
        $this->table_name = $db->tables['ip'];
    }

    /**
     * 查询IP ID
     * @since 4
     * @param int $id ID
     * @return boolean|array
     */
    public function view($id) {
        $sql = 'SELECT `id`,`ip_addr`,`ip_ban` FROM `' . $this->table_name . '` WHERE `id` = ?';
        $sth = $this->db->prepare($sql);
        $sth->bindParam(1, $id, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        if ($sth->execute() == true) {
            return $sth->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * 获取或创建IP信息
     * <p>返回数组array('id','addr','ban')</p>
     * @since 1
     * @return array
     */
    public function get_ip() {
        $ip_addr = $this->get_addr();
        $re = array('id' => 0, 'ban' => '1', 'addr' => '');
        if ($ip_addr != '') {
            $sql = 'SELECT `id`,`ip_addr`,`ip_ban` FROM `' . $this->table_name . '` WHERE `ip_addr` = ?';
            $sth = $this->db->prepare($sql);
            $sth->bindParam(1, $ip_addr, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
            if ($sth->execute() == true) {
                $res = $sth->fetch(PDO::FETCH_ASSOC);
                if ($res['id'] > 0) {
                    $re = array('id' => $res['id'], 'addr' => $res['ip_addr'], 'ban' => $res['ip_ban']);
                } else {
                    $sql_insert = 'INSERT INTO `' . $this->table_name . '`(`id`,`ip_addr`,`ip_ban`) VALUES(NULL,?,0)';
                    $sth_insert = $this->db->prepare($sql_insert);
                    $sth_insert->bindParam(1, $ip_addr, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
                    if ($sth_insert->execute() == true) {
                        $last_id = $this->db->lastInsertId();
                        $re = array('id' => $last_id, 'addr' => $ip_addr, 'ban' => '0');
                    }
                }
            }
        }
        return $re;
    }

    /**
     * 获取IP真实地址
     * @since 2
     * @param string $ip_addr IP地址
     * @return string
     */
    public function get_locate($ip_addr) {
        return '';
    }

    /**
     * 获取IP地址
     * @since 1
     * @return string
     */
    private function get_addr() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        elseif (isset($_SERVER["REMOTE_ADDR"]))
            $ip = $_SERVER["REMOTE_ADDR"];
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "0.0.0.0";
        return $ip;
    }

}

?>
