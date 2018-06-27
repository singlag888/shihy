<?php
/**
 * 谷歌验证
 */

namespace frontend\controllers;


use backend\models\Admin;
use backend\models\White;
use frontend\models\Gu;
use yii\web\Controller;
use yii\web\Response;

header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class GuController extends Controller
{
    //解决ajax报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
    /**生成谷歌二维码
     * @return mixed
     */
    public function actionGoogleQr()
    {
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            if ($data['admin_id']&&$data['token']){
                if (!token($data['admin_id'],$data['token'])){
                    return messages('秘钥错误',-2);
                }
                $ga = new Gu();
                //创建一个新的"安全密匙SecretKey"
//把本次的"安全密匙SecretKey" 入库,和账户关系绑定,客户端也是绑定这同一个"安全密匙SecretKey"
                $admin = Admin::findOne(['id'=>$data['admin_id']]);
                if ($admin){
                    if ($admin->type==2){
                        return messages('试玩账号不能绑定',1,['status'=>0]);
                    }
                    if ($admin->token){
                        return messages('该账号已经绑定',1,['status'=>1]);
                    }
                    $secret = $ga->createSecret();//安全密匙SecretKey
                    $redis = new \Redis();
                    $redis->connect('127.0.0.1');
                    $redis->set('url'.$admin->id,$secret);//将用户id和秘钥保存在redis中
                    $cache = \Yii::$app->cache;
                    $cache->add('key'.$admin->id,$secret,60);
                    $redis->expire('url'.$admin->id,3600*2);//设置过期时间
                    $qrCodeUrl = $ga->getQRCodeGoogleUrl('神榜娱乐:'.$admin->username, $secret,
                        '神榜娱乐'); //第一个参数是"标识",第二个参数为"安全密匙SecretKey" 生成二维码信息
                    return messages('成功',1,['url'=>$qrCodeUrl]);
                }
            }
        }
    }

    /**验证谷歌效验码
     * @return mixed
     */
    public function actionGoogleVerification()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data['admin_id'] && $data['token']&&$data['code']) {
                if (!token($data['admin_id'], $data['token'])) {
                    return messages('秘钥错误', -2);
                }
                $admin = Admin::findOne(['id'=>$data['admin_id']]);
                if ($admin){
                    if ($admin->type==2){
                        return messages('试玩账号不能绑定',1,['status'=>0]);
                    }
                    if ($admin->token){
                        return messages('该账号已经绑定', 1,['status'=>1]);
                    }
                    $cache = \Yii::$app->cache;
                    $key = $cache->get('key'.$admin->id);
                    if ($key){
                        $ga = new Gu();
                        $checkResult = $ga->verifyCode($key, $data['code'],8);
                        if ($checkResult) {
                            $admin->token = $key;//将安全密匙SecretKey保存在数据库
                            $admin->save();
                            return messages('绑定成功',1);
                        }
                    }
                    return messages('绑定失败,效验码错误');
                }
                return messages('绑定失败');
            }
        }
    }

    /**谷歌验证登陆
     * @return mixed
     */
    public function actionGoogleLogin()
    {
        if (\Yii::$app->request->isPost){
            $data = \Yii::$app->request->post();
            if ($data['username']&&$data['code']){
                $admin = Admin::find()->where(['username'=>$data['username']])->one();
                if ($admin){
                    if ($admin->token){
                        $ga = new Gu();
                        $checkResult = $ga->verifyCode($admin->token, $data['code'], 2);
                        if ($checkResult){
//                            $white = White::findOne(['ip'=>\Yii::$app->request->userIP]);
//                            if ($white){
                                $tokens = \Yii::$app->security->generateRandomString();
                                $admin->tokens = $tokens;
                                $admin->save();
                                return messages('登陆成功', 1, ['id' => $admin['id'], 'token' => $tokens]);
//                            }else{
//                                return messages('该ip不能访问');
//                            }
                        }else{
                            return messages('登陆失败,验证码不正确');
                        }
                    }else{
                        return messages('该账号尚未绑定谷歌验证码!');
                    }
                }else{
                    return messages('用户名或密码错误!');
                }
            }
        }
    }
}