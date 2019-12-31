<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 16:41
 */

namespace app\admin\model;


use think\Db;

class PeopleStandard
{
    public function addPeopleStandard($data)
    {
        $config = db('', config('database.manufa_connection'));
        $ret =$config->table("manufa_people_standard")->insertGetId($data,true);
        return $ret;
    }

    public function getDataInfo($table,$where,$field)
    {
        $config = db('', config('database.manufa_connection'));
        $list = $config->table($table)->where($where)->field($field)->select();
        return $list;
    }

    public function updatePeopleStandard($where,$data)
    {
        $config = db('', config('database.manufa_connection'));
        $ret = $config->table("manufa_people_standard")->where($where)->update($data);
        return $ret;
    }

    public function getPeopleStandard($where,$page,$pagesize)
    {
        $dept = Db::table('wages_dept')->where('state',1)->select();
        $deptarr = array_column($dept,'deptname','deptid');
        $config = db('', config('database.manufa_connection'));
        $list = $config->table("manufa_people_standard")
              ->where($where)
              ->field("id,deptid,groupid,number,create_byuser,create_time")
              ->order("id desc")
              ->page($page,$pagesize)
              ->select();
        foreach ($list as $key=>&$value){
            $value['deptname'] = $deptarr[$value['deptid']];
            $value['groupname'] = $deptarr[$value['groupid']];
        }
        return $list;
    }

    public function getPeopleStandardCount($where)
    {
        $config = db('', config('database.manufa_connection'));
        $count = $config->table("manufa_people_standard")
            ->where($where)
            ->field("id,deptid,groupid,number,create_byuser,create_time")
            ->order("id desc")
            ->count();
        return $count;
    }

    public function exportPeopleStandard($where)
    {
        //部门 班组 标准人数 操作人员 操作时间
        $config = db('', config('database.manufa_connection'));
        $dept = Db::table('wages_dept')->where('state',1)->select();
        $deptarr = array_column($dept,'deptname','deptid');
        $list = $config->table("manufa_people_standard")
            ->where($where)
            ->order("id desc")
            ->select();
        $dataList = [];
        foreach ($list as $k=>$v){
            $data['deptname'] = $deptarr[$v['deptid']];
            $data['groupname'] = $deptarr[$v['groupid']];
            $data['number'] = $v['number'];
            $data['create_byuser'] = $v['create_byuser'];
            $data['create_time'] = $v['create_time'];
            $dataList[]=$data;
        }
        return $dataList;
    }
}