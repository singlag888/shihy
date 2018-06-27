<?php
/**
 * 职业分类控制器
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\Occupation;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;

class OccupationController extends Controller
{
    //职业分类列表
    public function actionIndex()
    {
        $occupations = Occupation::find()->all();
        return $this->render('index',['occupations'=>$occupations]);
    }
    //添加职业分类
    public function actionCreate()
    {
        //判断是否是post提交
        $request = new Request();
        $model = new Occupation();
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);//跳转到列表页面
            }
        }
        return $this->render('create',['model'=>$model]);
    }
    //更新分类
    public function actionUpdate($id)
    {
        $request = new Request();
        $model = Occupation::findOne(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);//跳转到列表页面
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    //删除职业分类
    public function actionDelete($id)
    {
        Occupation::deleteAll(['id'=>$id]);
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