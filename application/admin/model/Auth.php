<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 下午 7:14
 */

namespace app\admin\model;

use think\Db;

class Auth
{
    public function getroleinfo($where){
        $data=Db::table("wages_role_manuapp")
            ->where($where)
            ->order("roleid desc")
            ->select();
        return $data;
    }
    public function role_add($data){
        $res=Db::table("wages_role_manuapp")
            ->insert($data);
        return $res;
    }
    public function role_update($where,$data){
        $res=Db::table("wages_role_manuapp")
            ->where($where)
            ->update($data);
        return $res;
    }
    public function role_del($where){
        $res=Db::table("wages_role_manuapp")
            ->where($where)
            ->update(["enable"=>0]);
        return $res;
    }
    //查询是否有人在使用角色
    public function queryrole_manager($where){
        $data=Db::table("wages_manager")
            ->where($where)
            ->order("registertime desc")
            ->select();
        return $data;
    }
    //查询角色列表
    public function getrolelist($where,$page,$pagesize){
        $data["list"]=Db::table("wages_role_manuapp")
            ->where($where)
            ->order("roleid desc")
            ->page($page,$pagesize)
            ->select();
        $data["count"]=Db::table("wages_role_manuapp")
            ->where($where)
            ->count("roleid");
        return $data;
    }
    //新增权限菜单
    public function add_auth_app($data){
        $res=Db::table("wages_auth_manuapp")
            ->insert($data);
        return $res;
    }
    //修改权限菜单
    public function update_auth_app($where,$data){
        $res=Db::table("wages_auth_manuapp")
            ->where($where)
            ->update($data);
        return $res;
    }
    //删除权限菜单
    public function del_auth_app($where){
        $res=Db::table("wages_auth_manuapp")
            ->where($where)
            ->delete();
        return $res;
    }
    //查询权限菜单
    public function query_auth_app($where=null){
        $data=Db::table("wages_auth_manuapp")
            ->where($where)
            ->order("authid desc")
            ->select();
        return $data;
    }
    //查询角色拥有的权限菜单，查询菜单是否被角色使用
    public function query_user_auth($where){
        $data=Db::table("wages_role_auth_manuapp")
            ->where($where)
            ->order("authid desc")
            ->select();
        return $data;
    }
    //更新某个角色的权限
    public function update_user_auth($role,$data){
        Db::startTrans();
        $delres=Db::table("wages_role_auth_manuapp")
            ->where(["roleid"=>$role])
            ->delete();
        if($delres>-1){
            $addres=Db::table("wages_role_auth_manuapp")
                ->insertAll($data);
        }
        if($addres){
            Db::commit();
            return true;
        }
        else{
            Db::rollback();
            return false;
        }
    }
    //查询某个角色对应的部门
    public function getroledata($where){
        $data=Db::table("wages_role_data")
            ->where($where)
            ->select();
        return $data;
    }


}