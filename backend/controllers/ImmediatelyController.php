<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 14:40
 */

namespace backend\controllers;


use backend\models\Immediately;
use yii\web\Controller;
use yii\web\Request;

class ImmediatelyController extends Controller
{
    public function actionCreate()
    {
        $model = new Immediately();
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
//        $model = Immediately::find()->all();//查询出所有的站内信
        return $this->render('index');
    }


    //查看内容
    public function actionView($id)
    {
        $game = Immediately::findOne(['id'=>$id]);
        if ($game->status==0){
            Immediately::updateAll(['reading_time'=>time(),'status'=>1],['id'=>$id]);
        }
        return $this->render('examine',['game'=>$game]);
    }



    //修改类型
    public function actionUpdate($id)
    {
        $model= Immediately::findOne(['id'=>$id]);
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
        Immediately::deleteAll(['id'=>$id]);
    }



    /**
     * 28彩票设置
     */
    public function actionBocai28(){
        return $this->render('b28');
    }
}