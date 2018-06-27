<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 17:12
 */

namespace backend\controllers;


use backend\models\Bank;
use yii\web\Controller;
use yii\web\Request;

class BankController extends Controller
{
    public function actionCreate()
    {
        $model = new Bank();
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
        $model = Bank::find()->all();
        return $this->render('index',['model'=>$model]);
    }





    //查看内容
    public function actionView($id)
    {
        $game = Bank::findOne(['id'=>$id]);
        if ($game->status==0){
            Bank::updateAll(['reading_time'=>time(),'status'=>1],['id'=>$id]);
        }
        return $this->render('examine',['game'=>$game]);
    }



    //修改类型
    public function actionUpdate($id)
    {
        $model= Bank::findOne(['id'=>$id]);
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
        Bank::deleteAll(['id'=>$id]);
    }


    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
          if($status == '已锁定'){
            Bank::updateAll(['status'=>-1],['id'=>$id]);
            return -1;
        }elseif($status == '未锁定'){
            Bank::updateAll(['status'=>1],['id'=>$id]);
            return 1;
        }
    }

}