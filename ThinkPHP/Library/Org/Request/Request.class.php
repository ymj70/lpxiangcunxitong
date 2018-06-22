<?php

namespace Org\Request;
class Request
{
    private $url = "";
    private $postData = "";
    private $type = "";

    /**
     * @param $url 请求的url
     * @param $postData post请求的请求数据
     * @param string $type 请求类型 post,get,json
     * @return mixed
     */
    public function requset($url, $postData = '', $type = "")
    {
            $this->url=$url;
            $this->type=$type;
            $this->postData=$postData;
            $result=$this->curl_request();
            return $result;
    }

    private function curl_request()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($this->type == "json") {
            $header = array("Content-type: application/json");// 注意header头，格式k:v
            $post = json_encode($this->postData);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        } else if ($this->type == "post") {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->postData));
        }
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }
}