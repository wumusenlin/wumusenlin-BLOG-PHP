<?php

/**
 * Created by VScode.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 16:04
 */

namespace app\admin\controller;

use think\Db;

header("Content-Type: text/html;charset=utf-8");

class Upload
{
    //用户头像上传接口
    public function personal()
    {
        //添加头部信息，因为在本地做测试，所以需要允许跨域。
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With,application/json,Content-Type/image/jpeg');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: *");

        $imgname = $_FILES['image']['name'];
        $imgname = md5($imgname);
        $tmp = $_FILES['image']['tmp_name'];
        $image = $_FILES['image'];
        $filepath = 'photo/userimg/';
        $tempname = $image['tmp_name'];
        $jpg = ".jpg";
        // 将接收到的图片文件的临时地址移动到我致电给的地址下
        move_uploaded_file($tempname, $filepath . $imgname . $jpg);

        // $res = Db::query("UPDATE users SET 列名称 = 新值 WHERE 列名称 = 某值");
        return array("pathname" => $filepath . $imgname, "resultcode" => 1);
    }
    //用户信息完善接口
    public function personinfopost()
    {
        //添加头部信息，因为在本地做测试，所以需要允许跨域。
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With,application/json,Content-Type/image/jpeg');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Origin: *");

        //接收请求携带的数据 json格式
        $postData = json_decode(file_get_contents("php://input"), true);
        $name = $postData['name'];
        $date = $postData['date'];
        $phone = $postData['phone'];
        $emile = $postData['email'];
        $id = $postData['userid'];
        $signature = $postData['signature'];
        $imgurl = $postData['userimgurl'];
        $res = Db::query("SELECT userId FROM users where userId = '$id'");
        if (!$res) {
            return array("resultcode" => 0, "resultmsg" => "没有这个用户");
        } else {
            Db::query("UPDATE users SET userTrueName = '$name', userPhone = '$phone', userEmail = '$emile', userSignature = '$signature', userBirth = '$date', userImg = '$imgurl' where userId = '$id' ");
            return array("resultcode" => 1, "resultmsg" => "提交成功");
        }
    }
}
