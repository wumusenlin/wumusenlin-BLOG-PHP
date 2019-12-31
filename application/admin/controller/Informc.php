<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/28
 * Time: 17:16
 */

namespace app\admin\controller;

use think\Loader;

class Informc
{
    /**
     * 类型下拉
     * @param string $token
     * @return mixed
     */
    public function gettypeList($token = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $informM = Loader::model('Inform');
        $ret = $informM->gettypeList();
        return retmsg(0,$ret);
    }
    /**
     * 部门下拉
     * @param string $token
     * @return mixed
     */
    public function getdeptList($token = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $memUserInfo = checktoken($token);
        if (!$memUserInfo) {
            return array("resultcode" => -2, "resultmsg" => "用户令牌失效，请重新登录", "data" => null);
        }
        $informM = Loader::model('Inform','logic');
        $ret = $informM->getdeptList();
        return $ret;
    }
    /**
     * 查询人员
     * @param string $token
     * @return mixed
     */
    public function getuserList($token = '')
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
//        $postData = json_decode('{"receiverName":"朱发明","deptId":"","orderby":"asc"}', true);
        $informM = Loader::model('Inform','logic');
        foreach ($postData as $index => $datum) {
            if (!empty($datum))
                $datum = urlencode($datum);
            $arr[] = $index .'='.$datum;
        }
        if (!empty($arr))
            $postData = implode('&',$arr);
        $ret = $informM->getuserList($postData);
        return $ret;
    }
    /**
     * 添加/修改通知
     * @param string $token
     * @return mixed
     */
    public function addInform($token = '')
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
        $informM = Loader::model('Inform','logic');
        if (array_key_exists("id", $postData)){
            $ret = $informM->updateInform($postData);
        }else{
            $postData['create_user'] = $memUserInfo['name'];
//        $postData['create_byuser'] = "袁伟仁";
            $ret = $informM->addInform($postData);
        }
        return $ret;
    }

    public function delInform($token = '')
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
        $informM = Loader::model('Inform','logic');
        $ret = $informM->delInform($postData);
        return $ret;
    }

    public function getInform($token = '',$effectiveDate='',$page=1,$pagesize=10,$title='',$type='',$states='')
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
       $where1 = 'isdeleted=0';
        if(!empty($title))
        {
            $where1 = $where1." and t1.title like '%$title%'";
        }
        if(!empty($type))
        {
            $where1 = $where1." and t1.type = $type";
        }
        if(!empty($states))
        {
            $date = date('Y-m-d H:i:s',time());
            if ($states=='已停止'){
                $where1 = $where1." and t1.effectiveDate_end <= '$date'";
            }elseif ($states=='正在发布'){
                $where1 = $where1." and t1.effectiveDate_end is null or t1.effectiveDate_end>='$date'";
            }
        }
        if(!empty($effectiveDate))
        {
            $effectiveDate = explode(',',$effectiveDate);
           if (!empty($effectiveDate[0])){
               $date1 = date('Y-m-d H:i:s',strtotime($effectiveDate[0]));
               $where1 = $where1." and (t1.effectiveDate_end is null or t1.effectiveDate_end>='$date1') and t1.effectiveDate_start<='$date1'";
           }
            if (!empty($effectiveDate[1])){
                $date2 = date('Y-m-d H:i:s',strtotime($effectiveDate[1]));
                $where1 = $where1." and (t1.effectiveDate_end is null or t1.effectiveDate_end>='$date2') and t1.effectiveDate_start<='$date2'";
            }
        }
        $informM = Loader::model('Inform','logic');
//        $ret = $informM->getInform($where,$memUserInfo['gonghao'],$page,$pagesize);
        $ret = $informM->getInform($where1,$page,$pagesize);
        $header = array(
            array("headerName"=>"类型","field"=>"typeName"),
            array("headerName"=>"标题","field"=>"title"),
            array("headerName"=>"发布人","field"=>"publisher"),
            array("headerName"=>"发布时间","field"=>"releaseTime"),
            array("headerName"=>"生效开始时间","field"=>"startTime"),
            array("headerName"=>"生效结束时间","field"=>"endTime"),
            array("headerName"=>"标题图","field"=>"titleMap"),
            array("headerName"=>"状态","field"=>"statesName"),
        );
        $ret['data']['header']=$header;
        return $ret;
    }
}