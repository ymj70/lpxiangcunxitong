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
    public function getIdcardInfo()
    {
        $this->display("getIdcardInfo");
    }

    public function showResult()
    {
        $userinfo["realName"]=I("realName");
        $userinfo["idcard"]=I("idcard");
        $this->assign("userinfo",$userinfo);
        $this->display("showResult");
    }

    public function getPeopleInfo()
    {
        $data["idcard"] = I("post.idcard");
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryAttendInsInfo"]["getPeopleInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!empty($result["data"])) {
            $data["realName"] = $result["data"]["name"];
            $data["idcard"] = $result["data"]["idcard"];
            $data["nation"] = $result["data"]["nation"];
            $data["sex"] = $result["data"]["sex"];
            $data["idcardAddress"] = $result["data"]["domicile"];
            $data["birthday"] = $result["data"]["birthDay"];
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $data;
        } else {
            $info["code"] = -1;
            $info["message"] = "未获取到人员信息";
        }
        $this->ajaxReturn($info);
    }
    public function getPeopleAttndIns(){
        $data["idcard"] = I("post.idcard");
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryAttendInsInfo"]["getPeopleAttndIns"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!empty($result["data"]["upInsured"])||!empty($result["data"]["umiInsured"])) {
            $this->assign("upInsured",$result["data"]["upInsured"]);
            $this->assign("umiInsured",$result["data"]["umiInsured"]);
            $data=$this->fetch("result");
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
