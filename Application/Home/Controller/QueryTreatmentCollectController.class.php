<?php

namespace Home\Controller;
class QueryTreatmentCollectController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "待遇领取查询");
        session("model", "信息查询");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function getIdcardInfo(){
        $this->display("getIdcardInfo");
    }
    public function showResult(){
        if (!IS_POST){
            $data["idcard"]=I("get.idcard");
            $data["realName"]=I("get.realName");
            $this->assign("userinfo",$data);
            $this->display("showResult");
            return;
        }
        $data["idcard"]=I("post.idCard");
        $data["pageNum"]=I("post.pageNum");
        $data["pageSize"]=I("post.pageSize");
        $data["startTime"]=I("post.startTime");
        $data["endTime"]=I("post.endTime");
        if (empty($data["startTime"])){
            $data["startTime"]=date('Y',strtotime('-1year'));
        }
        if (empty($data["endTime"])){
            $data["endTime"]=date('Y',time());
        }
        if (empty($data["pageNum"])){
            $data["pageNum"]=1;
        }
        if (empty($data["pageSize"])){
            $data["pageSize"]=10;
        }
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryTreatmentCollect"]["showResult"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!empty($result["list"])) {
            $list=$result["list"];
            foreach ($list as $key=>$value){
                $list[$key]["grantTime"]=ss;
            }
            $this->assign("list",$result["list"]);
            $data=$this->fetch("list");
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $data;
            $info["total"] = $result["total"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "未获取到数据信息";
            }
        }
        $this->ajaxReturn($info);
    }
}
