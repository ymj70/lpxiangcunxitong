<?php

namespace Home\Controller;

class IndexController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", null);
    }

    public function index()
    {

        /**获取账户信息开始**/

        $userinfo = S(session("username"));
        $url = C("REQUEST_URL") . "/user?userId=" . $userinfo["data"]["id"];
        $result = curl_request($url);
        $result = json_decode($result, true);

        /**获取账户信息结束**/
        /**待办事项数据展示开始**/

        $departmen = array($userinfo["data"]["departmentId"]);
        //根据权限循环获取数据
        foreach ($departmen as $value) {
            //档案审核列表
            if ($value === 132626000001) {
                $url = "/recordCore/getRecordList";
                $FileProcess = $this->indexListRequest($url);
            }
            //退休股审核列表
            if ($value === 132626000002) {
                $url = "/socialSecurity/retireCheck";
                $RetireAudit = $this->indexListRequest($url);
            }
            //养老保险审核列表
            if ($value === 132626000003) {
                $url = "/endInsurance/queryInsuranceList";
                $EndInsurance = $this->indexListRequest($url);
            }
            if ($value === 132626000004) {
                $url = "/recordCore/getRecordList";
                $FileProcess = $this->indexListRequest($url);
                $url = "/socialSecurity/retireCheck";
                $RetireAudit = $this->indexListRequest($url);
                $url = "/endInsurance/queryInsuranceList";
                $EndInsurance = $this->indexListRequest($url);
            }
        }
        //更多连接地址
        if (!empty($FileProcess["data"]["total"])) {
            $todo["moreUrl"] = U("FileProcess/getRecordList");
        } elseif (!empty($RetireAudit["data"]["total"])) {
            $todo["moreUrl"] = U("RetireAudit/retireCheck");
        } elseif (!empty($EndInsurance["data"]["total"])) {
            $todo["moreUrl"] = U("EndInsurance/queryInsuranceList");
        } else {
            $todo["moreUrl"] = "";
        }
        //总数据条数
        $todo["total"] = $EndInsurance["data"]["total"] + $RetireAudit["data"]["total"] + $FileProcess["data"]["total"];
        //数据列表
        $todo["EndInsurance"] = $EndInsurance["data"]["list"];
        $todo["RetireAudit"] = $RetireAudit["data"]["list"];
        $todo["FileProcess"] = $FileProcess["data"]["list"];
        $this->assign("todo", $todo);

        /**待办事项数据展示结束**/

        $this->display();
    }

    /**
     * index 操作辅助方法 获取档案审核 退休股审核 养老保险审核数据信息
     * @param $url 接口请求地址
     * @return mixed|string 接口返回的数据
     */
    private function indexListRequest($url)
    {
        $data["pagesize"] = "10";
        $data["pagenumber"] = "1";
        $url = C("REQUEST_URL") . $url;
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $list = $result["data"]["list"];
        foreach ($list as $key => $value) {
            $list[$key]["id"] = number_format($value["id"], 0, "", "");
        }
        $result["data"]["list"] = $list;
        return $result;

    }
}