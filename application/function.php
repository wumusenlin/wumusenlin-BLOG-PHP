<?php
use think\Db;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

//返回函数
function retmsg($retcode, $retdata=null, $retmessage=null)
{
    $retmsg = "";
    switch ($retcode) {
        case 0: { $retmsg = "操作成功"; break; }
        case -1: { $retmsg = "操作失败"; break; }
        case -2: { $retmsg = "token验证失败"; break; }
        default: { $retmsg = "未知错误";}
    }
    //处理orale大写转成小写
    if (!empty($retdata)) {
        foreach ($retdata as $k=>$v) {
            if (is_array($retdata[$k])) {
                $retdata[$k]=array_change_key_case($retdata[$k]);
            }
        }
    }
    return array("resultcode"=>$retcode,"resultmsg"=>empty($retmessage)?$retmsg:$retmessage,"data"=>$retdata);
}

//导入excel去空格
function excel_trim($content)
{
    if (is_object($content)) {
        $str=preg_replace("/(\s|\&nbsp\;||\xc2\xa0)/", "", $content->__toString());
    }
    $str=preg_replace("/(\s|\&nbsp\;||\xc2\xa0)/", "", $content);
    return $str;
}

//获取接口数据
function curl_get($url, $timeout=10)
{
    //初始化
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    return $output;
}

function curl_post($url,$timeout=20,$postData) {
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    $postData = json_encode($postData);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($curl, CURLOPT_TIMEOUT,$timeout);
    $data = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    //echo $data."  ";
    return $data;
}

function curl_http($url, $params, $method = 'POST', $header = array(), $multi = false)
{
    $opts = array(
        CURLOPT_POST          => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_BINARYTRANSFER=>true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER     => $header,
    );
    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method))
    {
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
			//$params=json_encode($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error) throw new Exception('请求发生错误：' . $error);
    return  $data;
}

function test($url, $array)
{
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $array );
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        print curl_error($ch);
    }
    curl_close($ch);
    var_dump($response);
}

function findNum($str='')
{
    $e = "/\d+/";
    preg_match_all($e, $str, $arr);
    return $arr[0][0];
}

/**
 * 修改pdo缓存计算错误
 * 使用cast(class_name as VARCHAR2(60)) as class_name方式组合列名
 * @param $tableName
 * @param $columnStr 指定列名以逗号隔开
 * @param $columnPrefix 列名前缀
 * @param $multiple //缓存设置倍数,默认2
 * @param 是否格式化日期
 * @return string
 */
function getColumnName($tableName, $columnStr = '', $columnPrefix = '', $multiple = 2, $formatDate = 1)
{
    $tableName=strtoupper($tableName);
    if ($columnStr!='') {
        $columnStr=strtoupper($columnStr);
        $columnStrArray=explode(',', excel_trim($columnStr));
        $columnArray=array();
    }
    $columnName='';//转化后的列
    $sql="select * from user_tab_columns where Table_Name='$tableName' ";
    $changeDataType=['VARCHAR2','CHAR'];//待转换的数据类型
    $data=db('','database.sales_connection')->query($sql);
    foreach ($data as $k => $v) {
        $dataType=$v['DATA_TYPE'];
        $tempColumnName=$v['COLUMN_NAME'];
        if (empty($columnStrArray)||in_array($tempColumnName, $columnStrArray)) {
            if (in_array($dataType, $changeDataType)) {
                //$dataLength=$v['DATA_LENGTH']*$multiple;
				if (($tableName == 'oeb_file' || $tableName == 'OEB_FILE') && $tempColumnName == 'REMARK') {
                    $dataLength = $v['DATA_LENGTH'];
                } else {
                    $dataLength=$v['DATA_LENGTH']*$multiple;
                }
                $temp="cast($columnPrefix$tempColumnName as $dataType ($dataLength)) as ". '"'.$columnPrefix.$tempColumnName.'"';
                $columnName.=" $temp,";
            } else {
                $temp=$columnPrefix.$tempColumnName.' as "'.$columnPrefix.$tempColumnName.'"';
                if ($formatDate&&$dataType=='DATE') {
                    $temp="to_char($columnPrefix$tempColumnName, 'yyyy-MM-dd')".' as "'.$columnPrefix.$tempColumnName.'"';
                }
                $columnName.=$temp.',';
            }
            $columnArray[$tempColumnName]=$temp;
        }
    }
    //列名排序按$columnStr的顺序排序$columnName
    if (!empty($columnStrArray)) {
        $coulumn=array();
        foreach ($columnStrArray as $k => $v) {
            $coulumn[]=$columnArray[$v];
        }
        $columnName=implode(',', $coulumn);
    }
    return empty($columnName)?$columnStr:rtrim($columnName, ',');
}

//laravel dd函数
// function dd(...$args)
// {
//     foreach ($args as $x) {
//         $dumper=new HtmlDumper();
//         $cloner=new VarCloner();
//         $dumper->dump($cloner->cloneVar($x));
//     }

//     die(1);
// }

//tp3 M()
function M()
{
    return DB::getInstance();
}

function two_array_merge(&$array1, $array2)
{
    foreach ($array2 as $k=>$v) {
        array_push($array1, $v);
    }
}

/**
 * 数组键转成小写
 * @param $array oracle查询出的二维索引数组
 */
function array_change_keycase(&$array)
{
    foreach ($array as $k => $v) {
        if (is_array($array[$k])) {
            $array[$k]=array_change_key_case($array[$k]);
        }
    }
    return $array;
}

//查询结果集健名转小写
function changeCase($result, $flag = 0)
{
    $arr = array();
    $combine = array();
    foreach ($result as $key => $val) {
        foreach ($val as $k => $v) {
            if ($flag) {
                $arr[strtoupper($k)] = $v;
            } else {
                $arr[strtolower($k)] = $v;
            }
        }
        array_push($combine, $arr);
    }
    return $combine;
}

function convertKaixiang($kaixiang)
{
    $str = "";
    if (strpos($kaixiang, '内') !== false) {
        $str = '内开';
    }
    if (strpos($kaixiang, '外') !== false) {
        $str = '外开';
    }
    if (strpos($kaixiang, '四开') !== false) {
        $str = '四开门';
    }
    return $str;
}

function convertMenshan($menshan)
{
    $str = $menshan;
    if (strpos($menshan, '单扇') !== false) {
        $str = '单扇门';
    }
    if (strpos($menshan, '对开') !== false) {
        $str = '对开门';
    }
    if (strpos($menshan, '子母门') !== false) {
        $str = '子母门';
    }
    if (strpos($menshan, '子母四开门') !== false) {
        $str = '子母四开门';
    }
    if (strpos($menshan, '均等四开门') !== false) {
        $str = '均等四开门';
    }
    return $str;
}

function chuanghuaConvert($chuanghua)
{
    if (strpos($chuanghua, '封闭式气窗') !== false) {
        return '封闭式窗花';
    } else {
        $changhuaIndex = strpos($chuanghua, '窗');
        return substr($chuanghua, 0, $changhuaIndex)."窗花";
    }
}

//设置特殊表别名
function getAlias($table)
{
    $data = array("oea_file"=>"oea","oeb_file"=>"oeb","plan_table_data_fenjieb"=>"fenjieb",
        "plan_table_data_menkuang"=>"menkuang","plan_table_data_menshan"=>"menshan",
        "plan_table_data_zongzhuang"=>"zongzhuang");
    return $data[$table];
}

function isDateTime($dateTime)
{
    if (is_float(intval($dateTime))) {
        return false;
    } else {
        $ret = strtotime($dateTime);
        if ($ret === false) {
            return false;
        } else {
            return true;
        }
    }
}

function arrayDiff($arr1, $arr2)
{
    $count1=count($arr1);
    $count2=count($arr2);
    $No_saca=0;
    for ($i=0; $i<$count1; $i++)
    {
        for ($j=0; $j<$count2; $j++)
        {
            if ($arr1[$i] == $arr2[$j])
                $No_saca=1;
        }
        if ($No_saca == 0)
            $nuevo_array[] = $arr1[$i];
        else
            $No_saca = 0;
    }
    return $nuevo_array;
}

function getFiledByName($str,$type = 1)
{
    $arr = array('订货部门'=>'dinghuobm','客户名称'=>'oea.oea032','档次'=>'oeb.dang_ci','门扇'=>'oeb.menshan',
        '底框材料'=>'oeb.dkcailiao','特殊要求'=>'qitatsyq','铰链'=>'oeb.jiaolian','猫眼'=>'oeb.maoyan',
        '窗花'=>'oeb.chuanghua','表面方式'=>'oeb.biaomcl','表面要求'=>'oeb.biaomiantsyq','订单数量'=>'shuliang',
        '副锁'=>'suoju','花色'=>'oeb.huase','框厚'=>'oeb.mkhoudu','标牌'=>'oeb.biao_pai','标件'=>'oeb.biaojian',
        '包装'=>'baozhuang');
    if ($type == 1){//分解表---shuliang
        $arr['数量'] = 'shuliang';
    } elseif ($type == 2 || $type == 3) {//门框、附件表---jihual
        $arr['数量'] = 'jihual';
    } elseif ($type == 4) {
        $arr['数量'] = 'mushanjhl';
    }
    return $arr[$str];
}

function peijianMatch($condition)
{
    $arr = array('窗花1'=>'chuanghua1','窗花2'=>'chuanghua2','玻璃卡槽'=>'bolikc','下档加强卡槽'=>'xiadangkc','中框盖板'=>'zhongkuanggb',
        '封闭窗花'=>'fengbich','观察孔'=>'guanchakong');
    return $arr[$condition];
}

function filterData($data, $options)
{
    foreach ($options as $key => $val) {
        foreach ($data as $kdata => $vdata) {
            return $data[$kdata][$key] == "$val";
        }
    }
}

//从数组中任意取m个数据，排列组合
//从$arr数组中，获取$m个数字组成数组,也就是排列组合的C运算符
function getCombinationToString($arr, $m)
{
    $result = array();
    if ($m == 1) {
        return $arr;
    }
    if ($m == count($arr)) {//当取出的个数等于数组的长度，就是只有一种组合，即本身
        $result[] = implode(',', $arr);
        return $result;
    }
    $temp_firstelement = $arr[0];
    unset($arr[0]);
    $arr = array_values($arr);
    $temp_first1 = getCombinationToString($arr, $m - 1);
    foreach ($temp_first1 as $s) {
        $s = $temp_firstelement.','.$s;
        $result[] = $s;
    }
    unset($temp_first1);
    $temp_first2 = getCombinationToString($arr, $m);
    foreach ($temp_first2 as $s) {
        $result[] = $s;
    }
    unset($temp_first2);
    return $result;
}

function minData($data)
{
    $min  = null;
    $len = count($data);
    for ($i = 0; $i < $len; $i++) {
        if ($i == 0) {
            $min = $data[$i];
            continue;
        }
        if ($data[$i]['distince'] < $min['distince']) {
            $min = $data[$i];
        }
    }
    return $min;
}

//去除字符串中的中文
function filterChinese($str)
{
    $result = preg_replace('/([\x80-\xff]*)/i', '', $str);
    return $result;
}

//查询字符串中的中文
function findChinese($str)
{
    preg_match_all("/[\x{4e00}-\x{9fff}]+/u", $str, $x);
    $b = join("",$x[0]);
    return $b;
}
//获取小数点后的位数
function getFloatLength($num) {
    $count = 0;
    $temp = explode ( '.', $num );
    if (sizeof ( $temp ) > 1) {
        $decimal = end ( $temp );
        $count = strlen ( $decimal );
    }
    return $count;
}

function pagination($data, $page, $pageSize, $amount)
{
    if (empty($data)) {
        return $data;
    }
    $result = array();
    $from = ($page-1)*$pageSize;
    //如果为最后一页
    if ($page == ceil($amount/$pageSize)) {
        $dest = $amount-1;
    } else {
        $dest = $from+$pageSize-1;
    }
    for ($i = $from; $i <= $dest; $i++) {
        array_push($result, $data[$i]);
    }
    return $result;
}


/**
 * 检查指定字符串是否为日期格式 年-月-日
 * @param $date  日期字符串
 * @return bool  true 是日期格式     false 不是日期格式
 */
function valid_date($date)
{
    //匹配日期格式
    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
    {
        //检测是否为日期,checkdate为月日年
        if(checkdate($parts[2],$parts[3],$parts[1]))
            return true;
        else
            return false;
    }
    else
        return false;
}

