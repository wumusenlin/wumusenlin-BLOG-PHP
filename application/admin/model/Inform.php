<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 17:17
 */

namespace app\admin\model;


use think\Db;

class Inform
{
    public function gettypeList(){
        $config = db('', config('database.manufa_connection'));
        $res = $config->table('manufa_inform_type')->field('id typeId,type typeName')->select();
        return $res;
    }
    public function addInform($data)
    {
        $config = db('', config('database.manufa_connection'));
        $ret =$config->table("manufa_inform_data")->insert($data);
        return $ret;
    }

    public function getDataInfo($table,$where,$field)
    {
        $config = db('', config('database.manufa_connection'));
        $list = $config->table($table)->where($where)->field($field)->select();
        return $list;
    }

    public function updateInform($where,$data)
    {
        $config = db('', config('database.manufa_connection'));
        $ret = $config->table("manufa_inform_data")->where($where)->update($data);
        return $ret;
    }

    public function getInform($where,$page,$pagesize)
    {
        $config = db('', config('database.manufa_connection'));
        $sql = "select t1.id,t1.type,t1.title,t2.type typeName,create_user publisher,t1.create_time releaseTime,effectiveDate_start startTime,effectiveDate_end endTime,title_img titleMap,attachment annex,dept,user,details from 
manufa_inform_data t1,manufa_inform_type t2 where t1.type=t2.id and $where ORDER  BY  t1.id DESC ";
        $list = $config->query($sql);
        $list = array_slice($list,($page-1)*$pagesize,$pagesize);
        $deptarr =  db('', config('database.sales_connection'))->query("select DEPT_CODE,DEPT_NAME from B_DEPARTMENT");
        $deptarr = array_column($deptarr,'DEPT_NAME','DEPT_CODE');
        $date = date('Y-m-d H:i:s',time());
        foreach ($list as $k=>&$v){
            $v['titleMap'] = json_decode($v['titleMap']);
            $v['annex'] = json_decode($v['annex']);
            if (!empty($v['dept'])){
                $v['dept'] = explode(',',$v['dept']);
                foreach ($v['dept'] as $dk=>$dv){
                    $v['deptname'][]=$deptarr[$dv];
                }
            }else{
                $v['dept'] = [];
                $v['deptname'] = [];
            }
           if (!empty($v['user'])){
               $v['personId'] = explode(",",$v['user']);
               $pers = $v['personId'];
               foreach ($pers as $kk=>&$vv){
                   $vv = "'".$vv."'";
               }
               $user =implode(',',$pers);
               $name = db('', config('database.sales_connection'))->query("select ID personid,USERNAME personName from sell_users where STATE=1 and ID in ($user)");
               foreach ($name as $nk=>&$nv){
                   $nv = array_change_key_case($nv);
               }
               foreach ($name as $kk=>&$vv){
                  $vv['personId'] = $vv['personid'];
                  $vv['personName'] = $vv['personname'];
                  unset($vv['personname']);unset($vv['personid']);
               }
               $name = array_reverse($name);
               $v['personName'] =$name;
            }else{
               $v['personId'] = [];
               $v['personName'] = [];
           }
            unset($v['user']);
//            if ($v['startTime']>$date){
//                $v['statesName'] = "正在发布中";
//            }
//            elseif ($v['startTime']<$date&&$date<$v['endTime']){
//                $v['statesName'] = "正在发布";
//            }elseif ($date>$v['endTime']){
//                $v['statesName'] = "已停止";
//            }
            if (!empty($v['endTime'])){
                if($date<$v['endTime']){
                    $v['statesName'] = "正在发布";
                }elseif ($date>$v['endTime']){
                    $v['statesName'] = "已停止";
                }
            }else{
                $v['statesName'] = "正在发布";
            }
        }
        return $list;
    }

    public function getInformCount($where)
    {
        $config = db('', config('database.manufa_connection'));
        $sql = "select t1.id,t1.type,t1.title,t2.type typeName,create_user publisher,t1.create_time releaseTime,effectiveDate_start startTime,effectiveDate_end endTime,title_img titleMap,attachment annex,dept,user,details from 
manufa_inform_data t1,manufa_inform_type t2 where t1.type=t2.id and $where ORDER  BY  t1.id DESC ";
        $list = $config->query($sql);
        return count($list);
    }

}