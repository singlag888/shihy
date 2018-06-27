<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/24 0024
 * Time: 11:29
 */

namespace backend\controllers;


use backend\models\ColorSettings;
use backend\models\Gfffc;
use backend\models\Gflfc;
use backend\models\Gfwfc;
use backend\models\HowToPlay;
use frontend\models\Ahks;
use frontend\models\Bjks;
use frontend\models\Bjpk10;
use frontend\models\Cqssc;
use frontend\models\Gd11x5;
use frontend\models\Gf11x5;
use frontend\models\Gfffsc;
use frontend\models\Gfjssc;
use frontend\models\Gfks;
use frontend\models\Gfsfsc;
use frontend\models\Gxks;
use frontend\models\Js11x5;
use frontend\models\Jsks;
use frontend\models\Jx11x5;
use frontend\models\Sh11x5;
use frontend\models\Ssctxffc;
use frontend\models\Tjssc;
use frontend\models\Xjssc;
use yii\web\Controller;
use yii\web\Request;

class SsctxffcController extends Controller
{

    public $enableCsrfValidation = false;

     //列表
    public function actionIndex()
    {
//        var_dump(\Yii::$app->request->post());die;
        $color = \Yii::$app->request->post('name');
        $settings =HowToPlay::find()->all();
        if($_SERVER['REQUEST_METHOD']=='GET'){
            $color = "官方分分彩";
            $model = Gfffc::find()->orderBy('id DESC')->limit(1000)->all();
        }else{
            if($color =="腾讯分分彩"){
                $model = Ssctxffc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方分分彩"){
                $model = Gfffc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方两分彩"){
                $model = Gflfc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方五分彩"){
                $model = Gfwfc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方快三"){
                $model = Gfks::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方11选5"){
                $model = Gf11x5::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方分分赛车"){
                $model = Gfffsc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "重庆时时彩"){
                $model = Cqssc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "天津时时彩"){
                $model = Tjssc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "新疆时时彩"){
                $model = Xjssc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "广东11选5"){
                $model = Gd11x5::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "江西11选5"){
                $model = Jx11x5::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "江苏11选5"){
                $model = Js11x5::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "上海11选5"){
                $model = Sh11x5::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "江苏快三"){
                $model = Jsks::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "安徽快三"){
                $model = Ahks::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "北京快三"){
                $model = Bjks::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "广西快三"){
                $model = Gxks::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "官方三分赛车"){
                $model = Gfsfsc::find()->orderBy('id DESC')->limit(1000)->all();
            }elseif ($color == "北京PK10"){
                $model = Bjpk10::find()->orderBy('id DESC')->limit(1000)->all();
            }else{
                $model = [];
            }


        }
        return $this->render('index',['model'=>$model,'settings'=>$settings,'color'=>$color]);
    }

    //查看内容
    public function actionView($id)
    {
        $game = Ssctxffc::findOne(['id'=>$id]);
        if ($game->status==0){
            Ssctxffc::updateAll(['reading_time'=>time(),'status'=>1],['id'=>$id]);
        }
        return $this->render('examine',['game'=>$game]);
    }


}