<?php

namespace Home\Controller;

use Think\Controller;
use Org\Request\Request;

class CommonController extends Controller
{
    var $javaUrl = "";//java接口url的信息

    public function _initialize()
    {
       /* //获取java接口url的信息
        $this->javaUrl = C("javaUrl");
        //判断是否登录 没有登录跳转到登录页面
        if (!is_login()) {
            redirect(U("Public/login"));
        }
        //检测用户是否10分钟没有动作 是退出登录
        $this->checkActivityTime();
        //设置左侧菜单 打开和关闭状态的session 默认是1 打开状态
        if (empty(session("menustatus"))) {
            session("menustatus", 1);
        }*/
        //输出账户信息 在网站顶部显示
        $this->showUserInfo();



    }

    /**
     * 检测用户是否10分钟没有动作 是退出登录
     */
    public function checkActivityTime(){
        $activityTime=session("activityTime");
        if (empty($activityTime)){
            session("activityTime",time());
        }else{
            $timeLong=time()-$activityTime;
            if ($timeLong >600){
                A("Public")->outLogin();
            }else{
                session("activityTime",time());
            }
        }
    }

    /**
     * 左侧菜单打开关闭状态控制 前端回请求该方法 请求一次调整状态
     * 1是打开状态 2是关闭状态
     */
    public function menuStatus()
    {
        $status = session("menustatus");
        if ($status == 2) {
            session("menustatus", 1);
            $info["message"]="当前状态：打开";
        } else {
            session("menustatus", 2);
            $info["message"]="当前状态：关闭";
        }
        $info["code"]=1;
        $this->ajaxReturn($info);
    }

    /**
     * 输出账户信息
     */
    private function showUserInfo()
    {
        $uerInfo["userAdress"]="滦平县大屯乡小城子村";
        $uerInfo["userName"]="王雨栋";
        $uerInfo["data1"]=date("Y年m月d日",time());//日期
        $uerInfo["data2"]="星期".mb_substr( "日一二三四五六",date("w"),1,"utf-8" );//星期
        $this->assign("userInfo",$uerInfo);

    }

    /**
     * 空操作
     */
    public function _empty(){
        $this->display('Empty:developing');
    }
}