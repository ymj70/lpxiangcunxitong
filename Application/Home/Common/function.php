<?php
use Org\Request\Request;

/**
 * 检查是否登录
 * @return bool
 */
function is_login(){
    if (empty(session("username"))){
        return false;
    }else{
        return true;
    }
}
/**
 *  根据身份证号码计算年龄
 *  author:xiaochuan
 *  @param string $idcard    身份证号码
 *  @return int $age
 */
function get_age($idcard){
    if(empty($idcard)) return null;
    #  获得出生年月日的时间戳
    $date = strtotime(substr($idcard,6,8));
    #  获得今日的时间戳
    $today = strtotime('today');
    #  得到两个日期相差的大体年数
    $diff = floor(($today-$date)/86400/365);
    #  strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
    $age = strtotime(substr($idcard,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
    return $age;
}
/**
 *  根据身份证号码获取性别
 *  author:xiaochuan
 *  @param string $idcard    身份证号码
 *  @return int $sex 性别 1男 2女 0未知
 */
function get_sex($idcard) {
    if(empty($idcard)) return null;
    $sexint = (int) substr($idcard, 16, 1);
    return $sexint % 2 === 0 ? '女' : '男';
}
/**
 *  根据身份证号码获取生日
 *  author:xiaochuan
 *  @param string $idcard    身份证号码
 *  @return $birthday
 */
function get_birthday($idcard) {
    if(empty($idcard)) return null;
    $bir = substr($idcard, 6, 8);
    $year = (int) substr($bir, 0, 4);
    $month = (int) substr($bir, 4, 2);
    $day = (int) substr($bir, 6, 2);
    return $year . "-" . $month . "-" . $day;
}
function getImg($path){
    $data["path"]=$path;
    $url = C("REQUEST_URL2") . C("imgDownUrl") ;
    $requestObject = new Request();
    $result = $requestObject->requset($url, $data,"post");
    $result = json_decode($result, true,512,JSON_BIGINT_AS_STRING);
    if ($result["code"]===0){
        return $result["data"];
    }else{
        return "/Public/image/user-info.png";
    }
}
