<?php
/**
 * Created by PhpStorm.
 * User: 000
 * Date: 2019/11/1
 * Time: 14:54
 */

namespace app\admin\logic;


class Quality
{
    public function importDirect($gongxud, $userinfo) 
    {
        set_time_limit(0);
        vendor("PHPExcel.Classes.PHPExcel");
        $file = $_FILES['file'] ['name'];
        $filetempname = $_FILES ['file']['tmp_name'];
        $filePath = str_replace('\\', '/', realpath(__DIR__ . '/../../../')) . '/upload/';
        $filename = explode(".", $file);
        $time = date("YmdHis");
        $filename [0] = $time;//取文件名t替换
        $name = implode(".", $filename); //上传后的文件名
        $uploadfile = $filePath . $name;
        $result = move_uploaded_file($filetempname, $uploadfile);
        if ($result) {
            $extension = substr(strrchr($file, '.'), 1);
            if ($extension != 'xlsx' && $extension != 'xls' && $extension != 'csv') {
                return retmsg(-1, ['errinfo' => []], '请上传Excel或csv文件！');
            }
            try {
                $objPHPExcel = \PHPExcel_IOFactory::load($uploadfile);
            } catch (\PHPExcel_Reader_Exception $e) {
                return retmsg(-1, ['errinfo' => []], '请重新上传文件');
            }
            $sheet = $objPHPExcel->getActiveSheet();//获取当前的工作表
            //获取列值
            $highestRow = $sheet->getHighestRow();
            $errData = [];
            for ($i = 2; $i <= $highestRow; $i++) {
                if ($gongxud == 'SMT') {
                    $riqi = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell('A' . $i)->getValue()));
                    $product_type = $sheet->getCell('B' . $i)->getValue();
                    $orderno = $sheet->getCell('C' . $i)->getValue();
                    $mach_num = $sheet->getCell('D' . $i)->getValue();
                    $unqualified = $sheet->getCell('E' . $i)->getValue();
                    $directlv = ($sheet->getCell('F' . $i)->getValue() * 100) . "%";
                    $xuhan = $sheet->getCell('G' . $i)->getValue();
                    $shaoxi = $sheet->getCell('H' . $i)->getValue();
                    $duanlu = $sheet->getCell('I' . $i)->getValue();
                    $libei = $sheet->getCell('J' . $i)->getValue();
                    $piaoyi = $sheet->getCell('K' . $i)->getValue();
                    $other = $sheet->getCell('L' . $i)->getValue();
                    $other_remark = $sheet->getCell('M' . $i)->getValue();
                    $create_time = date('Y-m-d H:i:s');
                    $data = [
                        'riqi' => $riqi,
                        'product_type' => $product_type,
                        'orderno' => $orderno,
                        'mach_num' => $mach_num,
                        'unqualified' => $unqualified,
                        'directlv' => $directlv,
                        'xuhan' => $xuhan,
                        'shaoxi' => $shaoxi,
                        'duanlu' => $duanlu,
                        'libei' => $libei,
                        'piaoyi' => $piaoyi,
                        'other' => $other,
                        'other_remark' => $other_remark,
                        'create_time' => $create_time,
                        'username' => $userinfo['name'],
                        'gongxud' => $gongxud
                    ];
                    if (is_string($data['mach_num']) || is_string($data['unqualified'])) {
                        $errData[] = [
                            'row' => $i,
                            'detail' => "请检查第.$i.行,加工量、不合格量数据是否为数字"
                        ];
                        continue;
                    }
                } elseif ($gongxud == 'MI') {
                    $riqi = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell('A' . $i)->getValue()));
                    $product_type = $sheet->getCell('B' . $i)->getValue();
                    $orderno = $sheet->getCell('C' . $i)->getValue();
                    $mach_num = $sheet->getCell('D' . $i)->getValue();
                    $unqualified = $sheet->getCell('E' . $i)->getValue();
                    $directlv = ($sheet->getCell('F' . $i)->getValue() * 100) . "%";
                    $xuhan = $sheet->getCell('G' . $i)->getValue();
                    $shaoxi = $sheet->getCell('H' . $i)->getValue();
                    $duanlu = $sheet->getCell('I' . $i)->getValue();
                    $debug = $sheet->getCell('J' . $i)->getValue();
                    $light = $sheet->getCell('K' . $i)->getValue();
                    $control = $sheet->getCell('L' . $i)->getValue();
                    $readcard = $sheet->getCell('M' . $i)->getValue();
                    $open = $sheet->getCell('N' . $i)->getValue();
                    $gaoqing = $sheet->getCell('O' . $i)->getValue();
                    $usb = $sheet->getCell('P' . $i)->getValue();
                    $view = $sheet->getCell('Q' . $i)->getValue();
                    $volice = $sheet->getCell('R' . $i)->getValue();
                    $download = $sheet->getCell('S' . $i)->getValue();
                    $rongduan = $sheet->getCell('T' . $i)->getValue();
                    $debug0 = $sheet->getCell('U' . $i)->getValue();
                    $other_remark = $sheet->getCell('V' . $i)->getValue();
                    $create_time = date('Y-m-d H:i:s');
                    $data = [
                        'riqi' => $riqi,
                        'product_type' => $product_type,
                        'orderno' => $orderno,
                        'mach_num' => $mach_num,
                        'unqualified' => $unqualified,
                        'directlv' => $directlv,
                        'xuhan' => $xuhan,
                        'shaoxi' => $shaoxi,
                        'duanlu' => $duanlu,
                        'debug' => $debug,
                        'light' => $light,
                        'control' => $control,
                        'readcard' => $readcard,
                        'control' => $control,
                        'open' => $open,
                        'gaoqing' => $gaoqing,
                        'usb' => $usb,
                        'view' => $view,
                        'volice' => $volice,
                        'download' => $download,
                        'rongduan' => $rongduan,
                        'debug0' => $debug0,
                        'other_remark' => $other_remark,
                        'create_time' => $create_time,
                        'username' => $userinfo['name'],
                        'gongxud' => $gongxud
                    ];
                    if (!is_numeric($data['mach_num']) || !is_numeric($data['unqualified'])) {
                        $errData[] = [
                            'row' => $i,
                            'detail' => "请检查第.$i.行,加工量、不合格量数据是否为数字"
                        ];
                        continue;
                    }
                }
                $importmM = new \app\admin\model\Quality();
                $importmM->addDirect($data);
            }
            return retmsg(0, ['errinfo' => $errData], '导入成功');
        }
    }

    public function exportDirect($gongxud, $riqi, $orderno, $product_type)
    {
        vendor("PHPExcel.Classes.PHPExcel");
        $objPhpExcel = new \PHPExcel();
        $quality = new \app\admin\model\Quality();
        $str['gongxud'] = $gongxud;
        if (!empty($riqi)) {
            $str['riqi'] = ['=', $riqi];
        }
        if (!empty($orderno)) {
            $str['orderno'] = ['like', "%$orderno"];
        }
        if (!empty($product_type)) {
            $str['product_type'] = ['like', "%$product_type%"];
        }
        $selectData = $quality->selectDirect($str);
        $gongxud = $str['gongxud'];
        $data = $selectData[0];
        for ($i = 0; $i < count($data); $i++) {
            $objPhpExcel->createSheet();
            if ($gongxud == 'SMT') {
                $objSheet = $objPhpExcel->setActiveSheetIndex($i);
                $objSheet->setCellValue('A1', '日期');
                $objSheet->setCellValue('B1', '产品型号');
                $objSheet->setCellValue('C1', '订单号');
                $objSheet->setCellValue('D1', '加工量');
                $objSheet->setCellValue('E1', '不合格量');
                $objSheet->setCellValue('F1', '直通率');
                $objSheet->setCellValue('G1', '虚焊');
                $objSheet->setCellValue('H1', '少锡');
                $objSheet->setCellValue('I1', '短路');
                $objSheet->setCellValue('J1', '立碑');
                $objSheet->setCellValue('K1', '漂移');
                $objSheet->setCellValue('L1', '其他异常');
                $objSheet->setCellValue('M1', '未达标异常说明');
            } elseif ($gongxud == 'MI') {
                $objSheet = $objPhpExcel->setActiveSheetIndex($i);
                $objSheet->setCellValue('A1', '日期');
                $objSheet->setCellValue('B1', '产品型号');
                $objSheet->setCellValue('C1', '订单号');
                $objSheet->setCellValue('D1', '加工量');
                $objSheet->setCellValue('E1', '不合格量');
                $objSheet->setCellValue('F1', '直通率');
                $objSheet->setCellValue('G1', '虚焊');
                $objSheet->setCellValue('H1', '少锡');
                $objSheet->setCellValue('I1', '短路');
                $objSheet->setCellValue('J1', '调试坏');
                $objSheet->setCellValue('K1', '灯不亮');
                $objSheet->setCellValue('L1', '不遥控');
                $objSheet->setCellValue('M1', '不读卡');
                $objSheet->setCellValue('N1', '不开机');
                $objSheet->setCellValue('O1', '高清坏');
                $objSheet->setCellValue('P1', 'usb坏');
                $objSheet->setCellValue('Q1', '图像异常');
                $objSheet->setCellValue('R1', '声音异常');
                $objSheet->setCellValue('S1', '下载失败');
                $objSheet->setCellValue('T1', '熔断失败');
                $objSheet->setCellValue('U1', '调试为零');
                $objSheet->setCellValue('V1', '未达标异常说明');
            }
            $objSheet->setTitle($gongxud == 'SMT' ? 'SMT' : 'MI');
            $heightCol = $objSheet->getHighestColumn();
            $heightCol = $heightCol . '1';
            $objSheet->getStyle("A1:$heightCol")->getFont()->setBold(true);
            $objSheet->freezePane('A2');
            $objPhpExcel->getActiveSheet($i)->getStyle("A1:$heightCol")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            foreach ($data as $key => $value) {
                $hang = $key + 2;
                if ($gongxud == 'SMT') {
                    $objSheet->setCellValue('A' . $hang, trim($value['riqi'], '/'));
                    $objSheet->setCellValue('B' . $hang, trim($value['product_type'], '/'));
                    $objSheet->setCellValue('C' . $hang, trim($value['orderno'], '/'));
                    $objSheet->setCellValue('D' . $hang, trim($value['mach_num'], '/'));
                    $objSheet->setCellValue('E' . $hang, $value['unqualified']);
                    $objSheet->setCellValue('F' . $hang, trim($value['directlv'], '/'));
                    $objSheet->setCellValue('G' . $hang, trim($value['xuhan'], '/'));
                    $objSheet->setCellValue('H' . $hang, $value['shaoxi']);
                    $objSheet->setCellValue('I' . $hang, $value['duanlu']);
                    $objSheet->setCellValue('J' . $hang, $value['libei']);
                    $objSheet->setCellValue('K' . $hang, $value['piaoyi']);
                    $objSheet->setCellValue('L' . $hang, $value['other']);
                    $objSheet->setCellValue('M' . $hang, $value['other_remark']);
                } elseif ($gongxud == 'MI') {
                    $objSheet->setCellValue('A' . $hang, trim($value['riqi'], '/'));
                    $objSheet->setCellValue('B' . $hang, trim($value['product_type'], '/'));
                    $objSheet->setCellValue('C' . $hang, trim($value['orderno'], '/'));
                    $objSheet->setCellValue('D' . $hang, trim($value['mach_num'], '/'));
                    $objSheet->setCellValue('E' . $hang, $value['unqualified']);
                    $objSheet->setCellValue('F' . $hang, trim($value['directlv'], '/'));
                    $objSheet->setCellValue('G' . $hang, trim($value['xuhan'], '/'));
                    $objSheet->setCellValue('H' . $hang, $value['shaoxi']);
                    $objSheet->setCellValue('I' . $hang, $value['duanlu']);
                    $objSheet->setCellValue('J' . $hang, $value['debug']);
                    $objSheet->setCellValue('K' . $hang, $value['light']);
                    $objSheet->setCellValue('L' . $hang, $value['control']);
                    $objSheet->setCellValue('M' . $hang, $value['readcard']);
                    $objSheet->setCellValue('N' . $hang, $value['open']);
                    $objSheet->setCellValue('O' . $hang, $value['gaoqing']);
                    $objSheet->setCellValue('P' . $hang, $value['usb']);
                    $objSheet->setCellValue('Q' . $hang, $value['view']);
                    $objSheet->setCellValue('R' . $hang, $value['volice']);
                    $objSheet->setCellValue('S' . $hang, $value['download']);
                    $objSheet->setCellValue('T' . $hang, $value['rongduan']);
                    $objSheet->setCellValue('U' . $hang, $value['debug0']);
                    $objSheet->setCellValue('V' . $hang, $value['other_remark']);
                }
            }
        }
        $objPhpExcel->setActiveSheetIndex(0);
        $title = $gongxud == 'SMT' ? '板卡生产段直通率报表统计—SMT' : '板卡生产段直通率报表统计—MI';
        $this->downLoadExcel($objPhpExcel, $title);
    }

    public function downLoadExcel($objPhpExcel, $file_name)
    {
        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');
        // 下载这个表格，在浏览器输出
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename=' . $file_name . '.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

    public function selectDirect($data, $page, $pagesize)
    {
        $selectM = new \app\admin\model\Quality();
        $gongxud = $data['gongxud'];
        $str['gongxud'] = ['=', $gongxud];
        if (!empty($data['riqi'])) {
            $riqi = $data['riqi'];
            $str['riqi'] = ['=', $riqi];
        }
        if (!empty($data['orderno'])) {
            $orderno = $data['orderno'];
            $str['orderno'] = ['like', "%$orderno"];
        }
        if (!empty($product_type)) {
            $product_type = $data['product_type'];
            $str['product_type'] = ['like', "%$product_type"];
        }
        $res = $selectM->selectDirect($str, $page, $pagesize);
        foreach ($res[0] as $k => &$v) {
            $v["id"] = (string)$v["id"];
            $v["mach_num"] = (string)$v["mach_num"];
            $v["unqualified"] = (string)$v["unqualified"];
        }
        if ($gongxud == 'SMT') {
            $header = [
                ["headerName" => "日期", "field" => "riqi"],
                ["headerName" => "产品型号", "field" => "product_type"],
                ["headerName" => "订单号", "field" => "orderno"],
                ["headerName" => "加工量", "field" => "mach_num"],
                ["headerName" => "不合格量", "field" => "unqualified"],
                ["headerName" => "直通率", "field" => "directlv"],
                ["headerName" => "虚焊", "field" => "xuhan"],
                ["headerName" => "少锡", "field" => "shaoxi"],
                ["headerName" => "短路", "field" => "duanlu"],
                ["headerName" => "立碑", "field" => "libei"],
                ["headerName" => "漂移", "field" => "piaoyi"],
                ["headerName" => "其他异常", "field" => "other"],
                ["headerName" => "未达标异常说明", "field" => "other_remark"],
            ];
        } elseif ($gongxud == 'MI') {
            $header = [
                ["headerName" => "日期", "field" => "riqi"],
                ["headerName" => "产品型号", "field" => "product_type"],
                ["headerName" => "订单号", "field" => "orderno"],
                ["headerName" => "加工量", "field" => "mach_num"],
                ["headerName" => "不合格量", "field" => "unqualified"],
                ["headerName" => "直通率", "field" => "directlv"],
                ["headerName" => "虚焊", "field" => "xuhan"],
                ["headerName" => "少锡", "field" => "shaoxi"],
                ["headerName" => "短路", "field" => "duanlu"],
                ["headerName" => "测试坏", "field" => "debug"],
                ["headerName" => "灯不亮", "field" => "light"],
                ["headerName" => "不遥控", "field" => "control"],
                ["headerName" => "不读卡", "field" => "readcard"],
                ["headerName" => "不开机", "field" => "open"],
                ["headerName" => "高清坏", "field" => "gaoqing"],
                ["headerName" => "usb坏", "field" => "usb"],
                ["headerName" => "图像异常", "field" => "view"],
                ["headerName" => "声音异常", "field" => "volice"],
                ["headerName" => "下载失败", "field" => "download"],
                ["headerName" => "熔断失败", "field" => "rongduan"],
                ["headerName" => "调试为零", "field" => "debug0"],
                ["headerName" => "未达标异常说明", "field" => "other_remark"],
            ];
        }
        if (!empty($res)) {
            return retmsg(0, ['data' => $res[0], 'count' => $res[1], 'head' => $header], '查询成功');
        } else {
            return retmsg(-1, $res, '查询失败');
        }
    }

    public function getGongxugd() //工序
    {
        $importM = new \app\admin\model\Quality();
        $data = $importM->getGongxugd();
        if (!empty($data)) {
            foreach ($data as &$v) {
                $temp[] = [
                    'name' => $v['name'],
                    'label' => $v['gongxud'],
                ];
            }
            return retmsg(0, $temp, '操作成功');
        } else {
            return retmsg(-1, '', '操作失败');
        }
    }

}