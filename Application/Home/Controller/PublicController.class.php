<?php
namespace Home\Controller;
use Think\Controller;
class PublicController extends controller{

    /**
     * 登录
     */
    public function login(){
        $userinfo = S(session("username"));
        if(is_login()){
            redirect(U("Index/index"));
        }
        //判断ajax请求 不是则显示页面
        if (!IS_AJAX){
            $this->display();
            return;
        }
        //I方法获取参数 userName passWord
        $data["userCode"]=I("get.userName");
        $data["password"]=I("get.passWord");
        //参数不全 返回错误信息
        if(empty($data["userCode"])||empty($data["password"])){
            $info['status']  = -1;
            $info['message'] = '信息不完整';
            $this->ajaxReturn($info);
        }
        //请求Java接口验证用户名和密码的合法性
        $url=C("REQUEST_URL")."/userLogin";
        $result=curl_request($url,$data);
        if ($result===false) {
            $info['status']  = -1;
            $info['message'] = '接口请求失败';
            $this->ajaxReturn($info);;
        }
        $result=json_decode($result,true);
        if ($result["code"]===0){
            //用户名生成session 并将用户相关数据加入缓存 保存一天退出登录时删除缓存
            session("username",$result['data']["username"]);
            S($result['data']["username"],$result,86400);
            $info['status']  = 1;
            $info['message'] = '登录成功';
            $info['url'] = U("Index/index");
            $this->ajaxReturn($info);;
        }else{
            $info['status']  = -1;
            $info['message'] = $result["msg"];
            $this->ajaxReturn($info);
        }
    }

    /**
     * 登出
     */
    public function outLogin(){
        S(session("username"),null);
        // 删除所有 Session 变量
        $_SESSION = array();
        //判断 cookie 中是否保存 Session ID
        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(),'',time()-3600, '/');
        }
        //彻底销毁 Session
        session_destroy();
        redirect(U("Public/login"));
    }
   
    public function shenhe(){
        $this->display("shenhe");
    }


    public function pay1(){
        $this->display("pay1");
    }

    public function pay2(){
        $this->display("pay2");
    }
}

