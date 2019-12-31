<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 下午 7:14
 */

namespace app\admin\logic;

use app\admin\model\Auth as authmodel;

class Auth
{
    public function role_add($data){
        $model = new authmodel;
        $info= $model->getroleinfo(["rolename"=>$data["rolename"]]);
        if(isset($info[0]["roleid"])){
            //存在则不重复添加
            return retmsg(-1,null,"该角色名已存在，请勿重复添加");
        }
        $res=$model->role_add($data);
        if($res){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //更新
    public function role_update($data){
        $model = new authmodel;
        $info= $model->getroleinfo(["rolename"=>$data["rolename"]]);
        if(!empty($info[0]["roleid"]) && $info[0]["roleid"]!=$data["roleid"]){
            //存在则不重复添加
            return retmsg(-1,null,"该角色名已存在");
        }
        $res= $model->role_update(["roleid"=>$data["roleid"]],["rolename"=>$data["rolename"]]);
        if($res>=0){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //删除
    public function role_del($data){
        $model = new authmodel;
        $user=$model->queryrole_manager(["approleid"=>$data["roleid"]]);
        if(count($user)>0){
            return retmsg(-1,null,"该角色正在使用中，无法删除");
        }
        $res=$model->role_del(["roleid"=>$data["roleid"]]);
        if ($res){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //查询角色列表
    public function getrolelist($seachkey=null,$page,$pagesize){
        $where["enable"]=1;
        if($seachkey){
            $where["rolename"]=array('like',"%$seachkey%");
        }
        $model = new authmodel;
        $data=$model->getrolelist($where,$page,$pagesize);
        return retmsg(0,$data);
    }
    //查询角色权限
    public function query_role_auth($roleid){
        $model = new authmodel;
        $checkautharr=array();
        $roleauth=$model->query_user_auth(["roleid"=>$roleid]);
        foreach ($roleauth as $k=>$v){
            $checkautharr[$v["authid"]]=1;
        }
        $firstaurh= $model->query_auth_app(["pid"=>0]);
        foreach ($firstaurh as $fk=>$fv){
            $firstaurh[$fk]["checked"]= isset($checkautharr[$fv["authid"]])?$checkautharr[$fv["authid"]]:0;
            //获取二级菜单
            $secondauth= $model->query_auth_app(["pid"=>$fv["authid"]]);
            foreach ($secondauth as $sk=>$sv){
                $secondauth[$sk]["checked"]=isset($checkautharr[$sv["authid"]])?$checkautharr[$sv["authid"]]:0;
                $three = $model->query_auth_app(["pid"=>$sv["authid"]]);
                foreach ($three as $tk=>$tv){
                    $three[$tk]["checked"]=isset($checkautharr[$tv["authid"]])?$checkautharr[$tv["authid"]]:0;
                    $three[$tk]["children"]=[];
                }
                $secondauth[$sk]["children"]=$three;
            }
            $firstaurh[$fk]["children"]=$secondauth;
        }
        return retmsg(0,$firstaurh);
    }
    //设置角色权限
    public function set_role_auth($data){
        $model = new authmodel;
        $temp=array();
        foreach ($data["auth"] as $k=>$v){
            $temp[]=array("roleid"=>$data["roleid"],"authid"=>$v);
        }
        $res=$model->update_user_auth($data["roleid"],$temp);
        if ($res){
            //更新该角色的token
            $url=config('appurl')."/manufacture/public/index.php/api/Extendc/deltoken/role/".$data["roleid"];
            file_get_contents($url);
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //新增菜单
    public function add_auth_app($data){
        $model = new authmodel;
        $addlog = $model->query_auth_app(["pid"=>$data["pid"],"authname"=>$data["authname"]]);
        if(isset($addlog[0]["authid"])){
            return retmsg(-1,null,"该菜单已存在");
        }
        $res = $model->add_auth_app($data);
        if($res){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //更新菜单
    public function update_auth_app($data){
        $model = new authmodel;
        $pid = $model->query_auth_app(["authid"=>$data["authid"]]);
        $updatelog = $model->query_auth_app(["pid"=>$pid[0]["pid"],"authname"=>$data["authname"]]);
        if(isset($updatelog[0]["authid"]) && $updatelog[0]["authid"]!=$data["authid"]){
            return retmsg(-1,null,"该模块已存在!");
        }
        $res=$model->update_auth_app(["authid"=>$data["authid"]],["authname"=>$data["authname"],"lable"=>$data["lable"]]);
        if($res>-1){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //删除菜单
    public function del_auth_app($data){
        $model = new authmodel;
        //查询菜单是否被使用
        $dellog = $model->query_user_auth(["authid"=>$data["authid"]]);
        if(isset($dellog[0]["roleid"])){
            return retmsg(-1,null,"权限已被使用，无法删除");
        }
        $res = $model->del_auth_app(["authid"=>$data["authid"]]);
        if($res){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    //查询菜单
    public function query_auth_app(){
        $model = new authmodel;
        $firstaurh= $model->query_auth_app(["pid"=>0]);
        foreach ($firstaurh as $fk=>$fv){
            //获取二级菜单
            $secondauth= $model->query_auth_app(["pid"=>$fv["authid"]]);
            foreach ($secondauth as $sk=>$sv){
                $three = $model->query_auth_app(["pid"=>$sv["authid"]]);
                foreach ($three as $tk=>$tv){
                    $three[$tk]["children"]=[];
                }
                $secondauth[$sk]["children"]=$three;
            }
            $firstaurh[$fk]["children"]=$secondauth;
        }
        return retmsg(0,$firstaurh);
    }
}