<?php
/**
 * 家长控制器
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\Coupon;
use backend\models\Mem;
use backend\models\Member;
use backend\models\Rule;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller
{
    //家长列表
    public function actionIndex()
    {
       $members = Member::find()->all();
//       var_dump($members);

       return $this->render('index',['members'=>$members]);
    }
    //给家长发送优惠卷
    public function actionCoupon($id)
    {
        //判断是否是post提交
        $request = new Request();
        $model = Mem::findOne(['id'=>$id]);
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);//跳转到列表页面
            }
        }else{
            //规则表
            $rule = Rule::find()->all();
            //优惠卷
            $occupation = Coupon::find()->all();
            $arr = ArrayHelper::map($occupation,'id','name');
            $str = ArrayHelper::map($rule,'id','name');
            return $this->render('coupon',['model' => $model, 'str' => $str,'arr'=>$arr]);
        }
    }
    //添加家长
    public function actionCreate()
    {
        $model = new Member();
        $request = new Request();
        if ($request->isPost) {
            //加载
            $model->load($request->post());
            if ($model->validate()) {
                //保存
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['model' => $model]);
    }
    //更新家长
    public function actionUpdate($id)
    {
        $model = Member::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            //加载并接收
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', ['model' => $model]);
    }
    //删除家长
    public function actionDelete($id)
    {
        Member::deleteAll(['id'=>$id]);
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