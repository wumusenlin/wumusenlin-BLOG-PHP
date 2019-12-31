<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 16:40
 */

namespace app\admin\controller;


use app\admin\logic\PeopleStandard;
use think\controller\Rest;

class PeopleStandardc extends Rest
{
    /**
     * 添加人员标配
     * @param string $token
     * @return mixed
     */
    public function addPeopleStandard($token = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $postData = json_decode(file_get_contents("php://input"), true);
//        $postData = json_decode('{"deptid":4,"groupid":42,"number":"12"}', true);

        $postData['create_by'] = $memUserInfo['gonghao'];
        $postData['create_byuser'] = $memUserInfo['name'];

//        $postData['create_by'] = '00282685';
//        $postData['create_byuser'] = "袁伟仁";

        $changeM = new PeopleStandard();
        $ret = $changeM->addPeopleStandard($postData);
        return $ret;
    }
    /**
     * 修改人员标配
     * @param string $token
     * @return mixed
     */
    public function updatePeopleStandard($token = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $postData = json_decode(file_get_contents("php://input"), true);
//        $postData = json_decode('{"id":1,"deptid":4,"groupid":42,"number":"13"}', true);

        $postData['update_by'] = $memUserInfo['gonghao'];
        $postData['update_byuser'] = $memUserInfo['name'];

//        $postData['update_by'] = '00282685';
//        $postData['update_byuser'] = "袁伟仁";
        $changeM = new PeopleStandard();
        $ret = $changeM->updatePeopleStandard($postData);
        return $ret;
    }

    public function delPeopleStandard($token = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $postData = json_decode(file_get_contents("php://input"), true);
//        $postData = json_decode('{"ids":[1]}', true);

        $postData['delete_by'] = $memUserInfo['gonghao'];
        $postData['delete_byuser'] = $memUserInfo['name'];

//        $postData['delete_by'] = '00282685';
//        $postData['delete_byuser'] = "袁伟仁";
        $changeM = new PeopleStandard();
        $ret = $changeM->delPeopleStandard($postData);
        return $ret;
    }

    public function getPeopleStandard($token = '')
    {
        //部门 班组 标准人数 操作人员 操作日期
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $postData = json_decode(file_get_contents("php://input"), true);
//        $postData = json_decode('{"page":1,"pagesize":20,"dept":"4","group":""}', true);
        $where = array();
//        $where['roleid'] = $memUserInfo['roleid'];
//        $where['roleid'] = 1;
        $where['isdeleted'] = 0;

        if(!empty($postData['dept']))
        {
            $where['deptid'] = $postData['dept'];
        }
        if(!empty($postData['group']))
        {
            $where['groupid'] = $postData['group'];
        }
        $page = 1;
        $pagesize=20;
        if(!empty($postData["page"]))
        {
            $page = $postData["page"];
        }
        if(!empty($postData["pagesize"]))
        {
            $pagesize = $postData["pagesize"];
        }

        $changeM = new PeopleStandard();
//        $ret = $changeM->getPeopleStandard($where,$page,$pagesize);
        $ret = $changeM->getPeopleStandard($where,$page,$pagesize);
        $header = array(
            array("headerName"=>"部门","field"=>"deptname"),
            array("headerName"=>"班组","field"=>"groupname"),
            array("headerName"=>"标准人数","field"=>"number"),
            array("headerName"=>"操作人员","field"=>"create_byuser"),
            array("headerName"=>"操作时间","field"=>"create_time"),
        );
        $ret['data']['header']=$header;
        return $ret;
    }

    public function exportPeopleStandard($token = '',$dept = 0,$group = 0)
    {
        //部门 班组 代码字段 代码位数 代码 字段名（为扫描产品表中提供字段名：自行填写 成品 门框 窗花 玻璃等）统一名称
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $where = array();
//        $where['roleid'] = $memUserInfo['roleid'];
//        $where['roleid']=1;
        $where['isdeleted'] = 0;
        if(!empty($dept))
        {
            $where['deptid'] = $dept;
        }
        if(!empty($group))
        {
            $where['groupid'] = $group;
        }
        $changeM = new \app\admin\model\PeopleStandard();
        $data = $changeM->exportPeopleStandard($where);
        $header = ['部门','班组','标准人数','操作人员','操作日期'];
        $this->excelExport("人员标配表",$header,$data);
    }
    function excelExport($fileName = '', $headArr = [], $data=[]) {
        array_unshift($data,$headArr);
        $tr = "";
        foreach($data as $temp_key=>$temp_value){
            $temp_value = array_values($temp_value);
            foreach($temp_value as $arr_key=>$arr_val){
                $tr.=$arr_val.",";
            }
            $tr.="\n";
        }

        //echo $tr;exit;
        $tr = iconv('utf-8','GB18030',$tr);
        $filename = date('Y-m-d').$fileName.'.csv';
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $tr;
        exit();
    }
}