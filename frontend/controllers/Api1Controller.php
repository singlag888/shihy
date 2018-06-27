<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10 0010
 * Time: 10:48
 */

namespace frontend\controllers;

use backend\models\Admin;
use frontend\models\Fc3d;
use frontend\models\Klsf;
use frontend\models\Klsf1;
use frontend\models\Ks;
use frontend\models\Ks1;
use frontend\models\Pk10;
use frontend\models\Pk101;
use frontend\models\Publics;
use frontend\models\S11x5;
use frontend\models\S11x51;
use frontend\models\Ssc;
use frontend\models\Ssc1;
use yii\base\Object;
use yii\web\Controller;
use yii\web\Response;

header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");

class Api1Controller extends Controller
{

    //解决网页报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 修改登陆密码
     */
    public function actionEditLogin()
    {
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id' => $query['admin_id']]);
        if (token($query['admin_id'], $query['token'])) {
            //如果传过来的密码和数据库密码解密后做对比
            if (\Yii::$app->security->validatePassword($query['password_login'], $admin->password_login)) {
                Admin::updateAll([
                    'password_login' => \Yii::$app->security->generatePasswordHash($query['newPassword'])
                ],
                    ['id' => $query['admin_id']]
                );
                return messages('操作成功', 1);
            } else {

                return messages('操作失败，登陆密码错误', -1);
            }
        } else {
            return messages('操作失败，密钥错误', -2);
        }

    }


    /**
     * 修改支付密码
     */
    public function actionEditPay()
    {
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id' => $query['admin_id']]);
        if (token($query['admin_id'], $query['token'])) {
            //将传过来的密码和数据库密码解密后做对比
            if (\Yii::$app->security->validatePassword($query['password_pay'], $admin->password_pay)) {
                Admin::updateAll([
                    'password_pay' => \Yii::$app->security->generatePasswordHash($query['newPassword'])
                ],
                    ['id' => $query['admin_id']]
                );
                return messages('操作成功', 1);
            } else {
                return messages('操作失败，支付密码错误', -2);
            }
        } else {
            return messages('操作失败，密钥错误', -1);
        }
    }


    /**
     * 时时彩购彩奖金详情
     */
    public function actionMoney()
    {
        $query = \Yii::$app->request->post();
        $admin = Admin::findOne(['id' => $query['admin_id']]);
        $color = \Yii::$app->request->post('color');
        if ($color == '时时彩') {
            $model = Ssc::find()->where(['parent_id'=>null])->asArray()->all();
        } elseif ($color == '11选5') {
            $model = S11x5::find()->where(['parent_id'=>null])->asArray()->all();
        } elseif ($color == '快三') {
            $model = Ks::find()->where(['parent_id'=>null])->asArray()->all();
        } elseif ($color == '快乐十分') {
            $model = Klsf::find()->where(['parent_id'=>null])->asArray()->all();
        } elseif ($color == 'PK10') {
            $model = Pk10::find()->where(['parent_id'=>null])->asArray()->all();
        } elseif ($color == '福彩') {
            $model = Fc3d::find()->where(['parent_id'=>null])->asArray()->all();
        }
        if (token($query['admin_id'], $query['token'])) {
            foreach ($model as $key=>$a) {
                $model[$key]['default'] = $a['bonuss'];
                //计算后的最大奖金
                $model[$key]['bonuss'] = sprintf("%.3f", $a['bonuss'] * $admin['max'] / 2000);
                if($color == '时时彩'){
                    $model[$key]['parent_id'] = Ssc::find()->where(['parent_id'=>$a['id']])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }

                }elseif ($color == '11选5'){
                    $model[$key]['parent_id'] = S11x5::find()->where(['parent_id'=>$a->id])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }
                }elseif ($color == '快三'){
                    $model[$key]['parent_id'] = Ks::find()->where(['parent_id'=>$a->id])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }
                }elseif ($color == '快乐十分'){
                    $model[$key]['parent_id']= Klsf::find()->where(['parent_id'=>$a->id])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }
                }elseif ($color == 'PK10'){
                    $model[$key]['parent_id'] = Pk10::find()->where(['parent_id'=>$a->id])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }
                }elseif ($color == '福彩'){
                    $model[$key]['parent_id']= Fc3d::find()->where(['parent_id'=>$a->id])->orderBy('bonuss desc')->asArray()->all();
                    foreach ($model[$key]['parent_id'] as $ks=>$k){
                        $model[$key]['parent_id'][$ks]['default'] =  $k['bonuss'];
                        $model[$key]['parent_id'][$ks]['bonuss'] =  sprintf("%.3f", $k['bonuss'] * $admin['max'] / 2000);
                    }
                }

            }
        } else {
            return messages('操作失败，密钥错误', -2);
        }
        return messages('操作成功', 1, $model);
    }




}