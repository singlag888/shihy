<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 18:16
 */
namespace frontend\controllers;


use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\Gflfc;
use backend\models\Gfwfc;
use backend\models\Hg15fc;
use backend\models\Jndsfc;
use backend\models\Ltxffc;
use backend\models\Xxl45mc;
use frontend\models\Ahks;
use frontend\models\Bjks;
use frontend\models\Bjpk10;
use frontend\models\Cqssc;
use frontend\models\Gd11x5;
use frontend\models\Gf11x5;
use frontend\models\Gfffsc;
use frontend\models\Gfks;
use frontend\models\Gfsfsc;
use frontend\models\Gxks;
use frontend\models\Js11x5;
use frontend\models\Jsks;
use frontend\models\Jx11x5;
use frontend\models\Mosaic;
use frontend\models\Sh11x5;
use frontend\models\Ssctxffc;
use frontend\models\Tjssc;
use frontend\models\Txffc;
use frontend\models\Xjplfc;
use frontend\models\Xjssc;
use yii\web\Response;
header("Content-type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin:*');
/**
 * 彩种开奖中心
 * Class Api5Controller
 * @package frontend\controllers
 */
class Api5Controller extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }





    /**
     *  彩种开奖中心
     */
      public function actionLottery(){
          $query = \Yii::$app->request->post();
          $color = \Yii::$app->request->post('color_id');
          $page = ($query['page'] - 1) * $query['page_number'];
          if ($color==60){
              $model = Txffc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Txffc::find()->count();
          }elseif ($color==1){
              $model = Cqssc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Cqssc::find()->count();
          }elseif ($color==3){
              $model = Tjssc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Tjssc::find()->count();
          }elseif ($color==7){
              $model = Xjssc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Xjssc::find()->count();
          }elseif ($color==112){
              $model =Gfffc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gfffc::find()->count();
          }elseif ($color==113){
              $model = Gflfc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gflfc::find()->count();
          }elseif ($color==114){
              $model = Gfwfc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gfwfc::find()->count();
          }elseif ($color==9){
              $model = Gd11x5::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gd11x5::find()->count();
          }elseif ($color==6){
              $model = Jx11x5::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Jx11x5::find()->count();
          }elseif ($color==115){
              $model = Js11x5::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Js11x5::find()->count();
          }elseif ($color==22){
              $model = Sh11x5::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Sh11x5::find()->count();
          }elseif ($color==116){
              $model = Gf11x5::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gf11x5::find()->count();
          }elseif ($color==23){
              $model = Jsks::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Jsks::find()->count();
          }elseif ($color==24){
              $model = Ahks::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Ahks::find()->count();
          }elseif ($color==37){
              $model = Bjks::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Bjks::find()->count();
          }elseif ($color==38){
              $model = Gxks::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gxks::find()->count();
          }elseif ($color==117){
              $model = Gfks::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gfks::find()->count();
          }elseif ($color==27){
              $model = Bjpk10::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Bjpk10::find()->count();
          }elseif ($color==118){
              $model = Gfffsc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gfffsc::find()->count();
          }elseif ($color==119){
              $model = Gfsfsc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Gfsfsc::find()->count();
          }elseif ($color == 1299) {
              $model = Ltxffc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Ltxffc::find()->count();
          }elseif ($color == 42) {
              $model = Hg15fc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Hg15fc::find()->count();
          }elseif ($color== 1297) {
              $model = Jndsfc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Jndsfc::find()->count();
          }elseif ($color== 1298) {
              $model = Xjplfc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Xjplfc::find()->count();
          }elseif ($color == 66) {
              $model = Xxl45mc::find()->orderBy('id desc')->offset($page)->limit($query['page_number'])->all();
              $count = Xxl45mc::find()->count();
          }
         if($count ==0){
            $yeshu  = 0;
        }else{
            $yeshu = ceil( $count/$query['page_number']);
        }
          $result['msg']='操作成功';
          $result['error']=1;
          $result['data']['list']=$model;
          $result['data']['yeshu']=$yeshu;
          return $result;
      }


    /**
     * 开奖中心
     * @return mixed
     */
      public function actionList(){
            $type = \Yii::$app->request->post('type');
            //1为时时彩
            if($type  == 1){
                $cqssc = Cqssc::find()->orderBy('id desc')->one();
                $tjssc = Tjssc::find()->orderBy('id desc')->one();
                $xjssc = Xjssc::find()->orderBy('id desc')->one();
                $gfffc = Gfffc::find()->orderBy('id desc')->one();
                $gflfc = Gflfc::find()->orderBy('id desc')->one();
                $gfwfc = Gfwfc::find()->orderBy('id desc')->one();
                $results = [
                    $cqssc,
                    $tjssc,
                   $xjssc,
                    $gfffc,
                    $gflfc,
                    $gfwfc,
                ];
                $result['msg']='操作成功';
                $result['error']= 1;
                $result['data']['list']= $results;
                return $result;
                //2为11选五
            }elseif ($type ==2){
                $gd11x5 = Gd11x5::find()->orderBy('id desc')->one();
                $jx11x5 = Jx11x5::find()->orderBy('id desc')->one();
                $js11x5 = Js11x5::find()->orderBy('id desc')->one();
                $sh11x5 = Sh11x5::find()->orderBy('id desc')->one();
                $gf11x5 = Gf11x5::find()->orderBy('id desc')->one();
                $results = [
                    $gd11x5,
                   $jx11x5,
                    $js11x5,
                  $sh11x5,
                   $gf11x5,
                ];
                $result['msg']='操作成功';
                $result['error']= 1;
                $result['data']['list']= $results;
                return $result;
                //快三
            }elseif ($type ==3){
                $jsks = Jsks::find()->orderBy('id desc')->one();
                $ahks = Ahks::find()->orderBy('id desc')->one();
                $bjks = Bjks::find()->orderBy('id desc')->one();
                $gxks = Gxks::find()->orderBy('id desc')->one();
                $gfks = Gfks::find()->orderBy('id desc')->one();
                $results = [
                  $jsks,
                    $ahks,
                     $bjks,
                   $gxks,
                  $gfks,
                ];
                $result['msg']='操作成功';
                $result['error']= 1;
                $result['data']['list']= $results;
                return $result;
                //PK10
            }elseif ($type ==4){
               $bjpk10 = Bjpk10::find()->orderBy('id desc')->one();
            $gfffsc = Gfffsc::find()->orderBy('id desc')->one();
            $gfsfsc = Gfsfsc::find()->orderBy('id desc')->one();
            $results = [
                $bjpk10,
                $gfffsc,
                $gfsfsc
            ];
            $result['msg']='操作成功';
            $result['error']= 1;
            $result['data']['list']= $results;
            return $result;
            //快乐彩
            }elseif ($type == 5 ){
                $Txffc = Txffc::find()->orderBy('id desc')->one();
                $Ltxffc = Ltxffc::find()->orderBy('id desc')->one();
                $Jndsfc = Jndsfc::find()->orderBy('id desc')->one();
                $Xxl45mc = Xxl45mc::find()->orderBy('id desc')->one();
                $Xjplfc = Xjplfc::find()->orderBy('id desc')->one();
                $results = [
                    $Txffc,
                    $Ltxffc,
                    $Jndsfc,
                    $Xxl45mc,
                    $Xjplfc,
                ];
                $result['msg']='操作成功';
                $result['error']= 1;
                $result['data']['list']= $results;
                return $result;
            }
      }
  
  
  
      public function actionNumber(){
        Mosaic::deleteAll();
        $admin = Admin::find()->where(['!=','type',3])->andWhere(['!=','type',2])->all();
        foreach ($admin as $v){
            $models = new Mosaic();
            $models->admin_id = $v['id'];
            $models->number = 5;
            $models->status = 1;
            $models->save();
        }
    }
}