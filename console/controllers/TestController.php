<?php

namespace console\controllers;

use backend\models\Gfffc;
use frontend\models\Ssctxffc;
use yii\console\Controller;
use frontend\models\Lottery;

class TestController extends  Controller
{
    //解决ajax报400错误
    public $enableCsrfValidation = false;

    /**
     * 腾讯分分彩获取接口数据
     */
    public function actionTest()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=txffc&format=json");
        $api = json_decode($url, true);
        $number = array_reverse($api['data']);//得到开奖号码
        ksort($number);
        //遍历号码
        foreach ($number as $value){
            //拿到期号到数据库里面去查，看有没有这个期号
            $ssctxffc = Ssctxffc::findOne(['period'=>$value['expect']]);
            if ($ssctxffc){//期号存在
                if (!$ssctxffc->number){
                    /**将开奖好码补起**/
                    if(!$redis->ttl('txffc')){
                        $redis->get('txffcs');
                        $redis->incr('txffcs');
                    }else{
                        $redis->set('txffc',time());
                        $redis->expire('txffc',60);
                        $result = $redis->ttl('txffc')-$redis->get('txffcs');
                        $redis->set('result',$result);
                        $redis->expire('txffcs',0);
                        $ssctxffc->time = time();
                        $ssctxffc->number = $value['opencode'];
                        $ssctxffc->receive_time = strtotime($value['opentime']);
                        $ssctxffc->status = 1;
                        $ssctxffc->save();
                 
                        /**
                         * 生成一条新的数据
                         */

                        $ssctxffcs = new Ssctxffc();
                        $ssctxffcs->name = '腾讯分分彩';
                        $ssctxffcs->period = $value['expect']+1;
                        $date = date('Ymd');
                        if($ssctxffcs->period !==$date.'1441'){
                            if($ssctxffcs->period ==$date.'1440'){
                                $num = substr($ssctxffc->period,0,-4);
                                $ssctxffcs->behind_period = ($num+1).'0001';
                                $ssctxffcs->save();//保存
                            }else{
                                $ssctxffcs->behind_period = $ssctxffcs->period+1;//拿到前端显示下一期的开奖期号
                                $ssctxffcs->save();//保存
                            }
                                

                        }
                       $lottery = new Lottery();
                       $lottery->Winning();
                    
                    }

                }
            }else{//期号没有存在，就新增数据
                $ssctxffcs = new Ssctxffc();
                $ssctxffcs->name = '腾讯分分彩';
                $ssctxffcs->number = $value['opencode'];
                $ssctxffcs->time = time();
                $ssctxffcs->receive_time = strtotime($value['opentime']);
                $ssctxffcs->status = 1;
                $ssctxffcs->period = $value['expect'];
                $ssctxffcs->behind_period = $value['expect']+1;//拿到前端显示下一期的开奖期号
                $ssctxffcs->save();//保存]
                /**
                 * 生成一条新的数据
                 */
                $ssctxffcs = new Ssctxffc();
                $ssctxffcs->name = '腾讯分分彩';
                $ssctxffcs->period = $value['expect']+1;
                $ssctxffcs->behind_period = $ssctxffcs->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffcs->save();//保存
                $lottery = new Lottery();
                $lottery->Winning('腾讯分分彩');
            }
        }
    }


}
