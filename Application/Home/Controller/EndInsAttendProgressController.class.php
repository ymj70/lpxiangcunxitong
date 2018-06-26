<?php

namespace Home\Controller;
class EndInsAttendProgressController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "养老参保进度查询");
        session("model", "信息查询");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function attentionMatters(){
        $this->display("attentionMatters");
    }
    public function getIdcardInfo(){
        $this->display("getIdcardInfo");
    }
    public function showResult(){
        if (!IS_POST){
            $this->display("showResult");
            return;
        }
        $data["idcard"]=I("post.idCard");
        $data["examineType"]=1;
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["EndInsAttendProgress"]["showResult"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "信息保存接口请求失败";
            }
        }
    }
}
