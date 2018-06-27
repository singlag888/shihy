<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;


use backend\models\Gfffc;
use frontend\models\Lottery;
use frontend\models\Robot;
use frontend\models\App;
use frontend\models\PrizePool;
use yii\web\Controller;

class GfffcController extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 官方分分彩自开型  112
     */
    public function actionGfffc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gfffc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Gfffc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gfffc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gfffc',time());
            $redis->expire('gfffc',60);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方分分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 112;
            if($model->period !==$time.'1441'){
                if($model->period ==$time.'1440'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(112);
           //$Robot = new Robot();//机器人
        	//$Robot->RobotBuy('官方分分彩');
          /**60秒倒计时的时候调
     * @return mixed
     */
        $prizepools = PrizePool::find()->where(['add_time' => date("Ymd", time())])->all();
        if ($prizepools) {
            foreach ($prizepools as $prizepool) {
                if ($prizepool->zhangfu >= -40000000 && $prizepool->zhangfu <= 60000000) {
                    if ($prizepool->zhangfu == 0) {
                        $jian = mt_rand(10000, 200000);
                        $prizepool->zhangfu -= $jian;
                        $prizepool->money -= $jian;
                    } else {
                        $jian = mt_rand(10000, 200000);
                        $jia = mt_rand(10000, 200000);
                        $prizepool->zhangfu += $jian - $jia;
                        $prizepool->money += $jian - $jia;
                    }
                }
                $prizepool->save();
            }
        }


        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方分分彩';
            $model->period = $period;
            $model->color_id = 112;
            $model->behind_period = $model->period +1;;
            $model->save();
        }

    }
}