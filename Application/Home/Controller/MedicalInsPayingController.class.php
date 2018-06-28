<?php

namespace Home\Controller;
class MedicalInsPayingController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡居民医疗保险");
        session("model", "社保缴纳");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function medicalInsPayingIndex()
    {
        if (!IS_POST) {
            $this->display("medicalInsPayingIndex");
            return false;
        }
        $info = $this->checkYear();
        $this->ajaxReturn($info);

    }

    public function choosePayingPeople()
    {
        if (!IS_POST){
            $year = I("get.year");
            $this->assign("year", $year);
            $this->display("choosePayingPeople");
            return;
        }
        $peopleArr=$_POST;

        if (empty($peopleArr)){
            $info["code"]=-1;
            $info["message"]="失败";
        }else{
            $info["code"]=1;
            $info["message"]="成功";
        }
        $this->ajaxReturn($info);
    }

    public function payingPeopleList()
    {
        $this->display("payingPeopleList");
    }

    public function toMedicalInsPaying()
    {
        $this->display("toMedicalInsPaying");
    }

    public function getPeoplePayInfo()
    {
        $data["idcard"] = I("post.idcard");
        $data["year"] = I("post.year");
        if ($data["idcard"] == '' || $data["year"] == '') {
            $info["code"] = -1;
            $info["message"] = "身份证号或者年度是空的";
            $this->ajaxReturn($info);
        }
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["MedicalInsPaying"]["getPeoplePayInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $list["realName"] = $result["data"]["name"];
            $list["idcard"] = $result["data"]["idcard"];
            $list["sex"] = $result["data"]["sex"];
            $list["address"] = $result["data"]["place"];
            $list["money"] = $result["data"]["amount"];
            $info["data"] = $list;
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }

    public function checkYear()
    {
        $data["insuredtype"]=0;//缴费类型 0医疗 1养老
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["MedicalInsPaying"]["medicalInsPayingIndex"];
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
        return $info;
    }
    public function pushPeopleinfo($data){
        $data["insuredtype"]=0;//缴费类型 0医疗 1养老
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["MedicalInsPaying"]["medicalInsPayingIndex"];
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
        return $info;
    }

}
