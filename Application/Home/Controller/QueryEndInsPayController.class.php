<?php

namespace Home\Controller;
class QueryEndInsPayController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡养老缴费查询");
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
       /* if (!IS_POST){
            $data["idcard"]=I("get.idcard");
            $data["realName"]=I("get.realName");
            $this->assign("userinfo",$data);
            $this->display("showResult");
            return;
        }*/
       // $data["idcard"]=I("post.idCard");
        $data["idcard"]="130321199310221238";
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
        $url = C("REQUEST_URL") . $javaurl["QueryEndInsPay"]["showResult"];
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
                $info["message"] = "请求失败";
            }
        }
        $this->ajaxReturn($info);
    }

    public function getPeopleInfo()
    {
        //$data["idcard"]=I("post.idcard");
        $data["idcard"]="130321199310221238";
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryEndInsPay"]["getPeopleInfo"];
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
