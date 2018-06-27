<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 15:30
 */

namespace backend\controllers;


use backend\models\Change;
use yii\web\Controller;
use yii\web\Request;

class ChangeController extends CommonController
{

    public function actionCreate()
    {
        $model = new Change();
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
        $model = Change::find()->orderBy('id DESC')->limit(2000)->all();//查询出所有的站内信
        return $this->render('index',['model'=>$model]);
    }


    //查看内容
    public function actionView($id)
    {
        $game = Change::findOne(['id'=>$id]);
        if ($game->status==0){
            Change::updateAll(['reading_time'=>time(),'status'=>1],['id'=>$id]);
        }
        return $this->render('examine',['game'=>$game]);
    }



    //修改类型
    public function actionUpdate($id)
    {
        $model= Change::findOne(['id'=>$id]);
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
        Change::deleteAll(['id'=>$id]);
    }


}