<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9 0009
 * Time: 18:09
 */

namespace console\controllers;


use frontend\models\Bjpk10;
use frontend\models\Lottery;
use yii\web\Controller;

class Pk10Controller extends Controller
{

    public $enableCsrfValidation =false;
    /**
     * 北京PK10获取接口数据
     */
    public function actionBjpk10()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=bjpk10&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Bjpk10::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('bjpk10',$date);
            $redis->expire('bjpk10',300);
            $ssctxffc = new Bjpk10();
            $ssctxffc->time = time();
            $ssctxffc->name = '北京PK10';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                      $ssctxffc->color_id =27;
            $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
            $ssctxffc->save();//保存
            $lottery = new Lottery();
            $lottery->Winning(27);
        }
    }

}