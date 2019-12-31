<?php

/**
 * Created by VScode.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 16:04
 */

namespace app\admin\controller;

use think\Db;

class Login
{
    //登录接口
    public function loginfun()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true'); //添加头部信息，因为在本地做测试，所以需要允许跨域。
        //接收请求携带的数据 json格式
        $postData = json_decode(file_get_contents("php://input"), true);
        $username = $postData['name']; //分解接收的json数据 分别赋值给不同变量
        $password = $postData['password'];
        $md5pass = md5($password);
        //uniqid() 函数基于以微秒计的当前时间，生成一个唯一的 ID。
        $token = md5(uniqid(md5(time())));
        // return $token;
        $ret =  Db::query("select * from users where userName='$username' and userPasswordMd5='$md5pass'"); //连接数据库并执行查询语句，查询到的匹配结果复制给此变量
        if ($ret) { //通过上面一行的匹配结果返回不同值，如果是数据库有匹配结果就返回成功。
            return array("resultcode" => 1, "resultmsg" => "登录成功", 'token' => $token, "data" => $ret);
        } else {
            return array("resultcode" => 0, "resultmsg" => "账号或密码错误");
        }
    }
    //注册接口
    public function registfun()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $postData = json_decode(file_get_contents("php://input"), true);
        $username = $postData['name'];
        $password = $postData['password'];
        $ret =  Db::query("select userName from users where userName='$username'");
        $md5 = md5($password);
        // echo $md5;
        $userid = rand(1, 100000); //生成随机id值
        // $userid = 1;
        $idret = Db::query("select userId from users where userId='$userid'"); //查询id值是否已经存在，将布尔值类型的值赋值给此变量
        if (!$idret) { //判断是否存在此变量，存在就直接返回注册失败
            if ($ret) { //判断账号是否已经注册
                return array("resultcode" => -1, "resultmsg" => "此账号已注册", "data" => array('username' => $username, 'password' => $password));
            } else { //达到注册条件就将数据插入数据库，包括四个字段的数据，id,密码，名字，以及密码的md5值
                Db::query("INSERT INTO users ( userId, userPassword, userPasswordMd5, userName ) VALUES ( '$userid', '$password', '$md5', '$username')");
                return array("resultcode" => 1, "resultmsg" => "注册成功", "data" => array('username' => $username, 'password' => $password));
            }
        } else {
            return array("resultcode" => 0, "resultmsg" => "注册失败");
        }
    }
    //登录后的token生成发放接口
    public static function creatToken()
    {
        $str = md5(uniqid(md5(microtime(true)), true)); //uniqid() 函数基于以微秒计的当前时间，生成一个唯一的 ID
        return $str;
    }
    //修改密码
    public function changepassword()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true'); //添加头部信息，因为在本地做测试，所以需要允许跨域。
        //接受一个 JSON 格式的字符串并且把它转换为 PHP变量；这里的TRUE 代表返回array而非 object
        $postData = json_decode(file_get_contents("php://input"), true);
        $old = $postData['old'];
        $old = md5($old);
        $new = $postData['new'];
        //获取新密码md5值
        $new = md5($new);
        $id = $postData['id'];
        //将$id转成int类型
        $id = intval($id);
        //拿出数据库的密码的md5值
        $temppasmd5 = Db::query("SELECT userPasswordMd5 FROM users where userId= '$id' ");
        $temppasmd5 =  $temppasmd5[0]['userPasswordMd5'];
        if ($old !=  $temppasmd5) {
            return array("resultcode" => 0, "resultmsg" => "旧密码错误");
        }
        if ($old ==  $temppasmd5) {
            Db::query("UPDATE users SET userPasswordMd5 = '$new' where userId = '$id' ");
            return array("resultcode" => 1, "resultmsg" => "修改成功");
        } else {
            return array("resultcode" => 0, "resultmsg" => "旧密码错误");
        }
    }
}
