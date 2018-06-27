<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/31 0031
 * Time: 15:57
 */

namespace frontend\controllers;


use backend\models\Admin;
use frontend\components\Sms;
use frontend\models\ErverLottery;
use frontend\models\Mosaic;
use frontend\models\MyTable;
use frontend\models\PrizePool;
use frontend\models\TeamLottery;
use yii\web\Controller;
use yii\web\Response;

/**
 * 通讯
 * Class CommunicationController
 * @package frontend\controllers
 */
class CommunicationController extends Controller
{


    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     *  发送短信模板 并保存在redis中
     * @param $phone 手机号码
     * @return string
     */
    //验证短信验证码
    public function actionVerification($phone)
    {
        //防止短信被刷
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $ttl = $redis->ttl('code_' . $phone);
        if ($ttl && $ttl > 5 * 60 * 60 - 60) {//间隔一秒才发送短信
            die;
        }
        //正则验证手机号
        $tre = preg_match("/^1[3|4|5|8][0-9]\d{4,8}$/", $phone);
        if ($tre) {
            $code = rand(10000, 99999);
            $send = \Yii::$app->sms->send($phone, ['code' => $code]);
            if ($send->Code == "OK") {
                //将短信根据手机号的不同存到redis

                $redis->set('code_' . $phone, $code, 5 * 60 * 60);
                return "ture";
            } else {
                return "验证码发送失败";
            }
        } else {
            return "手机号不正确";
        }
    }


    /**
     * 拿取redis中的手机验证码
     * @param $tel  手机号
     * @param $captcha 短信验证码
     * @return string
     */
    public function actionCheckCaptcha($tel, $captcha)
    {
        //从redis获取验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('captcha_' . $tel);
        //返回对比结果
        //验证验证码
        if ($code) {
            if ($captcha == $code) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }


    public function actionStart(){
        $data = [
            [
                'money'=>'130000000','color'=>116,'status'=>1
            ],
            [
                'money'=>'130000000','color'=>112,'status'=>1
            ],

            [
                'money'=>'130000000','color'=>117,'status'=>1
            ],
        ];
        foreach ($data as $datum){
            $prizepool = new PrizePool();
            $prizepool->money = $datum['money'];
            $prizepool->color = $datum['color'];
            $prizepool->add_time = date("Ymd",time());
            $prizepool->status = $datum['status'];
            $prizepool->save();
        }
        Mosaic::deleteAll();
        $admin = Admin::find()->where(['!=','type',3])->andWhere(['!=','type',2])->all();
        foreach ($admin as $a){
            $models = new Mosaic();
            $models->admin_id = $a['id'];
            $models->number = 5;
            $models->status = 1;
            $models->save();
        }
        $admin = Admin::find()->where(['!=','type',3])->all();
        foreach ($admin as $v){
            $time = date("Ymd");
            $erverlottery = new ErverLottery();
            $erverlottery->admin_id = $v['id'];
            $erverlottery->username = $v->username;
            $erverlottery->time = $time;//日期
            $erverlottery->all = 0.000;//总下注
            $erverlottery->number = 0;//笔数
            $erverlottery->rebate = 0.000;//返点
            $erverlottery->team = 0.000;//团队转水
            $erverlottery->color = 0.000;//派彩
            $erverlottery->activity = 0.000;//活动
            $erverlottery->yk = 0.000;//盈亏
            $erverlottery->save();

            $teamlottery = new TeamLottery();
            $teamlottery->admin_id = $v['id'];
            $teamlottery->username = $v->username;
            $teamlottery->time = $time;//日期
            $teamlottery->all = 0.000;//总下注
            $teamlottery->number = 0;//笔数
            $teamlottery->rebate = 0.000;//返点
            $teamlottery->team = 0.000;//团队转水
            $teamlottery->color = 0.000;//派彩
            $teamlottery->activity = 0.000;//活动
            $teamlottery->yk = 0.000;//盈亏
            $teamlottery->save();

            $my =new  MyTable();
            $my->time = $time;//日期
            $my->username = $v->username;
            $my->admin_id = $v['id'];
            $my->recharge = 0.000;
            $my->withdrawals = 0.000;
            $my->in_game = 0.000;
            $my->out_game = 0.000;
            $my->all = 0.000;//总下注
            $my->number = 0;//笔数
            $my->water = 0.000;//流水
            $my->rebate = 0.000;//返点
            $my->team = 0.000;//团队转水
            $my->color = 0.000;//派彩
            $my->activity = 0.000;//活动
            $my->yk = 0.000;//盈亏
            $my->save();

        }
    }

}