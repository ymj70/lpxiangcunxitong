<?php

namespace Home\Controller;
class QueryMedReimburseController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "医疗报销查询");
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
        if (!IS_POST) {
            $data["idcard"] = I("get.idcard");
            $data["realName"] = I("get.realName");
            $this->assign("userinfo", $data);
            $this->display("showResult");
            return;
        }
        $data["idcard"] = I("post.idcard");
        $data["page"] = I("post.pageNum");
        /*$data["startTime"] = I("post.startTime");
        $data["endTime"] = I("post.endTime");*/
        $data["startTime"] ="201703";
        $data["endTime"] = "201805";
        if (empty($data["startTime"])) {
            $data["startTime"] = date('Ym', strtotime('-1month'));
        }
        if (empty($data["endTime"])) {
            $data["endTime"] = date('Ym', time());
        }
        if (empty($data["page"])) {
            $data["page"] = 1;
        }
        if (empty($data["idcard"])) {
            $info["code"] = -1;
            $info["message"] = "身份证是空的";
            $this->ajaxReturn($info);
        }
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryMedReimburse"]["showResult"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"]===0) {
            $list = $result["data"];
            foreach ($list as $key => $value) {
                $list[$key]["visitTime"] = date("Y-m-d", strtotime($value["payTime"]));
                $list[$key]["admissionTime"] = date("Y-m-d", strtotime($value["payYear"]));
                $list[$key]["dischargeTime"] = date("Y-m-d", strtotime($value["arrivalTime"]));
                $list[$key]["clearingTime"] = date("Y-m-d", strtotime($value["clearingTime"]));
                $list[$key]["dayinUrl"] = "http://" . C("REQUEST_URL") . $javaurl["QueryEndInsPay"]["dayinUrl"] . "?id=" . $value["id"];
            }
            $this->assign("list", $list);
            $htmlData = $this->fetch("list");
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $htmlData;
            $info["dayinAllurl"] = "http://" . C("REQUEST_URL") . $javaurl["QueryEndInsPay"]["dayinAllurl"] . "?startTime=" . $data["startTime"] . "&endTime=" . $data["endTime"] . "&idcard=" . $data["idcard"];
            $info["total"] = $result["total"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "未查询到数据";
            }
        }
        $this->ajaxReturn($info);
    }
}
