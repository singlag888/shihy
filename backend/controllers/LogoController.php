<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/6 0006
 * Time: 15:33
 */

namespace backend\controllers;


use backend\models\Logo;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
class LogoController extends Controller
{

    public $enableCsrfValidation = false;
    public function actionCreate()
    {
        $model = new Logo();
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


    public function actionUpdate($id)
    {
        $model = Logo::findOne(['id' => $id]);
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

    public function actionLogo()
    {
        $img = UploadedFile::getInstanceByName('file');
        $file = '/upload/' . uniqid() .'.'.$img->getExtension();
        if($img->saveAs(\Yii::getAlias('@frontend/web') .$file)){
        return Json::encode(['url' =>$file]);
        }

    }

    public function actionIndex()
    {
        $query = Logo::find()->all();
        return $this->render('index', ['model' => $query]);
    }

    public function actionDelete($id)
    {
        Logo::deleteAll(['id'=>$id]);
    }
}