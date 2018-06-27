<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 16:30
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\Hierarchy;
use yii\web\Controller;

class HierarchyController extends Controller
{
    /**
     * 层级管理
     */
    public function actionIndex(){
        $model = Hierarchy::find()->all();
        foreach ($model as $value){
           $value->number = Admin::find()->where(['hierarchy'=>$value->id])->count();
        }
        return $this->render('index',['model'=>$model]);
    }

}