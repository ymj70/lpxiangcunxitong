<?php

namespace Home\Controller;
class MedicalInsAttendController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "城乡医疗参保登记");
        session("model", "人员登记");
    }

    /**
     *城乡医疗参保登记首页
     */
    public function medicalInsAttendIndex()
    {
        $this->display("medicalInsAttendIndex");
    }

    public function checkPeopleMedicalInsStatus()
    {
        if (!IS_POST) {
            $this->display();
            return;
        }
        $data["idcard"] = I("post.idCard");//身份证号
        $data["insuredtype"] = 0;//险种类型
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL") . $javaurl["MedicalInsAttend"]["showPeopleInfo"];
        //请求接口 检测用户是否参保 已参保不用再次登记参保 未参保 查询
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] !== 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/showPeopleInfo");
            if ($result["workcode"] === 0) {
                $info["workcode"] = 1;
                $info["workmessage"] = "已参加职工医疗保险";
            } else {
                $info["workcode"] = -1;
                $info["workmessage"] = "未参加职工医疗保险";
            }
            if (empty($result["data"])) {
                $result["data"]["name"] = I("realName");
                $result["data"]["idcard"] = I("idCard");
                $result["data"]["sex"] = get_sex($result["data"]["idcard"]);
                $result["data"]["birthDay"] = get_birthday($result["data"]["idcard"]);
            }
            session("MedicalInsAttendPeopleInfo", $result["data"]);
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
            $data["national"] = $peopleInfo["national"];//民族
            $data["birthday"] = $peopleInfo["birthDay"];//出生日期
            $data["idCardAddress"] = $peopleInfo[""];//户籍所在地
            $data["familyAddress"] = $peopleInfo[""];//家庭住址
            $data["telephone"] = $peopleInfo[""];//联系电话
            $data["specialAttendInsGroup"] = $peopleInfo[""];//人员类型
            $data["householdRegister"] = $peopleInfo[""];//户口性质
            $data["householdHeadName"] = $peopleInfo[""];//户主姓名
            $data["householdHeadIdCard"] = $peopleInfo[""];//户主身份证号
            $data["householdHeadRelation"] = $peopleInfo[""];//与户主关系
            $data["householdHeadPhone"] = $peopleInfo[""];//与户主关系
            $data["whetherOutsideAttendIns"] = $peopleInfo[""];//是否县外参保
            $this->assign("peopleInfo", $data);
            $this->display();
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
        $data["householdHeadPhone"] = I("post.householdHeadPhone");//与户主关系
        $data["openBank"] = I("post.openBank");//开户银行
        $data["bankCard"] = I("post.bankCard");//银行卡号
        $info["params"] = "未填写字段：";
        //遍历检测字段是否都是非空的 有一个字段为空就返回信息不完整
        foreach ($data as $key => $value) {
            if ($value == '') {
                $status = false;
                $info["params"] .= $key . ",";
            }
        }
        if ($status !== false) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $age = get_age($data);
            if ($age < 16) {
                $info["url"] = U("Home/MedicalInsAttend/gethouseholdImg");
            } else {
                $info["url"] = U("Home/MedicalInsAttend/getIdcardImg");
            }
            session("MedicalInsAttendPeopleInfo", $data);
        } else {
            $info["code"] = -1;
            $info["message"] = "信息未添写完整";
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
            $this->display();
            return;
        }
        $idcardBackImg = I("idcardBackImg");//身份证背面图片
        $idcardfrontImg = I("idcardfrontImg");//身份证正面图片
        if (!empty($idcardBackImg) && !empty($idcardfrontImg)) {
            $data = $peopleInfo;
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
        $peopleInfo = session("EndowmentInsAttendPeopleInfo");
        if (empty($peopleInfo)) {
            $this->redirect("checkEndowmentInsAttendStatus");
        }
        if (!IS_POST) {
            $this->display();
            return;
        }
        $gethouseholdFirstImg = I("gethouseholdFirstImg");//户口本主页
        $gethouseholdPeopleImg = I("gethouseholdPeopleImg");//户口本个人页
        if (!empty($gethouseholdFirstImg) && !empty($gethouseholdPeopleImg)) {
            $peopleInfo =$peopleInfo;
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
            $data["persontype"] = $peopleInfo["specialAttendInsGroup"];//人员类型
            $data["maritalStatus"] = $peopleInfo[""];//婚否
            $data["domicileType"] = $peopleInfo["householdRegister"];//户口性质
            $data["master"] = $peopleInfo["householdHeadName"];//户主姓名
            $data["masteridcard"] = $peopleInfo["householdHeadIdCard"];//户主身份证号
            $data["householderMobile"] = $peopleInfo["householdHeadPhone"];//户主电话号码
            $data["masterShip"] = $peopleInfo["householdHeadPhone"];//与户主关系
            $data["outCountyInsurance"] = $peopleInfo[""];//是否县外参保
            $data["workStatus"] = $peopleInfo[""];//是否就业
            $data["mobilePersonnel"] = $peopleInfo[""];//流动人员
            $data["uninsuredReson"] = $peopleInfo[""];//未参保原因
            $data["hukouBookUrl"] = $gethouseholdFirstImg;//户口被主页
            $data["hukouBookBackUrl"] = $gethouseholdPeopleImg;//户口被个人也
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
            $data["examineType"] = $peopleInfo[""];//村级机构0 医疗 1养老
            $data["villageId"] = $userInfo[""];//村级机构
            $data["countyCode"] = $userInfo[""];//村级机构
            $data["townTownCode"] = $userInfo[""];//村级机构


            //请求接口 检测用户是否参保
            $javaurl = $this->javaUrl;
            $url = C("REQUEST_URL") . $javaurl["MedicalInsAttend"]["saveMedicalInsPeopleInfo"];
            $requestObj = $this->requestObject;
            $result = $requestObj->requset($url, $data, "post");;
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
    public function getPeopleInfo()
    {
        $data["idCard"] = I("idCard");
        if (empty($data["idCard"])) {
            $info["code"] = -1;
            $info["message"] = "身份证号不能为空";
        }
        $javaurl = $this->javaUrl;
        $url = $javaurl["MedicalInsAttend"]["getPeopleInfo"];
        //请求接口 获取用户信息
        $requestObj = new Request();
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] == 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["data"] = $result["data"];
            //将用户信息保存在session中 下一步用
            session("MedicalInsAttendPeopleInfo", $info["data"]);
        } else {
            $info["code"] = -1;
            $info["message"] = $result["msg"];
            if (empty($info["message"])) {
                $info["message"] = "获取信息接口请求失败";
            }
        }
        $this->ajaxReturn($info);
    }
}
