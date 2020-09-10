<?php
/**
 * Created by PhpStorm.
 * User: haojin
 * Date: 2020/9/10
 * Time: 12:23
 */


namespace Timor\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation()
 * @Target({"PROPERTY"})
 */
class Mapping
{
    public $key;
}