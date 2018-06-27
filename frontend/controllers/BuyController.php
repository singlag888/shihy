<?php
/**
 * 关于用户投注之后的操作
 */

namespace frontend\controllers;

use backend\models\Gfffc;
use backend\models\Admin;
use backend\models\Hg15fc;
use backend\models\HowToPlay;
use backend\models\Jndsfc;
use backend\models\Ltxffc;
use backend\models\PlayMethod;
use backend\models\Reports;
use backend\models\Xxl45mc;
use frontend\models\Ahks;
use frontend\models\AhksWin;
use frontend\models\Bjks;
use frontend\models\BjksWin;
use frontend\models\Bjpk10;
use frontend\models\Buy;
use frontend\models\Fc3d1;
use frontend\models\Gd11x5;
use frontend\models\Gd11x5Win;
use frontend\models\Gf11x5;
use frontend\models\Gf11x5Win;
use backend\models\GfffcWin;
use frontend\models\Gfffsc;
use frontend\models\Gfsfsc;
use frontend\models\Gxks;
use frontend\models\GxksWin;
use frontend\models\Js11x5;
use frontend\models\Js11x5Win;
use frontend\models\Jsks;
use frontend\models\JsksWin;
use frontend\models\Jx11x5;
use frontend\models\Jx11x5Win;
use frontend\models\Klsf1;
use frontend\models\Ks1;
use frontend\models\Pk101;
use frontend\models\PrizePool;
use frontend\models\S11x51;
use frontend\models\Sh11x5;
use frontend\models\Sh11x5Win;
use frontend\models\Ssctxffc;
use frontend\models\Ssc1;
use frontend\models\Tjssc;
use frontend\models\TjsscWin;
use frontend\models\Txffc;
use frontend\models\Xjplfc;
use frontend\models\Xjssc;
use frontend\models\XjsscWin;
use yii\web\Controller;
use frontend\models\Arrays;
use frontend\models\Change;
use frontend\models\Cpbj;
use frontend\models\Robot;
use frontend\models\Gu;
use frontend\models\Lottery;
use frontend\models\SsctxffcWin;
use frontend\models\YkReports;
use frontend\models\RecallNumberRecords;
use yii\web\Response;
use frontend\models\Gfks;
use frontend\models\GfksWin;
use backend\models\Gflfc;
use backend\models\GflfcWin;
use backend\models\Gfwfc;
use backend\models\GfwfcWin;
use frontend\models\Cqssc;
use frontend\models\CqsscWin;
header("Content-type: text/html; charset=utf-8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

class BuyController extends Controller
{
    //解决ajax报400错误
    public $enableCsrfValidation = false;

    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionTe()
    {
        $ga = new Gu();
        //创建一个新的"安全密匙SecretKey"
//把本次的"安全密匙SecretKey" 入库,和账户关系绑定,客户端也是绑定这同一个"安全密匙SecretKey"
        $secret = $ga->createSecret();
        echo "安全密匙SecretKey: " . $secret . "\n\n";

        $qrCodeUrl = $ga->getQRCodeGoogleUrl('www.iamle.com', $secret); //第一个参数是"标识",第二个参数为"安全密匙SecretKey" 生成二维码信息
        echo "Google Charts URL for the QR-Code: " . $qrCodeUrl . "\n\n"; //Google Charts接口 生成的二维码图片,方便手机端扫描绑定安全密匙SecretKey

        $oneCode = $ga->getCode($secret); //服务端计算"一次性验证码"
        echo "服务端计算的验证码是:" . $oneCode . "\n\n";

//把提交的验证码和服务端上生成的验证码做对比
// $secret 服务端的 "安全密匙SecretKey"
// $oneCode 手机上看到的 "一次性验证码"
// 最后一个参数 为容差时间,这里是2 那么就是 2* 30 sec 一分钟.
// 这里改成自己的业务逻辑
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);
        if ($checkResult) {
            echo '匹配! OK';
            var_dump($secret);
        } else {
            echo '不匹配! FAILED';
        }
        /*
         * 二维码: otpauth://totp/%E6%8B%89%E6%96%AF%E7%BB%B4%E5%8A%A0%E6%96%AF:yunying3?secret=VSH5X35T2UM5EX57&issuer=%E6%92%92%E6%89%93%E7%AE%97
         *
         * 解码后:otpauth://totp/拉斯维加斯:yunying3?secret=VSH5X35T2UM5EX57&issuer=撒打算
         * 解码前:otpauth://totp/%E6%8B%89%E6%96%AF%E7%BB%B4%E5%8A%A0%E6%96%AF:yunying3?secret=VSH5X35T2UM5EX57&issuer=%E6%92%92%E6%89%93%E7%AE%97
         */
        //$checkResult = $ga->verifyCode('会员注册时生成的密钥secret', '在登陆时或其它操作时需要动态验证code', 1);
//        $urlToEncode="http://www.cnblogs.com/tinyphp";
//        generateQRfromGoogle($urlToEncode);
//        function generateQRfromGoogle($chl,$widhtHeight ='150',$EC_level='L',$margin='0')
//        {
//            $url = urlencode($url);
//            echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$size.'" widhtHeight="'.$size.'"/>';
//        }
    }



    /**
     * (2).用户购买后可以选择撤单
     */
    public function actionWithdrawal()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();//接收撤单数据
            if ($data) {
                if ($data['admin_id'] || $data['id'] && $data['token']||$data['r_id']) {
                    if (token($data['admin_id'], $data['token'])) {
                        $lottery = new Lottery();
                        return $lottery->Withdrawal($data);
                    } else {
                        return messages('秘钥错误',-2);
                    }
                } else {
                    return messages('撤单失败,数据部完整');
                }
            }
        }
    }

    /**
     * (3)统计用户的盈亏
     */
    public function actionSsctxffc()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data['admin_id'] && $data['token'] && $data['period'] && $data['color_id']) {
                if (!token($data['admin_id'], $data['token'])) {
                    return messages('秘钥错误',-2);
                } else {
                    $models = Lottery::find()->where(['admin_id' => $data['admin_id']])->andWhere(['period' => $data['period']])->andWhere(['>=', 'status', 1])->andWhere(['color'=>$data['color_id']])->sum('yk');
                    $howtoplay = HowToPlay::findOne(['id'=>$data['color_id']]);//彩种
                    if (!is_null($models)) {
                        $count =  Lottery::find()->where(['admin_id' => $data['admin_id']])->andWhere(['period' => $data['period']])->andWhere(['color'=>$data['color_id']])->andWhere(['status'=>1])->count();
                        if ($count){
                            $count = 1;
                        }
                        $data['color_id']= $howtoplay->name;
                        return ['error'=>1,'msg'=>'操作成功','data'=>['yk'=>round($models,3),'status'=>1,'color'=>$data['color_id'],'period'=>$data['period'],'winning'=>$count]];
                    } else {
                        $data['color_id']= $howtoplay->name;
                        return ['error'=>-1,'msg'=>'本期彩票您没有投注','data'=>['status'=>-1,'color'=>$data['color_id'],'period'=>$data['period']]];
                    }
                }
            }
        }
    }

    //安卓购买数据
    public function actionLottery()
    {
        if (\Yii::$app->request->isPost) {//判断是不是post提交
            $data = file_get_contents('php://input');
            $data = json_decode($data, TRUE);
            $data = $data['Lottery'];
            foreach ($data as $k => $v) {
                $redis = new \Redis();
                $redis->connect('127.0.0.1', 6379);
                if (!$v['price'] || !$v['color_id'] || !$v['play_id'] || !$v['price'] || !$v['bonus']) {
                    $result[] = ['index' => $k, 'msg' => '数据不完整'];
                    continue;
                }
                //彩种总开关
                $howtoplay = HowToPlay::findOne(['id'=>$v['color_id']]);
                if ($howtoplay->status==-1){
                    $result[] = ['index' => $k, 'msg' => '该彩种已停止'];
                    continue;
                }elseif ($howtoplay->status==-2){
                    $result[] = ['index' => $k, 'msg' => '该彩种已停售'];
                    continue;
                }
                //玩法控制
                $playmethod = PlayMethod::find()->where(['lottery_type'=>$v['color_id']])->andWhere(['number'=>$v['play_id']])->one();
                if ($playmethod->status==0){
                    $result[] = ['index' => $k, 'msg' => '该玩法维护中'];
                    continue;
                }elseif ($playmethod->status==-1){
                    $result[] = ['index' => $k, 'msg' => '该玩法已停售'];
                    continue;
                }elseif ($playmethod->note){
                    if ($v['quantity']>$playmethod->note){
                        $result[] = ['index' => $k, 'msg' => '注单数不能超过平台限制'.$playmethod->note.'注,当前投注'.$v['quantity'].'注'];
                        continue;
                    }
                }
                if ($v['color_id'] == 60) {
                    //前端显示倒计时
                    $redis = resis(60);
                    $fp = 10;
                } elseif ($v['color_id'] == 1) {
                    //前端显示倒计时
                    $redis = resis(1);
                    $fp = 90;
                    //前端显示倒计时
                } elseif ($v['color_id'] == 3) {
                    //前端显示倒计时
                    $redis = resis(3);
                    $fp = 120;
                } elseif ($v['color_id'] == 7) {
                    //前端显示倒计时
                    $redis = resis(7);
                    $fp = 120;
                } elseif ($v['color_id'] == 112) {
                    //前端显示倒计时
                    $redis = resis(112);
                    $fp = 10;
                } elseif ($v['color_id'] == 113) {
                    //前端显示倒计时
                    $redis = resis(113);
                    $fp = 20;
                } elseif ($v['color_id'] == 114) {
                    //前端显示倒计时
                    $redis = resis(114);
                    $fp = 60;
                } elseif ($v['color_id'] == 9) {
                    //前端显示倒计时
                    $redis = resis(9);
                    $fp = 120;
                } elseif ($v['color_id'] == 6) {
                    //前端显示倒计时
                    $redis = resis(6);
                    //封盘时间
                    $fp = 120;
                } elseif ($v['color_id'] == 115) {
                    //前端显示倒计时
                    $redis = resis(115);
                    $fp = 120;
                } elseif ($v['color_id'] == 22) {
                    //前端显示倒计时
                    $redis = resis(22);
                    $fp = 120;
                } elseif ($v['color_id'] == 116) {
                    //前端显示倒计时
                    $redis = resis(116);
                    $fp = 10;
                } elseif ($v['color_id'] == 23) {
                    //前端显示倒计时
                    $redis = resis(23);
                    $fp = 120;
                } elseif ($v['color_id'] == 24) {
                    //前端显示倒计时
                    $redis = resis(24);
                    $fp = 120;
                } elseif ($v['color_id'] == 37) {
                    //前端显示倒计时
                    $redis = resis(37);
                    //封盘时间
                    $fp = 120;
                } elseif ($v['color_id'] == 38) {
                    //前端显示倒计时
                    $redis = resis(38);
                    $fp = 120;
                } elseif ($v['color_id'] == 117) {
                    //前端显示倒计时
                    $redis = resis(117);
                    $fp = 10;
                } elseif ($v['color_id'] == 27) {
                    //前端显示倒计时
                    $redis = resis(27);
                    $fp = 20;
                } elseif ($v['color_id'] == 118) {
                    //前端显示倒计时
                    $redis = resis(118);
                    $fp = 10;
                } elseif ($v['color_id'] == 119) {
                    //前端显示倒计时
                    $redis = resis(119);
                    //封盘时间
                    $fp = 60;
                }elseif ($v['color_id']== 1299) {
                    $redis = resis(1299);
                    $fp = 10;
                }elseif ($v['color_id'] == 42) {
                    $redis = resis(42);
                    $fp = 15;
                }elseif ($v['color_id']== 1297) {
                    $redis = resis(1297);
                    $fp = 30;
                }elseif ($v['color_id'] == 1298) {
                    $redis = resis(1298);
                    $fp = 20;
                }elseif ($v['color_id'] == 66) {
                    $redis = resis(66);
                    $fp = 10;
                }




//查询：select * from 表名 where 学号后四位 = 学号后四位；

                else{
                    $redis = -1;
                    $fp = 0;
                }
                if ($redis < $fp) {
                    $result[] = ['index' => $k, 'msg' => '购买失败,正在开奖'];
                } else {
                    if (!token($v['admin_id'], $v['token'])) {
                        $result[] = ['error' => -2, 'msg' => '秘钥错误'];
                    } else {
                        $admin = Admin::findOne(['id' => $v['admin_id']]);
                        if ($v['price'] <= $admin->price) {   //判断用户的金额够不
                            $v['bian'] = $admin->price - $v['price'];
                            $v['username'] = $admin->username;
                            $v['time'] = time();
                            $v['recall_number_records_id'] = 0;
                            $v['type'] = 0;
                            $v['num'] = 0;
                            $lottery = new Lottery();
                            $resul = $lottery->Lottery($v);//调方法来添加  将开奖的
                            if ($resul) {
                                $change = new Change();
                                $arr['admin_id'] = $v['admin_id'];
                                $arr['username'] = $admin->username;
                                $arr['type'] = '彩票下注';
                                $arr['last_price'] = '-' . $v['price'];
                                $arr['front_price'] = $admin->price;
                                $arr['price'] = $admin->price - $v['price'];
                                $arr['game_type'] = '彩票';
                                $arr['intro'] = $howtoplay->name . $v['period'] . '期下注';
                                $change->Change($arr);
                                $admin->price -= $v['price'];
                                $admin->save();
                                $reports = Reports::findOne(['admin_id'=>$v['admin_id']]);
                                $reports->all_price += $v['price'];
                                $reports->all_price_num += 1;
                                $reports->save();
                                $result[] = ['error' => 1, 'msg' => '购买成功'];
                            } else {
                                $result[] = ['error' => -1, 'msg' => '购买失败'];
                            }
                        } else {
                            $result[] = ['error' => -1, 'msg' => "余额不足"];
                        }
                    }
                }
            }
            if ($result) {
                return messages('购买成功', 1, $result);
            } else {
                return messages('购买失败');
            }

        }
    }


    //IOS购买数据
    public function actionLotterys()
    {
        $data = \Yii::$app->request->post();
        $data = $data['Lottery'];
        foreach ($data as $k => $v) {
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            if (!$v['price'] || !$v['color_id'] || !$v['play_id'] || !$v['price'] || !$v['bonus']) {
                $result[] = ['index' => $k, 'msg' => '数据不完整'];
                continue;
            }
            //彩种总开关
            $howtoplay = HowToPlay::findOne(['id'=>$v['color_id']]);
            if ($howtoplay->status==-1){
                $result[] = ['index' => $k, 'msg' => '该彩种已停止'];
                continue;
            }elseif ($howtoplay->status==-2){
                $result[] = ['index' => $k, 'msg' => '该彩种已停售'];
                continue;
            }
            //玩法控制
            $playmethod = PlayMethod::find()->where(['lottery_type'=>$v['color_id']])->andWhere(['number'=>$v['play_id']])->one();
            if ($playmethod->status==0){
                $result[] = ['index' => $k, 'msg' => '该玩法维护中'];
                continue;
            }elseif ($playmethod->status==-1){
                $result[] = ['index' => $k, 'msg' => '该玩法已停售'];
                continue;
            }elseif ($playmethod->note){
                if ($v['quantity']>$playmethod->note){
                    $result[] = ['index' => $k, 'msg' => '注单数不能超过平台限制'.$playmethod->note.'注,当前投注'.$v['quantity'].'注'];
                    continue;
                }
            }
            if ($v['color_id'] == 60) {//腾讯分分彩
                //前端显示倒计时
                $redis = resis(60);
                $fp = 10;
            } elseif ($v['color_id'] == 1) {//重庆时时彩
                //前端显示倒计时
                $redis = resis(1);
                $fp = 90;
                //前端显示倒计时
            } elseif ($v['color_id'] == 3) {//天津时时彩
                //前端显示倒计时
                $redis = resis(3);
                $fp = 120;
            } elseif ($v['color_id'] == 7) {//新疆时时彩
                //前端显示倒计时
                $redis = resis(7);
                $fp = 120;
            } elseif ($v['color_id'] == 112) {//官方分分彩
                //前端显示倒计时
                $redis = resis(112);
                $fp = 10;
            } elseif ($v['color_id'] == 113) {//官方两分彩
                //前端显示倒计时
                $redis = resis(113);
                $fp = 20;
            } elseif ($v['color_id'] == 114) {//官方五分彩
                //前端显示倒计时
                $redis = resis(114);
                $fp = 60;
            } elseif ($v['color_id'] == 9) {//广东11选5
                //前端显示倒计时
                $redis = resis(9);
                $fp = 120;
            } elseif ($v['color_id'] == 6) {//江西11选5
                //前端显示倒计时
                $redis = resis(6);
                //封盘时间
                $fp = 120;
            } elseif ($v['color_id'] == 115) {//江苏11选5
                //前端显示倒计时
                $redis = resis(115);
                $fp = 120;
            } elseif ($v['color_id'] == 22) {//上海11选5
                //前端显示倒计时
                $redis = resis(22);
                $fp = 120;
            } elseif ($v['color_id'] == 116) {//官方11选5
                //前端显示倒计时
                $redis = resis(116);
                $fp = 10;
            } elseif ($v['color_id'] == 23) {//江苏快三
                //前端显示倒计时
                $redis = resis(23);
                $fp = 120;
            } elseif ($v['color_id'] == 24) {//安徽快三
                //前端显示倒计时
                $redis = resis(24);
                $fp = 120;
            } elseif ($v['color_id'] == 37) {//北京快三
                //前端显示倒计时
                $redis = resis(37);
                //封盘时间
                $fp = 120;
            } elseif ($v['color_id'] == 38) {//广西快三
                //前端显示倒计时
                $redis = resis(38);
                $fp = 120;
            } elseif ($v['color_id'] == 117) {//官方快三
                //前端显示倒计时
                $redis = resis(117);
                $fp = 10;
            } elseif ($v['color_id'] == 27) {//北京PK10
                //前端显示倒计时
                $redis = resis(27);
                $fp = 20;
            } elseif ($v['color_id'] == 118) {//官方分分赛车
                //前端显示倒计时
                $redis = resis(118);
                $fp = 10;
            } elseif ($v['color_id'] == 119) {//官方三分赛车
                //前端显示倒计时
                $redis = resis(119);
                //封盘时间
                $fp = 60;
            }elseif ($v['color_id']== 1299) {
                $redis = resis(1299);
                $fp = 10;
            }elseif ($v['color_id'] == 42) {
                $redis = resis(42);
                $fp = 15;
            }elseif ($v['color_id']== 1297) {
                $redis = resis(1297);
                $fp = 30;
            }elseif ($v['color_id'] == 1298) {
                $redis = resis(1298);
                $fp = 20;
            }elseif ($v['color_id'] == 66) {
                $redis = resis(66);
                $fp = 10;
            }else{
                $redis = -1;
                $fp = 0;
            }
            if ($redis < $fp) {
                $result[] = ['index' => $k, 'msg' => '购买失败,正在开奖'];
            } else {
                $admin = Admin::findOne(['id' => $v['admin_id']]);
                if (!token($v['admin_id'], $v['token'])) {
                    $result[] = ['error' => -2, 'msg' => '秘钥错误'];
                } else {
                    if ($v['price'] <= $admin->price) {   //判断用户的金额够不
                        $v['bian'] = $admin->price - $v['price'];
                        $v['username'] = $admin->username;
                        $v['time'] = time();
                        $v['recall_number_records_id'] = 0;
                        $v['type'] = 0;
                        $v['num'] = 0;
                        $lottery = new Lottery();
                        $resul = $lottery->Lottery($v);//调方法来添加  将开奖的
                        if ($resul) {
                            $change = new Change();
                            $arr['admin_id'] = $v['admin_id'];
                            $arr['username'] = $admin->username;
                            $arr['type'] = '彩票下注';
                            $arr['last_price'] = '-' . $v['price'];
                            $arr['price'] = $admin->price - $v['price'];
                            $arr['front_price'] = $admin->price;
                            $arr['game_type'] = '彩票';
                            $arr['intro'] = $howtoplay['name'] . $v['period'] . '期下注';
                            $change->Change($arr);
                            $admin->price -= $v['price'];
                            $admin->save();
                            $reports = Reports::findOne(['admin_id'=>$v['admin_id']]);
                            $reports->all_price += $v['price'];
                            $reports->all_price_num += 1;
                            $reports->save();
                            $result[] = ['error' => 1, 'msg' => '购买成功'];
                        } else {
                            $result[] = ['error' => -1, 'msg' => '购买失败'];
                        }
                    } else {
                        $result[] = ['error' => -1, 'msg' => "余额不足"];
                    }
                }
            }
        }
        if ($result) {
            return messages('购买成功', 1, $result);
        } else {
            return messages('购买失败');
        }
    }

    //前端购买数据
    public function actionLotteryss()
    {
        $data = \Yii::$app->request->post('data');
        $data = $data['Lottery'];
        $a = 0;//成功
        $b = 0;//失败
        $c = 0;//购买成功总金额
        foreach ($data as $k => $v) {
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            $howtoplay = HowToPlay::findOne(['id'=>$v['color_id']]);
            $playmethod = PlayMethod::find()->where(['lottery_type'=>$v['color_id']])->andWhere(['number'=>$v['play_id']])->one();
            if (!$v['price'] || !$v['color_id'] || !$v['play_id'] || !$v['period'] || !$v['bonus']) {
                $result[] = ['color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '数据不完整'];
                ++$b;
                continue;
            }
            //彩种总开关
            if ($howtoplay->status==-1){
                $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '该彩种已停止'];
                ++$b;
                continue;
            }elseif ($howtoplay->status==-2){
                $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '该彩种已停售'];
                ++$b;
                continue;
            }
            //玩法控制
            if ($playmethod->status==0){
                $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '该玩法维护中'];
                ++$b;
                continue;
            }elseif ($playmethod->status==-1){
                $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '该玩法已停售'];
                ++$b;
                continue;
            }elseif ($playmethod->note){
                if ($v['quantity']>$playmethod->note){
                    $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '注单数不能超过平台限制'.$playmethod->note.'注,当前投注'.$v['quantity'].'注'];
                    ++$b;
                    continue;
                }
            }
            if ($v['color_id'] == 60) {
                //前端显示倒计时
                $redis = resis(60);
                $fp = 10;
            } elseif ($v['color_id'] == 1) {
                //前端显示倒计时
                $redis = resis(1);
                $fp = 90;
                //前端显示倒计时
            } elseif ($v['color_id'] == 3) {
                //前端显示倒计时
                $redis = resis(3);
                $fp = 120;
            } elseif ($v['color_id'] == 7) {
                //前端显示倒计时
                $redis = resis(7);
                $fp = 120;
            } elseif ($v['color_id'] == 112) {
                //前端显示倒计时
                $redis = resis(112);
                $fp = 10;
            } elseif ($v['color_id'] == 113) {
                //前端显示倒计时
                $redis = resis(113);
                $fp = 20;
            } elseif ($v['color_id'] == 114) {
                //前端显示倒计时
                $redis = resis(114);
                $fp = 60;
            } elseif ($v['color_id'] == 9) {
                //前端显示倒计时
                $redis = resis(9);
                $fp = 120;
            } elseif ($v['color_id'] == 6) {
                //前端显示倒计时
                $redis = resis(6);
                //封盘时间
                $fp = 120;
            } elseif ($v['color_id'] == 115) {
                //前端显示倒计时
                $redis = resis(115);
                $fp = 120;
            } elseif ($v['color_id'] == 22) {
                //前端显示倒计时
                $redis = resis(22);
                $fp = 120;
            } elseif ($v['color_id'] == 116) {
                //前端显示倒计时
                $redis = resis(116);
                $fp = 10;
            } elseif ($v['color_id'] == 23) {
                //前端显示倒计时
                $redis = resis(23);
                $fp = 120;
            } elseif ($v['color_id'] == 24) {
                //前端显示倒计时
                $redis = resis(24);
                $fp = 120;
            } elseif ($v['color_id'] == 37) {
                //前端显示倒计时
                $redis = resis(37);
                //封盘时间
                $fp = 120;
            } elseif ($v['color_id'] == 38) {
                //前端显示倒计时
                $redis = resis(38);
                $fp = 120;
            } elseif ($v['color_id'] == 117) {
                //前端显示倒计时
                $redis = resis(117);
                $fp = 10;
            } elseif ($v['color_id'] == 27) {
                //前端显示倒计时
                $redis = resis(27);
                $fp = 20;
            } elseif ($v['color_id'] == 118) {
                //前端显示倒计时
                $redis = resis(118);
                $fp = 10;
            } elseif ($v['color_id'] == 119) {
                //前端显示倒计时
                $redis = resis(119);
                //封盘时间
                $fp = 60;
            }elseif ($v['color_id']== 1299) {
                $redis = resis(1299);
                $fp = 10;
            }elseif ($v['color_id'] == 42) {
                $redis = resis(42);
                $fp = 15;
            }elseif ($v['color_id']== 1297) {
                $redis = resis(1297);
                $fp = 30;
            }elseif ($v['color_id'] == 1298) {
                $redis = resis(1298);
                $fp = 20;
            }elseif ($v['color_id'] == 66) {
                $redis = resis(66);
                $fp = 10;
            }else{
                $redis = -1;
                $fp = 0;
            }
            if ($redis < $fp) {
                $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '购买失败,正在开奖'];
                ++$b;
            } else {
                $admin = Admin::findOne(['id' => $v['admin_id']]);
                if (!token($v['admin_id'], $v['token'])) {
                    ++$b;
                    $result[] = ['error' => -2, 'msg' => '秘钥错误'];
                } else {
                    if ($v['price'] <= $admin->price) {   //判断用户的金额够不
                        $v['bian'] = $admin->price - $v['price'];
                        $v['username'] = $admin->username;
                        $v['time'] = time();
                        $v['recall_number_records_id'] = 0;
                        $v['type'] = 0;
                        $v['num'] = 0;
                        $lottery = new Lottery();
                        $resul = $lottery->Lottery($v);//调方法来添加  将开奖的
                        if ($resul) {
                            $change = new Change();
                            $arr['admin_id'] = $v['admin_id'];
                            $arr['username'] = $admin->username;
                            $arr['type'] = '彩票下注';
                            $arr['last_price'] = '-' . $v['price'];
                            $arr['price'] = $admin->price - $v['price'];
                            $arr['game_type'] = '彩票';
                            $arr['front_price'] = $admin->price;
                            $arr['intro'] = $howtoplay['name'] . $v['period'] . '期下注';
                            $change->Change($arr);
                            $admin->price -= $v['price'];
                            $admin->save();
                            $reports = Reports::findOne(['admin_id'=>$v['admin_id']]);
                            $reports->all_price += $v['price'];
                            $reports->all_price_num += 1;
                            $reports->save();
                            $c+=$v['price'];
                            $ccc = Lottery::findOne(['id'=>$resul]);
                            $result[] = ['error' => 1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>$ccc->order, 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '购买成功'];
                            ++$a;
                        } else {
                            ++$b;
                            $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => '购买失败'];
                        }
                    } else {
                        ++$b;
                        $result[] = ['error' => -1,'color'=>$howtoplay['name'],'period'=>$v['period'],'play'=>$playmethod->name,'order'=>'- -', 'content'=>$v['content'],'price'=>$v['price'],'zongjine'=>$c,'time'=>time(), 'msg' => "余额不足"];
                    }
                }
            }
        }
        if ($result) {
            return messages('共计成功'.$a.'注;失败'.$b.'注', 1, $result);
        } else {
            return messages('共计成功'.$a.'注;失败'.$b.'注');
        }
    }


    /**
     * 奖池金额
     */
    public function actionPrizePool()
    {
        $prizepool = PrizePool::find()->select(['money', 'color'])->asArray()->where(['add_time' => date("Ymd", time())])->orderBy('money desc')->limit(3)->all();
        $c = [];
        foreach ($prizepool as $k => $item) {
            if ($item['color'] == 60) {
                $dbs = Txffc::find();
                //前端显示倒计时
                $redis = resis(60) - 10;
            } elseif ($item['color'] == 1) {
                $dbs = Cqssc::find();
                //前端显示倒计时
                $redis = resis(1) - 10;
            } elseif ($item['color'] == 3) {
                $dbs = Tjssc::find();
                //前端显示倒计时
                $redis = resis(3) - 120;
            } elseif ($item['color'] == 7) {
                $dbs = Xjssc::find();
                //前端显示倒计时
                $redis = resis(7) - 120;
            } elseif ($item['color'] == 112) {
                $dbs = Gfffc::find();
                //前端显示倒计时
                $redis = resis(112) - 10;

            } elseif ($item['color'] == 113) {
                $dbs = Gflfc::find();
                //前端显示倒计时
                $redis = resis(113) - 20;
            } elseif ($item['color'] == 114) {
                $dbs = Gfwfc::find();
                //前端显示倒计时
                $redis = resis(114) - 60;
            } elseif ($item['color'] == 9) {
                $dbs = Gd11x5::find();
                //前端显示倒计时
                $redis = resis(9) - 120;
            } elseif ($item['color'] == 6) {
                $dbs = Jx11x5::find();
                //前端显示倒计时
                $redis = resis(6) - 120;
                //封盘时间
            } elseif ($item['color'] == 115) {
                $dbs = Js11x5::find();
                //前端显示倒计时
                $redis = resis(115) - 120;
            } elseif ($item['color'] == 22) {
                $dbs = Sh11x5::find();
                //前端显示倒计时
                $redis = resis(22) - 120;
            } elseif ($item['color'] == 116) {
                $dbs = Gf11x5::find();
                //前端显示倒计时
                $redis = resis(116) - 10;
            } elseif ($item['color'] == 23) {
                $dbs = Jsks::find();
                //前端显示倒计时
                $redis = resis(23) - 120;
            } elseif ($item['color'] == 24) {
                $dbs = Ahks::find();
                //前端显示倒计时
                $redis = resis(60) - 120;
            } elseif ($item['color'] == 37) {
                $dbs = Bjks::find();
                //前端显示倒计时
                $redis =  resis(37) - 120;
                //封盘时间
            } elseif ($item['color'] == 38) {
                $dbs = Gxks::find();
                //前端显示倒计时
                $redis =  resis(38) - 120;
            } elseif ($item['color'] == 117) {
                $dbs = Gfks::find();
                //前端显示倒计时
                $redis =  resis(117) - 10;

            } elseif ($item['color'] == 27) {
                $dbs = Bjpk10::find();
                //前端显示倒计时
                $redis =  resis(27) - 20;
            } elseif ($item['color'] == 118) {
                $dbs = Gfffsc::find();
                //前端显示倒计时
                $redis =  resis(118) - 10;
            } elseif ($item['color'] == 119) {
                $dbs = Gfsfsc::find();
                //前端显示倒计时
                $redis =  resis(119) - 10;
            }elseif ($item['color']== 1299) {
                $dbs = Ltxffc::find();
                $redis = resis(1299) -10;
            }elseif ($item['color'] == 42) {
                $dbs = Hg15fc::find();
                $redis = resis(42) -15;
            }elseif ($item['color']== 1297) {
                $dbs = Jndsfc::find();
                $redis = resis(1297) -30;
            }elseif ($item['color'] == 1298) {
                $dbs = Xjplfc::find();
                $redis = resis(1298) - 20;
            }elseif ($item['color'] == 66) {
                $dbs = Xxl45mc::find();
                $redis = resis(66) - 10;
            }
            $d = $dbs->select(['name','period','number','behind_period'])->asArray()->orderBy('id desc')->one();//最新开奖
            // $b = $win->select(['username','game_play','winning_amount'])->orderBy('id desc')->limit(8)->all();//中奖公告
            $a = [];
//            foreach ($b as $val){
//                $val['username'] = mb_substr($val['username'],0,6,'utf-8').'***';
//                $val['game_play'] = substr($val['game_play'],strpos($val['game_play'],',')+1);
//            }
            $d['money'] = $item['money'];
            $d['redis'] = $redis;
            $a[] = $d;
//            $a[] = $b;
            array_push($c, $a);
        }
        $result['error'] = 1;
        $result['msg'] = '成功';
        $result['data']['list'] = $c;
        return $result;
    }

    /**60秒倒计时的时候调
     * @return mixed
     */
    public function actionGains()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data['color_id'] && $data['token'] && $data['admin_id'] && $data['period']) {
                if (!token($data['admin_id'], $data['token'])) {
                    return messages('秘钥错误',-2);
                } else {
                    $prizepool = PrizePool::find()->where(['add_time' => date("Ymd", time())])->andWhere(['color' => $data['color_id']])->one();
                    if ($prizepool) {
                        if ($prizepool->zhangfu >= -40000000 && $prizepool->zhangfu <= 60000000) {
                            if ($prizepool->zhangfu == 0) {
                                $jian = mt_rand(10000, 200000);
                                $prizepool->zhangfu -= $jian;
                                $prizepool->money -= $jian;
                            } else {
                                $jian = mt_rand(10000, 200000);
                                $jia = mt_rand(10000, 200000);
                                $prizepool->zhangfu += $jian - $jia;
                                $prizepool->money += $jian - $jia;
                            }
                        }
                        $prizepool->period = $data['period'];
                        $prizepool->save();
                    }
                    return messages('成功', 1);
                }
            }
        }
        return messages('失败');
    }

    public function actionZhuiWeekList()
    {
        $c = new Robot();
        $c->RobotBuy('官方分分彩');

    }


    /**
     * 前端的追号
     */
    public function actionH5()
    {
        if (\Yii::$app->request->isPost) {
            $datas = \Yii::$app->request->post();
            if ($datas['admin_id'] && $datas['token']) {
                if (!token($datas['admin_id'], $datas['token'])) {
                    return messages('秘钥错误',-2);
                } else {
                    $buy = new Buy();
                    $results = $buy->Zhuihao($datas);
                    $admin = Admin::findOne(['id' => $datas['admin_id']]);
                    $result['error'] = 1;
                    $result['msg'] = $admin->price;
                    $result['success'] = $results['success'];
                    $result['data'] = $results['result'];
                    return $result;
                }
            } else {
                return messages('数据中没有ID与TOKEN');
            }
        } else {

            return messages('不是POST提交');
        }
    }


    /**安卓追号
     * @return mixed
     */
    public function actionAndrews()
    {
        if (\Yii::$app->request->isPost) {
            $data = file_get_contents('php://input');
            $datas = json_decode($data, TRUE);
            $buy = new Buy();
            $results = $buy->ZhuihaoApp($datas);
            return messages('', 1, $results);
        } else {
            return messages('不是POST提交');
        }
    }

    /**Ios追号
     * @return mixed
     */
    public function actionIos()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            $buy = new Buy();
            $results = $buy->ZhuihaoApp($data);
            return messages('', 1, $results);
        } else {
            return messages('不是POST提交');
        }
    }

    /**追号详情
     * @return mixed
     */
    public function actionZhuihaoDetails()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data['admin_id']&&$data['token']&&$data['page']&&$data['page_number']){
                if (!token($data['admin_id'], $data['token'])) {
                    return messages('秘钥错误',-2);
                } else {
                    if ($data['color_id']){
                        $db = RecallNumberRecords::find()->where(['color'=>$data['color_id']]);
                    }else{
                        $db = RecallNumberRecords::find();
                    }
                    $page = ($data['page'] - 1) * $data['page_number'];
                    if (!$data['end_time']||$data['start_time']){
                        $RecallNumberRecords = $db->andWhere(['admin_id' => $data['admin_id']])->offset($page)->limit($data['page_number'])->orderBy('id desc')->all();
                    }else{
                        $end = strtotime($data['end_time']);
                        $start = strtotime($data['start_time']);
                        $RecallNumberRecords = $db->andWhere(['admin_id' => $data['admin_id']])->andWhere(['>=', 'order_time', $start])->andWhere(['<=', 'order_time', $end])->offset($page)->limit($data['page_number'])->orderBy('id desc')->all();
                    }
                    foreach ($RecallNumberRecords as $activeRecord){
                        if ($activeRecord->completion_period==$activeRecord->betting_period){
                            $activeRecord->get_on = 2;//追号完成
                        }
                        $howtoplay = HowToPlay::findOne(['id'=>$activeRecord->color]);
                        $playmethod = PlayMethod::find()->where(['lottery_type'=>$activeRecord->color])->andWhere(['number'=>$activeRecord->play])->one();
                        $activeRecord->color = $howtoplay->name;//彩种
                        $activeRecord->play = $playmethod->name;
                    }
                    $count = RecallNumberRecords::find()->where(['admin_id' => $data['admin_id']])->andWhere(['>=', 'order_time', strtotime($data['start_time'])])->andWhere(['<=', 'order_time', strtotime($data['end_time'])])->count();
                    $yeshu = ceil($count/$data['page_number']);
                }
                if ($RecallNumberRecords) {
                    $result['error'] = 1;
                    $result['msg'] = '操作成功';
                    $result['data']['list'] = $RecallNumberRecords;
                    $result['data']['yeshu'] = $yeshu;
                    return $result;
                } else {
                    return messages('暂无数据', 1);
                }
            }
        }
    }

    /**时间处理
     * @return mixed
     */
    public function actionTime($data)
    {
        if ($data == '周') {
            $w = date("w", time());   //这天是星期几
            $start = mktime(0, 0, 0, date("m"), date("d") - $w, date("Y"));       //创建周开始时间
            $end = mktime(23, 59, 59, date("m"), date("d") - $w + 6, date("Y"));//创建周结束时间
        } elseif ($data == '天') {
            //php获取今日开始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $end = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
        } elseif ($data == '月') {
//php获取本月起始时间戳和结束时间戳

            $start = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        } elseif ($data == '昨') {
            //php获取昨日起始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
            $end = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
        } elseif ($data == '上周') {
            //php获取上周起始时间戳和结束时间戳
            $start = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y'));
            $end = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
        }
        return ['start' => $start, 'end' => $end];
    }

    /**
     * 注册机器人
     */
    public function actionJqr()
    {
        //Admin::deleteAll(['type'=>3]);
        Robot::Robot();
    }

    public function actionTests()
    {
        $Robot = new Robot();//机器人
        $Robot->RobotBuy('官方分分彩');
    }

    /**奖金详情
     * @return mixed
     */
    public function actionBonus()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data['admin_id'] && $data['token'] && $data['color']) {
                if (!token($data['admin_id'], $data['token'])) {
                    return messages('秘钥错误',-2);
                }
                $ssc1 = Ssc1::find()->where(['big_color_id'=>$data['color']])->asArray()->all();
                $admin = Admin::findOne(['id' => $data['admin_id']]);
                if ($admin) {
                    foreach ($ssc1 as $k => $item) {
                        if (strpos($item['bonuss'], '<br/>') !== false) {
                            $arr = explode('<br/>', $item['bonuss']);
                            $s = [];
                            $b = [];
                            $cs = [];
                            foreach ($arr as $ks => $value) {
                                $c = explode(':', $value);
                                $s[$ks] = $c[0];
                                $b[$ks] = $c[1];
                                $cs[$ks] = sprintf("%.3f", $c[1] * $admin->max / 2000);
                            }
                            $f = implode('<br/>', $s);
                            $ff = implode('<br/>', $b);
                            $fff = implode('<br/>', $cs);
                        } else {
                            $f = $item['name'];
                            $ff = $item['bonuss'];
                            $fff = sprintf("%.3f", $item['bonuss'] * $admin->max / 2000);
                        }
                        $a[$item['bonus']][$k]['name'] = $f;
                        $a[$item['bonus']][$k]['default'] = $ff;
                        $a[$item['bonus']][$k]['highest'] = $fff;

                    }
                    foreach ($a as $k => $value) {
                        foreach ($value as $item) {
                            if ($data['color'] == 1) {//时时彩
                                if ($k == '五星') {
                                    $a = 0;
                                } elseif ($k == '四星') {
                                    $a = 1;
                                } elseif ($k == '前三') {
                                    $a = 2;
                                } elseif ($k == '中三') {
                                    $a = 3;
                                } elseif ($k == '后三') {
                                    $a = 4;
                                } elseif ($k == '前二') {
                                    $a = 5;
                                } elseif ($k == '后二') {
                                    $a = 6;
                                } elseif ($k == '定位胆') {
                                    $a = 7;
                                } elseif ($k == '不定位') {
                                    $a = 8;
                                } elseif ($k == '大小单双') {
                                    $a = 9;
                                } elseif ($k == '龙虎斗') {
                                    $a = 10;
                                } elseif ($k == '总和大小单双') {
                                    $a = 11;
                                } elseif ($k == '任选二') {
                                    $a = 12;
                                } elseif ($k == '任选三') {
                                    $a = 13;
                                } elseif ($k == '任选四') {
                                    $a = 14;
                                }
                            } elseif ($data['color'] == 2) {//11选5
                                if ($k == '三码') {
                                    $a = 0;
                                } elseif ($k == '二码') {
                                    $a = 1;
                                } elseif ($k == '一码') {
                                    $a = 2;
                                } elseif ($k == '不定胆') {
                                    $a = 3;
                                } elseif ($k == '定位胆') {
                                    $a = 4;
                                } elseif ($k == '趣味型') {
                                    $a = 5;
                                } elseif ($k == '任选复式') {
                                    $a = 6;
                                } elseif ($k == '任选单式') {
                                    $a = 7;
                                } elseif ($k == '任选胆拖') {
                                    $a = 8;
                                }
                            } elseif ($data['color'] == 3) {//快乐十分
                                if ($k == '快乐十分') {
                                    $a = 0;
                                }
                            } elseif ($data['color'] == 4) {//快三
                                if ($k == '二不同号') {
                                    $a = 0;
                                } elseif ($k == '二同号') {
                                    $a = 1;
                                } elseif ($k == '三不同号') {
                                    $a = 2;
                                } elseif ($k == '三同号') {
                                    $a = 3;
                                } elseif ($k == '和值') {
                                    $a = 4;
                                }
                            } elseif ($data['color'] == 5) {//PK10
                                if ($k == '猜第一') {
                                    $a = 0;
                                } elseif ($k == '猜前二') {
                                    $a = 1;
                                } elseif ($k == '猜前三') {
                                    $a = 2;
                                } elseif ($k == '猜前四') {
                                    $a = 3;
                                } elseif ($k == '猜前五') {
                                    $a = 4;
                                } elseif ($k == '猜前六') {
                                    $a = 5;
                                } elseif ($k == '猜前七') {
                                    $a = 6;
                                } elseif ($k == '猜前八') {
                                    $a = 7;
                                } elseif ($k == '猜前九') {
                                    $a = 8;
                                } elseif ($k == '猜前十') {
                                    $a = 9;
                                } elseif ($k == '定位胆') {
                                    $a = 10;
                                } elseif ($k == '趣味玩法') {
                                    $a = 11;
                                }
                            } elseif ($data['color'] == 6) {//福彩
                                if ($k == '三星') {
                                    $a = 0;
                                } elseif ($k == '二星') {
                                    $a = 1;
                                } elseif ($k == '不定位') {
                                    $a = 2;
                                } elseif ($k == '大小单双') {
                                    $a = 3;
                                } elseif ($k == '前一直选') {
                                    $a = 4;
                                } elseif ($k == '后一直选') {
                                    $a = 5;
                                }
                            }
                            $ss[$a][] = $item;
                        }
                    }
                    $result['data'] = $ss;
                    return $ss;
                }
            }
        }
    }


    public function actionAa()
    {
        $data = [
            [
                'money' => '130000000', 'color' => '官方11选5', 'status' => 1
            ],
            [
                'money' => '130000000', 'color' => '官方分分彩', 'status' => 1
            ],
            [
                'money' => '130000000', 'color' => '官方快三', 'status' => 1
            ],
        ];
        foreach ($data as $datum) {
            $prizepool = new PrizePool();
            $prizepool->money = $datum['money'];
            $prizepool->color = $datum['color'];
            $prizepool->add_time = date("Ymd", time());
            $prizepool->status = $datum['status'];
            $prizepool->save();
        }
    }

    public function actionCc()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $model = new Gfffc();
        $one1 = rand(0,9);
        $one2 = rand(0,9);
        $one3 = rand(0,9);
        $one4 = rand(0,9);
        $one5 = rand(0,9);
        $number = $one1.','.$one2.','.$one3.','.$one4.','.$one5;
        $time = date('Ymd');
        $result = Gfffc::find()->where(['time'=>$time])->orderBy('id desc')->one();
        $czdh = Gfffc::find()->orderBy('period DESC')->one();
        if($result){
            $redis->set('gfffc',time());
            $redis->expire('gfffc',60);
            $model->time = date('Ymd');
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方分分彩';
            $model->period = sprintf('%01s',$czdh->period+1);;
            $model->behind_period = $model->period +1;
            $model->color_id = 112;
            if($model->period !==$time.'1441'){
                if($model->period ==$time.'1440'){
                    $num = substr($model->period,0,-4);
                    $model->behind_period = ($num+1).'0001';
                    $model->save();//保存
                }else{
                    $model->behind_period = $model->period+1;//拿到前端显示下一期的开奖期号
                    $model->save();//保存
                }
            }
            $lottery = new Lottery();
            $lottery->Winning(112);
            //$Robot = new Robot();//机器人
            //$Robot->RobotBuy('官方分分彩');
            /**60秒倒计时的时候调
             * @return mixed
             */
            $prizepools = PrizePool::find()->where(['add_time' => date("Ymd", time())])->all();
            if ($prizepools) {
                foreach ($prizepools as $prizepool) {
                    if ($prizepool->zhangfu >= -40000000 && $prizepool->zhangfu <= 60000000) {
                        if ($prizepool->zhangfu == 0) {
                            $jian = mt_rand(10000, 200000);
                            $prizepool->zhangfu -= $jian;
                            $prizepool->money -= $jian;
                        } else {
                            $jian = mt_rand(10000, 200000);
                            $jia = mt_rand(10000, 200000);
                            $prizepool->zhangfu += $jian - $jia;
                            $prizepool->money += $jian - $jia;
                        }
                    }
                    $prizepool->save();
                }
            }


        }else{
            $model->time = date('Ymd');
            $period = $time.'0000';
            $receive_time = time();
            $model->status = 1;
            $model->number = $number;
            $model->receive_time = $receive_time;
            $model->name = '官方分分彩';
            $model->period = $period;
            $model->behind_period = $model->period +1;;
            $model->save();
        }
    }

    public function actionIn(){
        return resis(112);
    }
}
