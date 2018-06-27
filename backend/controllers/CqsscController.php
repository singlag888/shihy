<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/29 0029
 * Time: 14:40
 */

namespace backend\controllers;

/**
 * 玩法总开关
 */

use backend\models\BigColor;
use backend\models\PlayMethod;
use frontend\models\Fc3d1;
use frontend\models\Klsf1;
use frontend\models\Ks1;
use frontend\models\Pk101;
use frontend\models\S11x51;
use frontend\models\Ssc1;
use yii\web\Controller;
use yii\web\Request;

class CqsscController extends Controller
{
    public $enableCsrfValidation=false;
    //列表
    public function actionIndex()
    {
        if (\Yii::$app->request->isGet){
            $search = \Yii::$app->request->get('search',1);
        }else{
            $search = \Yii::$app->request->post('search','1');
        }
        $model = Ssc1::find()->where(['big_color_id'=>$search])->all();
        $aa = BigColor::find()->all();
        return $this->render('index',['model'=>$model,'aa'=>$aa,'search'=>$search]);
    }
     //修改类型
    public function actionUpdate($id)
    {
        $model = Ssc1::findOne(['id'=>$id]);
        $status = $model->status;//状态
        $note = $model->note;//注数
        $request = new Request();
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                if ($status!==$model->status){
                    PlayMethod::updateAll(['status'=>$model->status],['play_id'=>$model->id]);//修玩法
                }
                if ($note!==$model->note){
                    PlayMethod::updateAll(['note'=>$model->note],['play_id'=>$model->id]);//修玩法
                }
                $model->save();
                return $this->redirect('/index.php?r=cqssc%2Findex&search='.$model->big_color_id);
            }
        }
        return $this->render('update',['model'=>$model]);
    }
    /**
     * ajax修改状态
     */
    public function actionStatus($id,$status){
        Ssc1::updateAll(['status'=>$status],['id'=>$id]);
      PlayMethod::updateAll(['status'=>$status],['play_id'=>$id]);//修玩法
    }
}