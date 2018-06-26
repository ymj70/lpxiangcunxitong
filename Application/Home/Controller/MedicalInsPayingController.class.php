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
        $this->display("choosePayingPeople");
    }
    public function payingPeopleList(){
        $this->display("payingPeopleList");
    }
    public function toMedicalInsPaying(){
        $this->display("toMedicalInsPaying");
    }
    public function getPeoplePayInfo(){
        $data["idcard"]=I("pos.idcard");
        if ($data["idcard"]==''){
            $info["code"] = -1;
            $info["message"] = "身份证号不能为空";
            $this->ajaxReturn($info);
        }
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url =C("REQUEST_URL") .  $javaurl["EndowmentInsAttend"]["saveEndowmentInsPeopleInfo"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true);
        if ($result["code"] === 0) {
            $result["data"][]=array(
                "realName"=>"wyd",
                "idCard"=>"130321199310221238",
                "sex"=>"男",
                "adress"=>"河北秦皇岛",
                "payMoney"=>"100",
            );
            $result["data"][]=array(
                "realName"=>"wyd",
                "idCard"=>"130321199310221239",
                "sex"=>"男",
                "adress"=>"河北秦皇岛",
                "payMoney"=>"100",
            );
            $result["data"][]=array(
                "realName"=>"wyd",
                "idCard"=>"130321199310221230",
                "sex"=>"男",
                "adress"=>"河北秦皇岛",
                "payMoney"=>"100",
            );
            $result["data"][]=array(
                "realName"=>"wyd",
                "idCard"=>"130321199310221231",
                "sex"=>"男",
                "adress"=>"河北秦皇岛",
                "payMoney"=>"100",
            );
            $result["data"][]=array(
                "realName"=>"wyd",
                "idCard"=>"130321199310221232",
                "sex"=>"男",
                "adress"=>"河北秦皇岛",
                "payMoney"=>"100",
            );
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
        $this->ajaxReturn($info);
    }

}
