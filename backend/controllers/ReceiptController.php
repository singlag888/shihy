<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 14:00
 */

namespace backend\controllers;


use backend\models\Receipt;
use yii\web\Controller;
use yii\web\Request;

/**
 * 收款银行账号
 * Class ReceiptController
 * @package backend\controllers
 */
class ReceiptController extends Controller
{
    public function actionCreate()
    {
        $model = new Receipt();
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
        $model = Receipt::find()->all();//查询出所有的站内信
        return $this->render('index',['model'=>$model]);
    }




    //修改类型
    public function actionUpdate($id)
    {
        $model= Receipt::findOne(['id'=>$id]);
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
        Receipt::deleteAll(['id'=>$id]);
    }



    /**
     * 第三方收款账号
     */
    public function actionThree(){
        return $this->render('three');
    }
}