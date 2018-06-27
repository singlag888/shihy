<?php
/**
 * 历史登录记录
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\Log;
use yii\filters\AccessControl;
use yii\web\Controller;

class LogController extends CommonController
{
    //记录列表
    public function actionIndex()
    {
        $logs = Log::find()->all();//查询登录记录表的所有记录
        return $this->render('index',['logs'=>$logs]);
    }

    //删除记录
    public function actionDelete($id)
    {
        Log::deleteAll(['id'=>$id]);
    }

    public function behaviors()
    {
        return [
            'acf' => [
                'class' => AccessControl::className(),
                'only' => ['competence'],//除了这些操作，其他操作生效
                'rules' => [
                    //允许未登录用户访问show操作
                    /*[
                        'allow' => true,//允许
                        'actions' => ['login', 'captcha'],//操作
                        'roles' => ['?'],//? 未认证用户（未登陆） @已认证（已登陆）
                    ],*/
                    //允许已登陆用户访问center操作
                    [
                        'allow' => true,
                        'actions' => ['competence'],
                        'roles' => ['@']
                    ],

                    //其他全部禁止
                ]
            ],
            'rbac' => [
                'class' => RbacFilters::className(),
                'except'=>['login','logout','upload','captcha'],
            ]
        ];
    }
}