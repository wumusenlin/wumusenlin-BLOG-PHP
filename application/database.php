<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 数据库类型
    'type'            => 'mysql',
//    // 服务器地址
//    'hostname'        => '172.16.15.79',
//    // 数据库名
//    'database'        => 'wages',
//    // 用户名
//    'username'        => 'root',
//    // 密码
//    'password'        => 'ititgo_123',
//    // 端口
//    'hostport'        => '3306',
    // 服务器地址
    'hostname'        => '127.0.0.1',
    // 数据库名
    'database'        => 'test',
    // 用户名
    'username'        => 'root',
    // 密码
    'password'        => '',
    // 端口
    'hostport'        => '3306',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 自动读取主库数据
    'read_master'     => false,
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,

    //生产管理mysql数据库配置
    'manufa_connection'=>[
        'type'            => 'mysql',
//        // 服务器地址
//        'hostname'        => '172.16.15.79',
//        // 数据库名
//        'database'        => 'manufacture',
//        // 用户名
//        'username'        => 'root',
//        // 密码
//        'password'        => 'ititgo_123',
//        // 端口
//        'hostport'        => '3306',
        // 服务器地址
        'hostname'        => '192.111.111.220',
        // 数据库名
        'database'        => 'manufacture',
        // 用户名
        'username'        => 'progdoor',
        // 密码
        'password'        => '123456',
        // 端口
        'hostport'        => '3306',
        // 连接dsn
        'dsn'             => '',
        // 数据库连接参数
        'params'          => [],
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        // 数据库表前缀
        'prefix'          => 'manufa_',
        // 数据库调试模式
        'debug'           => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy'          => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate'     => false,
        // 读写分离后 主服务器数量
        'master_num'      => 1,
        // 指定从服务器序号
        'slave_no'        => '',
        // 是否严格检查字段是否存在
        'fields_strict'   => true,
        // 数据集返回类型
        'resultset_type'  => 'array',
        // 自动写入时间戳字段
        'auto_timestamp'  => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
    ],
    //内网mes配置
    'sales_connection' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '192.111.111.222',
        // 数据库名
        'database'        => 'kmis',
        // 用户名
        'username'        => 'sales',
        // 密码
        'password'        => 'kingvon',
        // 端口
        'hostport'        => '1527',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    'mes_connection' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '172.16.16.75',
        // 数据库名
        'database'        => 'qbjmes',
        // 用户名
        'username'        => 'qbjmes',
        // 密码
        'password'        => 'qbjmes',
        // 端口
        'hostport'        => '1521',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    //内网咸宁mes配置
    'mes_connection_xn' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '172.16.84.80',
        // 数据库名
        'database'        => 'xnmes',
        // 用户名
        'username'        => 'xnmes',
        // 密码
        'password'        => 'xnmes.com',
        // 端口
        'hostport'        => '1521',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    //内网齐河mes配置
    'mes_connection_qh' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '172.16.32.90',
        // 数据库名
        'database'        => 'qhmes',
        // 用户名
        'username'        => 'qhmes',
        // 密码
        'password'        => 'qhmes',
        // 端口
        'hostport'        => '1521',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    //内网wages配置
    'wages_connection' => [
        'type'            => 'mysql',
        // 服务器地址
        'hostname'        => '172.16.15.79',
        // 数据库名
        'database'        => 'wages',
        // 用户名
        'username'        => 'root',
        // 密码
        'password'        => 'ititgo_123',
        // 端口
        'hostport'        => '3306',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    //内网齐河mes配置
    'oracle_connection_KQ' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '172.16.15.105',
        // 数据库名
        'database'        => 'KAOQIN',
        // 用户名
        'username'        => 'kaoqin',
        // 密码
        'password'        => 'kaoqin.com',
        // 端口
        'hostport'        => '1521',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
    //ds1数据库配置
    //erp ds4配置
    'ds1_connection' => [
        'type'            => '\think\oracle\Connection',
        // 服务器地址
        'hostname'        => '192.111.111.222',
        // 数据库名
        'database'        => 'topprod',
        // 用户名
        'username'        => 'ds5',
        // 密码
        'password'        => 'ds5',
        // 端口
        'hostport'        => '4919',
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        'params' =>[
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
        ],
    ],
];
