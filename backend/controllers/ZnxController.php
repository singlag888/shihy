<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15 0015
 * Time: 13:54
 */

namespace backend\controllers;

use backend\models\Znx;
use yii\web\Controller;

class ZnxController extends Controller
{

    public function actionCreate()
    {
        $model = new Znx();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->created_time = time();
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
        $model = Znx::find()->all();//查询出所有的站内信
        return $this->render('index',['model'=>$model]);
    }


    //删除内容
    public function actionDelete($id)
    {
        Znx::deleteAll(['id'=>$id]);
    }
}