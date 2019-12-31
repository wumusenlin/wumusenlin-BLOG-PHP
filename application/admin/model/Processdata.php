<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 下午 3:11
 */

namespace app\admin\model;

use think\Db;

class Processdata
{
    public function processdata_add($data){
        $config = config('database.manufa_connection');
        $res=Db('',$config)->table("manufa_process_data")
            ->insert($data);
        return $res;
    }
    public function processdata_update($where,$data){
        $config = config('database.manufa_connection');
        $res=Db('',$config)->table("manufa_process_data")
            ->where($where)
            ->update($data);
        return $res;
    }
    public function processdata_query($where,$page,$pagesize){
        $config = config('database.manufa_connection');
        $res["list"]=Db('',$config)->table("manufa_process_data")
            ->where($where)
            ->order("id desc")
            ->page($page,$pagesize)
            ->select();
        $res["count"]=Db('',$config)->table("manufa_process_data")
            ->where($where)
            ->count("id");
        return $res;
    }
    public function processdata_del($ids){
        $config = config('database.manufa_connection');
        $res=Db('',$config)->table("manufa_process_data")
            ->where("id",'in',$ids)
            ->update(["state"=>"0"]);
        return $res;
    }
    //查询所有部门有效数据
    public function getalldept(){
        $data=Db::table("wages_dept")
            ->where("state=1")
            ->field("deptid,deptname")
            ->select();
        return $data;
    }
    //查询所有工序有效数据
    public function getallprocess(){
        $data=Db::table("wages_process")
            ->where("state=1")
            ->field("processid,processname")
            ->select();
        return $data;
    }


}