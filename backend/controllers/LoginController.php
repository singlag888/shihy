<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/25 0025
 * Time: 13:57
 */

namespace backend\controllers;


use backend\models\Admin;
use frontend\models\Login;
use yii\web\Controller;

class LoginController extends Controller
{

    public function actionIndex(){
        $model = Login::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    /**
     * 查看下级管理
     */
    public function actionCreate(){
        //1.输入获取查询的父ID   如果输入ID不是父ID的话查询失败
        //2.输入ID下有子ID 将子id显示出来
        //3.返回数据
        $parent_id = \Yii::$app->request->post('parent_id');
    }


    public function actionBlack(){
        return $this->render('black');
    }



    /**
     * ajax查询
     */
    public function actionSelect($ip){
        $login = Login::find()->orderBy('id DESC')->where(['login_ip'=>$ip])->one();
        $admin = Admin::findOne(['id'=>$login->admin_id]);
        if($admin->status == 1){
            return 1;
        }else{
            return -1;
        }
    }
}