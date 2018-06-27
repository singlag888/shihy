<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 16:33
 */

namespace backend\controllers;

//风控
use backend\models\Vip;
use backend\models\Wind;
use frontend\models\Link;
use yii\web\Controller;
use yii\web\Request;

class WindController extends Controller
{
    //列表
    public function actionIndex(){
        $model  = Wind::find()->all();
        return $this->render('index',['model'=>$model]);
    }




    //会员分层管理
    public function actionVip(){
        $model  = Vip::find()->all();
        return $this->render('vip',['model'=>$model]);
    }

    //添加会员分层
    public function actionCreate()
    {
        $model = new Vip();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['vip']);
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
        $model= Vip::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->updated_time = time();
                $model->save();
                return $this->redirect(['vip']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }


    //删除内容
    public function actionDelete($id)
    {
        Vip::deleteAll(['id'=>$id]);
    }
}