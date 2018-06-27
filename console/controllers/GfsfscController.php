<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19 0019
 * Time: 17:58
 */

namespace console\controllers;



use frontend\models\Gfsdwf;
use frontend\models\Gfsfsc;
use frontend\models\Lottery;
use yii\web\Controller;

class GfsfscController extends Controller
{

//解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 官方3分赛车自开型
     */
    public function actionGfsfsc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gfsfsc();
        $string = '1,2,3,4,5,6,7,8,9,10';
        $string  = explode(',',$string);
        shuffle($string);
        $number = implode(',',$string);
        $time = date('Ymd');
        $result = Gfsfsc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gfsfsc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gfsfsc',time());
            $redis->expire('gfsfsc',180);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方三分赛车';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
          	$model->color_id = 119;
            if($model->period !==$time.'481'){
                if($model->period ==$time.'480'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(119);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方三分赛车';
            $model->period = $period;
            $model->behind_period = $model->period +1;;
                   	$model->color_id = 119;
            $model->save();
        }

    }
}