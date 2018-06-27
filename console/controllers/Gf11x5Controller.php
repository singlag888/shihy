<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;


use frontend\models\Gf11x5;
use frontend\models\Lottery;
use yii\web\Controller;

class Gf11x5Controller extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 官方11x5自开型  116
     */
    public function actionGf11x5(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gf11x5();
        $string = '01,02,03,04,05,06,07,08,09,10,11';
        $string  = explode(',',$string);
        shuffle($string);

        $array= array_slice($string,0,5);
        $number = implode(',',$array);
        $time = date('Ymd');
        $result = Gf11x5::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gf11x5::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gf11x5',time());
            $redis->expire('gf11x5',60);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方11选5';
           $model->color_id = 116;
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
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
            $lottery->Winning(116);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方11选5';
            $model->period = $period;
            $model->behind_period = $model->period +1;
                     $model->color_id = 116;
            $model->save();
        }

    }
}