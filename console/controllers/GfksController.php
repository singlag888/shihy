<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;



use frontend\models\Gfks;
use frontend\models\Lottery;
use yii\web\Controller;

class GfksController extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /** 
     * 官方快三自开型  117
     */
    public function actionGfks(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gfks();
         $one1 = rand(01,06);
        $one2 = rand(01,06);
        $one3 = rand(01,06);
        $number = '0'.$one1.','.'0'.$one2.','.'0'.$one3;
        $time = date('Ymd');
        $result = Gfks::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gfks::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gfks',time());
            $redis->expire('gfks',60);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方快三';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
              $model->color_id = 117;
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
            $lottery->Winning(117);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方快三';
            $model->period = $period;
            $model->behind_period = $model->period +1;;
              $model->color_id = 117;
            $model->save();
        }

    }
}