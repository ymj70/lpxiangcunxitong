<?php
return array(
    //程序中访问java接口的映射 当访问java接口时获取该配置中的对应url去访问 而不是在程序中把接口url写死 这样便于修改
    "javaUrl" => array(
        "Public" => array(
            "login" => "/user/login",//登录
        ),
        "NotAttendIns" => array(
            "checkPeopleInsStatus"=>"/personBase/verificationIdCard",//检测是否参保
            "savePeopleInfo"=>"/personBase/save",//将未参保人员信息存入数据库
        ),
        "MedicalInsAttend" => array(
            "getPeopleInfo"=>"",//根据身份证号获取信息
            "checkPeopleMedicalInsStatus"=>"/personBase/checkIdCard",//根据身份证号检测是否参加城乡居民医疗和城镇职工医疗
            "saveMedicalInsPeopleInfo"=>"/personBase/saveMedical ",//将参加城乡医疗保险的的人员信息保存到数据路
        ),
        "EndowmentInsAttend" => array(
            "getPeopleInfo"=>"",//根据身份证号获取信息
            "checkEndowmentInsAttendStatus"=>"/personBase/checkIdCard",//根据身份证号检测是否参加城乡居民医疗和城镇职工医疗
            "saveEndowmentInsPeopleInfo"=>"/personBase/saveMedical",//将参加城乡医疗保险的的人员信息保存到数据路
        ),
        "EndInsAttendProgress" => array(
            "showResult"=>"/personBase/speedPension",//养老参保登记进度查询
        ),
        "QueryTreatmentCollect" => array(
            "showResult"=>"/grant/upTr",//待遇领取查询
        ),
        "MedInsAttendProgress" => array(
            "showResult"=>"/personBase/speedPension",//医疗参保登记进度查询
        ),
        "QueryEndInsPay" => array(
            "getPeopleInfo"=>"/personBase/verificationIdCard",//检测身份证号
            "showResult"=>"/grant/upPay",
        ),
        "QueryAttendInsInfo" => array(
            "getPeopleInfo"=>"/personBase/verificationIdCard",//检测身份证号
            "getPeopleAttndIns"=>"/personBase/getPensionMedical",
        ),
    ),
    "imgUploadUrl" => "/image/uploadFile",//图片上传
    "imgDownUrl" => "/image-service/misauth/getimage",//图片上传

);





