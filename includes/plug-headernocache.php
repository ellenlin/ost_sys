<?php

/**
 * 头信息无缓冲模块
 * 用户可能会设置一些选项来更改浏览器的默认缓存设置。通过发送上面的报头，您可以覆盖任何这些设置，强制浏览器不进行缓存！
 * @author suhuiling
 * @version 1
 * @package plugheadernocache
 */
function plugheadernocache() {
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
}

?>
