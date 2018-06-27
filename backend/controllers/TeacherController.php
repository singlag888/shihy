<?php
/**
 * 教师控制器
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\Teacher;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

class TeacherController extends Controller
{
    //教师列表
    public function actionIndex()
    {
        $teachers = Teacher::find()->all();
        return $this->render('index',['teachers'=>$teachers]);
    }

    //添加老师
    public function actionCreate()
    {
        $request = new Request();
        $model = new Teacher();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->add_time = time();
                $model->save();
                return $this->redirect(['index']);
            }
        }
        $model->status = 2;
        return $this->render('create',['model'=>$model]);
    }
    //更新教师
    public function actionUpdate($id)
    {
        $request = new Request();
        $model = Teacher::findOne(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
        }

        return $this->render('update',['model'=>$model]);
    }
    //删除教师
    public function actionDelete($id)
    {
        Teacher::deleteAll(['id'=>$id]);
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