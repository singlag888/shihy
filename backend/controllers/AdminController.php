<?php
/**
 * Created by PhpStorm.
 * Admini: Administrator
 * Date: 2018/1/8
 * Time: 15:15
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\Bank;
use backend\models\Lottery;
use backend\models\Recharge;
use backend\models\Reports;
use backend\models\Vip;
use backend\models\Withdrawals;
use frontend\models\Mosaic;
use frontend\controllers\GameController as Game;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AdminController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * 显示功能
     * @return string
     */
    //列表
    public function actionIndex()
    {
        $letter = Admin::find()->where(['!=','type',3])->andWhere(['!=','type',2])->andWhere(['!=','status',-1])->all();//查询出所有的站内信
        return $this->render('index',['letter'=>$letter]);
    }


    /**
     * 用户充值
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionRecharge($id){
        $model = Admin::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->price = $model->price + $model->last;
                $model->last = '';
                $model->save();
                \Yii::$app->session->setFlash('success','充值成功');
                return $this->redirect(['index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('price',['model'=>$model]);
    }



    /**
     * 添加
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
//        var_dump(1);die;
        $model = new Admin();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $uu = Admin::findOne(['username'=>$model->username]);
                if($uu){
                    \Yii::$app->session->setFlash('success','用户名已存在');
                    return $this->redirect('index');
                }
                $model->password_login = \Yii::$app->security->generatePasswordHash($model->password_login);
                $model->password_pay= \Yii::$app->security->generatePasswordHash($model->password_pay);
                $model->token = \Yii::$app->security->generateRandomString();
                $model->created_time = time();
                $reg =  new Admin();
                $reg->Register($model->username,$model->password_login);
                $model->save();
                $reports = new Reports();
                $reports->admin_id = $model->id;
                $reports->username = $model->username;
                $reports->save();
                $shy = new Mosaic();
                $shy->admin_id = $model->id;
                $shy->number = 5;
                $shy->save();
                return $this->redirect(['index']);
            }else{
                var_dump($model->getErrors());
            }
        }
//        var_dump(1);die;
        return $this->render('create', [
            'model' => $model,
        ]);
    }




    //修改类型
    public function actionUpdate($id)
    {
        $model= Admin::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->password_login = \Yii::$app->security->generatePasswordHash($model->password_login);
                $model->password_pay= \Yii::$app->security->generatePasswordHash($model->password_pay);
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $model->addErrors($model->getErrors());
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }






    /**
     * 删除
     * @param $id
     */
    public function actionDelete($id)
    {
        Admin::deleteAll(['id'=>$id]);
    }


    public function actionJs(){
        $result = file_get_contents('http://77tj.org/api/tencent/onlineim');
        var_dump($result);
    }



    /**
     * 代理列表
     */
    public function actionLink(){
        $model = Admin::find()->where(['!=','type',3])->all();
        return $this->render('link',['model'=>$model]);
    }


    /**
     * 账号黑名单
     * @param $id
     * @return array|string
     */
    public function actionBlack(){
        $model = Admin::find()->where(['status'=>-1])->all();
        return $this->render('black',['model'=>$model]);
    }


    /**
     * 黑名单启用/禁用
     */
    public function actionShy($id,$status){
        if($status == '启用'){
            Admin::updateAll(['status'=>-1],['id'=>$id]);
            return -1;
        }elseif($status == '禁用'){
            Admin::updateAll(['status'=>1],['id'=>$id]);
            return 1;
        }
    }


    /**
     * 点击查看余额
     * @param $id
     * @return array|string
     */
    public function actionPrice($id){
        $recharge = Recharge::find()->where(['admin_id'=>$id])->sum('price');
        $withdrawals = Withdrawals::find()->where(['admin_id'=>$id])->sum('price');
        $data = [
            'recharge'=>$recharge==null?'0.000':$recharge,
            'withdrawals'=>$withdrawals==null?'0.000':$withdrawals,
            'in'=>'0.000',
            'out'=>'0.000',

        ];
        $data = json_encode($data);
        return $data;
    }

    /**
     * 点击查看下级信息
     * @param $id
     * @return array|string
     */
    public function actionDown($id){
        $admin = new Admin();
        $result = $admin->all($id);
        $count = count($result);
        $shy = 0;
        foreach ($result as $v){
            $shy += Admin::find()->where(['id'=>$v])->sum('price');
        }
        $data = [
            'recharge'=>$shy==null?'0.000':$shy,
            'count'=>$count==null?'0':$count,
        ];
        $data = json_encode($data);
        return $data;
    }


    /**
     * 设置
     * @param $id
     * @return string
     */
    public function actionEdit($id){
        $bank = Bank::find()->where(['admin_id'=>$id])->all();
        $in_money_number = Recharge::find()->where(['admin_id'=>$id])->count();
        $in_money_price = Recharge::find()->where(['admin_id'=>$id])->sum('price');
        $out_money_number = Withdrawals::find()->where(['admin_id'=>$id])->count();
        $out_money_price = Withdrawals::find()->where(['admin_id'=>$id])->sum('price');
        $dama = 0;
        $dama1 = 0;
        $lottery = Lottery::find()->where(['admin_id'=>$id])->count();
        $lottery_price = Lottery::find()->where(['admin_id'=>$id])->sum('price');
        $out = '0.000';
        $team_water = '0.000';
        $win = Lottery::find()->where(['admin_id'=>$id])->andWhere(['>','yk',0])->sum('yk');
        $activity_price = '0.000';
        $all = Lottery::find()->where(['admin_id'=>$id])->sum('yk');

        $data =[
            'in_money_number'=>  $in_money_number,
            'in_money_price'=>  $in_money_price,
            'out_money_number'=>  $out_money_number,
            'out_money_price'=>  $out_money_price,
            'dama'=>  $dama,
            'dama1'=>  $dama1,
            'lottery'=>  $lottery,
            'lottery_price'=>  $lottery_price,
            'out'=>  $out,
            'team_water'=>  $team_water,
            'win'=>  $win,
            'activity_price'=>  $activity_price,
            'all'=>  $all,
        ];
        return $this->render('edit',['data'=>$data,'bank'=>$bank]);
    }


    /**
     * 审视下级
     */
    public function actionPelo($id){
            $letter = Admin::find()->where(['parent_id'=>$id])->all();
            return $this->render('index',['letter'=>$letter]);
    }
}