<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30 0030
 * Time: 10:27
 */

namespace console\controllers;


use frontend\models\Gd11x5;
use yii\web\Controller;

class Gd11x5Controller extends Controller
{

    //解决网页报400错误
    public $enableCsrfValidation = false;
    /**
     * 广东11选5  9
     */
    public function actionGd11x5(){
        //打开redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        //获取接口数据
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gd11x5&format=json");
        $api = json_decode($url, true);
        $number = array_reverse($api['data']);//得到开奖号码
        ksort($number);
        //遍历号码
        foreach ($number as $value){
            //拿到期号到数据库里面去查，看有没有这个期号
            $ssctxffc = Gd11x5::findOne(['period'=>$value['expect']]);
            if ($ssctxffc){//期号存在
                if (!$ssctxffc->number){
                    /**将开奖好码补起**/
                    if(!$redis->ttl('gd11x5')){
                        $redis->get('gd11x5s');
                        $redis->incr('gd11x5s');
                    }else{
                        $redis->set('gd11x5',time());
                        $redis->expire('gd11x5',60);
                        $result = $redis->ttl('gd11x5')-$redis->get('gd11x5s');
                        $redis->set('result',$result);
                        $redis->expire('gd11x5s',0);
                        $ssctxffc->time = time();
                        $ssctxffc->number = $value['opencode'];
                        $ssctxffc->receive_time = strtotime($value['opentime']);
                        $ssctxffc->status = 1;
                                    $ssctxffc->color_id = 9;
                        $ssctxffc->save();

                        /**
                         * 生成一条新的数据
                         */

                        $ssctxffcs = new Gd11x5();
                        $ssctxffcs->name = '广东11x5';
                        $ssctxffcs->period = $value['expect']+1;
                        $ssctxffcs->color_id = 9;
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
                        $lottery->Winning(9);
                    }

                }
            }else{//期号没有存在，就新增数据
                $ssctxffcs = new Gd11x5();
                $ssctxffcs->name = '广东11x5';
                $ssctxffcs->number = $value['opencode'];
                $ssctxffcs->time = time();
                $ssctxffcs->receive_time = strtotime($value['opentime']);
                $ssctxffcs->status = 1;
                $ssctxffcs->period = $value['expect'];
                  $ssctxffc->color_id = 9;
                $ssctxffcs->behind_period = $value['expect']+1;//拿到前端显示下一期的开奖期号
                $ssctxffcs->save();//保存]
                /**
                 * 生成一条新的数据
                 */
                $ssctxffcs = new Gd11x5();
                $ssctxffcs->name = '广东11x5';
                  $ssctxffc->color_id = 9;
                $ssctxffcs->period = $value['expect']+1;
                $ssctxffcs->behind_period = $ssctxffcs->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffcs->save();//保存
//                $lottery = new Lottery();
//                $lottery->Winning('腾讯分分彩');
            }
        }
    }
}