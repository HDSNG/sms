<?php
namespace Damow;
/**
 * Created by PhpStorm.
 * User: Damow
 * Date: 2018/10/8 0008
 * Time: 15:21
 */
class Sms{

    public function table(){
        echo 1;die;
    }
    /**
     * 对象转数组
     * @return object
     * @author:Damow
     */
    public function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
    /**
     * 保留两位小数,并四舍五入
     * @return object
     * @author:Damow
     */
    public function int_two($num){
        $number = sprintf("%.2f",$num);
        return $number;
    }

    /**
     * 图片弄成圆形的
     * @param
     *  将返回的数据传入进行保存：imagepng($img,$img_path);
     *  保存后销毁数据：imagedestroy($img);
     * @return object
     * @author:Damow
     */
    public function changeCircularImg($imgpath) {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
            case 'jpg':
                $src_img = imagecreatefromjpeg($imgpath);
                break;
            case 'png':
                $src_img = imagecreatefrompng($imgpath);
                break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }
    /**
     * 友好的时间显示
     *
     * @author yzm
     *
     * @param int $sTime 待显示的时间
     * @param string $type 类型. normal | mohu | full | ymd | other
     * @param string $alt 已失效
     *
     * @return string
     */
    public function friendly_date($sTime, $type = 'normal', $alt = 'false')
    {
        if (!$sTime) return '';
        //sTime=源时间，cTime=当前时间，dTime=时间差

        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));

        //normal：n秒前，n分钟前，n小时前，日期
        switch ($type) {
            case 'normal':
                if ($dTime < 60) {
                    if ($dTime < 10) {
                        return '刚刚';
                    } else {
                        return intval(floor($dTime / 10) * 10) . "秒前";
                    }
                } elseif ($dTime < 3600) {
                    return intval($dTime / 60) . "分钟前";
                    //今天的数据.年份相同.日期相同.
                } elseif ($dYear == 0 && $dDay == 0) {
                    //return intval($dTime/3600)."小时前";
                    return '今天' . date('H:i', $sTime);
                } elseif ($dYear == 0) {
                    return date("m月d日 H:i", $sTime);
                } else {
                    return date("Y-m-d H:i", $sTime);
                }
                break;
            case 'mohu':
                if ($dTime < 60) {
                    return $dTime . "秒前";
                } elseif ($dTime < 3600) {
                    return intval($dTime / 60) . "分钟前";
                } elseif ($dTime >= 3600 && $dDay == 0) {
                    return intval($dTime / 3600) . "小时前";
                } elseif ($dDay > 0 && $dDay <= 7) {
                    return intval($dDay) . "天前";
                } elseif ($dDay > 7 && $dDay <= 30) {
                    return intval($dDay / 7) . '周前';
                } elseif ($dDay > 30) {
                    return intval($dDay / 30) . '个月前';
                }
                break;
            case 'full':
                return date("Y-m-d , H:i:s", $sTime);
                break;
            case 'ymd':
                return date("Y-m-d", $sTime);
                break;
            default:
                if ($dTime < 60) {
                    return $dTime . "秒前";
                } elseif ($dTime < 3600) {
                    return intval($dTime / 60) . "分钟前";
                } elseif ($dTime >= 3600 && $dDay == 0) {
                    return intval($dTime / 3600) . "小时前";
                } elseif ($dYear == 0) {
                    return date("Y-m-d H:i:s", $sTime);
                } else {
                    return date("Y-m-d H:i:s", $sTime);
                }
                break;
        }
    }


    /**
     * 请求url
     * @param
     * @return object
     * @author:Damow
     */
    public function httpWurl($url, $params, $method = 'GET', $header = array(), $multi = false){
        date_default_timezone_set('PRC');
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_COOKIESESSION  => true,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_COOKIE         =>session_name().'='.session_id(),
        );
        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                // $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                // 链接后拼接参数  &  非？
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
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
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    /**
     * 验证验证码
     * @param
     * @return object
     * @author:Damow
     */
    public function checkCode($mobile,$code){
        if(cache($mobile)!=$code)
            throw new Exception("短信验证码错误");
        \cache($mobile,NULL);
    }

    /**
     * 获取每月时间
     * @param $date
     * @return array
     */
    public function getMonth(){
        $firstday = date("Y-m-d",strtotime('now'));
        $lastday = date("Y-m-d",strtotime("$firstday +1 month"));
        return array($firstday,$lastday);
    }


    /**
     * 随机生成编码.
     *
     * @author
     *
     * @param $len 长度.
     * @param int $type 1:数字 2:字母 3:混淆
     * @return string
     */
    public function rand_code($len, $type = 1){
        $output = '';
        $str = ['a', 'b', 'c', 'd', 'e', 'f', 'g',
            'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
            's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
            'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];
        $num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        switch ($type) {
            case 1:
                $chars = $num;
                break;
            case 2:
                $chars = $str;
                break;
            default:
                $chars = array_merge($str, $num);
        }

        $chars_len = count($chars) - 1;
        shuffle($chars);

        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $chars_len)];
        }

        return $output;
    }
    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public function arraySequence($array, $field, $sort = 'SORT_DESC'){
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }
    /**
     * 数组分页函数  核心函数  array_slice
     * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
     * $count   每页多少条数据
     * $page   当前第几页
     * $array   查询出来的所有数组
     * order 0 - 不变     1- 反序
     */
    public function page_array($count,$page,$array,$order){
        global $countpage; #定全局变量
        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
        $start=($page-1)*$count; #计算每次分页的开始位置
        if($order==1){
            $array=array_reverse($array);
        }
        $totals=count($array);
        $countpage=ceil($totals/$count); #计算总页面数
        $pagedata=array();

        $pagedata=array_slice($array,$start,$count);
        return $pagedata;  #返回查询数据
    }
    /**
     * base64图片上传
     * @param $base64
     * @param string $path
     * @return bool|string
     */
    public function get_base64_img($base64,$path = 'upload/cards/'){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            mk_dirs($path.date('Ymd',time()));
            $path = $path.date('Ymd',time())."/";
            $type = $result[2];
            $co=rand('1','20');
            $new_file = $path.md5(time().$co).".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
                return "/".$new_file;
            }else{
                return  false;
            }
        }
    }
    /**
     * 图片转换
     * @return [type] [description]
     */
    public function cover_img($img=''){
        $domain = db('config')->where(['name'=>'ali_file_pic_url'])->value('value');
        $data   = strstr($img,'2019.haitianshiyw.com');
        if(!$data){
            ($img !='' || $img !=0)?$data = $domain.'/'.$img:$data = $domain.'/static/nopic.png';
            $data  = str_replace("\\", '/', $data);
            $data  = str_replace("//u", '/u', $data);
            $data  = str_replace("//q", '/q', $data);
        }else{
            $data  = $img;
        }
        return $data;
    }

    /**
     * 获取请求头
     * @return object
     * @author:Damow
     */
    public function headers(){
        $is_headers = function_exists('getallheaders');
        $headers=array();
        if(!isset($is_headers)) #如果是nginx
        {
            foreach ($_SERVER as $key => $value)
            {
                if ('HTTP_' == substr($key, 0, 5)) {
                    $headers[str_replace('_', '-', substr($key, 5))] = $value;
                }
                if (isset($_SERVER['PHP_AUTH_DIGEST']))
                {
                    $header['AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
                } elseif (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
                {
                    $header['AUTHORIZATION'] = base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
                }
                if (isset($_SERVER['CONTENT_LENGTH'])) {
                    $header['CONTENT-LENGTH'] = $_SERVER['CONTENT_LENGTH'];
                }
                if (isset($_SERVER['CONTENT_TYPE'])) {
                    $header['CONTENT-TYPE'] = $_SERVER['CONTENT_TYPE'];
                }
            }
        }else
        {
            $headers=getallheaders();
        }
    }


    /**
     * 根据出生日期获取年龄
     * @param $birthday 出生年月日（1992-1-3）
     * @return string 年龄
     */
    public function countage($birthday){
        $year   = date('Y');
        $month  = date('m');
        if(substr($month,0,1)==0){
            $month=substr($month,1);
        }
        $day=date('d');
        if(substr($day,0,1)==0){
            $day=substr($day,1);
        }
        $arr=explode('-',$birthday);
        $age=$year-$arr[0];
        if($month<$arr[1]){
            $age=$age-1;
        }elseif($month==$arr[1]&&$day<$arr[2]){
            $age=$age-1;

        }
        return $age;
    }

    /**
     * 只显示后4位
     * @param $str
     * @return string
     */
    public function func($str){
        $len = strlen($str);
        if($len <= 4){
            return $str;
        }
        return str_repeat('*', $len - 4).substr($str, -4);
    }

    /**
     * 获取json类型
     * @param  [type] $result [json状态]
     * @return [type]         [返回json类型]
     */
    public function result_type($result){
        $res = $result;
        switch ($result) {
            case 'arr':
                $res = array();
                break;
            case 'obj':
                $res = (object)array();
                break;
            case 'str':
                $res = "";
                break;
            case null:
                $res = (object)array();
                break;
        }
        return $res;
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from 	[起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to 	[终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    public function get_distance($from,$to,$km=true,$decimal=2){
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $distance = $EARTH_RADIUS*2*asin(sqrt(pow(sin( ($from[0]*pi()/180-$to[0]*pi()/180)/2),2)+cos($from[0]*pi()/180)*cos($to[0]*pi()/180)* pow(sin( ($from[1]*pi()/180-$to[1]*pi()/180)/2),2)))*1000;
        if($km){
            $distance = $distance / 1000;
        }
        return round($distance, $decimal);
    }

    /**
     * 验证字段
     */
    public function validate_code($data,$val,$sence="goods"){
        $validate=validate($sence);
        if(!$validate->scene($val)->check($data)){
            return datamsg(LOSE,$validate->getError());
        }
    }

    /**
     * 返回json数据
     */
    public function datamsg($code, $msg, $result = '',$arr=[])
    {
        $data['code'] = $code;
        $data['msg']  = $msg;
        is_object($result)?$result = $result->toArray():'';
        $result = _unsetNull($result);
        !empty($result) && $data['data'] = result_type($result);
        echo json_encode($data);die;
    }

    public function _unsetNull($arr){
        if($arr !== null){
            if(is_array($arr)){
                if(!empty($arr)){
                    foreach($arr as $key => $value){
                        if($value === null){
                            $arr[$key] = '';
                        }else{
                            $arr[$key] = _unsetNull($value);      //递归再去执行
                        }
                    }
                }else{ $arr = []; }
            }else{
                if($arr === null){ $arr = ''; }         //注意三个等号
            }
        }else{ $arr = ''; }
        return $arr;
    }

    /**
     * 获取请求头数据
     *
     * @return array
     */
    public function getAllHeader(){
        // 忽略获取的header数据。这个函数后面会用到。主要是起过滤作用
        $ignore = array('host','accept','content-length','content-type');
        $headers = array();
        //这里大家有兴趣的话，可以打印一下。会出来很多的header头信息。咱们想要的部分，都是‘http_'开头的。所以下面会进行过滤输出。
        /*    var_dump($_SERVER);
            exit;*/
        foreach($_SERVER as $key=>$value){
            if(substr($key, 0, 5)==='HTTP_'){
                //这里取到的都是'http_'开头的数据。
                //前去开头的前5位
                $key = substr($key, 5);
                //把$key中的'_'下划线都替换为空字符串
                $key = str_replace('_', ' ', $key);
                //再把$key中的空字符串替换成‘-’
                $key = str_replace(' ', '-', $key);
                //把$key中的所有字符转换为小写
                $key = strtolower($key);

                //这里主要是过滤上面写的$ignore数组中的数据
                if(!in_array($key, $ignore)){
                    $headers[$key] = $value;
                }
            }
        }
        //输出获取到的header
        return $headers;
    }



    /**
     * 重组数组的结构(二维数组)
     *
     * @param $arr
     * @param null $find_index
     * @param null $value_index
     * @param null $operation
     * @return mixed|null|number
     */
    public function array_index_value($arr, $find_index = null, $value_index = null, $operation = null){
        if(empty($arr)){
            return array();
        }
        $ret = null;
        $names = function($v,$w) use ($find_index,$value_index) {
            $v[$find_index?$w[$find_index]:'']=$value_index?$w[$value_index]:$w;
            return $v;
        };

        $names = @array_reduce($arr,$names);

        switch($operation){
            case 'sum':
                $ret = array_sum($names);
                break;
            default:
                $ret = $names;
                break;
        }
        return $ret;
    }

    /**
     * 字符串截取
     * @param
     * @return object
     * @author:Damow
     */
    public function mb_sub($str,$length=10){
        if(mb_strlen($str,'utf-8')>$length){
            return mb_substr($str,0,$length,'utf-8').'...';
        }else{
            return $str;
        }
    }
    /**
     * 生成目录结构
     * @param string $path 插件完整路径
     * @param array $list 目录列表
     */
    public function mk_dirs($a1, $mode = 0777)
    {
        if (is_dir($a1) || @mkdir($a1, $mode)) return TRUE;
        if (!mkdir(dirname($a1), $mode)) return FALSE;
        return @mkdir($a1, $mode);
    }

    /**
     * 把返回的数据集转换成Tree
     * @param
     * @return object
     * @author:Damow
     */
    public function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'children', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 去除空格换行
     * @param
     * @return object
     * @author:Damow
     */
    public function DeleteHtml($str)
    {
        $str = trim($str); //清除字符串两边的空格
        $str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/","",$str);
        $str = preg_replace("/\r/","",$str);
        $str = preg_replace("/\n/","",$str);
        $str = preg_replace("/ /","",$str);
        $str = preg_replace("/  /","",$str);  //匹配html中的空格
        return trim($str); //返回字符串
    }


    /**
     * 根据一个时间获取那天的起始时间
     * @param
     * @return object
     * @author:Damow
     */
    public function todayTime($time=''){
        $today['start_time'] = strtotime(date('Y-m-d',$time));
        $today['end_time']   = $today['start_time']+(3600*24)-1;
        return $today;
    }


}

