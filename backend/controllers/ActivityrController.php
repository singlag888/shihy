<?php
/**
 * 活动记录控制器
 */

namespace backend\controllers;



use backend\models\Activityr;
use yii\web\Controller;

class ActivityrController extends Controller
{
    //记录列表
    public function actionIndex()
    {
       $model = Activityr::find()->all();//查询所有的活动记录
       return $this->render('index',['model'=>$model]);
    }

    //删除记录
    public function actionDelete($id)
    {
        Activityr::deleteAll(['id'=>$id]);
    }
}