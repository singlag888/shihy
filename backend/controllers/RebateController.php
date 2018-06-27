<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/24 0024
 * Time: 10:13
 */

namespace backend\controllers;


use backend\models\Rebate;
use yii\web\Controller;
use yii\web\Request;

class RebateController extends Controller
{

    /**
     * 返点，佣金列表
     */
    public function actionIndex(){
        $model = Rebate::find()->all();
        return $this->render('index',['model'=>$model]);
    }


    /**
     * 添加
     */
    public function actionCreate()
    {
        $model = new Rebate();
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


    /**
     * 修改
     */
    //修改类型
    public function actionUpdate($id)
    {
        $model= Rebate::findOne(['id'=>$id]);
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
        Rebate::deleteAll(['id'=>$id]);
    }

}