<?php
/**
 * 后台活动控制器
 */

namespace backend\controllers;


use backend\filters\RbacFilters;
use backend\models\ActivitiesCan;
use backend\models\Activity;
use backend\models\ActivityDetails;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ActivityController extends Controller
{
    //活动列表
    public function actionSettingsIndex()
    {
        //查询出所有的活动


        $activitys = Activity::find()->all();
        //分配数据和加载视图
        return $this->render('index', ['activitys' => $activitys]);
    }


    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
        if ($status==1){
            Activity::updateAll(['status'=>1],['id'=>$id]);
        }elseif($status==-1){
            Activity::updateAll(['status'=>-1],['id'=>$id]);
        }
    }


    //添加活动
    public function actionSettingsCreate()
    {
        //实例化表单模型
        $model = new Activity();
//        $ActivityDetails = new ActivityDetails();//实例化活动详情表单模型
//        $lo = Log::find()->where(['username'=>\Yii::$app->user->identity->username])->orderBy('id')->limit(1)->all();
////        var_dump($lo);die;
////        var_dump(max($lo['id']));die;
        //判断是否是post提交
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());//接受post提交的数据
            if ($model->validate()) {//验证信息
                $model->save();//插入数据库
//                $lo = Log::find()->where(['username'=>\Yii::$app->user->identity->username])->all();
//                Log::updateAll(['data'=>'添加活动'],['username'=>\Yii::$app->name,'id'=>max($lo->id)]);//操作记录
                return $this->redirect(['settings-index']);//跳转到列表页面
            } else {
                return $model->addErrors($model->getErrors());//添加错误消息
            }
        } else {
            $model->status = 1;
            $model->review = 1;
            return $this->render('create', ['model' => $model]);//加载视图
        }
    }

    //更新活动
    public function actionSettingsUpdate($id)
    {
        $model = Activity::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());//接受post提交的数据
            if ($model->validate()) {//验证信息
                $model->starting_time = strtotime($model->starting_time);//开始时间
                $model->end_time = strtotime($model->end_time);//结束时间
                $model->save();//插入数据库
                return $this->redirect(['settings-index']);//跳转到列表页面
            }
        } else {
            $model->starting_time = date('Y-m-d', $model->starting_time);//开始时间
            $model->end_time = date('Y-m-d', $model->end_time);//结束时间
            return $this->render('update', ['model' => $model,]);
        }

    }

    //删除某条活动
    public function actionSettingsDelete($id)
    {
        Activity::deleteAll(['id'=>$id]);//删除活动详情
    }

    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            $this->error('删除失败！');
        }
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
//            'rbac' => [
//                'class' => RbacFilters::className(),
//                'except'=>['login','logout','upload','captcha'],
//            ]
        ];
    }






    /**
     * 死数据  站内信
     */
    public function actionZnx(){
        return $this->render('znx');
    }


    /**
     * 滚动公告
     */
    public function actionXtxx(){
        return $this->render('xtxx');
    }

    /**
     * 活动设置
     */
    public function actionSettingsEdit(){


    }
}