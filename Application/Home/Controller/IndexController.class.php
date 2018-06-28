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
        $villageInsInfo=$this->getVillageInsInfo();
        $this->assign("villageInsInfo",$villageInsInfo);
        $this->display();
    }

    /**
     * index 操作辅助方法 获取档案审核 退休股审核 养老保险审核数据信息
     * @param $url 接口请求地址
     * @return mixed|string 接口返回的数据
     */
    private function getVillageInsInfo()
    {
        $userinfo=S(session("username"));
        $data["villageCode"]=$userinfo["villageId"];//缴费类型 0医疗 1养老
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["Index"]["getVillageInsInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);

    }
}