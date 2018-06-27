<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25 0025
 * Time: 10:39
 */

namespace frontend\controllers;

use backend\models\Admin;
use frontend\models\Buy;
use frontend\models\Lottery;
use frontend\models\BuyAll;
use frontend\models\BuyMonth;
use frontend\models\BuyWeek;
use frontend\models\NoAll;
use frontend\models\NoMonth;
use frontend\models\NoWeek;
use frontend\models\SingleAll;
use frontend\models\SingleMonth;
use frontend\models\SingleWeek;

use yii\web\Controller;
header("Content-type: text/html; charset=utf-8");
/**
 * 神榜
 * Class GodController
 * @package console\controllers
 */
class AppController extends Controller
{

    public $enableCsrfValidation = false;
    /**
     * 单期周榜(不分类型)
     * linux定时任务  每小时执行一次
     */
    public function actionSweek()
    {
        SingleWeek::deleteAll();
        //这周的开始时间 和这周的结束时间
        $time = Buy::Time('周');
        $start_time = $time['start'];
        $end_time = $time['end'];
        //**查询出在指定时间内产生的数据**//
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 1) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new SingleWeek();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 单期月榜(500)
     * linux定时任务  每小时执行一次
     */
    public function actionSmonth()
    {
        //单期月榜 购买次数必须达到500次
        //这月的开始时间 和这月的结束时间
        SingleMonth::deleteAll();
        $time = Buy::Time('月');
        $start_time = $time['start'];
        $end_time = $time['end'];
        //**查询出在指定时间内产生的数据**//
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 5) {//单期榜 购买次数必须达到500次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id

                $sing = new SingleMonth();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 单期总榜(1000)
     * linux定时任务  每小时执行一次
     */
    public function actionSall()
    {
        SingleAll::deleteAll();
        //单期榜 购买次数必须达到1000次
        //**查询出在指定时间内产生的数据**//
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 10) {//单期榜 购买次数必须达到500次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new SingleAll();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 和买周榜
     */
    public function actionBweek()
    {
        BuyWeek::deleteAll();
        //和买周榜 购买次数必须达到500次
        //这周的开始时间 和这周的结束时间
        $time = Buy::Time('周');
        $start_time = $time['start'];
        $end_time = $time['end'];
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' AND type=2 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 1) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new BuyWeek();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 和买月榜
     */
    public function actionBmonth()
    {
        BuyMonth::deleteAll();
        //和买月榜 购买次数必须达到500次
        //这月的开始时间 和这月的结束时间
        $time = Buy::Time('月');
        $start_time = $time['start'];
        $end_time = $time['end'];
        //sql
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' AND type=2 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->andWhere(['type' => 2])->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 5) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new BuyMonth();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 和买总榜
     */
    public function actionBall()
    {
        BuyAll::deleteAll();
        //sql
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where type=2 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 10) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new BuyAll();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 追号周榜
     */
    public function actionNweek()
    {
        NoWeek::deleteAll();
        //和买周榜 购买次数必须达到500次
        //这周的开始时间 和这周的结束时间
        $time = Buy::Time('周');
        $start_time = $time['start'];
        $end_time = $time['end'];
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' AND type=1 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();

        foreach ($data as $datum) {
            if ($datum['count'] >= 1) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new NoWeek();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 追号月榜
     * linux定时任务  每小时执行一次
     */
    public function actionNmonth()
    {
        NoWeek::deleteAll();
        //和买周榜 购买次数必须达到500次
        //这周的开始时间 和这周的结束时间
        $time = Buy::Time('月');
        $start_time = $time['start'];
        $end_time = $time['end'];
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where created_time between '$start_time' and '$end_time' AND type=1 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 5) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id


                $sing = new NoMonth();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }

    /**
     * 追号总榜
     * linux定时任务  每小时执行一次
     */
    public function actionNall()
    {
        NoWeek::deleteAll();
        //和买周榜 购买次数必须达到500次
        //这周的开始时间 和这周的结束时间
        $time = Buy::Time('月');
        $sql = "select username,admin_id,count(username) count,sum(yk) yk,sum(price) jine from lottery where type=1 group by username";//计算出从当前时间的周一到周末的所有数据
        $data = Lottery::findBySql($sql)->asArray()->all();
        foreach ($data as $datum) {
            if ($datum['count'] >= 10) {//单期榜 购买次数必须达到100次
                if($datum['yk']==0 || $datum['jine']==null){
                    $yinglv = 0;
                }else{
                    $yinglv = round($datum['yk'] / $datum['jine'] * 100);//赢率
                }
                //$datum['count'] 条数  $datum['yk'] 盈亏 $datum['username'] 用户名 $datum['admin_id']用户id
                $sing = new NoAll();
                $sing->admin_id = $datum['admin_id'];
                $admin = Admin::findOne(['id'=>$datum['admin_id']]);
                $sing->username = $admin->username;
                $sing->rate = $yinglv;
                $sing->number = $datum['count'];
                $sing->win = $datum['yk'];
                $sing->save();
            }
        }
    }
}