<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/27
 * Time: 9:52
 */

namespace backend\controllers;


use backend\models\Ratio;
use yii\web\Controller;

class RatioController extends Controller
{

    /*
     * 显示倍率
     */
    public function actionIndex(){
        $model = Ratio::find()->where(['>','status','0'])->all();
        return $this->render('index',['model'=>$model]);
    }


    /**
     * 添加倍率
     */
    public function actionCreate(){
        $request  = \Yii::$app->request;
        $model = new Ratio();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->status = 1;
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $model->addErrors($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 修改倍率
     */
    public function actionUpdate($id){
        $request  = \Yii::$app->request;
        $model = Ratio::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }else{
                return $model->addErrors($model->getErrors());
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    /**
     * 删除方法
     * @param $id
     */
    public function actionDelete($id){
        Ratio::deleteAll(['id'=>$id]);
    }

}