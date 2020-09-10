<?php
/**
 * Created by PhpStorm.
 * User: haojin
 * Date: 2020/7/28
 * Time: 17:03
 */

require "./vendor/autoload.php";

$res = \Timor\mapping(new \Timor\Test(), ["b" => 4]);


$obj = new \Timor\MultiArray();
$a = [
    [
        'weights' => 100,
        'start_time' => 1
    ],
    [
        'weights' => 99,
        'start_time' => 1
    ],
    [
        'weights' => 100,
        'start_time' => 3
    ],
    [
        'weights' => 98,
        'start_time' => 4
    ],
];
$res = $obj->sortArrByManyField($a, 'weights', SORT_DESC, 'start_time', SORT_DESC);
var_dump($res);
die;