<?php
/**
 * 机器人通知
 * User: Administrator
 * Date: 2020/4/29 0029
 * Time: 19:42
 */
namespace Damow;
use Damow\Common;
class Boot extends Base {
    //系统简码（后台配置）
    protected $system_code;
    //系统秘钥（后台配置）
    protected $secret_key;
    //消息级别
    protected $level;
    //推送渠道
    protected $channel;
    //机器人名称
    protected $robot_name;

    //消息类型 1.文本，2.图片，3图文
    protected $msg_type = [
        '1'=>'文本',
        '2'=>'图片',
        '3'=>'图文'
    ];
    //消息级别（1.debug,2.info,3,warning,4.error,5.achievement）,项目总业绩统一用5，如有导师业绩的可以用2
    protected $msg_level = [
        '1'=>'debug',
        '2'=>'info',
        '3'=>'warning',
        '4'=>'error',
        '5'=>'achievement',
    ];
    //推送渠道（1.企业微信群助手，2.微信公众号，3.邮箱，4.语音）
    protected $msg_channel = [
        '1'=>'企业微信群助手',
        '2'=>'微信公众号',
        '3'=>'邮箱',
        '4'=>'语音',
    ];

    const BOOT_DOMAIN = "http://notice.91cyt.com/messagePush";

    /**
     * 机器人对象
     * @access public
     * @param mixed $array['system_code'] 系统简码
     * @param mixed $array['secret_key'] 系统秘钥
     * @param mixed $array['robot_name'] 机器人名字（后台可查）
     * @param mixed $array['level'] 消息级别（1.debug,2.info,3,warning,4.error,5.achievement）,项目总业绩统一用5，如有导师业绩的可以用2
     * @param mixed $array['channel'] 推送渠道（1.企业微信群助手，2.微信公众号，3.邮箱，4.语音）
     * @auth Damow
     * @return array 返回类型
     */
    public function __construct(array $array)
    {
        parent::__construct();
        if(!isset($array['system_code']) || empty($array['system_code'])){
            return ['code'=>self::LOSE,'msg'=>'系统简码不能为空'];
        }
        if(!isset($array['secret_key']) || empty($array['secret_key'])){
            return ['code'=>self::LOSE,'msg'=>'系统秘钥不能为空'];
        }
        if(!isset($array['robot_name']) || empty($array['robot_name'])){
            return ['code'=>self::LOSE,'msg'=>'机器人名字不能为空'];
        }
        if(!isset($array['level']) || !isset($this->msg_level[$array['level']])){
            return ['code'=>self::LOSE,'msg'=>'请输入正确的消息级别'];
        }
        if(!isset($array['channel']) || !isset($this->msg_channel[$array['channel']])){
            return ['code'=>self::LOSE,'msg'=>'请输入正确的推送渠道'];
        }
        $this->system_code = $array['system_code'];//系统简码
        $this->secret_key  = $array['secret_key']; //系统秘钥
        $this->robot_name  = $array['robot_name']; //机器人名字
        $this->level       = $array['level'];//消息级别
        $this->channel     = $array['channel'];//推送渠道

    }

    /**
     * 调用机器人发送通知接口
     * @access public
     * @param mixed $array['type'] 消息类型（1.文本，2.图片，3图文）
     * @param mixed $array['content'] 消息内容
     * @auth Damow
     * @return array 返回类型
     */
    public function PushMsg(array $array){
        if(!isset($this->msg_type[$array['type']])){
            return ['code'=>self::LOSE,'msg'=>'请填写正确的消息类型'];
        }

        if(empty($array['content']) || $this->datamow->analyJson($array['content'])==false){
            return ['code'=>self::LOSE,'msg'=>'请填写正确格式的消息内容'];
        }
        $data = [
            'system_code'   => $this->system_code,
            'secret_key'    => $this->secret_key,
            'type'          => $array['type'],
            'level'         => $this->level,
            'channel'       => $this->channel,
            'content'       => $array['content'],
            'robot_name'    => $this->robot_name,
        ];
        $result = $this->datamow->httpWurl(self::BOOT_DOMAIN,$data,"POST");
        $result = json_decode($result,true);
        if($result['error_code']!=0){
            return ['code'=>self::LOSE,'msg'=>$result['message']];
        }
        return ['code'=>self::WIN,'msg'=>'发送成功'];
    }



}