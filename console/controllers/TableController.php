<?php

namespace console\controllers;

use backend\models\Admin;
use backend\models\Recharge;
use backend\models\Withdrawals;
use frontend\controllers\GameController;
use frontend\models\Aglog;
use frontend\models\ErverLottery;
use frontend\models\Imsblog;
use frontend\models\Lottery;
use frontend\models\Mosaic;
use frontend\models\MyTable;
use frontend\models\TeamLottery;
use frontend\models\PrizePool;
use yii\web\Controller;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/1 0001
 * Time: 10:37
 */

/**
 * 报表查询
 * Class TableController
 * @package console\controllers
 */
class TableController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 每天开始根据用户ID保存一条数据
     */
    public function actionStart(){
        $data = [
            [
                'money'=>'130000000','color'=>116,'status'=>1
            ],
            [
                'money'=>'130000000','color'=>112,'status'=>1
            ],
       
            [
                'money'=>'130000000','color'=>117,'status'=>1
            ],
        ];
        foreach ($data as $datum){
            $prizepool = new PrizePool();
            $prizepool->money = $datum['money'];
            $prizepool->color = $datum['color'];
            $prizepool->add_time = date("Ymd",time());
            $prizepool->status = $datum['status'];
            $prizepool->save();
        }
         Mosaic::deleteAll();
        $admin = Admin::find()->where(['!=','type',3])->andWhere(['!=','type',2])->all();
        foreach ($admin as $a){
          	$models = new Mosaic();
            $models->admin_id = $a['id'];
            $models->number = 5;
            $models->status = 1;
            $models->save();
        }
        $admin = Admin::find()->where(['!=','type',3])->all();
        foreach ($admin as $v){
            $time = date("Ymd");
                $erverlottery = new ErverLottery();
                $erverlottery->admin_id = $v['id'];
                $erverlottery->username = $v->username;
                $erverlottery->time = $time;//日期
                $erverlottery->all = 0.000;//总下注
                $erverlottery->number = 0;//笔数
                $erverlottery->rebate = 0.000;//返点
                $erverlottery->team = 0.000;//团队转水
                $erverlottery->color = 0.000;//派彩
                $erverlottery->activity = 0.000;//活动
                $erverlottery->yk = 0.000;//盈亏
                $erverlottery->save();

                $teamlottery = new TeamLottery();
                $teamlottery->admin_id = $v['id'];
                $teamlottery->username = $v->username;
                $teamlottery->time = $time;//日期
                $teamlottery->all = 0.000;//总下注
                $teamlottery->number = 0;//笔数
                $teamlottery->rebate = 0.000;//返点
                $teamlottery->team = 0.000;//团队转水
                $teamlottery->color = 0.000;//派彩
                $teamlottery->activity = 0.000;//活动
                $teamlottery->yk = 0.000;//盈亏
                $teamlottery->save();

                $my =new  MyTable();
                $my->time = $time;//日期
                $my->admin_id = $v['id'];
                $my->recharge = 0.000;
                $my->withdrawals = 0.000;
                $my->in_game = 0.000;
                $my->out_game = 0.000;
                $my->all = 0.000;//总下注
                $my->number = 0;//笔数
                $my->water = 0.000;//流水
                $my->rebate = 0.000;//返点
                $my->team = 0.000;//团队转水
                $my->color = 0.000;//派彩
                $my->activity = 0.000;//活动
                $my->yk = 0.000;//盈亏
                $my->save();

        }
    }


    /**
     * 统计用户彩票与个人报表
     */
    public function actionLottery(){
        $model = Lottery::find()->all();
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $time = date("Ymd");//日期
        //根据当前时间一天开始与结束 查询出每个用户所对应的条数
        foreach ($model as $m){
            //计算总下注条数
            $resultss[$m['admin_id']]['count']  = Lottery::find()->where(['admin_id'=>$m['admin_id']])->andWhere(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->count();
            //计算金额
            $ss = Lottery::find()->where(['admin_id'=>$m['admin_id']])->andWhere(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->select(['price'=>'SUM(price)','yk'=>'SUM(yk)','rebate'=>'SUM(rebate)'])->all();
            //计算充值
            $shy = Recharge::find()->where(['admin_id'=>$m['admin_id']])->andWhere(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->select(['price'=>'SUM(price)'])->all();
            //计算转出(体现)
            $shy1 = Withdrawals::find()->where(['admin_id'=>$m['admin_id']])->andWhere(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->select(['price'=>'SUM(price)'])->all();
            foreach ($ss as $s){
                $a = $s['price'];
                $b = $s['yk'];
                $c = $s['rebate'];
            }
            foreach ($shy as $ss){
                $d = $ss['price'];
            }
            foreach ($shy1 as $sss){
                $e = $sss['price'];
            }
            $resultss[$m['admin_id']]['price'] = $a??0.000;
            $resultss[$m['admin_id']]['yk'] = $b??0.000;
            $resultss[$m['admin_id']]['rebate'] = $c??0.000;
            $resultss[$m['admin_id']]['recharge'] = $d??0.000;
            $resultss[$m['admin_id']]['withdrawals'] = $e??0.000;
        }
//            return $resultss;
        //拿出ID所对应的条数来修改
        foreach ($resultss as $key=>$t){
        ErverLottery::updateAll(['number'=>$t['count'],'all'=>$t['price'],'yk'=>$t['yk'],'rebate'=>$t['rebate']],['admin_id'=>$key,'time'=>$time]);
        MyTable::updateAll(['number'=>$t['count'],'all'=>$t['price'],'yk'=>$t['yk'],'rebate'=>$t['rebate'],'recharge'=>$t['recharge'],'withdrawals'=>$t['withdrawals']],['admin_id'=>$key,'time'=>$time]);
    }

    }


    /**
     * 计算团队彩票统计
     */
    public function actionTeam(){
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $resultss = Lottery::find()->where(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->all();
        foreach ($resultss as $r){
            $admin = new Admin();
            $result[$r['admin_id']] = $admin->all($r['admin_id']);
        }
        foreach ($result as $key=>$e){
            $ss[$key]= Lottery::find()->where(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->andWhere(['in','admin_id',$e])->count();
            $sss[$key]= Lottery::find()->where(['admin_id'=>$e])->andWhere(['>=','created_time',$beginToday])->andWhere(['<=','created_time',$endToday])->select(['price'=>'SUM(price)','yk'=>'SUM(yk)','rebate'=>'SUM(rebate)'])->all();
        }
        foreach ($sss as $key=>$q){
            foreach ($q as $w){
                $a = $w['price'];
                $b = $w['yk'];
                $c = $w['rebate'];
                TeamLottery::updateAll(['all'=>$a??0.00,'yk'=>$b??0.00,'rebate'=>$c??0.00,'team'=>0,'color'=>0,'activity'=>0],['admin_id'=>$key]);
            }
        }
        foreach ($ss as $ke=>$s){
            TeamLottery::updateAll(['number'=>$s],['admin_id'=>$ke]);
        }
    }

    /**
     * 第三方详细游戏日志
     * CreateBy:melo.tao
     */
    public function actionGetBetLog()
    {
        $page =1;
        $EndDate = date('Y-m-d H.i.s',time()-15*60);
        $StartDate =date('Y-m-d H.i.s',time()-15*60-10*60);
        $pageSize = 5000;
        $data_ag = array(
            'MerchantCode' =>GameController::CODE,
            "StartDate" => $StartDate,
            "EndDate" => $EndDate,
            "Page" => $page,
            "PageSize" => $pageSize,
            "ProductWallet" => 201,
            "Currency" => "CNY"
        );
        $data_im = array(
            'MerchantCode' =>GameController::CODE,
            "StartDate" => $StartDate,
            "EndDate" => $EndDate,
            "Page" => $page,
            "DateFilterType" => 1,
            "BetStatus" => 0,
            "Language" => 'EN',
            "PageSize" => $pageSize,
            "ProductWallet" => 301,
        );
        $url = "http://imone.imaegisapi.com/Report/GetBetLog";
        $data_im = curl($url, $data_im);
        $data_ag = curl($url, $data_ag);
        //IMSB
        if($data_im['Result']){
            $model = new Imsblog();
            foreach ($data_im['Result'] as $item){
                $model->setAttributes($item,false);
                $model->save(false);
            }
        }
        //AG
        if($data_ag['Result']){
            $model = new Aglog();
            foreach ($data_ag['Result'] as $item){
                $model->setAttributes($item,false);
                $model->save(false);
            }
        }
    }

}