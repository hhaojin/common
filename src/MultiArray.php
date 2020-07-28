<?php
/**
 * Created by PhpStorm.
 * User: haojin
 * Date: 2020/7/27
 * Time: 10:22
 */


namespace Timor;

/**
 * 数组助手类，处理一些骚操作
 * Class MultiArray
 * @package app\common\util
 */
class MultiArray extends \MultipleIterator
{
    public function __construct($flags = \MultipleIterator::MIT_KEYS_ASSOC)
    {
        parent::__construct($flags);
    }

    /**
     * 把多个数组的相关相应key值重新组合
     * $obj = $mulArray->attach(['goods_id', 'goods_name'], $arr1, $arr2)->__toArray();
     * @param array $keys 例如  ['goods_id', 'goods_name']
     * @param array ...$values 例如  arr1['a1','b1'], arr2['a2','b2']
     * @return $this 返回 arr[['goods_id' => 'a1', 'goods_name' => 'a2'], ['goods_id' => 'b1', 'goods_name' => 'b2']]
     */
    public function attach(array $keys, array ...$values)
    {
        foreach ($keys as $k => $key) {
            $this->attachIterator(new \ArrayIterator($values[$k]), $key);
        }
        return $this;
    }

    public function toArray()
    {
        $arr = [];
        foreach ($this as $key => $value) {
            $arr[] = $value;
        }
        return $arr;
    }

    /**
     * 对多维数组排序
     * 例如 类似mysql的 order by id desc ,age asc
     * $arr = sortArrByManyField($array, 'id', SORT_DESC, 'age', SORT_ASC);
     * @return mixed|bool
     */
    public function sortArrByManyField(...$args)
    {
        if (empty($args)) {
            return null;
        }
        $arr = array_shift($args);
        if (!is_array($arr)) {
            return false;
        }
        foreach ($args as $key => $field) {
            if (is_string($field)) {
                $temp = array();
                foreach ($arr as $index => $val) {
                    $temp[$index] = $val[$field];
                }
                $args[$key] = $temp;
            }
        }
        $args[] = &$arr;//引用值
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

}