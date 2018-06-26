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
        $data["idcard"] = I("post.idCard");//身份证号
        $data["insuredtype"] = 1;//险种类型

        if (empty($data["idcard"])) {
            $info["code"] = -1;
            $info["message"] = "身份证号码不能为空";
            $this->ajaxReturn($info);
        }
        $age = get_age($data["idcard"]);
        if ($age < 16) {
            $info["code"] = -1;
            $info["message"] = "未到参保年龄";
            $this->ajaxReturn($info);
        }
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["EndowmentInsAttend"]["checkEndowmentInsAttendStatus"];
        //请求接口 检测用户是否参保 已参保不用再次登记参保 未参保 查询
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] === 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/EndowmentInsAttend/showPeopleInfo");
            if ($result["workcode"] === 0) {
                $info["workcode"] = 1;
                $info["workmessage"] = "已参加职工养老保险";
            } else {
                $info["workcode"] = -1;
                $info["workmessage"] = "未参加职工养老保险";
            }
            if (empty($result["data"])) {
                $result["data"]["name"] = I("realName");
                $result["data"]["idcard"] = I("idCard");
                $result["data"]["sex"] = get_sex($result["data"]["idcard"]);
                $result["data"]["birthDay"] = get_birthday($result["data"]["idcard"]);
            }
            session("EndowmentInsAttendPeopleInfo", $result["data"]);
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
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $data["realName"] = $peopleInfo["name"];//姓名
            $data["idCard"] = $peopleInfo["idcard"];//身份证号
            $data["sex"] = $peopleInfo["sex"];//性别
            $data["national"] = $peopleInfo["nation"];//民族
            $data["birthday"] = $peopleInfo["birthDay"];//出生日期
            $data["idCardAddress"] = $peopleInfo["domicile"];//户籍所在地
            $data["familyAddress"] = $peopleInfo["nowDomicile"];//家庭住址
            $data["phonenumber"] = $peopleInfo["phonenumber"];//联系电话
            $data["personIdentity"] = $peopleInfo["personIdentity"];//人员类型
            $data["domicileType"] = $peopleInfo["domicileType"];//户口性质

            $this->assign("peopleInfo", $data);
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
        $data["peopleType"] = I("post.peopleType");//人员类型
        $data["householdRegister"] = I("post.householdRegister");//户口性质
        $data["openBank"] = I("post.openBank");//开户银行
        $data["bankCard"] = I("post.bankCard");//银行卡号
        $data["captureLevel"] = I("post.captureLevel");//银行卡号
        $data["workerMedicalIns"] = I("post.workerMedicalIns");//职工养老保险
        $data["landlessSocialSecurity"] = I("post.landlessSocialSecurity");//被征地农民社会保障
        $data["oldFarmerIns"] = I("post.oldFarmerIns");//老农保
        $data["othersIns"] = I("post.othersIns");//其他保险
        $info["params"] = "";
        //遍历检测字段是否都是非空的 有一个字段为空就返回信息不完整
        foreach ($data as $key => $value) {
            if ($value == "") {
                $status = false;
                $info["params"] .= $key . ",";
            }
        }
        if ($status !== false) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/EndowmentInsAttend/getPeoplePhoto");
            session("EndowmentInsAttendPeopleInfo", $data);
        } else {
            $info["code"] = -1;
            $info["message"] = "信息未添写完整";
        }
        $this->ajaxReturn($info);
    }

    /**
     * 获取身份证照片
     */
    public function getPeoplePhoto()
    {
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $this->display("getPeoplePhoto");
            return;
        }
        $peoplePhoto = I("peoplePhoto");//身份证背面图片
        if (!empty($peoplePhoto)) {
            $data = session("EndowmentInsAttendPeopleInfo");
            $data["peoplePhoto"] = $peoplePhoto;
            session("EndowmentInsAttendPeopleInfo", $data);
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/EndowmentInsAttend/getIdcardImg");
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
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $this->display("getIdcardImg");
            return;
        }
        $idcardBackImg = I("idcardBackImg");//身份证背面图片
        $idcardfrontImg = I("idcardfrontImg");//身份证正面图片
        if (!empty($idcardBackImg) && !empty($idcardfrontImg)) {
            $data = session("EndowmentInsAttendPeopleInfo");
            $data["idcardBackImg"] = $idcardBackImg;
            $data["idcardfrontImg"] = $idcardfrontImg;
            session("EndowmentInsAttendPeopleInfo", $data);
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/EndowmentInsAttend/gethouseholdImg");
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
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $this->display("gethouseholdImg");
            return;
        }
        $gethouseholdFirstImg = I("gethouseholdFirstImg");//户口本主页
        $gethouseholdPeopleImg = I("gethouseholdPeopleImg");//户口本个人页
        if (!empty($gethouseholdFirstImg) && !empty($gethouseholdPeopleImg)) {
            $data = session("EndowmentInsAttendPeopleInfo");
            $data["gethouseholdFirstImg"] = $gethouseholdFirstImg;
            $data["gethouseholdPeopleImg"] = $gethouseholdPeopleImg;
            session("EndowmentInsAttendPeopleInfo", $data);
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/EndowmentInsAttend/getRegistrationForm");
        } else {
            $info["code"] = -1;
            $info["message"] = "图片路径接收失败";
        }
        $this->ajaxReturn($info);
    }

    /**
     * 获取户口本主页和个人页照片
     */
    public function getRegistrationForm()
    {
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $this->display("getRegistrationForm");
            return;
        }
        $registrationForm = I("registrationForm");//户口本主页
        if (!empty($registrationForm)) {
            $peopleInfo = session("EndowmentInsAttendPeopleInfo");
            $data["registrationForm"] = $registrationForm;
            $userInfo=S(session("username"));

            $data["naexamineTypeme"] = 1;////险种类型 0 代表医疗  1代表养老
            $data["name"] = $peopleInfo["realName"];//姓名
            $data["idcard"] = $peopleInfo["idCard"];//身份证号
            $data["sex"] = $peopleInfo["sex"];//性别
            $data["nation"] = $peopleInfo["national"];//民族
            $data["birthDay"] = $peopleInfo["birthday"];//出生日期
            $data["domicile"] = $peopleInfo["idCardAddress"];//户籍所在地
            $data["nowDomicile"] = $peopleInfo["familyAddress"];//家庭住址
            $data["phonenumber"] = $peopleInfo["telephone"];//联系电话
            $data["persontype"] = $peopleInfo["peopleType"];//人员类型
            $data["maritalStatus"] = $peopleInfo[""];//婚否
            $data["domicileType"] = $peopleInfo["householdRegister"];//户口性质
            $data["hukouBookUrl"] = $peopleInfo["gethouseholdFirstImg"];//户口被主页
            $data["hukouBookBackUrl"] = $peopleInfo["gethouseholdPeopleImg"];//户口被个人也
            $data["idCardBackUrl"] = $peopleInfo["idcardBackImg"];//身份证反面
            $data["idCardUrl"] = $peopleInfo["idcardfrontImg"];//身份证整面
            $data["age"] = get_age($data["idcard"]);//村级机构
            $data["insuredtype"] = $peopleInfo[""];//村级机构
            $data["county"] = $peopleInfo[""];//村级机构
            $data["street"] = $peopleInfo[""];//村级机构
            $data["coummunity"] = $peopleInfo[""];//村级机构
            $data["personalnumber"] = $peopleInfo[""];//村级机构
            $data["province"] = $peopleInfo[""];//村级机构
            $data["city"] = $peopleInfo[""];//村级机构
            $data["companyId"] = $peopleInfo[""];//村级机构
            $data["bankName"] = $peopleInfo["openBank"];//村级机构
            $data["bankAccount"] = $peopleInfo["bankCard"];//村级机构
            $data["specialInsurance"] = $peopleInfo[""];//村级机构
            $data["examineType"] = 1;//村级机构0 医疗 1养老
            $data["villageId"] = $userInfo["villageId"];//村级机构
            $data["countyCode"] = $userInfo["countyCode"];//村级机构
            $data["townTownCode"] = $userInfo["townTownCode"];//村级机构


            //请求接口 检测用户是否参保
            $javaurl = $this->javaUrl;
            $url = $javaurl["EndowmentInsAttend"]["saveEndowmentInsPeopleInfo"];
            $requestObj = $this->requestObject;
            $result = $requestObj->requset($url, $data, "post");
            $result = json_decode($result, true);
            if ($result["code"] === 0) {
                $info["code"] = 1;
                $info["message"] = "成功";
                $info["url"] = U("Home/MedicalInsAttend/MedicalInsAttendIndex");
                session("EndowmentInsAttendPeopleInfo", null);
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
    public function getPeopleInfo($data)
    {
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
            if (!empty($info["data"])) {
                $data = $info["data"];
            }
            session("EndowmentInsAttendPeopleInfo", $data);
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
