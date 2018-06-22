<?php

namespace Home\Controller;
class NotAttendInsController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
        //为菜单选中状态设置session
        session("menu", "未参保人员登记");
        session("model", "人员登记");
    }

    /**
     *
     * 未参保人员登记 首页
     */
    public function NotAttendInsIndex()
    {
        $this->display();
    }

    /**
     *检查当前用户是否已经参保，如已经参保无需再次登记 已参保进入下一步
     *
     */
    public function checkPeopleInsStatus()
    {
        if (!IS_POST) {
            $this->display();
            return;
        }
        $data["realName"] = I("post.realName");//姓名
        $data["idCard"] = I("post.idCard");//身份证号
        $javaurl = $this->javaUrl;
        $url = $javaurl["NotAttendIns"]["checkPeopleInsStatus"];
        //请求接口 检测用户是否参保
        $requestObj = new Request();
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true, 512, JSON_BIGINT_AS_STRING);
        if ($result["code"] == 0) {
            $info["code"] = 1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/getPeopleInfo");
            $data["sex"] = I("post.sex");//性别
            $data["national"] = I("post.national");//民族
            $data["birthday"] = I("post.birthday");//出生日期
            $data["idCardAddress"] = I("post.idCardAddress");//户籍所在地
            //将人员信息保存在session中下一步需要用
            session("NotAttendInsPeopleInfo", $data);
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
    public function getPeopleInfo()
    {
        if (!IS_POST) {
            $peopleInfo = session("NotAttendInsPeopleInfo");
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
        $data["peopleType"] = I("post.peopleType");//人员类型
        $data["marriage"] = I("post.marriage");//婚否
        $data["householdRegister"] = I("post.householdRegister");//户口性质
        $data["householdHeadName"] = I("post.householdHeadName");//户主姓名
        $data["householdHeadIdCard"] = I("post.householdHeadIdCard");//户主身份证号
        $data["householdHeadRelation"] = I("post.householdHeadRelation");//与户主关系
        $data["whetherOutsideAttendIns"] = I("post.whetherOutsideAttendIns");//是否县外参保
        $data["whetherEmployment"] = I("post.whetherEmployment");//是否就业
        $data["flowPeople"] = I("post.flowPeople");//流动人员
        $data["notAttendInsReason"] = I("post.notAttendInsReason");//未参保原因
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
            $info["url"] = U("Home/NotAttendIns/getIdcardImg");
            session("NotAttendInsPeopleInfo", $data);
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
            $data = session("NotAttendInsPeopleInfo");
            $data["idcardBackImg"] = $idcardBackImg;
            $data["idcardfrontImg"] = $idcardfrontImg;
            session("NotAttendInsPeopleInfo", $data);
            $info["code"] = -1;
            $info["message"] = "成功";
            $info["url"] = U("Home/NotAttendIns/gethouseholdImg");
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
            $this->display();
            return;
        }
        $gethouseholdFirstImg = I("gethouseholdFirstImg");//户口本主页
        $gethouseholdPeopleImg = I("gethouseholdPeopleImg");//户口本个人页
        if (!empty($gethouseholdFirstImg) && !empty($gethouseholdPeopleImg)) {
            $data = session("NotAttendInsPeopleInfo");
            $data["gethouseholdFirstImg"] = $gethouseholdFirstImg;
            $data["gethouseholdPeopleImg"] = $gethouseholdPeopleImg;
            //请求接口 检测用户是否参保
            $javaurl = $this->javaUrl;
            $url = $javaurl["NotAttendIns"]["savePeopleInfo"];
            $requestObj = new Request();
            $result = $requestObj->requset($url, $data, "post");
            $result = json_decode($result, true);
            if ($result["code"] == 0) {
                $info["code"] = 1;
                $info["message"] = "成功";
                $info["url"] = U("Home/NotAttendIns/NotAttendInsIndex");
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

}