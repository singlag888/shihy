<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 11:32
 */

namespace backend\controllers;


use backend\models\BankList;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BankListController extends CommonController
{
    public function actionCreate()
    {
        $model = new BankList();
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
        $model = BankList::find()->all();//查询出所有的站内信
        return $this->render('index',['model'=>$model]);
    }

    public function actionLogo()
    {
        $img = UploadedFile::getInstanceByName('logo');
        $file = '/upload/' . uniqid() .'.'.$img->getExtension();
        if($img->saveAs(\Yii::getAlias('@frontend/web') .$file)){
            return Json::encode(['url' =>$file]);
        }

    }

    //修改类型
    public function actionUpdate($id)
    {
        $model= BankList::findOne(['id'=>$id]);
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
        BankList::deleteAll(['id'=>$id]);
    }

}