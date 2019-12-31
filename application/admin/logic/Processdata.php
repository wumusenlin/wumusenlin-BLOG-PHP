<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 下午 3:10
 */

namespace app\admin\logic;

use app\admin\model\Processdata as process_model;
use app\admin\model\Auth as authmodel;

class Processdata
{
    public function processdata_add($data){
        $date = date("Y-m-d H:i:s");
        $model = new process_model;
        $data["file"]=serialize($data["file"]);
        $data["update_time"]=$date;
        $res = $model->processdata_add($data);
        return $res;
    }
    public function processdata_update($data){
        $date = date("Y-m-d H:i:s");
        $model = new process_model;
        $data["file"]=serialize($data["file"]);
        $id = $data["id"];
        unset($data["id"]);
        $res = $model->processdata_update(["id"=>$id],$data);
        if($res){
           // $data["update_time"]=$date;
            $model->processdata_update(["id"=>$id],["update_time"=>$date]);
            return retmsg(0);
        }
        elseif($res==0){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
    public function processdata_query($dept=null,$group=null,$process=null,$filename=null,$page,$pagesize,$gonghao){
        $model = new process_model;
        $authmodel = new authmodel;
        $where["state"]="1";
        $user = $authmodel->queryrole_manager(["gonghao"=>$gonghao]);
        $deptdata = $authmodel ->getroledata(["roleid"=>$user[0]["roleid"]]);
        $deptarr=array();
        foreach ($deptdata  as $dk=>$dv){
            $deptarr[]=$dv["deptid"];
        }
        $where["groupid"]=array("in",$deptarr);
        if($dept){
            $where["deptid"]=$dept;
        }
        if($group){
            if(in_array($group,$deptarr)){
                $where["groupid"]=$group;
            }
            else{
                return retmsg(-1,null,"您没有该工段的的查询权限");
            }
        }
        if ($process){
            $where["processid"]=$process;
        }
        if ($filename){
            $where["file"]=array('like',"%$filename%");
        }
        $res = $model->processdata_query($where,$page,$pagesize);
        $alldept = $model->getalldept();
        $allprocess = $model->getallprocess();
        foreach ($alldept as $k=>$v){
            $deptarr[$v["deptid"]]=$v["deptname"];
        }
        foreach ($allprocess as $k=>$v){
            $processarr[$v["processid"]]=$v["processname"];
        }
        foreach ($res["list"] as $lk=>&$lv){
            $lv["file"]=unserialize($lv["file"]);
            $lv["deptname"]=$deptarr[$lv["deptid"]];
            $lv["groupname"]=$deptarr[$lv["groupid"]];
            $lv["processname"]=$processarr[$lv["processid"]];
        //    $lv["update_time"]=date("Y-m-d",strtotime($lv["update_time"]));
        }
        $res["header"]=array(
            array("headerName"=>"制造部门","field"=>"deptname"),
            array("headerName"=>"工段","field"=>"groupname"),
            array("headerName"=>"工序","field"=>"processname"),
            array("headerName"=>"版本","field"=>"version"),
            array("headerName"=>"上传日期","field"=>"update_time"),
            array("headerName"=>"文件","field"=>"file"),
            array("headerName"=>"备注","field"=>"remark")
        );
        return retmsg(0,$res);
    }
    //删除
    public function processdata_del($ids){
        $model = new process_model;
        $res = $model->processdata_del($ids);
        if($res){
            return retmsg(0);
        }
        else{
            return retmsg(-1);
        }
    }
}