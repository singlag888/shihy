<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 10:07
 */

namespace backend\controllers;


use backend\models\ArticleImmediately;
use yii\web\Controller;
use yii\web\Request;

class ArticleImmediatelyController extends Controller
{
    //列表
    public function actionIndex(){
        $total =ArticleImmediately::find()->all();
        return $this->render('index',['model'=>$total]);
    }
    //添加
    public function actionCreate(){
        $request = new Request();
        $model = new ArticleImmediately();//模型表单
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()) {
                $model->created_time = time();
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $model->addErrors($model->getErrors());
            }
        }
        //显示模型表单
        return  $this->render('create',['model'=>$model]);
    }


    //修改
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $model =ArticleImmediately::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }



    //删除
    public function actionDelete($id){
        ArticleImmediately::deleteAll(['id'=>$id]);
    }
}