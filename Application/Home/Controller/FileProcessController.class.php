<?php
namespace Home\Controller;
class FileProcessController extends CommonController{
    public function _initialize(){
        parent::_initialize();
        session("menu","FileProcess");
    }

    /**
     * 档案核查列表接口
     */
    public function getRecordList()
    {
        if (I("type")=="ajax"){
        $pagenumber=I("pagenumber");
        $pagesize=I("pagesize");
        if (empty($pagenumber)){
            $pagenumber="1";
        }
        if (empty($pagesize)){
            $pagesize="10";
        }
        $pager["pagenumber"] = $pagenumber;
        $pager["pagesize"] = $pagesize;
        $url = C("REQUEST_URL") . "/recordCore/getRecordList";
        $result = curl_request($url, $pager);
        $result = json_decode($result, true);
        $totalpage=$result["data"]["total"]/$pager["pagesize"];
        $totalpage=ceil($totalpage);
        $this->assign("data",$result["data"]);
        $this->assign("total",$totalpage);
        $list = $result["data"]["list"];
        foreach ($list as $key=> $value) {
            $list[$key]["id"]=number_format($value["id"],0,"","");;
        }
        $result["data"]["list"] = $list;

            $info["code"]=1;
            $info["data"]=$result["data"]["list"];
            $info["total"]=$result["data"]["total"];
            $info["msg"]="成功";
            $this->ajaxReturn($info);
            return;
        }
        $this->display("getRecordList");
    }
    /**
     * 档案核查处理接口
     */
    public function getRecordById()
    {
        $data["id"] = I("get.id");
        $url = C("REQUEST_URL") . "/recordCore/getRecordById";
        $result = curl_request($url, $data);
        $result = json_decode($result, true);
        $result["data"]["data"]["id"]=number_format($result["data"]["data"]["id"],0,"","");
        $result["data"]["data"]["businessEventId"]=number_format($result["data"]["data"]["businessEventId"],0,"","");
        $this->assign("data",$result["data"]["data"]);
        $this->display("getRecordById");
    }
    /**
     * 调取档案接口
     */
    public function acquireRecord()
    {

        $data["idNumber"] = I("get.idNumber");
        $url = C("REQUEST_URL") . "/recordCore/acquireRecord";
        $result = curl_request($url, $data);
        $result = json_decode($result,true);
        $data=$result["data"]["data"];
        $imgPath=explode(",",$data["filePath"]);
        $result["data"]["data"]["id"]=number_format($result["data"]["data"]["id"],0,"","");
        $this->assign("data",$result["data"]["data"]);
        $this->assign("imgpath",$imgPath);
        $this->display("acquireRecord");
    }

    /**
     * 存档核查
     * type  1->存档通过，2->无档通过,3->档案不通过
     */
    public function recordCheck(){
        $type=I("post.type");
        $id=I("post.id");
        $userinfo=S(session("username"));
        $reason=I("post.reason");
        $businessEventId=I("post.businessEventId");
        switch ($type){
            case 1;
                $this->agreeRecordOnFile($id,$userinfo["data"]["id"],$businessEventId);
                break;
            case 2;
                $this->agreeRecordNoFile($id,$userinfo["data"]["id"],$businessEventId);
                break;
            case 3:
                $this->disagreeRecord($id,$userinfo["data"]["id"],$reason);
                break;
        }
    }
    /**
     * 存档通过接口
     */
    private function agreeRecordOnFile($id,$userid,$businessEventId)
    {
        $data["id"] = $id;
        $data["acceptorId"] =$userid ;
        $data["businessEventId"] =$businessEventId ;
        $url = C("REQUEST_URL") . "/recordCore/agreeRecordOnFile";
        $result = curl_request($url, $data);
        $result = json_decode($result,true);
        if ($result["data"]["code"]===0){
            $info["status"]=1;
            $info["message"]="审核通过";
            $info["url"]=U("getRecordList");
            $this->ajaxReturn($info);
        }else{
            $info["status"]=-1;
            $info["message"]="操作失败";
            $this->ajaxReturn($info);
        }

    }

    /**
     * 无档通过接口
     */
    private function agreeRecordNoFile($id,$userid,$businessEventId)
    {
        $data["id"] = $id;
        $data["acceptorId"] =$userid ;
        $data["businessEventId"] =$businessEventId ;
        $url = C("REQUEST_URL") . "/recordCore/agreeRecordNoFile";
        $result = curl_request($url, $data);
        $result = json_decode($result,true);
        if ($result["data"]["code"]===0){
            $info["status"]=1;
            $info["message"]="审核通过";
            $info["url"]=U("getRecordList");
            $this->ajaxReturn($info);
        }else{
            $info["status"]=-1;
            $info["message"]="操作失败";
            $this->ajaxReturn($info);
        }
    }

    /**
     * 档案不通过接口
     */
    private function disagreeRecord($id,$userid,$reason)
    {
        $data["id"] = $id;
        $data["acceptorId"] =$userid ;
        $data["reason"] =$reason ;
        $url = C("REQUEST_URL") . "/recordCore/disagreeRecord";
        $result = curl_request($url, $data);
        $result = json_decode($result,true);
        if ($result["data"]["code"]===0){
            $info["status"]=1;
            $info["message"]="操作成功";
            $info["url"]=U("getRecordList");
            $this->ajaxReturn($info);
        }else{
            $info["status"]=-1;
            $info["message"]="操作失败";
            $this->ajaxReturn($info);
        }
    }

}