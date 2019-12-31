<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
function p($value,$contiun=0)
{
    if (is_bool($value))
    {
        var_dump($value);
    }elseif (is_null($value))
    {
        var_dump(NULL);
    }else
    {
        echo "<div><pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($value,true)."</pre></div>";
    }
    if (!$contiun){
        exit;
    }
}
/**
 * where数组转str
 * @param $where
 * @return bool|string
 */
function whereArrToStr($where)
{
    if (empty($where))
        return false;
    foreach ($where as $value) {
        if (empty($where_str)) {
            $where_str = " where " . $value;
        } else {
            $where_str .= " and " . $value;
        }
    }
    return $where_str;
}
function getDateFromRange($startdate, $enddate){
    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);
    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;
    // 保存每天日期
    $date_arr = array();
    for($i=0; $i<$days; $i++){
        $date_arr[] = date('m月d日', $stimestamp+(86400*$i));
    }
    return $date_arr;
}
function getDateFromTemp($startdate, $enddate){
    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);
    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;
    // 保存每天日期
    $date_arr = array();
    for($i=0; $i<$days; $i++){
        $date_arr[date('Ymd', $stimestamp+(86400*$i))] = 0;
    }
    return $date_arr;
}
function getDateFromTemp_($startdate, $enddate){
    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);
    // 计算日期段内差多少天
    $days = ($etimestamp-$stimestamp)/86400;
    // 保存每天日期
    $date_arr = array();
    for($i=0; $i<$days; $i++){
        $date_arr[] = date('Y-m-d', $stimestamp+(86400*$i));
    }
    return $date_arr;
}
// 应用公共文件

function isCreditNo($vStr){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
    if ($vLength == 18) {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18) {
        $vSum = 0;
        for ($i = 17 ; $i >= 0 ; $i--) {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
        if($vSum % 11 != 1) return false;
    }
    return true;
}