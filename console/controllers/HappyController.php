<?php

namespace console\controllers;

use backend\models\Hg15fc;
use backend\models\Jndsfc;
use backend\models\Ltxffc;
use backend\models\Xxl45mc;
use frontend\models\Txffc;
use backend\models\Xjplfc;
use yii\console\Controller;
use frontend\models\Lottery;

class HappyController extends  Controller
{
    //解决ajax报400错误
    public $enableCsrfValidation = false;
    /**
     * 老腾讯分分彩  1299
     */
    public function actionLtxffc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=jsk3&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Ltxffc::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('ltxffc',$date);
            $redis->expire('ltxffc',60);
            $ssctxffc = new Ltxffc();
            $ssctxffc->time = time();
            $ssctxffc->name = '老腾讯分分彩';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
            $ssctxffc->color_id = 23;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '082'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(1299);
        }
    }




    /**
     * 腾讯分分彩 自开型 60
     */
    public function actionTxffc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Txffc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Txffc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Txffc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('txffc',time());
            $redis->expire('txffc',60);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '腾讯分分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 60;
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
            $lottery->Winning(60);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '腾讯分分彩';
            $model->period = $period;
            $model->color_id = 60;
            $model->behind_period = $model->period +1;;
            $model->save();
        }
    }



    /**
     * 韩国1.5分彩 自开型 42
     */
    public function actionHg15fc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Hg15fc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Hg15fc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Hg15fc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('hg15fc',time());
            $redis->expire('hg15fc',90);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '韩国1.5分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 42;
            if($model->period !==$time.'961'){
                if($model->period ==$time.'960'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(42);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '韩国1.5分彩';
            $model->period = $period;
            $model->color_id = 42;
            $model->behind_period = $model->period +1;;
            $model->save();
        }

    }



    /**
     * 加拿大三分彩 自开型 1297
     */
    public function actionJndsfc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Jndsfc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Jndsfc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Jndsfc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('jndsfc',time());
            $redis->expire('jndsfc',180);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '加拿大三分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 1297;
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
            $lottery->Winning(1297);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '加拿大三分彩';
            $model->period = $period;
            $model->color_id = 1297;
            $model->behind_period = $model->period +1;;
            $model->save();
        }
    }



    /**
     * 新加坡两分彩 自开型 1298
     */
    public function actionXjplfc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Xjplfc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Xjplfc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Xjplfc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('xjplfc',time());
            $redis->expire('xjplfc',120);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '新加坡两分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 1298;
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
            $lottery->Winning(1298);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '新加坡两分彩';
            $model->period = $period;
            $model->color_id = 1298;
            $model->behind_period = $model->period +1;;
            $model->save();
        }
    }





    /**
     * 新西兰45秒彩 自开型 66
     */
    public function actionXxl45mc(){
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Xxl45mc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Xxl45mc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Xxl45mc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('xxl45mc',time());
            $redis->expire('xxl45mc',45);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '新西兰45秒彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 66;
            if($model->period !==$time.'1561'){
                if($model->period ==$time.'1560'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(66);
        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '新西兰45秒彩';
            $model->period = $period;
            $model->color_id = 66;
            $model->behind_period = $model->period +1;;
            $model->save();
        }
    }
}
