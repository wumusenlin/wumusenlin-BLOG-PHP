<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 17:16
 */

namespace app\admin\logic;


use think\Db;
use think\Loader;

class Inform
{
    public function addInform($data)
    {
        $modelData = Loader::model('Inform');
        $nowTime = date("Y-m-d H:i:s");
        $data1['type']= $data['type'];
        $data1['title']= $data['title'];
        $data1['dept']= implode(',',$data['dept']);
        $data1['user']= implode(',',$data['person']);
        $data1['effectiveDate_start']= $data['effectiveDate'][0];
        $data1['effectiveDate_end']= $data['effectiveDate'][1];
        $data1['title_img']= json_encode($data['titleMap']);
        $data1['details']= $data['details'];
        $data1['attachment']= json_encode($data['img']);
        $data1['create_time']= $nowTime;
        $data1['create_user']= $data['create_user'];
        $data1['isdeleted']= 0;
        $ret = $modelData->addInform($data1);
        if ($ret){
            return retmsg(0);
        }else{
            return retmsg(-1);
        }
    }

    public function updateInform($data)
    {
        $modelData = Loader::model('Inform');
        //判断要修改的记录是否存在
        if(empty($data['id']))
        {
            return array("resultcode" => -1, "resultmsg" =>"请传入要修改的通知ID！", "data" => null);
        }
        $checkexlist = $modelData->getDataInfo('manufa_inform_data',array('id'=>$data['id']),"id");
        if(count($checkexlist) == 0)
        {
            return array("resultcode" => -1, "resultmsg" => "要修改的通知信息不存在或已经删除！", "data" => null);
        }
        $updateid = $data['id'];
        unset($data['id']);
        $data1['type']= $data['type'];
        $data1['title']= $data['title'];
        $data1['dept']= implode(',',$data['dept']);
        $data1['user']= implode(',',$data['person']);
        $data1['effectiveDate_start']= $data['effectiveDate'][0];
        $data1['effectiveDate_end']= $data['effectiveDate'][1];
        $data1['title_img']= json_encode($data['titleMap']);
        $data1['details']= $data['details'];
        $data1['attachment']= json_encode($data['img']);
        $ret = $modelData->updateInform(array('id'=>$updateid),$data1);
        return retmsg(0);
    }

    public  function delInform($postData)
    {
        $deductM = Loader::model('Inform');
        $ids=$postData["ids"];
        $ret = $deductM->updateInform(array('id'=>array('IN',$ids)),array("isdeleted"=>1));
        if($ret<=0)
        {
            return array("resultcode" => 0, "resultmsg" => "本次无信息被删除", "data" => null);
        }
        else
        {
            return array("resultcode" => 0, "resultmsg" => "删除成功", "data" => null);
        }
    }

    public function getInform($where,$page,$pagesize)
    {
        $modelData = Loader::model('Inform');
        $list = $modelData->getInform($where,$page,$pagesize);
        $count = $modelData->getInformCount($where);
        return array("resultcode" => 0, "resultmsg" => "查询成功", "data" => array('list'=>$list,'count'=>$count));
    }

    public function getdeptList($id=''){
        $getInfo = "http://172.16.15.88/NoticeController/getReceiverTree";
        $ch = curl_init($getInfo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        //超时2s
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
        $json = curl_exec($ch);
        $result = json_decode($json,true);
        $curl_errno = curl_errno($ch);
//        $result = json_decode($result,true);
        if(!empty($id)){
            $deptarr = db('', config('database.manufa_connection'))->query("select dept from manufa_inform_data where id =$id");
            $deptarr = explode(',',$deptarr[0]['dept']);
        }else{
            $deptarr = array();
        }
        $dataList = array();
        foreach ($result as $key=>$value){
            if ($value['pid']=='all'){
                $data['pid'] = $value['pid'];
                $data['deptname'] = $value['text'];
                $data['deptid'] = $value['id'];
                $data['checked'] = 0;
                $data['children'] = [];
                foreach ($result as $rk=>$rv){
                    if ($rv['pid']==$data['deptid']){
                        $data1['pid'] = $rv['pid'];
                        $data1['deptname'] = $rv['text'];
                        $data1['deptid'] = $rv['id'];
                        if (in_array($data1['deptid'],$deptarr)){
                            $data1['checked'] = 1;
                        }else{
                            $data1['checked'] = 0;
                        }
                        $data1['children'] = [];
                        foreach ($result as $rk1=>$rv1){
                            if ($rv1['pid']==$data1['deptid']){
                                $data2['pid'] = $rv1['pid'];
                                $data2['deptname'] = $rv1['text'];
                                $data2['deptid'] = $rv1['id'];
                                if (in_array($data2['deptid'],$deptarr)){
                                    $data2['checked'] = 1;
                                }else{
                                    $data2['checked'] = 0;
                                }
                                $data2['children'] = [];
                                foreach ($result as $rk2=>$rv2){
                                    if ($rv2['pid']==$data2['deptid']) {
                                        $data3['pid'] = $rv2['pid'];
                                        $data3['deptname'] = $rv2['text'];
                                        $data3['deptid'] = $rv2['id'];
                                        if (in_array($data3['deptid'],$deptarr)){
                                            $data3['checked'] = 1;
                                        }else{
                                            $data3['checked'] = 0;
                                        }
                                        $data3['children'] = [];
                                        foreach ($result as $rk3=>$rv3){
                                            if ($rv3['pid']==$data3['deptid']) {
                                                $data4['pid'] = $rv3['pid'];
                                                $data4['deptname'] = $rv3['text'];
                                                $data4['deptid'] = $rv3['id'];
                                                if (in_array($data4['deptid'],$deptarr)){
                                                    $data4['checked'] = 1;
                                                }else{
                                                    $data4['checked'] = 0;
                                                }
                                                $data4['children'] = [];
                                                $data3['children'][] = $data4;
                                            }
                                        }
                                        $data2['children'][] = $data3;
                                    }
                                }
                                $data1['children'][]=$data2;
                            }
                        }
                        $data['children'][]=$data1;
                    }
                }
                $dataList[] = $data;
            }
        }
//        foreach ($dataList as $k=>&$v){
//            foreach ($result as $rk=>$rv){
//                if ($rv['pid']==$v['deptid']){
//                    $data1['pid'] = $rv['pid'];
//                    $data1['deptname'] = $rv['text'];
//                    $data1['deptid'] = $rv['id'];
//                    $data1['checked'] = 0;
//                    $data1['children'] = [];
//                    $v['children'][]=$data1;
//                }
//            }
//        }
        $dataLists['pid'] = 'first';
        $dataLists['deptname'] = '所有部门';
        $dataLists['deptid'] = '';
        $dataLists['checked'] = 0;
        $dataLists['children'] = $dataList;
        return array("resultcode" => 0, "resultmsg" => "查询成功", "data" =>$dataLists);
    }

    public function getuserList($data){
        $getInfo = "http://172.16.15.88/NoticeController/getReceiverList?$data";
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$getInfo);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $postData = [];
        $postData = json_encode($postData);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_POST, 1);
        $dataList = curl_exec($curl);
        curl_close($curl);
        $dataList = json_decode($dataList,true);
//        $where = '';
//        if (!empty($data['receiverName'])){
//            $name = $data['receiverName'];
//            $where = $where." AND USERNAME like '%$name%'";
//        }
//        if ((!empty($data['deptId']))){
//            $dept = $data['deptId'];
//            $where = $where." and ERP_DEPT_CODE='$dept'";
//        }
//        $config = db('', config('database.sales_connection'));
//        $dataList =  $config->query("select ERP_USER_ID personId,USERNAME personName from sell_users where STATE=1 $where");
//       foreach ($dataList as $k=>&$v){
//           $v =array_change_key_case($v,CASE_LOWER);
//       }
        $dataList1=array();
        foreach ($dataList as $k=>$v){
            $datalist['personId'] = $v['receiverId'];
            $datalist['personName'] = $v['receiverName'];
            $dataList1[] =$datalist;
        }
        return array("resultcode" => 0, "resultmsg" => "查询成功", "data" => $dataList1);
    }
}