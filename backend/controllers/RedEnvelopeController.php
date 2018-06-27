<?php
/**
 * 红包管理
 * */

namespace backend\controllers;


use backend\models\RedEnvelope;
use yii\web\Controller;
use yii\web\Request;

class RedEnvelopeController extends Controller
{
    //红包列表
    public function actionIndex()
    {
        $redenvelope = RedEnvelope::find()->all();//查询所有红包
        return $this->render('index',['redenvelope'=>$redenvelope]);
    }

    public function actionCreate()
    {
        $request = new Request();
        $model = new RedEnvelope();
        if ($request->isPost){
            $model->load($request->post());//接受post提交的数据
            if ($model->validate()){//验证信息
                //活动开始时间和结束时间的验证
                if ($model->starting_time < date("Y-m-d H:i", time())) {
                    $model->addErrors(['starting_time'=>'开始时间必须大于当前时间']);
                    return $this->render('create', ['model' => $model]);
                }
                $model->starting_time = strtotime($model->starting_time);//开始时间
                $model->end_time = strtotime($model->end_time);//结束时间
                $model->status = 1;
                $model->save();//插入数据库
//                $lo = Log::find()->where(['username'=>\Yii::$app->user->identity->username])->all();
//                Log::updateAll(['data'=>'添加活动'],['username'=>\Yii::$app->name,'id'=>max($lo->id)]);//操作记录
                return $this->redirect(['index']);//跳转到列表页面
            }else{
                return $model->addErrors($model->getErrors());//添加错误消息
            }
        }
        return $this->render('create',['model'=>$model]);
    }

    //更新红包
    public function actionUpdate($id)
    {
        $model = RedEnvelope::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());//接受post提交的数据
            if ($model->validate()){//验证信息
                //活动开始时间和结束时间的验证
                if ($model->starting_time < date("Y-m-d H:i", time())) {
                    $model->addErrors(['starting_time'=>'开始时间必须大于当前时间']);
                    return $this->render('create', ['model' => $model]);
                }
                $model->starting_time = strtotime($model->starting_time);//开始时间
                $model->end_time = strtotime($model->end_time);//结束时间
                $model->save();//插入数据库
//                $lo = Log::find()->where(['username'=>\Yii::$app->user->identity->username])->all();
//                Log::updateAll(['data'=>'添加活动'],['username'=>\Yii::$app->name,'id'=>max($lo->id)]);//操作记录
                return $this->redirect(['index']);//跳转到列表页面
            }else{
                return $model->addErrors($model->getErrors());//添加错误消息
            }
        }
        $model->starting_time = date('Y-m-d',$model->starting_time);
        $model->end_time = date('Y-m-d',$model->end_time);
        return $this->render('update',['model'=>$model]);
    }

    //删除红包
    public function actionDelete($id)
    {
        RedEnvelope::deleteAll(['id'=>$id]);
    }
}