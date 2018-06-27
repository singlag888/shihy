<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 11:29
 */

namespace backend\controllers;


use frontend\models\Change;


class GameController extends CommonController
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
        $game = Change::find()->all();//查询出所有的站内信
        return $this->render('index',['game'=>$game]);
    }

}