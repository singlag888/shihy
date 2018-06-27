<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 17:02
 */

namespace backend\controllers;


use backend\models\ThreePayment;
use yii\web\Request;

class ThreePaymentController extends CommonController
{

    //会员分层管理
    public function actionIndex(){
        $model  = ThreePayment::find()->all();
        return $this->render('index',['model'=>$model]);
    }

    //添加会员分层
    public function actionCreate()
    {
        $model = new ThreePayment();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $model->addErrors($model->getErrors());
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }




    //修改类型
    public function actionUpdate($id)
    {
        $model= ThreePayment::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }


    //删除内容
    public function actionDelete($id)
    {
        ThreePayment::deleteAll(['id'=>$id]);
    }
}