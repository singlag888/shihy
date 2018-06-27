<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9 0009
 * Time: 9:45
 */

namespace frontend\controllers;

use backend\models\White;
use backend\models\Admin;
use backend\models\Article;
use backend\models\ArticleIndex;
use backend\models\ArticleLogin;
use backend\models\ArticleScroll;
use backend\models\Bank;
use backend\models\BankList;
use backend\models\Game;
use backend\models\Gfffc;
use backend\models\Logo;
use backend\models\Recharge;
use backend\models\Reports;
use backend\models\Withdrawals;
use frontend\models\BuyAll;
use frontend\models\BuyMonth;
use frontend\models\BuyWeek;
use frontend\models\Change;
use frontend\models\Link;
use frontend\models\Login;
use frontend\models\Lottery;
use frontend\models\Mosaic;
use frontend\models\NoAll;
use frontend\models\NoMonth;
use frontend\models\NoWeek;
use frontend\models\Publics;
use frontend\models\SingleAll;
use frontend\models\SingleMonth;
use frontend\models\SingleWeek;
use frontend\models\Ssctxffc;
use yii\web\Controller;
use yii\web\Response;
header('Access-Control-Allow-Origin:*');
class ApiController  extends  Controller
{
    //解决网页报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 登陆
     */
    public function actionLogin()
    {
           $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['username' => $query['username']]);
        $login = new Login();
        if($admin->status == -1){
            return messages('您的账号已被禁用，请重新注册');
        }else{
            $white =   White::find()->all();
            foreach ($white as $w){
                if($w->type == -1){
                    $sss = White::find()->where(['ip'=>\Yii::$app->request->userIP])->one();
                    if(!$sss){
                        return messages('您的IP不允许访问，请联系管理员');
                    }
                }
            }
            if ($admin) {
                if (\Yii::$app->security->validatePassword($query['password_login'], $admin->password_login)) {
                    //密码正确 验证是否异地登陆
                    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
                    $type = 'windows登陆';
                    if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
                        $type = 'app登陆ios';
                    }
                    if (strpos($agent, 'android')) {
                        $type = 'app登陆android';
                    }

//                    $ipInfos = GetIpLookup(\Yii::$app->request->userIP); //baidu.com IP地址  //ipInfos 是一个数组
                    //查看登陆记录中是否是用户第一次登陆
                    $loginss = Login::find()->where(['admin_id' => $admin->id])->one();
                    $admin->login_time = time();
                    $admin->login_ip = \Yii::$app->request->userIP;
                    $admin->city = $query['city'];
                    $token = \Yii::$app->security->generateRandomString();
                    $admin->tokens = $token;
                    $login->url = \Yii::$app->request->getHostInfo() . \Yii::$app->request->url;//获取用户登陆的Url
                    $login->admin_id = $admin->id;
                    $login->type = $type;
                    $login->explorer = $_SERVER['HTTP_USER_AGENT'];
                    $login->login_ip = \Yii::$app->request->getUserIP();
                    $login->username = $query['username'];
                    $login->login_time = time();
                    $login->city = $query['city'];
                    //如果登陆信息表中没有次用户数据
                    if (!$loginss) {
                        //判断登陆的IP  登陆城市  IP段
                        //如果登陆信息表中没此用户的登陆信息
                        //密码正确，验证成功
                        //保存登陆信息
                        $login->number += 1;
                        $login->save();
                        $admin->save();
                        Admin::updateAll(['tokens'=>$token],['id'=>$admin->id]);
                        return messages('操作成功', 1, ['id' => $admin->id, 'token' =>$token,'status'=>0]);
                    }else {
                        //if (\Yii::$app->request->getUserIP() !== $admin->login_ip && $ipInfos['city'] !== $admin->city) {
                        // return messages('操作失败，异地登陆，跳转验证页面', 1, ['id' => $admin->id, 'token' => $admin->tokens,'status'=>-1]);
                        //} else {
                        //如果登陆信息表中没此用户的登陆信息
                        //密码正确，验证成功
                        //保存登陆信息
                        $desc = Login::find()->where(['admin_id' => $admin->id])->orderBy('number desc')->one();
                        $login->number = sprintf('%01s', $desc->number + 1);
                        $login->save();
                        $admin->save();
                        Admin::updateAll(['tokens'=> $token],['id'=>$admin->id]);
                        return messages('操作成功', 1, ['id' => $admin->id, 'token' => $token,'status'=>0]);
                        //}
                    }
                }else {
                    return messages('操作失败,用户账号或密码错误');
                }
            }else {
                return messages('操作失败,用户账号或密码错误');
            }
        }

    }

    /**
     * 注册
     */
    public function actionRegister(){
        $query = \Yii::$app->request->post();
        $get = \Yii::$app->request->post('time');
        $time  = Link::findOne(['created_time'=>$get]);
        $model  = new Admin();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->username = $query['username'];
            $key = Admin::findOne(['username' => $query['username']]);
            if ($key) {
                return messages('操作失败,用户名已存在',-1);
            }
            $model->password_login =\Yii::$app->security->generatePasswordHash($query['password_login']);
            $model->password_pay =\Yii::$app->security->generatePasswordHash($query['password_pay']);
            $model->token = \Yii::$app->security->generateRandomString();//随机生成32位auth_key
            $model->created_ip = \Yii::$app->request->userIP;
            $model->created_time = time();
            $model->price = 0;
            function GetIpLookup($ip = ''){
                if(empty($ip)){
                    return '请输入IP地址';
                }
                $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
                if(empty($res)){ return false; }
                $jsonMatches = array();
                preg_match('#\{.+?\}#', $res, $jsonMatches);
                if(!isset($jsonMatches[0])){ return false; }
                $json = json_decode($jsonMatches[0], true);
                if(isset($json['ret']) && $json['ret'] == 1){
                    $json['ip'] = $ip;
                    unset($json['ret']);
                }else{
                    return false;
                }
                return $json;
            }
            $ipInfos = GetIpLookup( \Yii::$app->request->userIP); //baidu.com IP地址  //ipInfos 是一个数组
            $model->city =$ipInfos['city'];
            $model->parent_id =$time->admin_id;
            $model->max =$time->max;
            $model->city =$time->city;
            $model->min =$time->min;
            $model->type =$time->type;
            $model->status = 1;
            $result = GameController::Register($query['username'],$query['password_login']);
            if ($result['Code'] != 0)return messages('操作失败', -1);
            $model->save();
            $reports = new Reports();
            $reports->admin_id = $model->id;
            $reports->username = $query['username'];
            $reports->save();

            $shy = new Mosaic();
            $shy->admin_id = $model->id;
            $shy->number = 5;
            $shy->save();
        }else{
            return messages('操作失败,请按照正确方式提交',-1);
        }
    }


    /**
     * 验证异地登录
     */
    public function actionArea(){
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $bank = Bank::find()->where(['admin_id'=>$query['admin_id']])->andWhere(['bank_number'=>$query['bank_number']])->one();
        if($admin->username == $query['username'] && $bank){
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            $type = 'windows登陆';
            if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
                $type = 'app登陆ios';
            }
            if(strpos($agent, 'android')){
                $type = 'app登陆android';
            }
            //获取IP地址(市区)
            function GetIpLookup($ip = ''){
                if(empty($ip)){
                    return '请输入IP地址';
                }
                $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
                if(empty($res)){ return false; }
                $jsonMatches = array();
                preg_match('#\{.+?\}#', $res, $jsonMatches);
                if(!isset($jsonMatches[0])){ return false; }
                $json = json_decode($jsonMatches[0], true);
                if(isset($json['ret']) && $json['ret'] == 1){
                    $json['ip'] = $ip;
                    unset($json['ret']);
                }else{
                    return false;
                }
                return $json;
            }
            $ipInfos = GetIpLookup(\Yii::$app->request->userIP); //baidu.com IP地址  //ipInfos 是一个数组
            $login = new Login();
            $login->url =\Yii::$app->request->getHostInfo().\Yii::$app->request->url;//获取用户登陆的Url
            $login->admin_id=$admin->id;
            $login->type=$type;
            $login->explorer =  $_SERVER['HTTP_USER_AGENT'];
            $login->login_ip = \Yii::$app->request->getUserIP();
            $login->username = $query['username'];
            $login->number += 1;
            $login->login_time = time();
            $admin->city = $ipInfos['city'];
            $login->city = $ipInfos['city'];
            $login->save();
            $admin->save();
            return messages('操作成功',1);
        }else{
            return messages('操作失败,用户名或银行卡不正确');
        }
    }


    /**
     * 用户协议(登录弹出框)
     */
    public function actionProtocol(){
        $article_login = ArticleLogin::find()->all();
        return messages('操作成功',1,$article_login);

    }


    /**
     * 首页获取用户数据
     */
    public function actionBalance(){
        $t = time();
        $start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));  //当天开始时间
        $end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); //当天结束时间
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $admin['token'] = $admin->tokens;
        //上级ID == 账号
        $admins = Admin::findOne(['id'=>$admin->parent_id]);
        if($admins){
            $admin->parent_id = $admins->username;
        }else{
            $admin->parent_id = '';
        }

        //团队人数
        $model = new Admin();
        $peoples = $model->all($query['admin_id']);
        $price = 0;
        $rebate = 0;
        foreach ($peoples as $v){
            $adminss = Admin::findOne(['id'=>$v]);
            $price += $adminss->price;
            $ly = Lottery::find()->where(['admin_id'=>$v])->andWhere(['>=','created_time',strtotime($start_time)])->andWhere(['<=','created_time',strtotime($end_time)])->select(['rebate'=>'SUM(rebate)'])->all();
            foreach ($ly as $y){
                $rebate +=$y->rebate;
            }
        }
        $people = count($peoples);

        //今日注册
        $register = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->count();
        //团队在线人数
        $online_number = Admin::online($query['admin_id']);
        //今日返点

        $loginList = Login::find()->where(['admin_id'=>$query['admin_id']])->orderBy('id DESC')->offset(1)->limit(2)->one();
        if($loginList!='' ||$loginList!=null){
            if(token($query['admin_id'],$query['token'])){
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$admin;//个人信息
                $result['data']['team']['last_login_time']=$loginList->login_time;//个人信息
                $result['data']['team']['last_login_ip']=$loginList->login_ip;//个人信息
                $result['data']['team']['prices']=$price;//团队余额
                $result['data']['team']['number']=$people;//团队人数
                $result['data']['team']['register']=$register;//今日注册
                $result['data']['team']['online_number']=$online_number;//当前在线
                $result['data']['team']['rebate']=$rebate;//当前在线
                return $result;
            }else{
                return messages('操作失败,秘钥错误',-2);
            }
        }else{
            if(token($query['admin_id'],$query['token'])){
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$admin;//个人信息
                $result['data']['team']['last_login_time']=0;//个人信息
                $result['data']['team']['last_login_ip']=0;//个人信息
                $result['data']['team']['prices']=$price;//团队余额
                $result['data']['team']['number']=$people;//团队人数
                $result['data']['team']['register']=$register;//今日注册
                $result['data']['team']['online_number']=$online_number;//当前在线
                $result['data']['team']['rebate']=$rebate;//当前在线
                return $result;
            }else{
                return messages('操作失败,秘钥错误',-2);
            }
        }


    }

    /**
     * 前端首页轮播图
     */
    public function actionLogo(){
        $url = \Yii::$app->params['url'];
        $data['msg']='操作成功';
        $data['error']=1;
        $data['data']['list'] = [
            $url."/upload/webtiyu.jpg",
            $url."/upload/webyouxi.jpg",
            $url."/upload/webssq.jpg",
            $url."/upload/webzhibo.jpg",
        ];
        return $data;

    }

    /**
     * App首页轮播图
     */
    public function actionLogos(){
        $url = \Yii::$app->params['url'];
        $data['msg']='操作成功';
        $data['error']=1;
        $data['data']['list'] = [
            $url."/upload/appty.jpg",
            $url."/upload/appyx.jpg",
            $url."/upload/appssq.jpg",
            $url."/upload/appzb.jpg",
        ];
        return $data;

    }


    /**
     * 首页滚动公告
     */
    public function actionScroll(){
        $model = ArticleScroll::find()->all();
        return messages('操作成功',1,$model);
    }


    /**
     * 首页系统公告
     */
    public function actionArticle(){
        $model = Article::find()->all();
        return messages('操作成功',1,$model);
    }


    /**
     * 用户绑定银行卡
     */
    public function actionBank(){
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $bank = new Bank();
        if(token($query['admin_id'],$query['token'])){
            if(\Yii::$app->security->validatePassword($query['password_pay'],$admin->password_pay)){
                $banks = Bank::find()->where(['admin_id'=>$query['admin_id']])->andWhere(['bank_number'=>$query['bank_number']])->one();
                if($banks){
                    return messages('操作失败,银行卡号已存在');
                }
                $bank->status = 1;
                $bank->admin_id = $query['admin_id'];
                $bank->address = $query['address'];
                $bank->username = $admin->username;
                $b = BankList::findOne(['id'=>$query['bank_id']]);
                $bank->bank_name = $b->name;
                $bank->bank_number = $query['bank_number'];
                $bank->bank_id = $query['bank_id'];
                $bank->save();
                return messages('操作成功',1);
            }else{
                return messages('操作失败,资金密码错误');
            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }
    }


    /**
     * 银行卡列表
     */
    public function actionBankList()
    {

        $contents =  file_get_contents('http://gif.shenbang9.com/api/bank-list');
        $model = json_decode($contents,true);
        return messages('操作成功', 1, $model);
    }



    /**
     * 用户查看自己银行卡列表
     */
    public function actionBankMy(){
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $mosaic = Mosaic::findOne(['admin_id'=>$query['admin_id']]);
        if(token($query['admin_id'],$query['token'])){
            $bank = Bank::find()->where(['admin_id'=>$query['admin_id']])->asArray()->all();
            foreach ($bank as $key=>$item){
                $banklist = BankList::findOne(['id'=>$item['bank_id']]);
                $bank[$key]['logo'] = \Yii::$app->params['bank_lis'].$banklist->logo;
            }
            if (!$mosaic){
                $mosaic = [];
            }
            $result['msg']='操作成功';
            $result['error']=1;
            $result['data']['list']=$bank;
            $result['data']['mosaic']=$mosaic;
            $result['data']['price']=$admin->price;
            return $result;
        }else{
            return messages('操作失败,秘钥错误',-2);
        }



    }




    /**
     * 用户登陆记录
     */
    public function actionLoginList()
    {
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        $start_time  = strtotime($query['start_time']);
        $end_time  = strtotime($query['end_time']);
        if (token($query['admin_id'], $query['token'])) {
            $bank = Login::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'login_time',$start_time])->andWhere(['<=', 'login_time',$end_time])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
            $count = Login::find()->where(['admin_id' => $query['admin_id']])->count();
            $yeshu = ceil($count / $query['page_number']);
            $result['msg'] = '操作成功';
            $result['error'] = 1;
            $result['data']['list'] = $bank;
            $result['data']['yeshu'] = $yeshu;
            return $result;
        } else {
            return messages('操作失败,秘钥错误', -2);
        }


    }



    /**
     * 会员列表搜索
     */
    public function actionSearch(){
        $data = \Yii::$app->request->post();
        $admin = Admin::findOne(['username'=>$data['username']]);
        return messages('操作成功',1,$admin);
    }


    /**
     * 查询用户余额
     */
    public function actionPrice(){
        $admin_id = \Yii::$app->request->post('admin_id');
        $token = \Yii::$app->request->post('token');
        if(token($admin_id,$token)){
            $admin = Admin::findOne(['id'=>$admin_id]);
            return messages('操作成功', 1, $admin->price);
        }else{
            return messages('操作失败,秘钥错误', -2);
        }


    }


    /**
     * 用户登陆记录
     */
    public function actionLoginList1()
    {
        $query = \Yii::$app->request->post();
        if (token($query['admin_id'], $query['token'])) {
            $bank = Login::find()->where(['admin_id' => $query['admin_id']])->orderBy('id desc')->limit(5)->all();
           return messages('操作成功',1,$bank);
        } else {
            return messages('操作失败,秘钥错误', -2);
        }


    }


    public function actionCs(){
        $url = \Yii::$app->params['url'];
        $data['msg']='操作成功';
        $data['error']=1;
        $data['data']['list'] = [
            $url."/upload/ceshu1.jpg",
            $url."/upload/ceshu2.jpg",
            $url."/upload/ceshu4.jpg",
        ];
        return $data;
    }
}