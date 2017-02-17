<?php

/**
 * 助手类
 * @author www.shouce.ren
 *
 */
class Helper {
    /**
     * 判断当前服务器系统
     * @return string
     */
    public static function getOS() {
        if (PATH_SEPARATOR == ':') {
            return 'Linux';
        } else {
            return 'Windows';
        }
    }

    /**
     * 当前微妙数
     * @return number
     */
    public static function microtime_float() {
        list ($usec, $sec) = explode(" ", microtime());
        return (( float )$usec + ( float )$sec);
    }

    /**
     * 切割utf-8格式的字符串(一个汉字或者字符占一个字节)
     *
     * @author zhao jinhan
     * @version v1.0.0
     *
     */
    public static function truncate_utf8_string($string, $length, $etc = '...') {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }

    /**
     * 遍历文件夹
     * @param string $dir
     * @param boolean $all true表示递归遍历
     * @return array
     */
    public static function scanfDir($dir = '', $all = false, &$ret = []) {
        if (false !== ($handle = opendir($dir))) {
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, ['.', '..', '.git', '.gitignore', '.svn', '.htaccess', '.buildpath', '.project'])) {
                    $cur_path = $dir . '/' . $file;
                    if (is_dir($cur_path)) {
                        $ret['dirs'][] = $cur_path;
                        $all && self::scanfDir($cur_path, $all, $ret);
                    } else {
                        $ret ['files'] [] = $cur_path;
                    }
                }
            }
            closedir($handle);
        }
        return $ret;
    }

    /**
     * 邮件发送
     * @param string $toemail
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    public static function sendMail($toemail = '', $subject = '', $message = '') {
        $mailer = Yii::createComponent('application.extensions.mailer.EMailer');

        //邮件配置
        $mailer->SetLanguage('zh_cn');
        $mailer->Host = Yii::app()->params['emailHost']; //发送邮件服务器
        $mailer->Port = Yii::app()->params['emailPort']; //邮件端口
        $mailer->Timeout = Yii::app()->params['emailTimeout']; //邮件发送超时时间
        $mailer->ContentType = 'text/html'; //设置html格式
        $mailer->SMTPAuth = true;
        $mailer->Username = Yii::app()->params['emailUserName'];
        $mailer->Password = Yii::app()->params['emailPassword'];
        $mailer->IsSMTP();
        $mailer->From = $mailer->Username; // 发件人邮箱
        $mailer->FromName = Yii::app()->params['emailFormName']; // 发件人姓名
        $mailer->AddReplyTo($mailer->Username);
        $mailer->CharSet = 'UTF-8';

        // 添加邮件日志
        $modelMail = new MailLog ();
        $modelMail->accept = $toemail;
        $modelMail->subject = $subject;
        $modelMail->message = $message;
        $modelMail->send_status = 'waiting';
        $modelMail->save();
        // 发送邮件
        $mailer->AddAddress($toemail);
        $mailer->Subject = $subject;
        $mailer->Body = $message;

        if ($mailer->Send() === true) {
            $modelMail->times = $modelMail->times + 1;
            $modelMail->send_status = 'success';
            $modelMail->save();
            return true;
        } else {
            $error = $mailer->ErrorInfo;
            $modelMail->times = $modelMail->times + 1;
            $modelMail->send_status = 'failed';
            $modelMail->error = $error;
            $modelMail->save();
            return false;
        }
    }

    /**
     * 判断字符串是utf-8 还是gb2312
     * @param unknown $str
     * @param string $default
     * @return string
     */
    public static function utf8_gb2312($str, $default = 'gb2312') {
        $str = preg_replace("/[\x01-\x7F]+/", "", $str);
        if (empty($str)) return $default;

        $preg = [
            "gb2312" => "/^([\xA1-\xF7][\xA0-\xFE])+$/", //正则判断是否是gb2312
            "utf-8"  => "/^[\x{4E00}-\x{9FA5}]+$/u", //正则判断是否是汉字(utf8编码的条件了)，这个范围实际上已经包含了繁体中文字了
        ];

        if ($default == 'gb2312') {
            $option = 'utf-8';
        } else {
            $option = 'gb2312';
        }

        if (!preg_match($preg[$default], $str)) {
            return $option;
        }
        $str = @iconv($default, $option, $str);

        //不能转成 $option, 说明原来的不是 $default
        if (empty($str)) {
            return $option;
        }
        return $default;
    }

    /**
     * utf-8和gb2312自动转化
     * @param unknown $string
     * @param string $outEncoding
     * @return unknown|string
     */
    public static function safeEncoding($string, $outEncoding = 'UTF-8') {
        $encoding = "UTF-8";
        for ($i = 0; $i < strlen($string); $i++) {
            if (ord($string{$i}) < 128)
                continue;

            if ((ord($string{$i}) & 224) == 224) {
                // 第一个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    // 第二个字节判断通过
                    $char = $string{++$i};
                    if ((ord($char) & 128) == 128) {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if ((ord($string{$i}) & 192) == 192) {
                // 第一个字节判断通过
                $char = $string{++$i};
                if ((ord($char) & 128) == 128) {
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }

        if (strtoupper($encoding) == strtoupper($outEncoding))
            return $string;
        else
            return @iconv($encoding, $outEncoding, $string);
    }

    /**
     * 返回二维数组中某个键名的所有值
     * @param input $array
     * @param string $key
     * @return array
     */
    public static function array_key_values($array = [], $key = '') {
        $ret = [];
        foreach ((array)$array as $k => $v) {
            $ret[$k] = $v[$key];
        }
        return $ret;
    }


    /**
     * 判断 文件/目录 是否可写（取代系统自带的 is_writeable 函数）
     * @param string $file 文件/目录
     * @return boolean
     */
    public static function is_writeable($file) {
        if (is_dir($file)) {
            $dir = $file;
            if ($fp = @fopen("$dir/test.txt", 'w')) {
                @fclose($fp);
                @unlink("$dir/test.txt");
                $writeable = 1;
            } else {
                $writeable = 0;
            }
        } else {
            if ($fp = @fopen($file, 'a+')) {
                @fclose($fp);
                $writeable = 1;
            } else {
                $writeable = 0;
            }
        }

        return $writeable;
    }

    /**
     * 格式化单位
     */
    static public function byteFormat($size, $dec = 2) {
        $a = ["B", "KB", "MB", "GB", "TB", "PB"];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 下拉框，单选按钮 自动选择
     *
     * @param $string 输入字符
     * @param $param  条件
     * @param $type   类型
     * selected checked
     * @return string
     */
    static public function selected($string, $param = 1, $type = 'select') {

        $true = false;
        if (is_array($param)) {
            $true = in_array($string, $param);
        } elseif ($string == $param) {
            $true = true;
        }
        $return = '';
        if ($true)
            $return = $type == 'select' ? 'selected="selected"' : 'checked="checked"';

        echo $return;
    }

    /**
     * 下载远程图片
     * @param string $url 图片的绝对url
     * @param string $filepath 文件的完整路径（例如/www/images/test） ，此函数会自动根据图片url和http头信息确定图片的后缀名
     * @param string $filename 要保存的文件名(不含扩展名)
     * @return mixed 下载成功返回一个描述图片信息的数组，下载失败则返回false
     */
    static public function downloadImage($url, $filepath, $filename) {
        //服务器返回的头信息
        $responseHeaders = [];
        //原始图片名
        $originalfilename = '';
        //图片的后缀名
        $ext = '';
        $ch = curl_init($url);
        //设置curl_exec返回的值包含Http头
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //设置curl_exec返回的值包含Http内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设置抓取跳转（http 301，302）后的页面
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //设置最多的HTTP重定向的数量
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        //服务器返回的数据（包括http头信息和内容）
        $html = curl_exec($ch);
        //获取此次抓取的相关信息
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        if ($html !== false) {
            //分离response的header和body，由于服务器可能使用了302跳转，所以此处需要将字符串分离为 2+跳转次数 个子串
            $httpArr = explode("\r\n\r\n", $html, 2 + $httpinfo['redirect_count']);
            //倒数第二段是服务器最后一次response的http头
            $header = $httpArr[count($httpArr) - 2];
            //倒数第一段是服务器最后一次response的内容
            $body = $httpArr[count($httpArr) - 1];
            $header .= "\r\n";

            //获取最后一次response的header信息
            preg_match_all('/([a-z0-9-_]+):\s*([^\r\n]+)\r\n/i', $header, $matches);
            if (!empty($matches) && count($matches) == 3 && !empty($matches[1]) && !empty($matches[1])) {
                for ($i = 0; $i < count($matches[1]); $i++) {
                    if (array_key_exists($i, $matches[2])) {
                        $responseHeaders[$matches[1][$i]] = $matches[2][$i];
                    }
                }
            }
            //获取图片后缀名
            if (0 < preg_match('{(?:[^\/\\\\]+)\.(jpg|jpeg|gif|png|bmp)$}i', $url, $matches)) {
                $originalfilename = $matches[0];
                $ext = $matches[1];
            } else {
                if (array_key_exists('Content-Type', $responseHeaders)) {
                    if (0 < preg_match('{image/(\w+)}i', $responseHeaders['Content-Type'], $extmatches)) {
                        $ext = $extmatches[1];
                    }
                }
            }
            //保存文件
            if (!empty($ext)) {
                //如果目录不存在，则先要创建目录
                if (!is_dir($filepath)) {
                    mkdir($filepath, 0777, true);
                }

                $filepath .= '/' . $filename . ".$ext";
                $local_file = fopen($filepath, 'w');
                if (false !== $local_file) {
                    if (false !== fwrite($local_file, $body)) {
                        fclose($local_file);
                        $sizeinfo = getimagesize($filepath);
                        return ['filepath' => realpath($filepath), 'width' => $sizeinfo[0], 'height' => $sizeinfo[1], 'orginalfilename' => $originalfilename, 'filename' => pathinfo($filepath, PATHINFO_BASENAME)];
                    }
                }
            }
        }
        return false;
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    function arraySequence($array, $field, $sort = 'SORT_DESC') {
        $arrSort = [];
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    /*
    * array unique_rand( int $min, int $max, int $num )
    * 生成一定数量的不重复随机数
    * $min 和 $max: 指定随机数的范围
    * $num: 指定生成数量
    *
    * $arr = unique_rand(1, 25, 16);
    * sort($arr);
    * $result = '';
    * for ($i = 0;
    * $i < count($arr);
    * $i++)
    * {
    *    $result .= $arr[$i].',';
    *}
    *$result = substr($result, 0, -1);
    *echo $result;
    */
    function unique_rand($min, $max, $num) {
        $count = 0;
        $return = [];
        while ($count < $num) {
            $return[] = mt_rand($min, $max);
            $return = array_flip(array_flip($return));
            $count = count($return);
        }
        shuffle($return);
        return $return;
    }


    //记录错误信息，结合register_shutdown_function使用
    function _rare_shutdown_catch_error() {

        $_error = error_get_last();

        if ($_error && in_array($_error['type'], [E_ERROR, E_WARNING])) {

            echo '致命错误:' . $_error['message'] . '</br>';

            echo '文件:' . $_error['file'] . '</br>';

            echo '在第' . $_error['line'] . '行</br>';

        }

    }

//register_shutdown_function("_rare_shutdown_catch_error");
    /**
     * 导出数据到csv文件 最新统一版
     * @param $file_name
     * @param $datas
     */
    protected function exportDataToCsv($file_name, $datas) {
        $filename = $file_name . '.csv';
        header("Content-type: application/octet-stream");
        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        $handle = fopen('php://output', 'r+');
        fputs($handle, chr(239) . chr(187) . chr(191));
        if (!empty($datas)) {
            foreach ($datas as $line) {
                fputcsv($handle, $line);
            }
        }
        fclose($handle);
    }

    /**
     * ob_flush|flush用法 ob_flush是刷新php buffer到可输出状态
     * flush是把可输出的数刷新到浏览器 也就是webserver 两个必须一块用才有效果
     * @link http://www.laruence.com/2010/04/15/1414.html http://www.cnblogs.com/godok/p/6341300.html
     */
    public function flush() {
        echo str_pad('', 4096);

        set_time_limit(50);
        for ($i = 0; $i <= 5; $i++) {
            echo $i . '<br />';
            ob_flush();
            flush();
            sleep(1);
        }
    }

}