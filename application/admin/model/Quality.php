<?php
/**
 * Created by PhpStorm.
 * User: 000
 * Date: 2019/11/1
 * Time: 14:54
 */

namespace app\admin\model;



use think\Db;

class Quality
{
    public function addDirect($data)
    {
        $res =\db('','database.manufa_connection')->table('manufa_quality_dianzi') 
            ->insertAll($data,true);
        return $res;
    }

    public function selectDirect($str,$page=1,$pagesize=1000000){
        $res = \db('', 'database.manufa_connection')->table('manufa_quality_dianzi')
            ->where($str)
            ->order('riqi desc')
            ->page($page,$pagesize)
            ->select();
        $count= \db('', 'database.manufa_connection')->table('manufa_quality_dianzi')
            ->where($str)
            ->count();
        return [$res,$count];
    }

    public function deleteDirect($id){
        $res =\db('','database.manufa_connection')->table('manufa_quality_dianzi')
            ->where('id','in',$id)
            ->delete();
        return $res;
    }

    public function updateDirect($data){
        $res = \db('','database.manufa_connection')->table('manufa_quality_dianzi')
            ->update($data);
        return $res;
    }

    public function getGongxugd(){ //获取工序id
        //获取工序段
        $res =\db('','database.manufa_connection')->table('manufa_quality_gongxu')
            ->select();
        return $res;
    }

    public function addFdmDirect($data){ //新增防盗门
        $res = \db('','database.manufa_connection')->table('manufa_quality_gongxu')
            ->insert($data,true);
        return $res;
    }
}