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
       /* if (!IS_POST) {
            $this->display();
            return;
        }*/
        //$data["idcard"] = I("post.idCard");//身份证号
        $data["idcard"] = "130321199310221238";//身份证号
        $javaurl = $this->javaUrl;
        $url = C("REQUEST_URL").$javaurl["MedicalInsAttend"]["checkPeopleMedicalInsStatus"];
        //请求接口 检测用户是否参保 已参保不用再次登记参保 未参保 查询
        $requestObj = $this->requestObject;
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] == 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/MedicalInsAttend/showPeopleInfo");
            if (empty($result["data"])) {
                $data = $result["data"];
            } else {
                $data["sex"] = I("post.sex");//性别
                $data["national"] = I("post.national");//民族
                $data["birthday"] = I("post.birthday");//出生日期
                $data["idCardAddress"] = I("post.idCardAddress");//户籍所在地
            }
            session("MedicalInsAttendPeopleInfo", $data);
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
        if (!IS_POST) {
            $peopleInfo = session("MedicalInsAttendPeopleInfo");
            $this->assign("peopleInfo", $peopleInfo);
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
            $age = get_age($data);
            if ($age < 16) {
                $info["url"] = U("Home/MedicalInsAttend/gethouseholdImg");
            } else {
                $info["url"] = U("Home/MedicalInsAttend/getIdcardImg");
            }
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
    public function getIdcardImg()
    {
        if (!IS_POST) {
            $this->display();
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
        /*if (!IS_POST) {
            $this->display();
            return;
        }*/
        //$gethouseholdFirstImg = I("gethouseholdFirstImg");//户口本主页
        //$gethouseholdPeopleImg = I("gethouseholdPeopleImg");//户口本个人页
        //if (!empty($gethouseholdFirstImg) && !empty($gethouseholdPeopleImg)) {
            //$data = session("MedicalInsAttendPeopleInfo");
            //$data["gethouseholdFirstImg"] = $gethouseholdFirstImg;
           // $data["gethouseholdPeopleImg"] = $gethouseholdPeopleImg;

        $data["name"] = "王栋";//姓名
        $data["idcard"] = "130321199810221238";//身份证号
        $data["sex"] = "男";//性别
        $data["nation"] = "满";//民族
        $data["birthDay"] = "1998-10-22";//出生日期
        $data["domicile"] = "河北省秦皇岛市";//户籍所在地
        $data["nowDomicile"] = "河北省秦皇岛市";//家庭住址
        $data["phonenumber"] = "15033307336 ";//联系电话
        $data["persontype"] = "未成年";//人员类型
        $data["maritalStatus"] = "是";//婚否
        $data["domicileType"] = "农村";//户口性质
        $data["master"] = "父亲";//户主姓名
        $data["masteridcard"] = "130321199310221238";//户主身份证号
        $data["householderMobile"] = "15033307225";//户主电话号码
        $data["masterShip"] = 0;//与户主关系
        $data["outCountyInsurance"] ="否";//是否县外参保
        $data["workStatus"] = "是";//是否就业
        $data["mobilePersonnel"] = "否";//流动人员
        $data["uninsuredReson"] ="交不起";//未参保原因
        $data["hukouBookUrl"] = "sdsfdsfsdfsdfsdfsdfsdfdsf";//户口被主页
        $data["hukouBookBackUrl"] = "sdsfdsfsdfsdfsdfsdfsdfdsf";//户口被个人也
        $data["idCardBackUrl"] = "sdsfdsfsdfsdfsdfsdfsdfdsf";//身份证反面
        $data["idCardUrl"] = "sdsfdsfsdfsdfsdfsdfsdfdsf";//身份证整面
        $data["countryCode"] = "131464464";//村级机构
        $data["age"] =get_age($data["idcard"]);//村级机构
        $data["insuredtype"] = "131464464";//村级机构
        $data["county"] = "131464464";//村级机构
        $data["street"] = "131464464";//村级机构
        $data["coummunity"] = "131464464";//村级机构
        $data["personalnumber"] = "131464464";//村级机构
        $data["province"] = "131464464";//村级机构
        $data["city"] = "131464464";//村级机构
        $data["companyId"] = "131464464";//村级机构
        $data["bankName"] = "131464464";//村级机构
        $data["bankAccount"] = "131464464";//村级机构
        $data["specialInsurance"] = "1312";//村级机构
        $data["examineType"] = "0";//村级机构0 医疗 1养老
        $data["villageId"] = "1312";//村级机构
        $data["countyCode"] = "1312";//村级机构
        $data["townTownCode"] = "1312";//村级机构



            //请求接口 检测用户是否参保
            $javaurl = $this->javaUrl;
            $url = C("REQUEST_URL").$javaurl["MedicalInsAttend"]["saveMedicalInsPeopleInfo"];
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
        /*} else {
            $info["code"] = -1;
            $info["message"] = "图片路径接收失败";
        }*/
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
