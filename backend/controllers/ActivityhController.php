<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22
 * Time: 10:31
 */

namespace backend\controllers;


use backend\models\Activityh;
use yii\web\Controller;

class ActivityhController extends Controller
{

    public function actionIndex(){
        $activityh = Activityh::find()->all();
        return $this->render('index',['activityh'=>$activityh]);
    }

    public function actionCreate()
    {
        $model = new Activityh();
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                $model->add_time = time();
                $model->save();
                return $this->redirect(['index']);
            }
            $model->getErrors();
        }
        return $this->render('create',['model'=>$model]);
    }
    public function actionUpdate($id)
    {
        $model = Activityh::findOne(['id'=>$id]);
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                $model->update_time = time();
                $model->save();
                return $this->redirect(['index']);
            }
            $model->getErrors();
        }
        return $this->render('create',['model'=>$model]);
    }
    //删除某条活动
    public function actionDelete($id)
    {
        Activityh::deleteAll(['id'=>$id]);//删除活动详情
    }

    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
        if ($status==1){
            Activityh::updateAll(['status'=>1],['id'=>$id]);
        }elseif($status==0){
            Activityh::updateAll(['status'=>0],['id'=>$id]);
        }
    }
}