    <?php
    // +----------------------------------------------------------------------
    // | ThinkPHP [ WE CAN DO IT JUST THINK ]
    // +----------------------------------------------------------------------
    // | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
    // +----------------------------------------------------------------------
    // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
    // +----------------------------------------------------------------------
    // | Author: 流年 <liu21st@gmail.com>
    // +----------------------------------------------------------------------

    // 应用公共文件
    function isMobile()
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        if (isset($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 子元素计数器
     * @param array $array
     * @param int $pid
     * @return array
     */
    function array_children_count($array, $pid)
    {
        $counter = [];
        foreach ($array as $item) {
            $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
            $count++;
            $counter[$item[$pid]] = $count;
        }
        return $counter;
    }

    /**
     * 数组层级缩进转换
     * @param array $array 源数组
     * @param int $pid
     * @param int $level
     * @return array
     */
    function array2level($array, $pid = 0, $level = 1)
    {
        static $list = [];
        foreach ($array as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $list[] = $v;
                array2level($array, $v['id'], $level + 1);
            }
        }
        return $list;
    }

    function arrayCounts($array, $pid = 0, $level = 1, $child = '_child')
    {
        $arr = [];
        foreach ($array as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $v[$child] = arrayCounts($array, $v['id'], $level + 1, $child);
                $arr[] = $v;
            }
        }
        return $arr;
    }

    /**
     * 构建层级（树状）数组
     * @param array $array 要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
     * @param string $pid_name 父级ID的字段名
     * @param string $child_key_name 子元素键名
     * @return array|bool
     */
    function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
    {
        $counter = array_children_count($array, $pid_name);
        if (!isset($counter[0]) || $counter[0] == 0) {
            return $array;
        }
        $tree = [];
        while (isset($counter[0]) && $counter[0] > 0) {
            $temp = array_shift($array);
            if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
                array_push($array, $temp);
            } else {
                if ($temp[$pid_name] == 0) {
                    $tree[] = $temp;
                } else {
                    $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
                }
            }
            $counter = array_children_count($array, $pid_name);
        }
        return $tree;
    }

    /**
     * 把元素插入到对应的父元素$child_key_name字段
     * @param        $parent
     * @param        $pid
     * @param        $child
     * @param string $child_key_name 子元素键名
     * @return mixed
     */
    function array_child_append($parent, $pid, $child, $child_key_name)
    {
        foreach ($parent as &$item) {
            if ($item['id'] == $pid) {
                if (!isset($item[$child_key_name])) {
                    $item[$child_key_name] = [];
                }

                $item[$child_key_name][] = $child;
            }
        }
        return $parent;
    }

    /**
     * 手机号格式检查
     * @param string $mobile
     * @return bool
     */
    function check_mobile_number($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        $reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

        return preg_match($reg, $mobile) ? true : false;
    }

    //获取客户端真实IP
    function getClientIP()
    {
        global $ip;
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "Unknow";
        }

        return $ip;
    }

    /**
     * 获取 IP  地理位置
     * 淘宝IP接口
     * @Return: array
     */
    function getCity($ip = '')
    {
        if ($ip == '') {
            $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
            $ip = json_decode(file_get_contents($url), true);
            $data = $ip;
        } else {
            $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
            $ip = json_decode(file_get_contents($url));
            if ((string)$ip->code == '1') {
                return false;
            }
            $data = (array)$ip->data;
        }

        return $data;
    }

    /**
     * 判断 LotusAdmin 核心是否安装
     * @return bool
     */
    function lotus_is_installed()
    {
        static $lotusIsInstalled;
        if (empty($lotusIsInstalled)) {
            $lotusIsInstalled = file_exists(LOTUS_ROOT . 'data/install.lock');
        }
        return $lotusIsInstalled;
    }

    /**
     * 切分SQL文件成多个可以单独执行的sql语句
     * @param $file sql文件路径
     * @param $tablePre 表前缀
     * @param string $charset 字符集
     * @param string $defaultTablePre 默认表前缀
     * @param string $defaultCharset 默认字符集
     * @return array
     */
    function lotus_split_sql($file, $tablePre, $charset = 'utf8mb4', $defaultTablePre = 'lotus_', $defaultCharset = 'utf8mb4')
    {
        if (file_exists($file)) {
            //读取SQL文件
            $sql = file_get_contents($file);
            $sql = str_replace("\r", "\n", $sql);
            $sql = str_replace("BEGIN;\n", '', $sql);//兼容 navicat 导出的 insert 语句
            $sql = str_replace("COMMIT;\n", '', $sql);//兼容 navicat 导出的 insert 语句
            $sql = str_replace($defaultCharset, $charset, $sql);
            $sql = trim($sql);
            //替换表前缀
            $sql = str_replace(" `{$defaultTablePre}", " `{$tablePre}", $sql);
            $sqls = explode(";\n", $sql);
            return $sqls;
        }

        return [];
    }

    /**
     * 随机字符串生成
     * @param int $len 生成的字符串长度
     * @return string
     */
    function lotus_random_string($len = 6)
    {
        $chars = [
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        ];
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 获取网站根目录
     * @return string 网站根目录
     */
    function lotus_get_root()
    {
        $request = \think\Request::instance();
        $root = $request->root();
        $root = str_replace('/index.php', '', $root);
        if (defined('APP_NAMESPACE') && APP_NAMESPACE == 'api') {
            $root = preg_replace('/\/api$/', '', $root);
            $root = rtrim($root, '/');
        }

        return $root;
    }

    /**
     * 设置系统配置，通用
     * @param string $key 配置键值,都小写
     * @param array $data 配置值，数组
     * @param bool $replace 是否完全替换
     * @return bool 是否成功
     */
    function lotus_set_option($key, $data, $replace = false)
    {
        if (!is_array($data) || empty($data) || !is_string($key) || empty($key)) {
            return false;
        }

        $key = strtolower($key);
        $option = [];
        $findOption = \think\Db::name('option')->where('option_name', $key)->find();
        if ($findOption) {
            if (!$replace) {
                $oldOptionValue = json_decode($findOption['option_value'], true);
                if (!empty($oldOptionValue)) {
                    $data = array_merge($oldOptionValue, $data);
                }
            }

            $option['option_value'] = json_encode($data);
            \think\Db::name('option')->where('option_name', $key)->update($option);
            \think\Db::name('option')->getLastSql();
        } else {
            $option['option_name'] = $key;
            $option['option_value'] = json_encode($data);
            \think\Db::name('option')->insert($option);
        }
        cache('cmf_options_' . $key, null);//删除缓存
        return true;
    }

    /**后台系统统一返回格式
     * @param $code  业务状态码
     * @param $msg 提示信息
     * @param array $data 返回的数据
     */
    function json_code($code, $msg, $data = [])
    {
        $arr = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        return exit(json_encode($arr));
    }

    /**API接口输出通用化函数返回格式
     * @param $code   业务状态码
     * @param $msg   提示信息
     * @param array $data 反回的数据
     * @param int $httpCode http状态码
     * @return string
     */
    function show($code, $msg, $data = [], $httpCode = 200)
    {
        $arr = ['code' => $code, 'msg' => $msg, 'data' => $data];
        return json($arr, $httpCode);
    }

    //新AES加密
    function encrypt($str, $localIv, $encryptKey)
    {
        $sign = openssl_encrypt($str, 'AES-128-CBC', $encryptKey, 0, $localIv);
        return $sign;
    }

    function decrypt($str,$localIV,$encryptKey){
        $sign=openssl_decrypt($str,'AES-128-CBC',$encryptKey,0,$localIV);
        return $sign;
    }