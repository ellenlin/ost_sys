<?php

/**
 * 跳转操作模块
 * @author suhuiling
 * @version 1
 * @package plugtourl
 * @param string $url 跳转URL
 */
function plugtourl($url) {
    header('Location:' . $url);
}

?>
