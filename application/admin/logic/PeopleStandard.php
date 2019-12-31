<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 16:40
 */

namespace app\admin\logic;


use think\Loader;

class PeopleStandard
{
    public function addPeopleStandard($data)
    {
        $modelData = Loader::model('PeopleStandard');
        $nowTime = date("Y-m-d H:i:s");
        $data['create_time'] = $nowTime;
         $modelData->addPeopleStandard($data);
        return array("resultcode" => 0, "resultmsg" => "添加成功", "data" => null);

    }

    public function updatePeopleStandard($data)
    {
        $modelData = Loader::model('PeopleStandard');
        $nowTime = date("Y-m-d H:i:s");

        //判断要修改的记录是否存在
        if(empty($data['id']))
        {
            return array("resultcode" => -1, "resultmsg" =>"请传入要修改的人员标配ID！", "data" => null);
        }
        $checkexlist = $modelData->getDataInfo('manufa_people_standard',array('id'=>$data['id']),"id");
        if(count($checkexlist) == 0)
        {
            return array("resultcode" => -1, "resultmsg" => "要修改的人员标配记录信息不存在或已经删除！", "data" => null);
        }
        $data['update_time'] = $nowTime;
        $updateid = $data['id'];
        unset($data['id']);
        $modelData->updatePeopleStandard(array('id'=>$updateid),$data);
        return array("resultcode" => 0, "resultmsg" => "修改成功", "data" => null);
    }

    public  function delPeopleStandard($postData)
    {
        $deductM = Loader::model('PeopleStandard');
        $nowTime = date("Y-m-d H:i:s");
        $ids=$postData["ids"];
        $ret = $deductM->updatePeopleStandard(array('id'=>array('IN',$ids),'create_by'=>$postData['delete_by']),array("isdeleted"=>1,'delete_by'=>$postData['delete_by'],'delete_byuser'=>$postData['delete_byuser'],'delete_time'=>$nowTime));
        if($ret<=0)
        {
            return array("resultcode" => 0, "resultmsg" => "本次无信息被删除", "data" => null);
        }
        else
        {
            return array("resultcode" => 0, "resultmsg" => "删除成功", "data" => null);
        }
    }

    public function getPeopleStandard($where,$page,$pagesize)
    {
        $modelData = Loader::model('PeopleStandard');
        $list = $modelData->getPeopleStandard($where,$page,$pagesize);
        $count = $modelData->getPeopleStandardCount($where);
        return array("resultcode" => 0, "resultmsg" => "查询成功", "data" => array('list'=>$list,'count'=>$count));
    }
}