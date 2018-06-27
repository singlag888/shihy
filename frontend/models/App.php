<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/23 0023
 * Time: 16:58
 */

namespace frontend\models;


use backend\models\Admin;
use yii\base\Model;

class App extends Model
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
}