<?php

namespace Home\Controller;
class RetireAuditController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        session("menu", "RetireAudit");
    }

    /**
     * 社保股退休审核接口
     */
    public function retireCheck()
    {
        if (I("type") == "ajax") {
        $pagenumber = I("pagenumber");
        $pagesize = I("pagesize");
        if (empty($pagenumber)) {
            $pagenumber = "1";
        }
        if (empty($pagesize)) {
            $pagesize = "10";
        }
        $data["pagenumber"] = $pagenumber;
        $data["pagesize"] = $pagesize;
        $url = C("REQUEST_URL") . "/socialSecurity/retireCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $totalpage = $result["data"]["total"] / $data["pagesize"];
        $totalpage = ceil($totalpage);
        $this->assign("data", $result["data"]);
        $this->assign("total", $totalpage);
        $list = $result["data"]["list"];
        foreach ($list as $key=> $value) {
            $list[$key]["id"]=number_format($value["id"],0,"","");;
        }
        $result["data"]["list"] = $list;

            $info["code"] = 1;
            $info["data"] = $result["data"]["list"];
            $info["msg"] = "成功";
            $info["total"]=$result["data"]["total"];
            $this->ajaxReturn($info);
            return;
        }
        $this->display("retireCheck");

    }

    /**
     * 退休核查接口
     */
    public function getRetireCheckByIdNumber()
    {

        $data["idNumber"] = I("get.idNumber");
        $url = C("REQUEST_URL") . "/socialSecurity/getRetireCheckByIdNumber";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $result["data"]["data"]["id"]=number_format($result["data"]["data"]["id"],0,"","");
        $result["data"]["data"]["businessEventId"]=number_format($result["data"]["data"]["businessEventId"],0,"","");
        $this->assign("data", $result["data"]["data"]);
        $this->display("getRetireCheckByIdNumber");
    }

    /**
     * 退休股审核
     * type 1->审核通过 2->审核不通过
     */
    public function Check()
    {
        $type = I("post.type");
        $businessEventId = I("post.businessEventId");
        $recordAcceptorId = I("post.recordAcceptorId");
        $recordHandleTime = I("post.recordHandleTime");
        $idNumber = I("post.idNumber");
        $id = I("post.id");
        $reason = I("post.reason");
        $userinfo = S(session("username"));
        $acceptorId = $userinfo["data"]["id"];
        switch ($type) {
            case 1:
                $this->agreeRetireCheckr($idNumber, $id, $acceptorId,$businessEventId,$recordAcceptorId,$recordHandleTime);
                break;
            case 2:
                $this->disagreeRetireCheck($idNumber, $id, $acceptorId, $reason);
                break;
        }

    }

    /**
     * 审核通过
     */
    public function agreeRetireCheckr($idNumber, $id, $acceptorId,$businessEventId,$recordAcceptorId,$recordHandleTime)
    {
        $data["idNumber"] = $idNumber;
        $data["acceptorId"] = $acceptorId;
        $data["businessEventId"] = $businessEventId;
        $data["recordAcceptorId"] = $recordAcceptorId;
        $data["recordHandleTime"] = $recordHandleTime;
        $data["id"] = $id;
        $url = C("REQUEST_URL") . "/socialSecurity/agreeRetireCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        if ($result["data"]["code"] === 0) {
            $info["status"] = 1;
            $info["message"] = "审核通过";
            $info["url"] = U("retireCheck");
            $this->ajaxReturn($info);
        } else {
            $info["status"] = -1;
            $info["message"] = "操作失败";
            $this->ajaxReturn($info);
        }
    }

    /**
     * 审核不通过
     */
    public function disagreeRetireCheck($idNumber, $id, $acceptorId, $reason)
    {
        $data["idNumber"] = $idNumber;
        $data["acceptorId"] = $acceptorId;
        $data["id"] = $id;
        $data["reason"] = $reason;
        $url = C("REQUEST_URL") . "/socialSecurity/disagreeRetireCheck";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        if ($result["data"]["code"] === 0) {
            $info["status"] = 1;
            $info["message"] = "操作成功";
            $info["url"] = U("retireCheck");
            $this->ajaxReturn($info);
        } else {
            $info["status"] = -1;
            $info["message"] = "操作失败";
            $this->ajaxReturn($info);
        }
    }

    /**
     * 缴费历史查询
     */
    public function queryPayHistory()
    {
        if (I("type") == "ajax") {
            $pagenumber = I("pagenumber");
            $pagesize = I("pagesize");
            $firstPaymentPeriod = I("firstPaymentPeriod");
            $endPaymentPeriod = I("endPaymentPeriod");
            if (empty($pagenumber)) {
                $pagenumber = "1";
            }
            if (empty($pagesize)) {
                $pagesize = "10";
            }
            $data["pagenumber"] = $pagenumber;
            $data["pagesize"] = $pagesize;
            $data["idNumber"] = I("idNumber");
            if (!empty($firstPaymentPeriod)&&!empty($endPaymentPeriod)){
                $data["firstPaymentPeriod"] =$firstPaymentPeriod;
                $data["endPaymentPeriod"] = $endPaymentPeriod;
            }

            $url = C("REQUEST_URL") . "/socialSecurity/queryPayHistory";
            $result = curl_request($url, $data);
            $result = json_decode($result, true);
            $totalpage = $result["data"]["total"] / $data["pagesize"];
            $totalpage = ceil($totalpage);
            $this->assign("data", $result["data"]);
            $this->assign("total", $totalpage);
            $info["code"] = 1;
            $info["data"] = $result["data"]["list"];
            $info["msg"] = "成功";
            $info["total"]=$result["data"]["total"];
            $this->ajaxReturn($info);
            return;
        }
        $this->display("queryPayHistory");
    }

    /**
     * 打印基础表
     */
    public function printBasicsForm()
    {
        $data["idNumber"] = "130322199501271816";
        $url = C("REQUEST_URL") . "/socialSecurity/printBasicsForm";
        $result = curl_request($url, $data);
        $result = json_decode($result);
        var_dump($result);
    }

    /**
     * 打印核准表
     */
    public function printApprovalForm()
    {
        $data["idNumber"] = "130322199501271816";
        $url = C("REQUEST_URL") . "/socialSecurity/printApprovalForm";
        $result = curl_request($url, $data);
        $result = json_decode($result);
        var_dump($result);
    }
}



