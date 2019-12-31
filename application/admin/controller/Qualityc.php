<?php
/**
 * Created by PhpStorm.
 * User: 000
 * Date: 2019/11/1
 * Time: 14:52
 */

namespace app\admin\controller;


use app\admin\logic\Quality;

class Qualityc
{
    public function importDirect($token, $gongxud)
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With'); 
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $importL = new Quality();
        $res = $importL->importDirect($gongxud, $userinfo);
        return $res;
    }

    public function exprotDirect($token, $gongxud, $riqi = '', $orderno = '', $product_type = '')
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $importL = new Quality();
        $importL->exportDirect($gongxud, $riqi, $orderno, $product_type);
    }

    public function addDirect($token)
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $data['data']['username'] = $userinfo['name'];
        $data['data']['directlv'] = $data['data']['directlv'] . '%';
        $importM = new \app\admin\model\Quality();
        $res = $importM->addDirect($data['data']);
        if (!empty($res)) {
            return retmsg(0, '', '新增成功');
        } else {
            return retmsg(-1, '', '新增失败');
        }
    }

    public function deleteDirect($id, $token)
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $importM = new \app\admin\model\Quality();
        $res = $importM->deleteDirect($id);
        if (!empty($res)) {
            return retmsg(0, '', '删除成功');
        } else {
            return retmsg(0, '', '删除失败');
        }
    }

    public function updateDirect($token)
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $data = $data['data'];
        $data['username'] = $userinfo['name'];
//        $data['directlv'] = ($data['mach_num'] - $data['unqualified']) . '%';
        $data['directlv'] = $data['directlv'] . '%';
        $importM = new \app\admin\model\Quality();
        $res = $importM->updateDirect($data);
        if ($res>-1) {
            return retmsg(0, '', '编辑成功');
        } else {
            return retmsg(-1, '', '编辑失败');
        }
    }

    public function selectDirect($token = '', $page = 1, $pagesize = 1000000) //查询
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $selectL = new Quality();
        $res = $selectL->selectDirect($data['data'], $page, $pagesize);
        return $res;
    }

    public function getGongxugd($token)
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        $userinfo = checktoken($token);
        if (!$userinfo) {
            return retmsg(-2);
        }
        $importL = new Quality();
        $res = $importL->getGongxugd();
        return $res;
    }

}