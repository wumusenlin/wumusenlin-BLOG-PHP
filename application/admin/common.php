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

// 模块公共文件
//配置 memorycache 名称
//define("MEM_CACHE_NAME","afb33e1381504b4d.m.cnhzalicm10pub001.ocs.aliyuncs.com");
//配置 memorycache 名称
//define("MEM_CACHE_PWD","11211");

//配置 memorycache 名称
define("MEM_CACHE_NAME","172.16.15.87");
//define("MEM_CACHE_NAME","afb33e1381504b4d.m.cnhzalicm10pub001.ocs.aliyuncs.com");
//配置 memorycache 名称
define("MEM_CACHE_PWD","11211");
//配置 memorycache 缓存时间 7*24*3600=604800
define("MEM_CACHE_TIME",604800);
//配置系统名
//define("CHAOGE_SYS_NAME","wagesapp");
//配置系统名
if (defined('TALENTS')) {
    define("CHAOGE_SYS_NAME","wages_talent");
} else {
    //define("CHAOGE_SYS_NAME","wagesapp");
    define("CHAOGE_SYS_NAME","wagesadmin");
}
//

/**
 * Created by Iyoule .
 * 模拟PHP Memcache 类。
 *  当服务器没有开启Memcache扩展的时候。可以采用本类
 *  使用方法
 *            class_exists('Memcache') or include './Memcache.class.php';
 *            $mem = new Memcache;
 *            $mem->add('key','value');
 *            $mem->get('key')
 *  目前已实现方法
 *            Memcache::connect ( string $host [, int $port ] )
 *            Memcache::get( string $key )
 *            Memcache::add( string $key , mixed $var [, int $expire] )
 *            Memcache::set( string $key , mixed $var [, int $expire] )
 *            Memcache::replace( string $key , mixed $var [, int $expire] )
 *            Memcache::getVersion( void )
 *            Memcache::flush ( void )
 *            Memcache::delete( string $key )
 *            Memcache::close( void )           2014-3-29 02:13:19
 *
 * 属性
 *            Memcache::info 服务器相关信息 返回数组
 *
 *  注意
 *      本类需要sockets支持
 *      本类的 指定Memcache下标长度超出32字节。自动对key进行MD5
 *
 * @Version Memcache.class.php 1.1 2014-3-29 04:10:18  $
 *            bug 修复
 *            1. 修复必须需要sockets扩展才能使用的缺陷
 *            2. 代码优化
 *            3. 添加手动关闭 连接方法
 *
 * Email: 136045277#qq.com 群：220256148
 * Version: Memcache.class.php 1.1 $
 * Time: 2014-3-28 上午12:04
 */
//class Memcache
//{
//    /**
//     * @var 服务器地址
//     */
//    public $host;
//
//    /**
//     * @var 服务器端口
//     */
//    public $port;
//
//    /**
//     * @var array memcache 服务信息
//     */
//    private $info = array();
//
//    /**
//     * @var null socket资源
//     */
//    private $socket = null;
//
//    /**
//     * @var memcache 命令
//     */
//    private $command;
//
//    /**
//     * @var int 连接超时时间
//     */
//    private $connect_timeout = 30;
//    private $errno;
//    private $errstr;
//
//    /**
//     * @var mamcache保存数据时长
//     */
//    private $expire;
//
//    /**
//     * @var memcache 保存数据的key 长于32位置将被MD5
//     */
//    private $key;
//
//    /**
//     * @var memcache 保存的值
//     */
//    private $var;
//
//    /**
//     * @var bool 主机是否关闭连接
//     */
//    private $is_close = false;
//
//    /**
//     * @var string 连接函数
//     */
//    private $connect_method;
//
//    /**
//     * 构造方法 判断 根据系统自动判断连接类型
//     *  优先级
//     *   优先 -> 低
//     *  stream_socket_client -> fsockopen -> pfsockopen -> socket_create
//     */
//    public function __construct()
//    {
//        if (function_exists('stream_socket_client')) {
//            $this->connect_method = 'stream_socket_client';
//        } elseif (function_exists('fsockopen')) {
//            $this->connect_method = 'fsockopen';
//        } elseif (function_exists('pfsockopen')) {
//            $this->connect_method = 'pfsockopen';
//        } elseif (function_exists('socket_create')) {
//            $this->connect_method = 'socket_create';
//        }
//    }
//
//    /**
//     * 从服务端检回一个元素
//     * 如果服务端之前有以key作为key存储的元素， Memcache::get()方法此时返回之前存储的值。
//     * 你可以给 Memcache::get()方法传递一个数组（多个key）来获取一个数组的元素值，返回的数组仅仅包含从 服务端查找到的key-value对。
//     * @param $key 要获取值的key或key数组。
//     * @return bool|string 返回key对应的存储元素的字符串值或者在失败或key未找到的时候返回FALSE。
//     * @lastTime 2014-3-28 02:44:00
//     */
//    public function get($key)
//    {
//        $this->key = isset($key{32}) ? md5($key) : $key;
//        $command = "get $key\r\n";
//        $this->socket_write($command);
//        do {
//            $out = $this->socket_read(128);
//        } while (strlen($out) == 1);
//        $list = preg_split("/\s/", $out);
//        if ($list[0] == 'END') return false;
//        $string = array();
//        $runing = 0;
//        do {
//            $string[$runing] = $this->socket_read(128);
//            substr($string[$runing], 0, 3) == 'END' && strlen($string[$runing]) <= 5 ? $runing = false : $runing++;
//        } while ($runing !== false);
//        array_pop($string);
//        $string = join('', $string);
//        $indexOf = 0;
//        if ($this->connect_method == 'socket_create') {
//            $indexOf = 1;
//        }
//        $string = substr($string, $indexOf, -2);
//        return $string;
//    }
//
//    /**
//     * 建立连接
//     * @param $host memcache 服务器地址
//     * @param int $post 端口
//     * @lastTime 2014-3-28 02:40:09
//     */
//    public function connect($host, $post = 11211)
//    {
//        $this->host = $host;
//        $this->port = $post;
//        $this->create_socket();
//        $this->info();
//    }
//
//    /**
//     * 魔法方法 构造 add set replace方法
//     * @param $method
//     * @param $args
//     * @return mixed
//     * @lastTime 2014-3-28 02:40:28
//     */
//    public function __call($method, $args)
//    {
//        if (in_array($method, array('add', 'set', 'replace', 'delete'))) {
//            array_unshift($args, $method);
//            return call_user_func_array(array($this, 'set__'), $args);
//        }
//    }
//
//    /**
//     * 魔法方法
//     * @param $name
//     * @return array
//     * @lastTime 2014-3-29 04:17:17
//     */
//    public function __get($name)
//    {
//        if($name=='info')
//            return $this->info;
//    }
//
//    /**
//     * 针对 魔法方法 add set replace方法进行的处理
//     * @param $func 方法名字
//     * @param $key memcache的key下标
//     * @param $var 设置的值
//     * @param int $expire memcache对数据的保存时间 默认24小时
//     * @return bool
//     * @lastTime 时间
//     */
//    private function set__($func, $key, $var = null, $expire = 86400)
//    {
//        if ($this->is_close)
//            return false;
//        $this->command = trim($func);
//        $this->key = isset($key{64}) ? md5($key) : $key;
//        if ($func != 'delete') {
//            $this->var = trim($var);
//            $this->expire = trim($expire);
//        }
//        return $this->send_do();
//    }
//
//    /**
//     * 清空memcache的值
//     * @return bool
//     * @lastTime 2014-3-28 02:43:23
//     */
//    public function flush()
//    {
//        $command = "flush_all\r\n";
//        $this->socket_write($command);
//        if ($this->socket_read(3) == 'OK') {
//            return true;
//        }
//        return false;
//    }
//
//    /**
//     * Memcache::getVersion — 返回服务器版本信息
//     * Memcache::getVersion()返回一个字符串表示的服务端版本号
//     * 同样你也可以使用 Memcache::info['version']。
//     * @return mixed
//     * @lastTime 2014-3-28 02:44:56
//     */
//    public function getVersion()
//    {
//        return $this->info['version'];
//    }
//
//    /**
//     * 对 add set   replace delete 方法进行的处理
//     * @return bool
//     * @lastTime 2014-3-28 02:45:55
//     */
//    private function send_do()
//    {
//        if ($this->command != 'delete') {
//            $command = sprintf("%s %s 0 %d %d\r\n", $this->command, $this->key, $this->expire, strlen($this->var));
//            $var = sprintf("%s\r\n", $this->var);
//            $this->socket_write($command);
//            $this->socket_write($var);
//        } else {
//            $command = sprintf("%s %s\r\n", $this->command, $this->key);
//            $this->socket_write($command);
//        }
//        do {
//            $result = $this->socket_read(64);
//        } while (strlen($result) == 1);
//        $result = str_replace(array("\r", "\n"), '', $result);
//        if (substr($result, 0, 5) == 'STORE' || substr($result, 0, 6) == 'DELETE') {
//            return true;
//        }
//        return false;
//    }
//
//    /**
//     * 创建连接类型
//     * @return bool
//     * @lastTime 2014-3-29 04:06:15
//     */
//    private function create_socket()
//    {
//        $method = $this->connect_method;
//        if ($method) {
//            switch ($method) {
//                case 'socket_create':
//                    $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//                    socket_connect($this->socket, $this->host, $this->port);
//                    break;
//                case 'fsockopen':
//                case 'pfsockopen':
//                    $this->socket = $method($this->host, $this->port, $this->errno, $this->errstr, $this->connect_timeout);
//                    break;
//                case 'stream_socket_client':
//                    $address = sprintf("tcp://%s:%d", $this->host, $this->port);
//                    $this->socket = $method($address, $this->errno, $this->errstr, $this->connect_timeout);
//                    break;
//            }
//        }
//        if (is_resource($this->socket))
//            return true;
//    }
//
//    /**
//     * 写入套字节
//     * @param $string
//     * @return int
//     * @lastTime 2014-3-28 02:46:40
//     */
//    private function socket_write($string)
//    {
//        $return = false;
//        if ($this->connect_method == 'socket_create')
//            $return = socket_write($this->socket, $string, strlen($string));
//        else if ($this->connect_method)
//            $return = fwrite($this->socket, $string);
//        return $return;
//    }
//
//    /**
//     * 读取套字节
//     * @param $len 取出的长度
//     * @return string
//     * @lastTime 2014-3-28 02:47:08
//     */
//    private function socket_read($len)
//    {
//        $return = null;
//        if ($this->connect_method == 'socket_create')
//            $return = socket_read($this->socket, $len, PHP_NORMAL_READ);
//        else if ($this->connect_method)
//            $return = fgets($this->socket, $len);
//        return $return;
//    }
//
//    /**
//     * 服务器的信息处理
//     * @return array
//     * @lastTime 2014-3-28 02:47:46
//     */
//    private function info()
//    {
//        if (!empty($this->info))
//            return $this->info;
//        $this->socket_write("stats\r\n");
//        $string = array();
//        if ($this->connect_method) {
//            $runing = 0;
//            do {
//                $string[$runing] = $this->socket_read(68);
//                substr($string[$runing], 0, 3) == 'END' ? $runing = false : $runing++;
//            } while ($runing !== false);
//            $string = join("\r\n", $string);
//            $string = explode("\r\n", $string);
//            $string = array_filter($string, function ($value) {
//                return isset($value{4});
//            });
//            $string = array_map(function ($value) {
//                return explode(" ", $value);
//            }, $string);
//            foreach ($string as $val) {
//                $this->info[$val[1]] = $val[2];
//            }
//            return $this->info;
//        }
//    }
//
//    /**
//     * memcache 关闭连接
//     * @return bool
//     * @lastTime 2014-3-29 01:11:23
//     */
//    public function close()
//    {
//        $this->is_close = true;
//        return !!$this->socket_write("quit\r\n");
//    }
//
//    /**
//     * 析构函数 关闭套字节
//     */
//    public function __destruct()
//    {
//        if (is_resource($this->socket) && $this->connect_method == 'socket_create')
//            socket_close($this->socket);
//        else if ($this->connect_method)
//            fclose($this->socket);
//    }
//
//}
//生成token
function createToken($paramarr)
{
    //生成token
    $tokenPars = '';
    ksort($paramarr);
    foreach($paramarr as $k => $v)
    {
        if("" != $v)
        {
            $tokenPars .= $k . "=" . $v . "&";
        }
    }
    $nowtime = date("Y-m-d H:i:s",time());
    $tokenPars .= $nowtime;
    $tokenPars .= $paramarr["gonghao"];
    $token = md5($tokenPars);
    $expiration = date("Y-m-d H:i:s",strtotime("+1 week",strtotime($nowtime)));
    $paramarr["token"] = $token;
    $paramarr["expiration"] = $expiration;
    return $paramarr;
}

//检查token是否存在、过期，若不存在或已过期，则重新从用户中心查询
function checktoken($token)
{
    $cache = new \Memcache;
    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $userinfo = $cache->get(CHAOGE_SYS_NAME . $token);
    if(!$userinfo)//token不存在
    {
        return false;
    }
    else//token存在
    {
        $userinfo_arr = json_decode($userinfo,true);
        $nowtoken = $cache->get($userinfo_arr["gonghao"] . CHAOGE_SYS_NAME . "uid-token");
        if($nowtoken == $token && strtotime($userinfo_arr["expiration"]) >= strtotime(date("Y-m-d H:i:s")))
        {
            return $userinfo_arr;
        }
        else
            return false;

    }
}

function updatetoken($token,$uid,$userinfo)
{
    $cache = new \Memcache;

    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $oldtoken = $cache->get($uid . CHAOGE_SYS_NAME . "uid-token");
    if($cache->set($uid . CHAOGE_SYS_NAME . "uid-token",$token,0,MEM_CACHE_TIME))
    {
        $cache->delete(CHAOGE_SYS_NAME . $oldtoken);
        if($cache->set(CHAOGE_SYS_NAME . $token,json_encode($userinfo),0,MEM_CACHE_TIME))
        {
            return true;
        }
        else
            return false;
    }
    else
    {
        return false;
    }
}

function updatetoken_test($token,$uid,$userinfo)
{
    $cache = new \Memcache;
    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $oldtoken = $cache->get($uid . CHAOGE_SYS_NAME . "uid-token");
    if($cache->set($uid . CHAOGE_SYS_NAME . "uid-token",$token,0,MEM_CACHE_TIME))
    {
        $cache->delete(CHAOGE_SYS_NAME . $oldtoken);
        if($cache->set(CHAOGE_SYS_NAME . $token,json_encode($userinfo),0,MEM_CACHE_TIME))
        {
            return true;
        }
        else {
            echo '设置 '.CHAOGE_SYS_NAME . $token." token对应userinfo失败";
            return false;
        }
    }
    else
    {
        echo '设置 '.$uid . CHAOGE_SYS_NAME . "uid-token"." uid对应token失败";
        return false;
    }
}

function updatevalue($token,$key,$value)
{
    $cache = new \Memcache;
    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $jsonstr = $cache->get(CHAOGE_SYS_NAME . $token);
    if($jsonstr)
    {
        $json_arr = json_decode($jsonstr,true);
        $json_arr[$key] = $value;
        if($cache->set(CHAOGE_SYS_NAME . $token,json_encode($json_arr),0,MEM_CACHE_TIME))
        {
            return true;
        }
        else
            return false;
    }
    else
    {
        return false;
    }
}

function updatevaluebyuid($uid,$key,$value)
{
    $cache = new \Memcache;
    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $token = $cache->get($uid . CHAOGE_SYS_NAME . "uid-token");
    return updatevalue($token,$key,$value);
}

//清除缓存
function delkey($uid)
{
    $cache = new \Memcache;
    $cache->connect(MEM_CACHE_NAME, MEM_CACHE_PWD);
    $token = $cache->get($uid . CHAOGE_SYS_NAME . "uid-token");
    $ret = $cache->delete(CHAOGE_SYS_NAME . $token);
    return true;
}
