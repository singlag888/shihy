<?php

namespace frontend\controllers;
use backend\models\Admin;
use backend\models\Recharge;
use backend\models\Withdrawals;
use frontend\models\ErverLottery;
use frontend\models\Lottery;
use backend\models\Reports;
use frontend\models\Mosaic;
use frontend\models\MyTable;
use frontend\models\TeamLottery;
use yii\web\Controller;
use yii\web\Response;
use frontend\models\PrizePool;
use frontend\models\Message;
header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/1 0001
 * Time: 10:37
 */

//用户中心报表定时任务查询
class Api6Controller extends Controller
{
    public $enableCsrfValidation = false;


    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }



    /**
     * 报表查询->彩票报表->个人
     */
    public function actionLotteryTable(){


        $data = \Yii::$app->request->post();
        $page = ($data['page'] - 1) * $data['page_number'];
        $start_time = str_replace('-', '', $data['start_time']);
        $end_time = str_replace('-', '', $data['end_time']);
        if(token($data['admin_id'],$data['token'])){
            $model = ErverLottery::find()->offset($page)->limit($page['page_number'])->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->all();
            foreach ($model as $item){
                $item->time =strtotime($item->time);
            }
            $count = ErverLottery::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->count();
            $yeshu = ceil($count/$data['page_number']);
            $result['msg']='操作成功';
            $result['error']=1;
            $result['data']['list']=$model;
            $result['data']['yeshu']=$yeshu;
            return $result;
        }else{
            return messages('操作失败,秘钥错误',-2);
        }

    }


    /**
     * 报表查询->彩票报表->团队
     */
    public function actionTeamLottery(){
        $data = \Yii::$app->request->post();
        $page = ($data['page'] - 1) * $data['page_number'];
        $start_time = str_replace('-', '', $data['start_time']);
        $end_time = str_replace('-', '', $data['end_time']);
        if(token($data['admin_id'],$data['token'])){
            $model = TeamLottery::find()->offset($page)->limit($page['page_number'])->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->all();
            foreach ($model as $item){
                $item->time =strtotime($item->time);
            }
            $count = TeamLottery::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->count();
            $yeshu = ceil($count/$data['page_number']);
            $result['msg']='操作成功';
            $result['error']=1;
            $result['data']['list']=$model;
            $result['data']['yeshu']=$yeshu;
            return $result;
        }else{
            return messages('操作失败,秘钥错误',-2);
        }
    }



    /**
     * 报表查询->个人报表
     */
    public function actionMyTable(){
        $data = \Yii::$app->request->post();
        $page = ($data['page'] - 1) * $data['page_number'];
        $start_time = str_replace('-', '', $data['start_time']);
        $end_time = str_replace('-', '', $data['end_time']);
        if(token($data['admin_id'],$data['token'])){
            $model = MyTable::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->offset($page)->limit($page['page_number'])->all();
            foreach ($model as $item){
                $item->time =strtotime($item->time);
            }
            $count = MyTable::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'time', $start_time])->andWhere(['<=', 'time', $end_time])->count();
            $yeshu = ceil($count/$data['page_number']);
            $result['msg']='操作成功';
            $result['error']=1;
            $result['data']['list']=$model;
            $result['data']['yeshu']=$yeshu;
            return $result;
        }else{
            return messages('操作失败,秘钥错误',-2);
        }
    }



    /**
     * 报表查询->盈亏报表
     */
    public function actionYk(){
        $data = \Yii::$app->request->post();
        $start_time = strtotime($data['start_time']);
        $end_time = strtotime($data['end_time']);
        if(token($data['admin_id'],$data['token'])){
            $admin = Admin::findOne(['id'=>$data['admin_id']]);
            $model = Lottery::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->select(['price'=>'SUM(price)','rebate'=>'SUM(rebate)','yk'=>'SUM(yk)'])->all();
            foreach ($model as $v){
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['username']=$admin->username;
                $result['data']['in_game']=0.000;
                $result['data']['out_game']=0.000;
                $result['data']['price']=$v['price'];
                $result['data']['peple']=0.000;
                $result['data']['rebate']=$v['rebate'];
                $result['data']['team']=0.000;
                $result['data']['pc']=$v['price'] - $v['yk'];
                $result['data']['activity']= 0.000;
                $result['data']['yk']=   $v['yk'];
            }
            return $result;
        }else{
            return messages('操作失败,秘钥错误',-2);
        }
    }





    /**
     * 团队管理统计
     */
    public function actionTeam(){
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            if ($data['admin_id']&&$data['token']){
                if (token($data['admin_id'],$data['token'])){
                    $admin_id = $data['admin_id'];
                    $t = time();
                    $start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));  //当天开始时间
                    $end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); //当天结束时间

                    //团队余额
                    $model = new Admin();
                    $peoples = $model->all($admin_id);
                    $price = 0;
                    foreach ($peoples as $v){
                        $adminss = Admin::findOne(['id'=>$v]);
                        $price += $adminss->price;

                    }

                    //团队人数
                    $people = count($peoples);

                    //今日注册
                    $register = Admin::find()->where(['parent_id'=>$data['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->count();

                    //今日充值
                    $model = Admin::find()->where(['parent_id'=>$data['admin_id']])->all();
                    $ss = 0;
                    foreach ($model as $m){
                        $recharge = Recharge::find()->where(['admin_id'=>$m['id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->all();
                        foreach ($recharge as $rr){
                            $ss += $rr->price;
                        }
                    }

                    //今日取款
                    $sss = 0;
                    foreach ($model as $m){
                        $recharge = Withdrawals::find()->where(['admin_id'=>$m['id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->all();
                        foreach ($recharge as $r){
                            $sss += $r->price;
                        }
                    }

                    //总充值
                    $shi = 0;
                    foreach ($model as $m){
                        $recharge = Recharge::find()->where(['admin_id'=>$m['id']])->all();
                        foreach ($recharge as $rr){
                            $shi += $rr->price;
                        }
                    }

                    //总取款
                    $shih = 0;
                    foreach ($model as $m){
                        $recharge = Withdrawals::find()->where(['admin_id'=>$m['id']])->all();
                        foreach ($recharge as $r){
                            $shih += $r->price;
                        }
                    }

                    //团队在线人数
                    $online_number = Admin::online($admin_id);


                    //投注量
                    $query = new Admin();
                    $touzhu = $query->all($admin_id);
//                    var_dump($touzhu);die;
                    $yy = 0;
                    $uu = 0;
                    $ii = 0;
                    $all = 0;
                    foreach ($touzhu as $v){
                        $ly = Lottery::find()->where(['admin_id'=>$v])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->select(['price'=>'SUM(price)','rebate'=>'SUM(rebate)','yk'=>'SUM(yk)','win_price'=>'SUM(win_price)'])->all();
                        foreach ($ly as $y){
                            $yy +=$y->price;
                            $uu +=$y->rebate;
                            $ii +=$y->yk;
                            $all +=$y->win_price;
                        }
                    }

                    $result['msg']='操作成功';
                    $result['error']=1;
                    $result['data']['number']=$people;//团队人数
                    $result['data']['online_number']=$online_number;//在线人数
                    $result['data']['team_price']=$price;//团队余额

                    $result['data']['team_water']='0.000';//团队转水
                    $result['data']['activity_price']='0.000';//活动派发
                    $result['data']['win_price']=$all;//中奖金额
                    $result['data']['give_price']=$all;//派发金额
                    $result['data']['recharges']=$shi;//充值金额
                    $result['data']['withdrawalss']=$shih;//取款金额

                    $result['data']['today_register']=$register;//今日注册
                    $result['data']['recharge']=$ss;//今日充值
                    $result['data']['withdrawals']=$sss;//今日取款
                    $result['data']['betting']=$yy;//投注量
                    $result['data']['rebate']=$uu;//游戏返点
                    $result['data']['yk']=$ii;//实际阴盈亏
                    $result['data']['in']='0.000';//转入
                    $result['data']['out']='0.000';//转出
                    $result['data']['activity']='0.000';//活动
                    return $result;
                }else{
                    return messages('秘钥错误',-2);
                }
            }
        }
    }


    /**
     * 平台报表
     */
    public function actionPlatform(){
        $data = \Yii::$app->request->post();
        if(token($data['admin_id'],$data['token'])){
            //用户账号
            $admin = Admin::findOne(['id'=>$data['admin_id']]);


            //入款金额
            $recharge = Recharge::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->select(['price'=>'SUM(price)'])->all();
            foreach ($recharge as $re){

            }

            //入款笔数
            $recharge_count = Recharge::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->select(['price'=>'SUM(price)'])->count();

            //出款金额
            $out_price = Withdrawals::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->select(['price'=>'SUM(price)'])->all();
            foreach ($out_price as $ou){

            }

            //出款笔数
            $out_num = Withdrawals::find()->where(['admin_id'=>$data['admin_id']])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->select(['price'=>'SUM(price)'])->count();

            //活动金额
            $activity = '0.000';

            //活动次数
            $activity_num = 0;

            $result['msg'] = '操作成功';
            $result['error'] = 1;
            $result['list']['username'] = $admin->username;
            $result['list']['recharge'] = $re->price;
            $result['list']['recharge_count'] = $recharge_count;
            $result['list']['out_price'] = $ou->price;
            $result['list']['out_num'] = $out_num;
            $result['list']['activity'] = $activity;
            $result['list']['activity_num'] = $activity_num;
            return $result;
        }else{
            return messages('密钥错误',-1);
        }

    }



    /**
     * 递归查询
     */
    public function actionRecursive(){
        $model = new Admin();
        $shy = $model->all('1');
        return $shy;
    }


    /**
     * 退出登陆
     */
    public function actionLogout(){
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            if ($data['admin_id']){
                $admin = Admin::updateAll(['online'=>0],['id'=>$data['admin_id']]);
                \Yii::$app->user->logout();
                return messages('操作成功',1);
            }
            return messages('操作失败');
        }
    }


    /**
     * 我的消息
     */
    public function actionMessage(){
        $data  = \Yii::$app->request->post();
        $page = ($data['page'] - 1) * $data['page_number'];
        $admin = Admin::findOne(['id'=>$data['admin_id']]);
        if(token($data['admin_id'],$data['token'])){
            if($data['start_time'] || $data['end_time']){
                $model = Message::find()->where(['recipient'=>$admin->username])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->offset($page)->limit($data['page_number'])->all();
                $count = Message::find()->where(['recipient'=>$admin->username])->andWhere(['>=','created_time',strtotime($data['start_time'])])->andWhere(['<=','created_time',strtotime($data['end_time'])])->count();
            }else{
                $model = Message::find()->where(['recipient'=>$admin->username])->offset($page)->limit($data['page_number'])->all();
                $count = Message::find()->where(['recipient'=>$admin->username])->count();
            }
            if($model ==null || $model==''){
                return messages("操作失败,没有数据",1);
            }
            $yeshu = ceil( $count/$data['page_number']);
            $result['msg']='操作成功';
            $result['error']=1;
            $result['data']['list']=$model;
            $result['data']['yeshu']=$yeshu;
            return $result;
        }else{
            return messages("密钥错误");
        }

    }

    /**
     * 发送消息
     */
    public function actionSendMessage(){
        $data = \Yii::$app->request->post();
        $model = new Message();
        if(token($data['admin_id'],$data['token'])){
            //发送人
            $admin = Admin::findOne(['id'=>$data['admin_id']]);//发送人
            $admins = Admin::findOne(['username'=>$data['username']]);//接收人

            if($data['status'] == 1){ //直属下级
                $model->username = $admin->username;//发送人
                $model->recipient = $data['username'];//接受人
                if($admins){
                    $model->created_time = time();//发送时间
                    $model->updated_time = 0;//发送时间
                    $model->status = 0;//发送时间
                    $model->title = $data['title'];//标题
                    $model->content = $data['content'];//内容
                    $model->save();
                    return messages('操作成功',1);
                }else{
                    return messages('没有此用户',-1);
                }

            }elseif ($data['status'] == 2){ //直属上级
                $message = Admin::findOne(['id'=>$admin->parent_id]);
                if($message){
                    $model->username = $admin->username;//发送人
                    $model->recipient = $message->username;//接受人
                    $model->created_time = time();//发送时间
                    $model->status = 0;//发送时间
                    $model->updated_time = 0;//发送时间
                    $model->title = $data['title'];//标题
                    $model->content = $data['content'];//内容
                    $model->save();
                    return messages('操作成功',1);
                }else{
                    return messages('没有上级',-1);
                }

            }else{
                //得到所有下级
                $yii = new Admin();
                $shy = $yii->all($data['admin_id']);
                foreach ($shy as $v){
                    $model = new Message();
                    $admins = Admin::findOne(['id'=>$v]);
                    $model->username = $admin->username;//发送人
                    $model->recipient = $admins->username;//接受人
                    $model->created_time = time();//发送时间
                    $model->updated_time = 0;//发送时间
                    $model->status = 0;//发送时间
                    $model->title = $data['title'];//标题
                    $model->content = $data['content'];//内容
                    $model->save();
                    return messages('操作成功',1);
                }
            }
        }else{
            return messages("密钥错误");
        }

    }




    public function actionStart(){
        $data = [
            [
                'money'=>'130000000','color'=>'腾讯分分彩','status'=>1
            ],
            [
                'money'=>'130000000','color'=>'官方分分彩','status'=>1
            ],
            [
                'money'=>'130000000','color'=>'官方快三','status'=>1
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


    public function actionShy(){
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
    }

    //验证是否登陆是否有效
    public function actionValidate()
    {
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            if ($data['token']&&$data['admin_id']){
                if (token($data['admin_id'],$data['token'])){
                    return messages('通过验证',1);
                }else{
                    return messages('您的账号已在别处登录!',-2);
                }
            }
        }
    }

}