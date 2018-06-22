<?php

namespace Home\Controller;

class IndexController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "首页");
    }

    public function index()
    {
        $this->display();
    }

    /**
     * index 操作辅助方法 获取档案审核 退休股审核 养老保险审核数据信息
     * @param $url 接口请求地址
     * @return mixed|string 接口返回的数据
     */
    private function indexListRequest($url)
    {
        $data["pagesize"] = "10";
        $data["pagenumber"] = "1";
        $url = C("REQUEST_URL") . $url;
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $list = $result["data"]["list"];
        foreach ($list as $key => $value) {
            $list[$key]["id"] = number_format($value["id"], 0, "", "");
        }
        $result["data"]["list"] = $list;
        return $result;

    }
}