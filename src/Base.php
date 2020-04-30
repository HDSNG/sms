<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/29 0029
 * Time: 19:43
 */
namespace Damow;
use Damow\Common;
class Base{
    const WIN  = 200;//成功
    const LOSE = 201;//错误
    public function __construct()
    {
        $this->datamow = new Common();
    }
}