<?php
/**
 * 系统消息控制器
 */

namespace backend\controllers;


use backend\models\ArticleLogin;
use yii\web\Request;

class ArticleLoginController extends CommonController
{
    //列表
    public function actionIndex(){
        $total =ArticleLogin::find()->all();
        return $this->render('index',['model'=>$total]);
    }
    //添加
    public function actionCreate(){
        $request = new Request();
        $model = new ArticleLogin();//模型表单
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
        $model =ArticleLogin::findOne(['id'=>$id]);
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
        ArticleLogin::deleteAll(['id'=>$id]);
    }




}