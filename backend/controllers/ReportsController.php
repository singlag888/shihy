<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 15:10
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\Lottery;
use backend\models\Recharge;
use backend\models\Reports;
use backend\models\Withdrawals;
use DeepCopy\f001\A;

class ReportsController extends CommonController
{

    //平台报表
    public function actionPt(){
//       $sql = "select count(*) count,sum(price) AVG(price)  from Recharge where created_time between '$start_time' and '$end_time'";//计算出从当前时间的周一到周末的所有数据
//        var_dump($sql);
        $strat_time = \Yii::$app->request->post('start_time');
        $end_time = \Yii::$app->request->post('end_time');
        $admin = Admin::find()->where(['type'=>1])->andWhere(['parent_id'=>10])->andWhere(['!=','type',2])->andWhere(['!=','type',3])->all();
        foreach ($admin as $value){
            $admins = new Admin();
            $shys[$value->id] = $admins->all($value->id);
        }
        $in=[];
       
        foreach ($shys as $key=>$ss){
           $in_price = 0;
        $in_count = 0;
        $out_price = 0;
        $out_count = 0;
            foreach ($ss as $s){
                $admin = Admin::findOne(['id'=>$key]);
                $in_price += Recharge::find()->where(['admin_id'=>$s])->sum('price');
                $in_count += Recharge::find()->where(['admin_id'=>$s])->count();


                $in[$key]['in_price'] = $in_price;
                $in[$key]['in_count'] = $in_count;
                if($in_count == 0){
                    $in[$key]['in_aug'] = 0;
                }else{
                    $in[$key]['in_aug'] = $in_price / $in_count;
                }
                $out_price += Withdrawals::find()->where(['admin_id'=>$s])->sum('price');
                $out_count += Withdrawals::find()->where(['admin_id'=>$s])->count();
                $in[$key]['out_price'] = $out_price;
                $in[$key]['out_count'] = $out_count;
                if($out_count == 0){
                    $in[$key]['out_aug'] = 0;
                }else{
                    $in[$key]['out_aug'] = $out_price / $out_count;
                }
                $in[$key]['my_price'] = '0.000';
                $in[$key]['my_count'] = 0;
                $in[$key]['my_aug'] = '0.000';
                $in[$key]['username'] = $admin->username;
            }
        }

        return $this->render('pt',['in'=>$in]);
    }

    //游戏报表
    public function actionIndex(){
        $strat_time = \Yii::$app->request->post('start_time');
        $end_time = \Yii::$app->request->post('end_time');
        $admin = Admin::find()->where(['type'=>1])->andWhere(['parent_id'=>10])->andWhere(['!=','type',2])->andWhere(['!=','type',3])->all();
        foreach ($admin as $value){
            $admins = new Admin();
            $shys[$value->id] = $admins->all($value->id);
        }
        $in=[];
   
        foreach ($shys as $key=>$ss){
               $buy_price = 0;
        $buy_count = 0;
        $buy_rebate = 0;
        $buy_yk = 0;
            foreach ($ss as $s){
                $admin = Admin::findOne(['id'=>$key]);
                $in[$key]['username'] = $admin->username;
                $in[$key]['vip'] = count($ss);
                $buy_price += Lottery::find()->where(['admin_id'=>$s])->sum('price');
                $buy_rebate += Lottery::find()->where(['admin_id'=>$s])->sum('rebate');
                $buy_yk += Lottery::find()->where(['admin_id'=>$s])->sum('yk');
                $buy_count += Lottery::find()->where(['admin_id'=>$s])->count();
                $in[$key]['buy_price'] = $buy_price;
                $in[$key]['buy_count'] = $buy_count;
                $in[$key]['buy_rebate'] = $buy_rebate;
                $in[$key]['buy_yk'] = $buy_yk;
                if($buy_count == 0){
                    $in[$key]['buy_aug'] = 0;
                }else{
                    $in[$key]['buy_aug'] = $buy_price / $buy_count;
                }


            }
        }
        return $this->render('game',['in'=>$in]);
    }

    //个人报表
    public function actionMy(){
        $model = Reports::find()->all();
        return $this->render('index',['model'=>$model]);
    }


    //每日趋势
    public function actionDay(){
        return $this->render('day');
    }
}