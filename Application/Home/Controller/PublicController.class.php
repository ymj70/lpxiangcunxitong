<?php

namespace Home\Controller;

use Think\Controller;
use Org\Request\Request;

class PublicController extends controller
{

    /**
     * 登录
     */
    public function login()
    {
        if (is_login()) {
            redirect(U("Index/index"));
        }
        //判断ajax请求 不是则显示页面
        if (!IS_AJAX) {
            $this->display();
            return;
        }
        //I方法获取参数 userName passWord
        $data["userName"] = I("get.userName");
        $data["password"] = I("get.passWord");
        //参数不全 返回错误信息
        if (empty($data["userName"]) || empty($data["password"])) {
            $info['code'] = -1;
            $info['message'] = '信息不完整';
            $this->ajaxReturn($info);
        }
        //请求Java接口验证用户名和密码的合法性
        $javaUrl = C("javaUrl");
        $url = C("REQUEST_URL") . $javaUrl["Public"]["login"];
        $requestObj = new Request();
        $result = $requestObj->requset($url, $data, "post");
        $result = json_decode($result, true);
        if ($result["code"] === 0) {
            //用户名生成session 并将用户相关数据加入缓存 保存一天退出登录时删除缓存
            session("username", $result['data']["userName"]);
            S($result['data']["userName"], $result["data"], 86400);
            $info['code'] = 1;
            $info['message'] = '登录成功';
            $info['url'] = U("Index/index");
            $this->ajaxReturn($info);;
        } else {
            $info['code'] = -1;
            $info['message'] = $result["msg"];
            if (empty($info['message'])) {
                $info['message'] = "接口请求失败";
            }
            $this->ajaxReturn($info);
        }
    }

    /**
     * 登出
     */
    public function outLogin()
    {
        S(session("username"), null);
        // 删除所有 Session 变量
        $_SESSION = array();
        //判断 cookie 中是否保存 Session ID
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        //彻底销毁 Session
        session_destroy();
        redirect(U("Public/login"));
    }
   //测试
    public function shenhe(){
        $this->display("shenhe");
    }

//社保缴费 > 城乡居民医疗保险缴费   缴费须知：
    public function pay1(){
        $this->display("pay1");
    }
//社保缴费 > 城乡居民医疗保险缴费   列表
    public function pay2(){
        $this->display("pay2");
    }


//社保缴费 > 城乡居民医疗保险缴费   城乡居民医疗保险缴费人员明细
    public function pay3(){
        $this->display("pay3");
    }


    //社保缴费 > 城乡居民医疗保险缴费   微信支付
    public function pay4(){
        $this->display("pay4");
    }
//人员登记 > 城乡居民医疗保险参保登记 > 参保登记进度查询   参保登记审批进度查询结果
       public function cbdjjdcx(){
        $this->display("cbdjjdcx");
    }

    
//人员登记 > 城乡居民医疗保险参保登记 > 参保登记进度查询   参保登记审批进度查询结果
       public function cbcx(){
        $this->display("cbcx");
    }


//人员登记 > 城乡居民养老保险参保登记 > 参保登记进度查询    参保登记审批进度查询结果
       public function cbdjjdcx2(){
       // $this->display("cbdjjdcx2");
    }
//人员登记 > 城乡居民养老保险参保登记     城乡居民基本养老保险参保注意事项：
       public function cxjmylcbdj(){
       // $this->display("cxjmylcbdj");
    }
//人员登记 > 城乡居民医疗保险参保登记    城乡居民基本医疗保险参保注意事项：
       public function cxylcbdj(){
        //$this->display("cxylcbdj");
    }
//人员登记 > 未参保人员登记   读取人员身份证信息
       public function wcbryba(){
        //$this->display("wcbryba");
    }

    //首页
       public function homePage(){
        //$this->display("homePage");
    }

   //人员登记 > 就业困难人员登记    登记就业困难人员信息须知
       public function jyknrydj(){
        $this->display("jyknrydj");
    }






   //人员登记 > 城乡居民养老保险参保登记   填写人员信息及其他参保
       public function cxjmylbxcbdj(){
        $this->display("cxjmylbxcbdj");
    }
       //人员登记 > 城乡居民医疗保险参保登记   填写参保人员信息
       public function cxjmylbxcbdj1(){
        $this->display("cxjmylbxcbdj1");
    }
       //人员登记 > 未参保人员登记    登记人员信息
       public function cxjmylbxcbdj2(){
        $this->display("cxjmylbxcbdj2");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylbxcbdjScwj(){
        $this->display("cxjmylbxcbdjScwj");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylcbdj2(){
        $this->display("cxjmylcbdj2");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylcbdj3(){
        $this->display("cxjmylcbdj3");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylcbdj4(){
        $this->display("cxjmylcbdj4");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylcbdj5(){
        $this->display("cxjmylcbdj5");
    }
       //人员登记 > 城乡居民养老保险参保登记
       public function cxjmylcbdj6(){
        $this->display("cxjmylcbdj6");
    }





           //我的消息   创建消息
       public function xcreatnews1(){
        $this->display("xcreatnews1");
    }
           //我的消息
       public function xcreatnews2(){
        $this->display("xcreatnews2");
    }
           //我的消息   消息详情
       public function xcreatnews3(){
        $this->display("xcreatnews3");
    }
           //待遇资格认证
       public function xdyzgrz1(){
        $this->display("xdyzgrz1");
    }
           //待遇资格认证
       public function xdyzgrz2(){
        $this->display("xdyzgrz2");
    }
           //社保缴费   城乡养老缴费档次变更
       public function xpay5(){
        $this->display("xpay5");
    }
           //信息查询   城乡养老缴费信息查询
       public function xyljfcx1(){
        $this->display("xyljfcx1");
    }
           //信息查询    城乡医疗缴费信息查询
       public function xyljfcx10(){
        $this->display("xyljfcx10");
    }
           //信息查询   医疗提高查询
       public function xyljfcx11(){
        $this->display("xyljfcx11");
    }
           //信息查询     医疗报销查询
       public function xyljfcx12(){
        $this->display("xyljfcx12");
    }
           //信息查询     城乡居民养老保险参保登记    参保登记进度查询
       public function xyljfcx13(){
       $this->display("xyljfcx13");
    }
           //信息查询   城乡居民养老保险参保登记    参保登记进度查询
       public function xyljfcx14(){
        $this->display("xyljfcx14");
    }
           //信息查询    城乡养老缴费信息查询
       public function xyljfcx15(){
        $this->display("xyljfcx15");
    }
           //信息查询    参保信息查询
       public function xyljfcx2(){
        $this->display("xyljfcx2");
    }
           //信息查询    待遇领取信息查询
       public function xyljfcx3(){
        $this->display("xyljfcx3");
    }
           //信息查询    待遇领取信息查询
       public function xyljfcx4(){
        $this->display("xyljfcx4");
    }
           //信息查询    医疗报销查询
       public function xyljfcx5(){
        $this->display("xyljfcx5");
    }
               //信息查询    医疗报销结果查询
       public function xyljfcx6(){
        $this->display("xyljfcx6");
    }
               //信息查询    城乡居民医疗保险参保登记    参保登记进度查询
       public function xyljfcx7(){
    //    $this->display("xyljfcx7");
    }
               //信息查询    城乡居民医疗保险参保登记    参保登记进度查询
       public function xyljfcx8(){
       // $this->display("xyljfcx8");
    }
               //信息查询   城乡医疗缴费信息查询
       public function xyljfcx9(){
        $this->display("xyljfcx9");
    }
}

