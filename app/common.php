<?php
// 应用公共文件

/**
 * 创建订单号
 * @param $prefix  string 订单号前缀
 * @return string
 */
function CreateOrderId($prefix){
    return $prefix . date('YmdHis') . str_pad(rand(1,99999),5,'0',STR_PAD_LEFT);
}
