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
     /*   if (!IS_POST){
            $this->display("showResult");
        }*/
        //String startTime, String endTime, String idcard,Integer pageNum, Integer pageSize

        /*$data["idcard"]=I("post.idCard");
        $data["startTime"]=I("post.startTime");
        $data["endTime"]=I("post.endTime");
        $data["pageNum"]=I("post.pageNum");
        $data["pageSize"]=I("post.pageSize"); */
        $data["idcard"]="132626193811254014";
        $data["startTime"]="2017";
        $data["endTime"]="2018";
        $data["pageNum"]=1;
        $data["pageSize"]=10;
        //请求接口 检测用户是否参保
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["QueryTreatmentCollect"]["showResult"];
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");;
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "信息保存接口请求失败";
            }
        }
    }
}
