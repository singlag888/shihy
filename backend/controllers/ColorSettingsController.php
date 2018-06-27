<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 15:30
 */

namespace backend\controllers;

/**
 * 彩种总开关
 */
use backend\models\HowToPlay;
use yii\web\Controller;

class ColorSettingsController extends Controller
{
    public $enableCsrfValidation = false;




    //列表
    public function actionIndex()
    {
        $model = HowToPlay::find()->all();
        return $this->render('index',['model'=>$model]);
    }


//    //查看内容
    //修改
    public function actionUpdate($id){
        $request = \Yii::$app->request;
        $model =HowToPlay::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }





    //删除内容
    public function actionDelete($id)
    {
        HowToPlay::deleteAll(['id'=>$id]);
    }


    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
        if ($status==1){
            HowToPlay::updateAll(['status'=>1],['id'=>$id]);
        }elseif($status==-1){
            HowToPlay::updateAll(['status'=>-1],['id'=>$id]);
        }elseif($status==-2){
            HowToPlay::updateAll(['status'=>-2],['id'=>$id]);
        }
    }
}