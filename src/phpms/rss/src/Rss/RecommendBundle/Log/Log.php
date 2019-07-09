<?php
namespace Rss\RecommendBundle\Log;

/**
 * Log
 * Conntroller以外からログ出力を行うためのユーティリティ
 */
class Log
{
    /**
     * Output info logmessage
     *
     * @param string $msg
     */
    public static function info($msg){
        list($micro, $Unixtime) = explode(" ", microtime());
        $sec = $micro + date("s", $Unixtime);
        $time = date("Y-m-d H:i:", $Unixtime).$sec;
        // ファイルの出力先を指定
        $file = "/var/www/src/phpms/rss/app/logs/dev.log";
        // 出力するログメッセージを表示
        $logs_msg = "[" . $time . "] app.INFO: " . $msg . PHP_EOL;
        file_put_contents($file, print_r($logs_msg, TRUE), FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Output info logmessage
     *
     * @param string $msg
     */
    public static function debug($msg){
        list($micro, $Unixtime) = explode(" ", microtime());
        $sec = $micro + date("s", $Unixtime);
        $time = date("Y-m-d H:i:", $Unixtime).$sec;
        // ファイルの出力先を指定
        $file = "/var/www/src/phpms/rss/app/logs/dev.log";
        // 出力するログメッセージを表示
        $trace_msg = debug_backtrace();
        $logs_msg = "[" . $time . "] app.DEBUG: [" . $trace_msg[0]['file'] . "(" . $trace_msg[0]['line'] . ")]" . $msg . PHP_EOL;
        file_put_contents($file, print_r($logs_msg, TRUE), FILE_APPEND | LOCK_EX);
    }
}
