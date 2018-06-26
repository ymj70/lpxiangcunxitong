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
        $this->display("medicalInsPayingIndex");
    }
    public function choosePayingPeople(){
        if (!IS_POST){
            $this->display("choosePayingPeople");
            return;
        }
        $data["idcard"]=I("pos.idcard");
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = $javaurl["EndowmentInsAttend"]["saveEndowmentInsPeopleInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] =$result["data"];
            foreach ($result["data"] as $key=>$value){
                $idcard=$value["idcard"];
                $sessionData[$idcard]=$value;
            }
            session("EndowmentInsAttendPeopleInfo", $sessionData);
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
    }
    public function payingPeopleList(){
        $this->display("payingPeopleList");
    }
    public function toMedicalInsPaying(){
        $this->display("toMedicalInsPaying");
    }

}
