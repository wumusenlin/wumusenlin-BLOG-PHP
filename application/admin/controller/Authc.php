<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 下午 7:14
 */

namespace app\admin\controller;

use app\admin\logic\Auth as authlogic;
use think\controller\Rest;

class Authc extends  Rest
{
    //角色新增
    public function role_add($token=null){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        $postdata = json_decode(file_get_contents("php://input"), true);
        try{
            $res=$logic->role_add($postdata["data"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //角色查询
    public function getrolelist($token,$seachkey=null,$page=1,$pagesize=10){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            $res=$logic->getrolelist($seachkey,$page,$pagesize);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //角色修改
    public function role_update($token){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $postdata = json_decode(file_get_contents("php://input"), true);
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            $res=$logic->role_update($postdata["data"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //角色删除
    public function role_del($token){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        $postdata = json_decode(file_get_contents("php://input"), true);
        try{
            $res=$logic->role_del($postdata["data"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //获取角色对应的模块权限
    public function query_role_auth($token=null,$roleid){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            $res=$logic->query_role_auth($roleid);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //设置角色对应的模块权限
    public function set_role_auth($token=null){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $postdata = json_decode(file_get_contents("php://input"), true);
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            if(empty($postdata["data"])){
                return retmsg(-1);
            }
            $res=$logic->set_role_auth($postdata["data"]);
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //菜单操作，同一模块不能重复菜单
    public function operate_menu_auth($token){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        $postdata = json_decode(file_get_contents("php://input"), true);
        try{
            if($postdata["method"]=="add"){
                $res=$logic->add_auth_app($postdata["data"]);
            }
            elseif($postdata["method"]=="update"){
                $res=$logic->update_auth_app($postdata["data"]);
            }
            elseif($postdata["method"]=="delete"){
                $res=$logic->del_auth_app($postdata["data"]);
            }
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }
    //菜单查询
    public function query_auth_manu($token=null){
        header("Access-Control-Allow-Origin: *");
        $logic = new authlogic;
        $userinfo=checktoken($token);
        if(!$userinfo){
            return retmsg(-2);
        }
        try{
            $res = $logic->query_auth_app();
            return $res;
        }
        catch (\Exception $e){
            $msg = $e->getMessage();
            return retmsg(-1,null,$msg);//捕获异常
        }
    }

}