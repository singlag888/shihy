<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/6
 * Time: 13:59
 */

namespace frontend\controllers;

use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\Limit;
use backend\models\PlayMethod;
use frontend\models\Arrays;
use frontend\models\Buy;
use frontend\models\BuyTogether;
use frontend\models\Determine;
use frontend\models\Jpush;
use frontend\models\Judgment;
use frontend\models\Lottery;
use frontend\models\RecallNumberRecords;
use frontend\models\Robot;
use frontend\models\Test;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class TestController extends Controller
{
    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
       // \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $lottery = new Lottery();
        $lottery->Winning(112);die;
       $Lottery = Lottery::find()->where(['admin_id'=>133])->orderBy('id desc')->limit(11)->all();
        var_dump($Lottery);die;
    }



}

