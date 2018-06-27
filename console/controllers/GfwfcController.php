<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;


use backend\models\Gfwfc;
use frontend\models\Lottery;
use frontend\models\Robot;
use yii\web\Controller;

class GfwfcController extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 官方五分彩自开型  114
     */
    public function actionGfwfc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gfwfc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Gfwfc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gfwfc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gfwfc',time());
            $redis->expire('gfwfc',300);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方五分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 114;
            if($model->period !==$time.'289'){
                if($model->period ==$time.'288'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(114);
           // $Robot = new Robot();//机器人
        	//$Robot->RobotBuy(112);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方五分彩';
            $model->period = $period;
            $model->behind_period = $model->period +1;;
          	$model->color_id = 114;
            $model->save();
        }

    }
}