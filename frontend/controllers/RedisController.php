<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21 0021
 * Time: 9:34
 */

namespace frontend\controllers;


use yii\web\Controller;
use yii\web\Response;
use frontend\models\PrizePool;

header('Access-Control-Allow-Origin:*');

class RedisController extends Controller
{
    //解决网页报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 获取redis倒计时
     * @return bool|string
     */
    public function actionRedis()
    {
        $color = \Yii::$app->request->post('color_id');
        $data = array(
            "color" => $color
        );
        $url="http://gif.shenbang9.com/redis/redis";
        $ch = curl_init();//创建连接
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//将数组转换为URL请求字符串，否则有些时候可能服务端接收不到参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); //接收服务端范围的html代码而不是直接浏览器输出
        curl_setopt($ch, CURLOPT_HEADER, false);
        $responds = curl_exec($ch);//接受响应
        curl_close($ch);//关闭连接
        return json_decode($responds,true);
    }


    public function actionRe()
    {
        $data = [
            [
                'money' => '130000000', 'color' => '官方11选5', 'status' => 1
            ],
            [
                'money' => '130000000', 'color' => '官方分分彩', 'status' => 1
            ],
            [
                'money' => '130000000', 'color' => '重庆分分彩', 'status' => 1
            ],
            [
                'money' => '130000000', 'color' => '官方快三', 'status' => 1
            ],
        ];
        foreach ($data as $datum) {
            $prizepool = new PrizePool();
            $prizepool->money = $datum['money'];
            $prizepool->color = $datum['color'];
            $prizepool->add_time = date("Ymd", time());
            $prizepool->status = $datum['status'];
            $prizepool->save();
        }
    }


}