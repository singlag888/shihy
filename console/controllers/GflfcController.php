<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;


use backend\models\Gflfc;
use frontend\models\Lottery;
use yii\web\Controller;

class GflfcController extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 官方两分彩自开型  113
     */
    public function actionGflfc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gflfc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        var_dump($number);
        $time = date('Ymd');
        $result = Gflfc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gflfc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gflfc',time());
            $redis->expire('gflfc',120);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方两分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->number = $number;
               $model->color_id = 113;
            if($model->period !==$time.'0721'){
                if($model->period ==$time.'0720'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(113);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方两分彩';
            $model->period = $period;
            $model->behind_period = $model->period +1;;
              $model->color_id = 113;
            $model->save();
        }

    }
}