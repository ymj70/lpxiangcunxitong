<?php

namespace Home\Controller;
class PersonalJobRegController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "个人求职登记");
        session("model", "帮村民找工作");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function index()
    {
       /* if (!IS_POST) {
            $this->display("index");
            return;
        }*/
        $data["pageNum"] = 1;
        $data["pageSize"] = 10;
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL3") . $javaurl["PersonalJobReg"]["getlist"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] !== 0) {
            $this->assign("list",$result["data"]);
            $htmlData=$this->fetch("list");
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $htmlData;
            $info["total"] = 26;
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function getinfoByIdcard()
    {
        $data["idcard"] = I("idcard");
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["PersonalJobReg"]["getinfoByIdcard"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] !== 0) {
            $this->assign("list",$result["data"]);
            $htmlData=$this->fetch("list2");
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] =$htmlData;
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function add(){
        $this->display("add");
    }

    public function addBaseInfo()
    {
        $data["pageNum"] = I("pageNum");
        $data["pageSize"] = I("pageSize");
        $data["idcard"] = I("idcard");
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["PersonalJobReg"]["addBaseInfo"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/getPeopleInfo");
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function addEducationInfo()
    {
        $data["pageNum"] = I("pageNum");
        $data["pageSize"] = I("pageSize");
        $data["idcard"] = I("idcard");
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["PersonalJobReg"]["addEducationInfo"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/getPeopleInfo");
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function addWorkInfo()
    {
        $data["pageNum"] = I("pageNum");
        $data["pageSize"] = I("pageSize");
        $data["idcard"] = I("idcard");
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["PersonalJobReg"]["addWorkInfo"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/getPeopleInfo");
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
    public function addIntentionInfo()
    {
        $data["pageNum"] = I("pageNum");
        $data["pageSize"] = I("pageSize");
        $data["idcard"] = I("idcard");
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["PersonalJobReg"]["addIntentionInfo"];
        //请求接口 检测用户是否参保
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/getPeopleInfo");
            $info["data"] = $result["data"];
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }

    public function update()
    {
        $this->display("update");
    }

    public function delete()
    {
        $this->display("delete");
    }


}
