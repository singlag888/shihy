<?php
/**
 * 站内信控制器
 */

namespace backend\controllers;


use backend\models\Letter;
use yii\web\Controller;
use yii\web\Request;

class LetterController extends CommonController
{

    public function actionCreate()
    {
        $model = new Letter();
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

    //列表
    public function actionIndex()
    {
        $letter = Letter::find()->all();//查询出所有的站内信
        return $this->render('index',['letter'=>$letter]);
    }
    
    //查看内容
    public function actionView($id)
    {
        $letter = Letter::findOne(['id'=>$id]);
        if ($letter->status==0){
            Letter::updateAll(['reading_time'=>time(),'status'=>1],['id'=>$id]);
        }
        return $this->render('examine',['letter'=>$letter]);
    }

    //修改类型
    public function actionUpdate($id)
    {
        $model= Letter::findOne(['id'=>$id]);
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
        Letter::deleteAll(['id'=>$id]);
    }
}