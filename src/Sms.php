<?php
namespace Damow;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/24 0024
 * Time: 17:02
 */
use Damow\Common;
class Sms extends Base {
    protected $sence_array = [1,2];
    const DOMAIN = "http://02lumen/Sms/SendCode";//本地环境1
    /**
     * 调用发送短信接口
     * @access public
     * @param mixed $data['mobile'] 手机号
     * @param mixed $data['sence'] 场景，1:短信发送版本1,2:短信发送版本2
     * @return array 返回类型
     */
    public function SendMsg($data){
        $Damow = $this->datamow;
        if(!isset($data['mobile'])){
            return ['code'=>self::LOSE,'msg'=>'请填写手机号'];
        }
        if(strlen($data['mobile'])!='11'){
            return ['code'=>self::LOSE,'msg'=>'手机号格式有误'];
        }
        if(!preg_match("/^1[345678]{1}\d{9}$/",$data['mobile'])){
            return ['code'=>self::LOSE,'msg'=>'请输入正确的手机号'];
        }
        if($Damow->isVirPN($data['mobile'])){
            return  ['code'=>self::LOSE,'msg'=>'错误的手机号'];
        }
        $data['sence'] = (isset($data['sence']) && isset($this->sence_array[$data['sence']]))?$data['sence']:2;
        $result = $Damow->httpWurl(self::DOMAIN,$data,"POST");
        $result = json_decode($result,true);
        if($result['code']!=200){
            return  ['code'=>self::LOSE,'msg'=>$result['msg']];
        }
        return  ['code'=>self::LOSE,'msg'=>'发送成功'];
    }


}