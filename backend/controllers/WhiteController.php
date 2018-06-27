<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24 0024
 * Time: 11:16
 */

namespace backend\controllers;


use backend\models\White;
use yii\web\Controller;

class WhiteController extends Controller
{

        public function actionIndex(){
            $model = White::find()->all();
            return $this->render('index',['model'=>$model]);
        }


        public function actionCreate(){
            $model = new White();
            $request = \Yii::$app->request;
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
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

        public function actionUpdate($id){
            $model = White::findOne(['id' => $id]);
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    public function actionDelete($id)
    {
        White::deleteAll(['id'=>$id]);
    }


    public function actionStatus(){
        $model =  White::find()->all();
        foreach ($model as $value){
            if($value->type ==1){
                White::updateAll(['type'=>-1]);
                return -1;
            }elseif($value->type ==-1){
                White::updateAll(['type'=>1]);
                return 1;
            }
        }

    }


//    public function actionShy(){
//        $model =  White::find()->all();
//        if($model->type ==1 ){
//            White::updateAll(['type'=>-1]);
//            return -1;
//        }elseif($model->type ==1 ){
//            White::updateAll(['type'=>1]);
//            return 1;
//        }
//    }
}
