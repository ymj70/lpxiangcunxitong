<?php
/**
 * 请求java接口方法
 * @param $url                访问的URL
 * @param string $post         post数据(不填则为GET)
 * @return mixed|string
 */
function curl_request($url, $post = '')
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if ($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;

}

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
 * 将id拼接成字符串
 * @param $str
 * @return string
 */
function toString($str){

    $str=number_format($str,0,"","");
    return "id".$str;
}

/**
 * 将数字用number_format格式化 防止自动科学计数发
 * @param $str
 * @return string
 */
function numChange($str){

    $str=number_format($str,0,"","");
    return $str;
}

/**
 * 获取接口请求地址
 */
function getRequestUrl(){
    return C("REQUEST_URL");
}

/**
 * 查询缴费记录的缴费档期格式调整
 * @param $data
 * @return string
 */
function paymentPeriod($data){
    if (empty($data)){
        return "未获取数据";
    }
    $year=substr($data,0,4);
    $motn=substr($data,4,2);
    return $year."-".$motn;
}


