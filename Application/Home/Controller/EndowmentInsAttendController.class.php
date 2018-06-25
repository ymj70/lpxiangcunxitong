<?php

namespace Home\Controller;
class EndowmentInsAttendController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡养老参保登记");
        session("model", "人员登记");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function endowmentInsAttendIndex()
    {
        $this->display("endowmentInsAttendIndex");
    }

    public function checkEndowmentInsAttendStatus()
    {
        if (!IS_POST) {
            $this->display("checkEndowmentInsAttendStatus");
            return;
        }
        $data["idCard"] = I("post.idCard");//身份证号
        if (empty($data["idCard"])){
            $info["code"]=-1;
            $info["message"]="身份证号码不能为空";
            $this->ajaxReturn($info);
        }
        $age=get_age($data["idCard"]);
        if ($age<16){
            $info["code"]=-1;
            $info["message"]="未到参保年龄";
            $this->ajaxReturn($info);
        }
        $javaurl = $this->javaUrl;
        $url = $javaurl["EndowmentInsAttend"]["checkEndowmentInsAttendStatus"];
        //请求接口 检测用户是否参保 已参保不用再次登记参保 未参保 查询
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!($result["code"] === 0)) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/showPeopleInfo");
            $peopleInfo=$this->getPeopleInfo($data["idCard"]);
            if ($peopleInfo["code"]===1){
                $info["peopleInfo"]="人员信息成功获取";
            }else{
                $info["peopleInfo"]=$peopleInfo["message"];
            }
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }

    /**
     * 展示人员信息 没有填写的填写完成 并提交 存入session 下一步用
     */
    public function showPeopleInfo()
    {
        $peopleInfo=session("NotAttendInsPeopleInfo");
        session("NotAttendInsPeopleInfo",null);
        if (empty($peopleInfo)){
            $this->redirect("checkPeopleInsStatus");
        }
        if (!IS_POST) {
            $this->assign("peopleInfo", $peopleInfo);
            $this->display("showPeopleInfo");
            return;
        }
        $data["realName"] = I("post.realName");//姓名
        $data["idCard"] = I("post.idCard");//身份证号
        $data["sex"] = I("post.sex");//性别
        $data["national"] = I("post.national");//民族
        $data["birthday"] = I("post.birthday");//出生日期
        $data["idCardAddress"] = I("post.idCardAddress");//户籍所在地
        $data["familyAddress"] = I("post.familyAddress");//家庭住址
        $data["telephone"] = I("post.telephone");//联系电话
        $data["specialAttendInsGroup"] = I("post.specialAttendInsGroup");//人员类型
        $data["householdRegister"] = I("post.householdRegister");//户口性质
        $data["householdHeadName"] = I("post.householdHeadName");//户主姓名
        $data["householdHeadIdCard"] = I("post.householdHeadIdCard");//户主身份证号
        $data["householdHeadRelation"] = I("post.householdHeadRelation");//与户主关系
        $data["whetherOutsideAttendIns"] = I("post.whetherOutsideAttendIns");//是否县外参保
        $data["openBank"] = I("post.openBank");//开户银行
        $data["bankCard"] = I("post.bankCard");//银行卡号
        $info["params"] = "未填写字段：";
        //遍历检测字段是否都是非空的 有一个字段为空就返回信息不完整
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $status = false;
                $info["params"] .= $key . ",";
            }
        }
        if ($status !== false) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/getIdcardImg");
            session("MedicalInsAttendPeopleInfo", $data);
        } else {
            $info["code"] == -1;
            $info["message"] == "信息未添写完整";
        }
        $this->ajaxReturn($info);
    }

    /**
     * 获取身份证照片
     */
    public function getPeoplePhoto()
    {
        if (!IS_POST) {
            $this->display("getPeoplePhoto");
            return;
        }
        $idcardBackImg = I("idcardBackImg");//身份证背面图片
        $idcardfrontImg = I("idcardfrontImg");//身份证正面图片
        if (!empty($idcardBackImg) && !empty($idcardfrontImg)) {
            $data = session("MedicalInsAttendPeopleInfo");
            $data["idcardBackImg"] = $idcardBackImg;
            $data["idcardfrontImg"] = $idcardfrontImg;
            session("MedicalInsAttendPeopleInfo", $data);
            $info["code"] = -1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/gethouseholdImg");
        } else {
            $info["code"] = -1;
            $info["message"] = "图片路径接收失败";
        }
        $this->ajaxReturn($info);
    }
    /**
     * 获取身份证照片
     */
    public function getIdcardImg()
    {
        if (!IS_POST) {
            $this->display("getIdcardImg");
            return;
        }
        $idcardBackImg = I("idcardBackImg");//身份证背面图片
        $idcardfrontImg = I("idcardfrontImg");//身份证正面图片
        if (!empty($idcardBackImg) && !empty($idcardfrontImg)) {
            $data = session("MedicalInsAttendPeopleInfo");
            $data["idcardBackImg"] = $idcardBackImg;
            $data["idcardfrontImg"] = $idcardfrontImg;
            session("MedicalInsAttendPeopleInfo", $data);
            $info["code"] = -1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/gethouseholdImg");
        } else {
            $info["code"] = -1;
            $info["message"] = "图片路径接收失败";
        }
        $this->ajaxReturn($info);
    }

    /**
     * 获取户口本主页和个人页照片
     */
    public function gethouseholdImg()
    {
        if (!IS_POST) {
            $this->display("gethouseholdImg");
            return;
        }
        $gethouseholdFirstImg = I("gethouseholdFirstImg");//户口本主页
        $gethouseholdPeopleImg = I("gethouseholdPeopleImg");//户口本个人页
        if (!empty($gethouseholdFirstImg) && !empty($gethouseholdPeopleImg)) {
            $data = session("MedicalInsAttendPeopleInfo");
            $data["gethouseholdFirstImg"] = $gethouseholdFirstImg;
            $data["gethouseholdPeopleImg"] = $gethouseholdPeopleImg;
            //请求接口 检测用户是否参保
            $javaurl = $this->javaUrl;
            $url = $javaurl["MedicalInsAttend"]["savePeopleInfo"];
            $requestObj = new Request();
            $result = $requestObj->requset($url, $data, "post");
            $result = json_decode($result, true);
            if ($result["code"] == 0) {
                $info["code"] = 1;
                $info["message"] = "成功";
                $info["url"] = U("Home/MedicalInsAttend/MedicalInsAttendIndex");
                session("NotAttendInsPeopleInfo", null);
            } else {
                $info["code"] = -1;
                $info["message"] = $result["msg"];
                if (empty($info["message"])) {
                    $info["message"] = "信息保存接口请求失败";
                }
            }
        } else {
            $info["code"] = -1;
            $info["message"] = "图片路径接收失败";
        }
        $this->ajaxReturn($info);
    }

    /**
     * 根据身份证号获取用户信息
     */
    public function getPeopleInfo($idCard)
    {
        $data["idCard"] = $idCard;
        if (empty($data["idCard"])) {
            $info["code"] = -1;
            $info["message"] = "身份证号不能为空";
        }
        $javaurl = $this->javaUrl;
        $url = $javaurl["MedicalInsAttend"]["getPeopleInfo"];
        //请求接口 获取用户信息
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if (!($result["code"] === 0)) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $result["data"];
            //将用户信息保存在session中 下一步用
            $data=$info["data"];
            session("MedicalInsAttendPeopleInfo", $data);
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "获取人员信息接口请求失败";
            }
        }
        return $info;
    }
}
