<?php

namespace Home\Controller;
class EndInsuranceController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        session("menu", "EndInsurance");
    }

    /**
     * 养老保险科审核列表接口
     */
    public function queryInsuranceList()
    {
        if (!IS_POST) {
            $this->display();
            return;
        }
        $data["pagesize"] = I("pagesize");
        $data["pagenumber"] = I("pagenumber");
        $url = C("REQUEST_URL") . "/endInsurance/queryInsuranceList";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $list = $result["data"]["list"];
        foreach ($list as $key => $value) {
            $list[$key]["id"] = number_format($value["id"], 0, "", "");;
        }
        $result["data"]["list"] = $list;

        if ($result["code"] === 0) {
            $info["data"] = $result["data"]["list"];
            $info["message"] = "成功";
            $info["code"] = 1;
            $info["total"] = $result["data"]["total"];
        } else {
            $info["message"] = "请求失败";
            $info["code"] = -1;
        }
        $this->ajaxReturn($info);
    }

    /**
     * 养老保险科审核列表接口
     */
    public function queryInsuranceById()
    {
        $data["id"] = I("id");
        $url = C("REQUEST_URL") . "/endInsurance/queryInsuranceById";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $result["data"]["data"]["id"] = number_format($result["data"]["data"]["id"], 0, "", "");;
        $this->assign("data", $result["data"]["data"]);
        $this->display("queryInsuranceById");
    }

    /**
     * 审核
     */
    public function Check()
    {
        $type = I("post.type");
        $data["phoneNumber"] = I("post.phoneNumber");
        $data["idcard"] = I("post.idcard");
        $data["planGrantDate"] = I("post.planGrantDate");
        $data["basicPensionFunds"] = I("post.basicPensionFunds");
        $data["selfPensionFunds"] = I("post.selfPensionFunds");
        $data["transitionPensionFunds"] = I("post.transitionPensionFunds");
        $data["subsidy"] = I("post.subsidy");
        $data["id"] = I("post.id");
        $data["treatmentTotal"] = I("post.treatmentTotal");
        $data["reason"] = I("post.reason");
        $userinfo = S(session("username"));
        $data["acceptorId"] = $userinfo["data"]["id"];
        switch ($type) {
            case 1:
                $this->submitCheck($data);
                break;
            case 2:
                $this->agreeCheck($data);
                break;
            case 3:
                $this->disagreeCheck($data);
        }

    }

    /**
     * 提交市级审批接口
     */
    public function submitCheck($params)
    {
        $data["phoneNumber"] = $params["phoneNumber"];
        $url = C("REQUEST_URL") . "/endInsurance/submitCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        if ($result["data"]["code"] === 0) {
            $info["message"] = "提交成功";
            $info["code"] = 1;
            $info["code"] = '';
        } else {
            $info["message"] = "请求失败";
            $info["code"] = -1;
        }
        $this->ajaxReturn($info);
    }

    /**
     * 审核通过
     */
    public function agreeCheck($params)
    {
        $data["id"] = $params["id"];
        $data["idcard"] =$params["idcard"];
        $data["planGrantDate"] = $params["planGrantDate"];
        $data["basicPensionFunds"] = $params["basicPensionFunds"];
        $data["selfPensionFunds"] = $params["selfPensionFunds"];
        $data["transitionPensionFunds"] = $params["transitionPensionFunds"];
        $data["subsidy"] = $params["subsidy"];
        $data["treatmentTotal"] = $params["treatmentTotal"];
        $data["acceptorId"] = $params["acceptorId"];
        $url = C("REQUEST_URL") . "/endInsurance/agreeCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        if ($result["data"]["code"] === 0) {
            $info["message"] = "审核通过";
            $info["code"] = 1;
            $info["url"] = U("EndInsurance/queryInsuranceList");
        } else {
            $info["message"] = "请求失败";
            $info["code"] = -1;
        }
        $this->ajaxReturn($info);
    }

    /**
     * 审核不通过
     */
    public function disagreeCheck($params)
    {
        $data["id"] = $params["id"];
        $data["idNumber"] = $params["idcard"];
        $data["reason"] = $params["reason"];
        $data["acceptorId"] = $params["acceptorId"];
        $url = C("REQUEST_URL") . "/endInsurance/disagreeCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        if ($result["data"]["code"] === 0) {
            $info["message"] = "操作成功";
            $info["code"] = 1;
            $info["url"] = U("EndInsurance/queryInsuranceList");
        } else {
            $info["message"] = "请求失败";
            $info["code"] = -1;
        }
        $this->ajaxReturn($info);
    }
}