<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 14:10
 */

namespace backend\controllers;


use backend\models\Admin;
use frontend\models\Lottery;

class LotteryController extends CommonController
{

        /**
        * 用户投注记录
        */
        public function actionIndex(){

            $model = Lottery::find()->where(['!=','root',1])->orderBy('id DESC')->limit(1000)->all();
            return $this->render('index',['model'=>$model]);
        }

    /**
     * 投注详情
     */
        public function actionIntro($id){
            $model = Lottery::findOne(['id'=>$id]);
            return $this->render('view',['model'=>$model]);
        }

}