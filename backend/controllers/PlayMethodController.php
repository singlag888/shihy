<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/16
 * Time: 10:18
 */

namespace backend\controllers;
/**
 * 玩法细开关
 */

use backend\models\BigColor;
use backend\models\HowToPlay;
use backend\models\PlayMethod;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class PlayMethodController extends Controller
{
    public $enableCsrfValidation=false;
    /**
     * 列表
     */
    public function actionIndex()
    {
        $search = \Yii::$app->request->post('search',1);
       $playmethod = PlayMethod::find()->where(['lottery_type'=>$search])->all();
       $aa = HowToPlay::find()->all();
       return $this->render('index',['playmethod'=>$playmethod,'aa'=>$aa,'search'=>$search]);
    }

    public function actionAdd()
    {
        $model = new PlayMethod();
        $bigcolors = BigColor::find()->all();
        $colorsettings = HowToPlay::find()->all();
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }else{
                $model->getErrors();
            }
        }
        $colorsetting = ArrayHelper::map($colorsettings,'id','name');
        $bigcolor = ArrayHelper::map($bigcolors,'id','name');
        return $this->render('add',['model'=>$model,'colorsetting'=>$colorsetting,'bigcolor'=>$bigcolor]);
    }
    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
        PlayMethod::updateAll(['status'=>$status],['id'=>$id]);
    }

    public function actionUpdate($id)
    {
        $model = PlayMethod::findOne(['id'=>$id]);
        $bigcolors = BigColor::find()->all();
        $colorsettings = HowToPlay::find()->all();
        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }else{
                $model->getErrors();
            }
        }
        $colorsetting = ArrayHelper::map($colorsettings,'id','name');
        $bigcolor = ArrayHelper::map($bigcolors,'id','name');
        return $this->render('add',['model'=>$model,'colorsetting'=>$colorsetting,'bigcolor'=>$bigcolor]);
    }
}