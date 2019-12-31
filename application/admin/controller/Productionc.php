<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 下午 3:02
 */

namespace app\admin\controller;

use think\Loader;
use think\Request;
use app\admin\logic\Processdata as process_logic;
use think\controller\Rest;

class Productionc extends Rest
{
    //添加工艺文件
    public function processdata_add($token){
        header("Access-Control-Allow-Origin: *");
        $postdata = json_decode(file_get_contents("php://input"), true);
        $logic = new process_logic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            if(isset($postdata["id"])){
                $res = $this->processdata_update($postdata);
            }
            else{
                $res = $logic->processdata_add($postdata);
            }
            if($res){
                return retmsg(0);
            }
            else{
                return retmsg(-1);
            }
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }

    }
    //更新工艺文件
    protected function processdata_update($data){
        header("Access-Control-Allow-Origin: *");
        $logic = new process_logic;
        try{
            $res = $logic->processdata_update($data);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //查询工艺文件
    public function processdata_query($token=null,$dept=null,$group=null,$process=null,$filename=null,$page=1,$pagesize=10){
        header("Access-Control-Allow-Origin: *");
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        $logic = new process_logic;
        try{
            $res = $logic->processdata_query($dept,$group,$process,$filename,$page,$pagesize,$userinfo["gonghao"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //删除工艺文件
    public function processdata_del($token){
        header("Access-Control-Allow-Origin: *");
        $postdata = json_decode(file_get_contents("php://input"), true);
        $logic = new process_logic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            $res = $logic->processdata_del($postdata["ids"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }




}