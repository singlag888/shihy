<?php
/**
 * 用户类型控制器
 */

namespace backend\controllers;


use backend\models\Genre;
use yii\web\Controller;
use yii\web\Request;

class GenreController extends Controller
{
    //类型列表
    public function actionIndex()
    {//["FIELD(`id`,4,5,6)"=>true, 'g.start_time'=>'desc']
        $genre = Genre::find()->orderBy('type desc')->all();//查询出所有的用户类型并且做倒叙
        return $this->render('index',['genre'=>$genre]);
    }

    //添加类型
    public function actionCreate()
    {
       $model= new Genre();
       $request = new Request();
       if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
       }
       $model->status = 1;
       return $this->render('create',['model'=>$model]);
    }
    //修改类型
    public function actionUpdate($id)
    {
        $model= Genre::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->save();
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    //删除类型
    public function actionDelete($id)
    {
        Genre::deleteAll(['id'=>$id]);
    }
}