<?php

namespace Home\Controller;
class QueryAttendInsInfoController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "参保信息查询");
        session("model", "信息查询");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function getIdcardInfo(){
       /* if (IS_POST){
            $this->display("getIdcardInfo");
        }*/
        $data["idcard"]="130321199310221238";
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryAttendInsInfo"]["getIdcardInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!empty($result["data"])) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $data;
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function showResult(){
        $this->display("showResult");
    }
    public function getPeopleInfo()
    {
        //$data["idcard"]=I("post.idcard");
        $data["idcard"]="130321199310221238";
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryAttendInsInfo"]["getPeopleInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!empty($result["data"])) {
            $data[""]="";
            $data[""]="";
            $data[""]="";
            $data[""]="";
            $data[""]="";
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $data;
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
}
