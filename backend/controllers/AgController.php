<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27 0027
 * Time: 10:12
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\Ag;
use backend\models\Black;
use backend\models\Hierarchy;
use backend\models\Recharge;
use yii\web\Controller;

class AgController extends Controller
{

    /**
     * Ag购彩页面
     * @return string
     */
    public function actionIndex(){
        $model = Ag::find()->all();
        return $this->render('index',['model'=>$model]);
    }


    /**
     * 黑名单页面
     */
    public function actionBlack(){
        $model = Black::find()->all();
        return $this->render('black',['model'=>$model]);
    }


    /**
     * 层级管理
     */
    public function actionHierarchy(){
        $model = Hierarchy::find()->all();
        return $this->render('hierarchy',['model'=>$model]);
    }


    /**
     * 死数据  理财记录
     */
    public function actionLcjl(){
        return $this->render('lcjl');
    }

    /**
     *  金流次数
     */
    public function actionJlcs(){
        $hierarchy = Hierarchy::find()->asArray()->all();
        foreach ($hierarchy as $key=>$value){
            $kuai_nu = 0;
            $kuai_jine = 0;
            $pu_nu = 0;
            $pu_jine = 0;
            $san_nu = 0;
            $san_jine = 0;
            $ren_nu = 0;
            $ren_jine = 0;
            $admin = Admin::find()->where(['hierarchy'=>$value['id']])->select('id')->asArray()->all();
            foreach ($admin as $item){
               $recharge = Recharge::find()->where(['admin_id'=>$item['id'],'status'=>1])->all();
               foreach ($recharge as $it){
                   /*快速入款*/
                   if ($it->deposit_mode==0){
                       $kuai_jine +=$it->price;
                       $kuai_nu +=1;
                       /*网银转账*/
                   }elseif ($it->deposit_mode==1){
                       $pu_nu += 1;
                       $pu_jine += $it->price;
                    /*第三方
                        快捷充值，网银、微信、支付宝等渠道的网关页面自动跳转
                    */
                   }elseif ($it->deposit_mode==2){
                       $san_nu += 1;
                       $san_jine +=$it->price;
                    /*人工入款*/
                   }elseif ($it->deposit_mode==3){
                       $ren_nu += 1;
                       $ren_jine +=$it->price;
                   }
               }
            }
            $hierarchy[$key]['kuai_nu'] =$kuai_nu;
            $hierarchy[$key]['kuai_jine'] =$kuai_jine;
            $hierarchy[$key]['pu_nu'] =$pu_nu;
            $hierarchy[$key]['pu_jine'] =$pu_jine;
            $hierarchy[$key]['san_nu'] =$san_nu;
            $hierarchy[$key]['san_jine'] =$san_jine;
            $hierarchy[$key]['ren_nu'] =$ren_nu;
            $hierarchy[$key]['ren_jine'] =$ren_jine;
        }
        return $this->render('jlcs',['hierarchy'=>$hierarchy]);
    }


    /**
     * 死数据  账目汇总
     */
    public function actionZmhz(){
        return $this->render('zmhz');
    }

    /**
     * 死数据  手动存入
     */
    public function actionSdcr(){
        return $this->render('sdcr');
    }

    /**
     * 死数据  手动提出
     */
    public function actionSdtc(){
        return $this->render('sdtc');
    }

    /**
     * 死数据  手动转点
     */
    public function actionSdzd(){
        return $this->render('sdzd');
    }
}