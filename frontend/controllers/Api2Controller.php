<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10 0010
 * Time: 14:24
 */

namespace frontend\controllers;

use backend\models\Activityh;
use backend\models\Bank;
use backend\models\Admin;
use backend\models\Recharge;
use backend\models\Reports;
use backend\models\Transfer;
use backend\models\Withdrawals;
use frontend\models\Change;
use frontend\models\Link;
//mosaic打码
use frontend\models\Mosaic;
use frontend\models\Publics;
use frontend\models\Quota;
use yii\web\Controller;
use yii\web\Response;
header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");
class Api2Controller extends Controller
{

    //解决网页报400错误
    public $enableCsrfValidation  = false;
    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 提款
     */
    public function actionGet(){
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $model = new Withdrawals();
        $reports = Reports::findOne(['admin_id'=>$query['admin_id']]);
        $w = Withdrawals::find()->orderBy('orders desc')->one();
        $shy = Mosaic::findOne(['admin_id'=>$query['admin_id']]);
        if($admin->type ==2){
            return messages('操作失败,试玩账号不能提款',-1);
        }else{
            if(token($query['admin_id'],$query['token'])){
                if(!\Yii::$app->security->validatePassword($query['password_pay'],$admin->password_pay)){
                    return messages('操作失败,支付密码错误',-1);
                }else{
                    if($query['price'] > $admin->price){
                        return messages('操作失败,余额不足',-1);
                    }
                    if($shy->number ==0){
                        return messages('操作失败,操作提款次数',-1);
                    }
                    $shy->number = $shy->number -  1;
                    if($shy->number -1 ==0){
                        Mosaic::updateAll(['status'=>-1],['admin_id'=>$query['admin_id']]);
                    }
                    $model->created_time = time();
                    //手续费计算
                    $model->fees = $shy->fees;
                    $model->front_price = $admin->price;
                    $model->after_price = $admin->price - $query['price'];
                    $model->price = $query['price'];
                    $model->orders =sprintf('%01s',$w->orders+1);
                    $model->bank_id = $query['bank_id'];
                    $model->username = $admin->username;
                    $model->admin_id = $query['admin_id'];
                    $model->status = 0;
                    $reports->out_price += $query['price'];
                    $reports->out_price_num += 1;
                    $change = new Change();
                    $arr['admin_id'] = $query['admin_id'];
                    $arr['username']=$admin->username;
                    $arr['type'] = '用户提款';
                    $arr['last_price'] = '-'.$query['price'];
                    $arr['price'] = $admin->price-$query['price'];
                    $arr['front_price'] = $admin->price;
                    $arr['game_type'] = '提款';
                    $arr['intro'] = $admin->username.'提款';
                    $change->Change($arr);
                    $reports->save();
                    $model->save();
                    $shy->save();
                    return messages('操作成功',1);
                }

            }else{
                return messages('操作失败,秘钥错误',-2);
            }


        }

    }


    /**
     * 用户点击输入账号查询下级余额
     */
    public function actionPrice(){

        //用户ID
        $query = \Yii::$app->request->post();
        if(token($query['admin_id'],$query['token'])){
            $admini = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['username'=>$query['username']])->one();
            if ($admini){
                return messages('操作成功',1,$admini->price);
            }else{
                return messages('操作失败,不是您下级或用户名错误',-1);
            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }


    }


    /**
     * 转账功能
     */
    public function actionTransfer(){

        $query = \Yii::$app->request->post();
        $w = Transfer::find()->orderBy('orders desc')->one();
        $admin = Admin::findOne(['id'=>$query['admin_id']]);
        $model = new Transfer();
        if($admin->type ==2){
            return messages('操作失败,试玩账号不能提款');
        }else{
            if(token($query['admin_id'],$query['token'])){
                if($query['price'] > $admin->price){
                    return messages('操作失败,余额不足');
                }else{
                    if(\Yii::$app->security->validatePassword($query['password_pay'],$admin->password_pay)){
                        $shy = Admin::findOne(['username'=>$query['username']]);
                        $shy->price = $shy->price + $query['price'];
                        $admin->price = $admin->price - $query['price'];
                        $model->created_time = time();
                        //手续费计算
                        $model->front_price = $admin->price;
                        $model->after_price = $admin->price - $query['price'];
                        $model->price = $query['price'];
                        $model->orders =sprintf('%01s',$w->orders+1);
                        $model->username = $admin->username;
                        $model->admin_id = $query['admin_id'];
                        $model->status = 0;
                        $change = new Change();
                        $arr['admin_id'] = $query['admin_id'];
                        $arr['username']=$admin->username;
                        $arr['type'] = '用户转账';
                        $arr['last_price'] = '-'.$query['price'];
                        $arr['price'] = $admin->price-$query['price'];
                        $arr['front_price'] = $admin->price;
                        $arr['game_type'] = '转账';
                        $arr['intro'] = $admin->username.'转账';
                        $change->Change($arr);
                        $model->save();
                        $admin->save();
                        $shy->save();
                        return messages('操作成功',1);
                    }else{
                        return messages('操作失败,资金密码错误');
                    }

                }

            }else{
                return messages('操作失败,秘钥错误',-2);
            }



        }

    }





    /**
     * 充值记录
     */
    public function actionRechargeList(){
        $query = \Yii::$app->request->post();
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $status = $query['status'];
        $status = (int)$status;
        $page = ($query['page'] - 1) * $query['page_number'];

        if(token($query['admin_id'],$query['token'])){
            if ($start_time && $end_time && $query['lottery']==0) {
                $model = Recharge::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                if ($model){
                    $count = Recharge::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->count();
                    $yeshu = ceil($count/$query['page_number']);
                }
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
            if ($start_time && $end_time && $query['lottery']==1){
                $model = Recharge::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                if($model){
                    $count = Recharge::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->count();
                    $yeshu = ceil($count/$query['page_number']);
                }
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }
    }


    /**
     * 提款记录
     */
    public function actionGetList(){

        $query = \Yii::$app->request->post();
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $status = $query['status'];
        $status = (int)$status;
        $page = ($query['page'] - 1) * $query['page_number'];
        if(token($query['admin_id'],$query['token'])){
            if ($start_time && $end_time && $query['lottery']==0) {
                $model = Withdrawals::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                foreach ($model as $v){
                    $bank = Bank::findOne(['id'=>$v['bank_id']]);
                    $v->bank_id = $bank->bank_name;
                }
                $count = Withdrawals::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->count();
                $yeshu = ceil($count/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
            if ($start_time && $end_time && $query['lottery']==1){
                $model = Withdrawals::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                foreach ($model as $v){
                    $bank = Bank::findOne(['id'=>$v['bank_id']]);
                    $v->bank_id = $bank->bank_name;
                }
                $count = Withdrawals::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->count();
                $yeshu = ceil($count/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }




    }


    /**
     * 转账记录
     */
    public function actionTransferList(){
        $query = \Yii::$app->request->post();
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $status = $query['status'];
        $status = (int)$status;
        $page = ($query['page'] - 1) * $query['page_number'];
        if(token($query['admin_id'],$query['token'])){
            if ($start_time && $end_time && $query['lottery']==0) {
                $model = Transfer::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time+86400])->andWhere(['<=', 'created_time', $end_time])->orderBy('id desc')->offset($page)->limit( $query['page_number'])->all();
                $count = Transfer::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time+86400])->andWhere(['<=', 'created_time', $end_time])->count();
                $yeshu = ceil($count/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
            if ($start_time && $end_time && $query['lottery']==1){
                $model = Transfer::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time+86400])->andWhere(['<=', 'created_time', $end_time])->andWhere(['status' => $status])->orderBy('id desc')->offset($page)->limit( $query['page_number'])->all();
                $count =Transfer::find()->where(['admin_id' => $query['admin_id']])->andWhere(['>=', 'created_time', $start_time+86400])->andWhere(['<=', 'created_time', $end_time])->andWhere(['status' => $status])->count();
                $yeshu = ceil($count/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }



    }


    /**
     * 下级管理
     */
    public function actionSubordinate(){
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $type = $query['type'];
        $online = $query['online'];
        if(token($query['admin_id'],$query['token'])){
            //根据我的ID查询admin表中parent_id 等于我的ID的数据
            if($start_time && $end_time && $query['lottery']==0){
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->offset($page)->limit($query['page_number'])->all();
                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->offset($page)->limit($query['page_number'])->count();

            }elseif ($start_time && $end_time && $query['lottery']==1){//用户类型
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->andWhere(['type'=>$type])->offset($page)->limit($query['page_number'])->all();

                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->andWhere(['type'=>$type])->offset($page)->limit($query['page_number'])->count();
            }elseif ($start_time && $end_time && $query['lottery']==2){//是否在线
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->andWhere(['type'=>$type])->andWhere(['online'=>$online])->offset($page)->limit($query['page_number'])->all();

                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['>=', 'created_time', $start_time+60*60*24])->andWhere(['<=', 'created_time', $end_time])->andWhere(['type'=>$type])->andWhere(['online'=>$online])->offset($page)->limit($query['page_number'])->count();
            }elseif ($start_time && $end_time && $query['lottery']==3){ //只传类型
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['type'=>$type])->offset($page)->limit($query['page_number'])->all();

                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['type'=>$type])->offset($page)->limit($query['page_number'])->count();
            }elseif ($start_time && $end_time && $query['lottery']==4){ //只传是否在线
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['online'=>$online])->offset($page)->limit($query['page_number'])->all();

                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->andWhere(['online'=>$online])->offset($page)->limit($query['page_number'])->count();
            }else{
                $model = Admin::find()->where(['parent_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->all();

                $count = Admin::find()->where(['parent_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->count();
            }

            if($model){
                $yeshu = ceil($count/$query['page_number']);

                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$model;
                $result['data']['yeshu']=$yeshu;
                return $result;
            }else{
                return messages('操作失败,没有下级',1);
            }

        }else{
            return messages('操作失败,秘钥错误',-2);
        }


    }


    /**
     * 调点
     */
    public function actionPoint(){

    }


    /**
     * 调点记录
     */
    public function actionPointList(){

    }


    /**
     * 下级充值记录
     */
    public function actionLowerRecharge(){
        $query = \Yii::$app->request->post();
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $parent = Admin::find()->where(['parent_id'=>$query['admin_id']])->all();
        $status = $query['status'];
        $status = (int)$status;
        $page = ($query['page'] - 1) * $query['page_number'];
        if(token($query['admin_id'],$query['token'])){
            if ($start_time && $end_time && $query['lottery']==0) {
                $model=[];
                $cou = 0;
                foreach ($parent as $v){
                    $shuju = Recharge::find()->where(['admin_id' =>$v->id])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                    if ($shuju){
                        $count = Recharge::find()->where(['admin_id' =>$v->id])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->orderBy('id desc')->count();
                        $cou+=$count;
                        array_push($model,$shuju);
                    }
                }
                $aa = [];
                foreach ($model as $value){
                    foreach ($value as $item){
                        array_push($aa,$item);
                    }
                }
                $yeshu = ceil( $cou/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$aa;
                $result['data']['yeshu']=$yeshu;
                return $result;

            }
            if ($start_time && $end_time && $query['lottery']==1){
                $model=[];
                $cou = 0;
                foreach ($parent as $k){
                    $shuju= Recharge::find()->where(['admin_id' => $k->id])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                    if ($shuju){
                        $count = Recharge::find()->where(['admin_id' => $k->id])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->count();
                        $cou+=$count;
                        array_push($model,$shuju);
                    }
                }
                $aa = [];
                foreach ($model as $value){
                    foreach ($value as $item){
                        array_push($aa,$item);
                    }
                }
                $yeshu = ceil( $cou/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$aa;
                $result['data']['yeshu']=$yeshu;
                return $result;

            }
            return messages('操作成功',1);
        }else{
            return messages('操作失败,秘钥错误',-2);
        }


    }


    /**
     * 下级取款记录
     */
    public function actionLowerGet()
    {
        $Publics = new Publics();
        $result = $Publics->Arraies();
        $query = \Yii::$app->request->post();
        $parent = Admin::find()->where(['parent_id' => $query['admin_id']])->all();
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $status = $query['status'];
        $status = (int)$status;
        $page = ($query['page'] - 1) * $query['page_number'];
        if(token($query['admin_id'],$query['token'])){
            if ($start_time && $end_time && $query['lottery'] == 0) {
                $model=[];
                $cou = 0;
                foreach ($parent as $v) {
                    $shuju = Withdrawals::find()->where(['admin_id' => $v->id])->andWhere(['>=', 'created_time', $start_time ])->andWhere(['<=', 'created_time', $end_time+86400])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                    if ($shuju){
                        $count =Withdrawals::find()->where(['admin_id' => $v->id])->andWhere(['>=', 'created_time', $start_time ])->andWhere(['<=','created_time',$end_time+ 86400])->count();
                        $cou+=$count;
                        array_push($model,$shuju);
                    }
                }
                $aa = [];
                foreach ($model as $value){
                    foreach ($value as $item){
                        array_push($aa,$item);
                    }
                }
                $yeshu = ceil( $cou/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$aa;
                $result['data']['yeshu']=$yeshu;
                return $result;

            }
            if ($start_time && $end_time && $query['lottery'] == 1) {
                $model=[];
                $cou = 0;
                foreach ($parent as $k) {
                    $shuju = Withdrawals::find()->where(['admin_id' => $k->id])->andWhere(['>=', 'created_time', $start_time ])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
                    if ($shuju){
                        $count =Withdrawals::find()->where(['admin_id' => $k->id])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time+86400])->andWhere(['status' => $status])->count();
                        $cou+=$count;
                        array_push($model,$shuju);
                    }
                }
                $aa = [];
                foreach ($model as $value){
                    foreach ($value as $item){
                        array_push($aa,$item);
                    }
                }
                $yeshu = ceil( $cou/$query['page_number']);
                $result['msg']='操作成功';
                $result['error']=1;
                $result['data']['list']=$aa;
                $result['data']['yeshu']=$yeshu;
                return $result;

            }
        }else{
            return messages('操作失败,秘钥错误',-2);
        }


    }


    /**
     * 添加下级
     */
    public function actionAddUser()
    {
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id' => $query['admin_id']]);
        if (!$admin) {
            return messages('添加失败');
        }
        if ($admin->type == 2) {
            return messages('操作失败,试玩账号不能提款');
        } else {
            if (token($query['admin_id'], $query['token'])) {
                if ($admin->max < $query['max'] || $query['max'] < 1800) {
                    return messages('注册失败,请填写正确的返点');
                }
                $model = new Admin();
                $model->username = $query['username'];
                $model->password_login = \Yii::$app->security->generatePasswordHash($query['password_login']);
                $model->token = \Yii::$app->security->generateRandomString();
                $model->password_pay = \Yii::$app->security->generatePasswordHash('000000');
                $model->parent_id = $query['admin_id'];
                $model->created_time = time();
                $model->created_ip = \Yii::$app->request->userIP;
                $model->city = $query['city'];
                $model->parent_id = $query['admin_id'];
                $model->max = $query['max'];
                $model->min = 1800;
                $model->type = $query['type'];
                $model->status = 1;
                $model->hierarchy = $admin->hierarchy + 1;
                if ($model->validate()) {
                    $shy = new Mosaic();
                    $shy->admin_id = $query['admin_id'];
                    $shy->number = 5;
                    $shy->save();
                    $result = GameController::Register($query['username'], $query['password_login']);
                    if ($result['Code'] != 0) return messages('操作失败', -1);
                    $model->save();
                    $reports = new Reports();
                    $reports->admin_id = $model->id;
                    $reports->username = $model->username;
                    $reports->save();
                    return messages('操作成功', 1);
                } else {
                    return messages('操作失败，用户名以被采取');
                }
            } else {
                return messages('操作失败,秘钥错误', -2);
            }
        }
    }


    /**
     * 链接开户
     */
    public function actionAddUrl()
    {
        $model = new Link();
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id' => $query['admin_id']]);
        if ($admin->type == 2) {
            return messages('操作失败,试玩账号不能提款', -1);
        } else {
            if (token($query['admin_id'], $query['token'])) {
                if ($admin->max < $query['max'] || $query['max'] < 1800) {
                    return messages('注册失败,请填写正确的返点');
                }
                $model->type = $query['type'];
                $model->max = $query['max'];
                $model->min = 1800;
                $model->note = $query['note'];
                $model->admin_id = $query['admin_id'];
                $model->created_time = time();
                $model->url = \Yii::$app->params['register'] . '/#/register?time=' . $model->created_time;
                $model->save();
                return messages('操作成功', 1);
            } else {
                return messages('操作失败,秘钥错误', -2);
            }
        }
    }



    /**
     * 链接管理
     */
    public function actionUrlList(){
        $query = \Yii::$app->request->post();
        if(token($query['admin_id'],$query['token'])){
            $page = ($query['page'] - 1) * $query['page_number'];
            $model = Link::find()->where(['admin_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->all();
            if($model){
                $count = Link::find()->where(['admin_id'=>$query['admin_id']])->count();
                $yeshu = ceil( $count/$query['page_number']);
            }
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
     * 配额管理
     */
    public function actionQuota(){
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        if(token($query['admin_id'],$query['token'])){
            $model = Quota::find()->where(['admin_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->all();
            if($model){
                $count = Quota::find()->where(['admin_id'=>$query['admin_id']])->count();
                $yeshu = ceil( $count/$query['page_number']);
            }
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
     * 账变记录
     * @return mixed
     */
    public function actionChange(){
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        if(token($query['admin_id'],$query['token'])){
            if($start_time && $end_time){
                $model = Change::find()->where(['admin_id'=>$query['admin_id']])->andWhere(['>=', 'updated_time', $start_time])->andWhere(['<=', 'updated_time', $end_time+86400])->offset($page)->limit($query['page_number'])->orderBy('id desc')->all();
                if($model){
                    $count = Change::find()->where(['admin_id'=>$query['admin_id']])->andWhere(['>=', 'updated_time', $start_time])->andWhere(['<=', 'updated_time', $end_time+86400])->count();
                    $yeshu = ceil( $count/$query['page_number']);
                }
            }else{
                $model = Change::find()->where(['admin_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->orderBy('id desc')->all();
                if($model){
                    $count = Change::find()->where(['admin_id'=>$query['admin_id']])->count();
                    $yeshu = ceil( $count/$query['page_number']);
                }
            }
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
     * 账变记录
     * @return mixed
     */
    public function actionTeamChange(){
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        $lottery = $query['lottery'];
        if(token($query['admin_id'],$query['token'])){
            if($start_time && $end_time && $lottery == 0){
                $models = new Admin();
                $shy = $models->all($query['admin_id']);
                $ssss=0;
                foreach ($shy as $s){
                    $sss = Change::find()->where(['admin_id'=>$s])->andWhere(['>=', 'updated_time', $start_time+86400])->andWhere(['<=', 'updated_time', $end_time])->offset($page)->limit($query['page_number'])->all();
                    $ssss += Change::find()->where(['admin_id'=>$s])->andWhere(['>=', 'updated_time', $start_time+86400])->andWhere(['<=', 'updated_time', $end_time])->count();
                    foreach ($sss as $v){
                        $model[] = $v;
                    }
                }
                if($model == '' || $model ==null){
                    return messages('操作失败,没有数据',1);
                }
                $model = array_slice($model,$page,$page['page_number']);
                $yeshu = ceil( $ssss/$query['page_number']);
            }else{
                $model = Change::find()->where(['admin_id'=>$query['admin_id']])->offset($page)->limit($query['page_number'])->all();
                if($model){
                    $count = Change::find()->where(['admin_id'=>$query['admin_id']])->count();
                    $yeshu = ceil( $count/$query['page_number']);
                }
            }

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
     * 会员列表搜索
     */
    public function actionSearch(){
        $data = \Yii::$app->request->post();
        $admin = Admin::findOne(['username'=>$data['username']]);
        return messages('操作成功',1,$admin);
    }


    /**
     * App点开会员列表 获取用户信息
     */
    public function actionDown(){
        $data = \Yii::$app->request->post();
        $admin = Admin::findOne(['id'=>$data['admin_id']]);
        //总入款
        $ss = 0;
        $recharge = Recharge::find()->where(['admin_id'=>$data['admin_id']])->all();
        foreach ($recharge as $rr){
            $ss += $rr->price;
        }
        //总出款
        $sss = 0;
        $with = Withdrawals::find()->where(['admin_id'=>$data['admin_id']])->select(['price'=>'SUM(price)'])->all();
        foreach ($with as $r){
            $sss += $r->price;
        }
        //团队人数
        $model = new Admin();
        $peoples = $model->all($data['admin_id']);
        $price = 0;
        foreach ($peoples as $v){
            $adminss = Admin::findOne(['id'=>$v]);
            $price += $adminss->price;

        }
        $people = count($peoples);
        $result['msg']='操作成功';
        $result['error']=1;
        $result['data']['list']=$admin;//个人信息
        $result['data']['team']['prices']=$price;//团队余额
        $result['data']['team']['recharge']=$ss;//充值
        $result['data']['team']['withdrawals']=$sss;//取款
        $result['data']['team']['number']=$people;//团队人数
        return $result;

    }

    public function actionActivity()
    {
        if (\Yii::$app->request->isGet){
            $activityh = Activityh::find()->where(['status'=>1])->select(['content','id','sort'])->orderBy('sort asc')->limit(8)->all();
            return messages('请求成功',1,$activityh);
        }
    }
}