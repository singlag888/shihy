<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11 0011
 * Time: 18:59
 */

namespace frontend\controllers;


use backend\models\Hg15fc;
use backend\models\Hg15fcWin;
use backend\models\Jndsfc;
use backend\models\JndsfcWin;
use backend\models\Ltxffc;
use backend\models\LtxffcWin;
use backend\models\Rebate;
use backend\models\Reports;
use backend\models\White;
use backend\models\Admin;
use backend\models\Gfffc;
use backend\models\GfffcWin;
use backend\models\Gflfc;
use backend\models\GflfcWin;
use backend\models\Gfwfc;
use backend\models\GfwfcWin;
use backend\models\HowToPlay;
use backend\models\PlayMethod;
use backend\models\XjplfcWin;
use backend\models\Xxl45mc;
use backend\models\Xxl45mcWin;
use frontend\models\Ahks;
use frontend\models\AhksWin;
use frontend\models\Bjks;
use frontend\models\BjksWin;
use frontend\models\Bjpk10;
use frontend\models\Bjpk10Win;
use frontend\models\BuyTogether;
use frontend\models\Change;
use frontend\models\Cqssc;
use frontend\models\CqsscWin;
use frontend\models\Gd11x5;
use frontend\models\Gd11x5Win;
use frontend\models\Gf11x5;
use frontend\models\Gf11x5Win;
use frontend\models\Gfffsc;
use frontend\models\GfffscWin;
use frontend\models\Gfks;
use frontend\models\GfksWin;
use frontend\models\Gfsfsc;
use frontend\models\GfsfscWin;
use frontend\models\Gxks;
use frontend\models\GxksWin;
use frontend\models\Js11x5;
use frontend\models\Js11x5Win;
use frontend\models\Jsks;
use frontend\models\JsksWin;
use frontend\models\Jx11x5;
use frontend\models\Jx11x5Win;
use frontend\models\Lottery;
use frontend\models\Sh11x5;
use frontend\models\Sh11x5Win;
use frontend\models\Ssctxffc;
use frontend\models\SsctxffcWin;
use frontend\models\Tjssc;
use frontend\models\TjsscWin;
use frontend\models\Txffc;
use frontend\models\TxffcWin;
use frontend\models\Xjplfc;
use frontend\models\Xjssc;
use frontend\models\XjsscWin;
use yii\web\Controller;
use yii\web\Response;

header('Access-Control-Allow-Origin:*');
header("Content-type: text/html; charset=utf-8");

class Api3Controller extends Controller
{

    public $that = [];

    //解决网页报400错误
    public $enableCsrfValidation = false;


    //设置相应的数据格式
    public function init()
    {
        //数据格式为JSON
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }


    /**
     * 试玩账号
     */
    public function actionTrialPlay()
    {
        $admin = new Admin();
        $ip = \Yii::$app->request->userIP;
        $ips = White::findOne(['ip' => $ip]);
        if (!$ips) {
            return messages('操作失败,您的IP不允许访问');
        }
        $string = 'qwertyuioplkjhgfdsazxcvbnm0123456987';
        $str = str_shuffle($string);
        $rand = substr($str, 0, 5);
        $admin->username = $rand;
        $admin->password_login = \Yii::$app->security->generateRandomString();
        $admin->password_pay = \Yii::$app->security->generateRandomString();
        $token = \Yii::$app->security->generateRandomString();
        $admin->tokens = $token;
        $admin->price = 5000;
        $admin->max = 1900;
        $admin->min = 1800;
        $admin->type = 2;
        $admin->save();
        return messages('操作成功', 1, ['id' => $admin->id, 'token' => $token]);
    }


    /**
     * 开奖期号,下期期号,当期开奖号码
     */
    public function actionPrize()
    {
        $data = \Yii::$app->request->post('color_id');
        if ($data == 60) {
            $model = Txffc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 112) {
            $model = Gfffc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 113) {
            $model = Gflfc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 114) {
            $model = Gfwfc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 117) {
            $model = Gfks::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 116) {
            $model = Gf11x5::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 118) {
            $model = Gfffsc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 1) {
            $model = Cqssc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 3) {
            $model = Tjssc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 7) {
            $model = Xjssc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 9) {
            $model = Gd11x5::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 6) {
            $model = Jx11x5::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 115) {
            $model = Js11x5::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 22) {
            $model = Sh11x5::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 23) {
            $model = Jsks::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 24) {
            $model = Ahks::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 37) {
            $model = Bjks::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 38) {
            $model = Gxks::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 119) {
            $model = Gfsfsc::find()->orderBy('id DESC')->limit(10)->all();
        } elseif ($data == 27) {
            $model = Bjpk10::find()->orderBy('id DESC')->limit(10)->all();
        }elseif ($data == 1299) {
            $model = Ltxffc::find()->orderBy('id DESC')->limit(10)->all();
        }elseif ($data == 42) {
            $model = Hg15fc::find()->orderBy('id DESC')->limit(10)->all();
        }elseif ($data == 1297) {
            $model = Jndsfc::find()->orderBy('id DESC')->limit(10)->all();
        }elseif ($data == 1298) {
            $model = Xjplfc::find()->orderBy('id DESC')->limit(10)->all();
        }elseif ($data == 66) {
            $model = Xxl45mc::find()->orderBy('id DESC')->limit(10)->all();
        }
//        if($model =='' || $model==null){
//            return messages('操作失败，没有数据',1);
//        }
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['list'] = $model;
        return $result;
    }


    public function actionWinList()
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            if ($data) {
                $db = '';
                if ($data['color_id'] == 60) {
                    $db = TxffcWin::find();
                } elseif ($data['color_id'] == 112) {//官方分分彩
                    $db = GfffcWin::find();
                } elseif ($data['color_id'] == 117) {//官方快三
                    $db = GfksWin::find();
                } elseif ($data['color_id'] == 116) {//官方11选5
                    $db = Gf11x5Win::find();
                } elseif ($data['color_id'] == 118) {//官方分分赛车
                    $db = GfffscWin::find();
                } elseif ($data['color_id'] == 113) {//官方两分彩
                    $db = GflfcWin::find();
                } elseif ($data['color_id'] == 114) {//官方五分彩
                    $db = GfwfcWin::find();
                } elseif ($data['color_id'] == 1) {//重庆时时彩
                    $db = CqsscWin::find();
                } elseif ($data['color_id'] == 3) {//天津时时彩
                    $db = TjsscWin::find();
                } elseif ($data['color_id'] == 7) {//新疆时时彩
                    $db = XjsscWin::find();
                } elseif ($data['color_id'] == 9) {//广东11选5
                    $db = Gd11x5Win::find();
                } elseif ($data['color_id'] == 6) {//江西11选5
                    $db = Jx11x5Win::find();
                } elseif ($data['color_id'] == 115) {//江苏11选5
                    $db = Js11x5Win::find();
                } elseif ($data['color_id'] == 22) {//上海11选5
                    $db = Sh11x5Win::find();
                } elseif ($data['color_id'] == 23) {//江苏快三
                    $db = JsksWin::find();
                } elseif ($data['color_id'] == 24) {//安徽快三
                    $db = AhksWin::find();
                } elseif ($data['color_id'] == 37) {//北京快三
                    $db = BjksWin::find();
                } elseif ($data['color_id'] == 38) {//广西快三
                    $db = GxksWin::find();
                } elseif ($data['color_id'] == 119) {//官方三分赛车
                    $db = GfsfscWin::find();
                } elseif ($data['color_id'] == 27) {//北京PK10
                    $db = Bjpk10Win::find();
                }elseif ($data['color_id'] == 66) {//新西兰45秒彩
                    $db = Xxl45mcWin::find();
                }elseif ($data['color_id'] == 1298) {//新加坡两分彩
                    $db = XjplfcWin::find();
                }elseif ($data['color_id'] == 1297) {//加拿大三分彩
                    $db = JndsfcWin::find();
                }elseif ($data['color_id'] == 42) {//韩国1.5分彩
                    $db = Hg15fcWin::find();
                }elseif ($data['color_id'] == 1299) {//老腾讯分分彩
                    $db = LtxffcWin::find();
                }
                $data = $db->select(['username', 'game_play', 'winning_amount'])->orderBy('id desc')->limit(10)->all();
                if ($data) {
                    foreach ($data as $v) {
                        $v->username = mb_substr($v->username, 0, 3, 'utf-8') . '***';
                    }
                } else {
                    $data = [];
                }

                return messages('操作成功', 1, $data);
            }
        }

    }


    /**
     * 上期选号
     */
    public function actionLastNumber()
    {
        $query = \Yii::$app->request->post();
        $color = \Yii::$app->request->post('color_id');
        if (token($query['admin_id'], $query['token'])) {
            if ($color == 60) { //腾讯分分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 60, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 1) { //重庆时时彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 1, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 3) { //天津时时彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 3, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 7) { //新疆时时彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 7, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 112) { //官方分分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 112, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 113) { //官方两分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 113, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 114) { //官方五分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 114, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 9) { //广东11选5
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 9, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 6) { //江西11选5
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 6, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 115) { //江苏11选5
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 115, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 22) { //上海11选5
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 22, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 116) { //官方11选5
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 116, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 23) { //江苏快三
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 23, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 24) { //安徽快三
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 24, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 37) { //北京快三
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 37, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 38) { //广西快三
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 38, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 117) { //官方快三
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 117, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 27) { //北京PK10
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 27, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 118) { //官方分分赛车
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 118, 'admin_id' => $query['admin_id']]);
            } elseif ($color == 119) { //官方三分赛车
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 119, 'admin_id' => $query['admin_id']]);
            }elseif ($color == 1299) { //老腾讯分分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 1299, 'admin_id' => $query['admin_id']]);
            }elseif ($color == 42) { //韩国1.5分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 42, 'admin_id' => $query['admin_id']]);
            }elseif ($color == 1297) { //加拿大三分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 1297, 'admin_id' => $query['admin_id']]);
            }elseif ($color == 1298) { //新加坡两分彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 1298, 'admin_id' => $query['admin_id']]);
            }elseif ($color == 66) { //新西兰45秒彩
                $result = Lottery::findOne(['period' => $query['period'], 'color' => 66, 'admin_id' => $query['admin_id']]);
            }

            if ($result) {
                foreach ($result as $value) {
                    $howtoplay = HowToPlay::findOne(['id' => $value->color]);
                    $playmethod = PlayMethod::find()->where(['lottery_type' => $value->color])->andWhere(['number' => $value->play])->one();
                    $value->color = $howtoplay->name;//彩种
                    $value->play = $playmethod->name;
                }
                return messages('操作成功', 1, $result);
            } else {
                return messages('操作失败，上期没有购买', -1);
            }
        } else {
            return messages('操作失败,秘钥错误', -2);
        }


    }


    /**
     * 投注记录
     */
    public function actionLottery()
    {
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];
        $start_time = strtotime($query['start_time']);
        $end_time = strtotime($query['end_time']);
        if (!$start_time || !$end_time) {
            $model = Lottery::find()->where(['admin_id' => $query['admin_id']])->andWhere(['type' => $query['type']])->orderBy('id DESC')->offset($page)->limit($query['page_number'])->all();
            $pageSize = Lottery::find()->where(['admin_id' => $query['admin_id']])->andWhere(['type' => $query['type']])->count();
        } else {
            $model = Lottery::find()->where(['admin_id' => $query['admin_id']])->andWhere(['type' => $query['type']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->offset($page)->limit($query['page_number'])->orderBy('id desc')->all();
            $pageSize = Lottery::find()->where(['admin_id' => $query['admin_id']])->andWhere(['type' => $query['type']])->andWhere(['>=', 'created_time', $start_time])->andWhere(['<=', 'created_time', $end_time])->count();
        }

        if (empty($model)) {
            return messages('操作失败,没有数据', 1);
        }
        foreach ($model as $item) {
            $howtoplay = HowToPlay::findOne(['id' => $item->color]);//彩种
            $playmethod = PlayMethod::find()->where(['lottery_type' => $item->color])->andWhere(['number' => $item->play])->one();
            $item->color = $howtoplay->name;
            $item->play = $playmethod->name;
        }
        $page = ceil($pageSize / $query['page_number']);
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['list'] = $model;
        $result['data']['yeshu'] = $page;
        return $result;
    }


    /**
     * 下级投注记录(团队)
     */
    public function actionTeamLottery()
    {
        $query = \Yii::$app->request->post();
        if ($query['page'] && $query['page_number'] && $query['token'] && $query['admin_id']) {
            $page = ($query['page'] - 1) * $query['page_number'];
            if (token($query['admin_id'], $query['token'])) {
                $admin = new Admin();
                $shy = $admin->all($query['admin_id']);
                foreach ($shy as $s) {
                    $this->that[] = Lottery::find()->where(['admin_id' => $s])->orderBy('id DESC')->asArray()->all();
                }
                $sh = array_filter($this->that);
                foreach ($sh as $vv) {
                    foreach ($vv as $item) {
                        $qwe[] = $item;
                    }
                }
                if (empty($qwe)) {
                    return messages('操作失败,没有数据', 1);
                }
                $qwe = array_slice($qwe, $page, $query['page_number']);
                $result['msg'] = '操作成功';
                $result['error'] = 1;
                $result['data']['list'] = $qwe;
                $result['data']['yeshu'] = ceil(count($qwe) / $query['page_number']);
                return $result;
            } else {
                return messages('操作失败,秘钥错误', -2);
            }
        }
    }


    /**
     * 查看网页多少人在线
     */
    public function actionPeople()
    {
        $online_log = "count.txt"; //保存人数的文件,
        $timeout = 30;//30秒内没动作者,认为掉线
        $entries = file($online_log);
        $temp = array();
        for ($i = 0; $i < count($entries); $i++) {
            $entry = explode(",", trim($entries[$i]));
            if (($entry[0] != getenv('REMOTE_ADDR')) && ($entry[1] > time())) {
                array_push($temp, $entry[0] . "," . $entry[1] . "\n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp
            }
        }
        array_push($temp, getenv('REMOTE_ADDR') . "," . (time() + ($timeout)) . "\n"); //更新浏览者的时间
        $users_online = count($temp); //计算在线人数
        $entries = implode("", $temp);
//写入文件
        $fp = fopen($online_log, "w");
        flock($fp, LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
        fputs($fp, $entries);
        flock($fp, LOCK_UN);
        fclose($fp);
        echo "当前有" . $users_online . "人在线";
    }


    /**
     * 测试token保存有效时间
     */
    public function actionToken()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $token = \Yii::$app->security->generateRandomString();
        $redis->set('token', $token);
        var_dump($redis->get('token'));
    }







//-------------------------------------------------------合买-------------------------------------------------------------//

    /**
     * 用户发起合买
     */
    public function actionBuy()
    {
        $query = \Yii::$app->request->post();
        $model = new BuyTogether();
        $models = new Lottery();
        if (token($query['admin_id'], $query['token'])) {
            //彩种总开关
            $howtoplay = HowToPlay::findOne(['id' => $query['color_id']]);
            if ($howtoplay->status == -1) {
                return messages('该玩法已停止');
            } elseif ($howtoplay->status == -2) {
                return messages('该玩法已停售');
            }
            //玩法控制
            $playmethod = PlayMethod::find()->where(['lottery_type' => $query['color_id']])->andWhere(['number' => $query['play_id']])->one();
            if ($playmethod->status == 0) {
                return messages('该玩法维护中');
            } elseif ($playmethod->status == -1) {
                return messages('该玩法已停售');
            } elseif ($playmethod->note) {
                if ($query['quantity'] > $playmethod->note) {
                    return messages('注单数不能超过平台限制');
                }
            }
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            if ($query['color_id'] == 112) {
                if (resis(112) < 10) {
                    return messages('购买失败，官方分分彩已封盘');
                }
            } elseif ($query['color_id'] == 60) {
                if (resis(60) < 10) {
                    return messages('购买失败，腾讯分分彩已封盘');
                }
            } elseif ($query['color_id'] == 113) {
                if (resis(113) < 20) {
                    return messages('购买失败，官方两分彩已封盘');
                }
            } elseif ($query['color_id'] == 114) {
                if (resis(114) < 60) {
                    return messages('购买失败，官方五分彩已封盘');
                }
            } elseif ($query['color_id'] == 1) {
                if (resis(1) < 90) {
                    return messages('购买失败，重庆时时彩已封盘');
                }
            } elseif ($query['color_id'] == 3) {
                if (resis(3) < 120) {
                    return messages('购买失败，天津时时彩已封盘');
                }
            } elseif ($query['color_id'] == 7) {
                if (resis(7) < 120) {
                    return messages('购买失败，新疆时时彩已封盘');
                }
            } elseif ($query['color_id'] == 9) {
                if (resis(9) < 120) {
                    return messages('购买失败，广东11选5已封盘');
                }
            } elseif ($query['color_id'] == 6) {
                if (resis(6) < 120) {
                    return messages('购买失败，江西11选5已封盘');
                }
            } elseif ($query['color_id'] == 115) {
                if (resis(115) < 120) {
                    return messages('购买失败，江苏11选5已封盘');
                }
            } elseif ($query['color_id'] == 22) {
                if (resis(22) < 120) {
                    return messages('购买失败，上海11选5已封盘');
                }
            } elseif ($query['color_id'] == 116) {
                if (resis(116) < 120) {
                    return messages('购买失败，官方11选5已封盘');
                }
            } elseif ($query['color_id'] == 23) {
                if (resis(23) < 120) {
                    return messages('购买失败，江苏快三已封盘');
                }
            } elseif ($query['color_id'] == 24) {
                if (resis(24) < 120) {
                    return messages('购买失败，安徽快三已封盘');
                }
            } elseif ($query['color_id'] == 37) {
                if (resis(37) < 120) {
                    return messages('购买失败，北京快三已封盘');
                }
            } elseif ($query['color_id'] == 38) {
                if (resis(38) < 120) {
                    return messages('购买失败，广西快三已封盘');
                }
            } elseif ($query['color_id'] == 117) {
                if (resis(117) < 120) {
                    return messages('购买失败，官方快三已封盘');
                }
            } elseif ($query['color_id'] == 27) {
                if (resis(27) < 120) {
                    return messages('购买失败，北京PK10已封盘');
                }
            } elseif ($query['color_id'] == 118) {
                if (resis(118) < 120) {
                    return messages('购买失败，官方分分赛车已封盘');
                }
            } elseif ($query['color_id'] == 119) {
                if (resis(119) < 120) {
                    return messages('购买失败，官方三分赛车已封盘');
                }

            }elseif ($query['color_id'] == 1299) {
                if (resis(1299) < 10) {
                    return messages('购买失败，老腾讯分分彩已封盘');
                }

            }elseif ($query['color_id'] == 42) {
                if (resis(42) < 15) {
                    return messages('购买失败，韩国1.5分彩已封盘');
                }

            }elseif ($query['color_id'] == 1297) {
                if (resis(1297) < 30) {
                    return messages('购买失败，加拿大三分彩已封盘');
                }

            }elseif ($query['color_id'] == 1298) {
                if (resis(1298) < 20) {
                    return messages('购买失败，新加坡两分彩已封盘');
                }

            }elseif ($query['color_id'] == 66) {
                if (resis(66) < 10) {
                    return messages('购买失败，新西兰45秒彩已封盘');
                }

            }
            if ($query['color_id'] == 60) {//腾讯分分彩
                $dbs = Txffc::find();
            } elseif ($query['color_id'] == 1) {//重庆时时彩
                $dbs = Cqssc::find();
            } elseif ($query['color_id'] == 3) {//天津时时彩
                $dbs = Tjssc::find();
            } elseif ($query['color_id'] == 7) {//新疆时时彩
                $dbs = Xjssc::find();
            } elseif ($query['color_id'] == 112) {//官方分分彩
                $dbs = Gfffc::find();
            } elseif ($query['color_id'] == 113) {//官方两分彩
                $dbs = Gflfc::find();
            } elseif ($query['color_id'] == 114) {//官方五分彩
                $dbs = Gfwfc::find();
            } elseif ($query['color_id'] == 9) {//广东11选5
                $dbs = Gd11x5::find();
            } elseif ($query['color_id'] == 6) {//江西11选5
                $dbs = Jx11x5::find();
            } elseif ($query['color_id'] == 115) {//江苏11选5
                $dbs = Js11x5::find();
            } elseif ($query['color_id'] == 22) {//上海11选5
                $dbs = Sh11x5::find();
            } elseif ($query['color_id'] == 116) {//官方11选5
                $dbs = Gf11x5::find();
            } elseif ($query['color_id'] == 23) {//江苏快三
                $dbs = Jsks::find();
            } elseif ($query['color_id'] == 24) {//安徽快三
                $dbs = Ahks::find();
            } elseif ($query['color_id'] == 37) {//北京快三
                $dbs = Bjks::find();
            } elseif ($query['color_id'] == 38) {//广西快三
                $dbs = Gxks::find();
            } elseif ($query['color_id'] == 117) {//官方快三
                $dbs = Gfks::find();
            } elseif ($query['color_id'] == 27) {//北京PK10
                $dbs = Bjpk10::find();
            } elseif ($query['color_id'] == 118) {//官方分分赛车
                $dbs = Gfffsc::find();
            } elseif ($query['color_id'] == 119) {//官方三分赛车
                $dbs = Gfsfsc::find();
            }elseif ($query['color_id'] == 1299) {//老腾讯分分彩
                $dbs = Ltxffc::find();
            }elseif ($query['color_id'] == 42) {//韩国1.5分彩
                $dbs = Hg15fc::find();
            }elseif ($query['color_id'] == 1297) {//加拿大三分彩
                $dbs = Jndsfc::find();
            }elseif ($query['color_id'] == 1298) {//新加坡两分彩
                $dbs = Xjplfc::find();
            }elseif ($query['color_id'] == 66) {//新西兰45秒彩
                $dbs = Xxl45mc::find();
            }
            $ssctxffc = $dbs->orderBy('id desc')->where(['status' => 1])->one();
            if ($ssctxffc->behind_period !== $query['period']) {
                return messages('购买失败,改期已经开奖');
            }
//            $zimu = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');
//            $shuzi = str_shuffle('123456789');
//            $dingdan = substr($shuzi, 0, 5) . substr($zimu, 0, 5);
            $model->admin_id = $query['admin_id'];//用户ID
            $models->admin_id = $query['admin_id'];
            $admin = Admin::findOne(['id' => $query['admin_id']]);
            $model->username = $admin->username;//用户名
            $models->username = $admin->username;//用户名
            $model->order = $query['order'];//订单号
            $models->order = $query['order'];//订单号
            $model->created_time = time();//购买时间
            $models->created_time = time();//购买时间
            $model->content = $query['content'];//购买号码
            $models->content = $query['content'];//购买号码
            $model->prices = $query['prices'];//总金额
            $models->prices = $query['prices'];//总金额
            $models->price = $query['price'];//认购金额
            if($query['price'] > $admin->price){
                return messages('购买失败,余额不足');
            }
            if ($query['price'] > $query['prices']) {
                return messages('购买失败,发起购买的金额大于总金额');
            }
            $model->last_price = $query['prices'] - $query['price']; //剩余金额
            $model->color = $query['color_id'];//彩种
            $models->color = $query['color_id'];//彩种
            $model->play = $query['play_id'];//玩法
            $models->play = $query['play_id'];//玩法
            $model->bonus = $query['bonus'];//单柱奖金
            $models->bonus = $query['bonus'];//单柱奖金
            $model->period = $query['period'];//购买期号
            $models->period = $query['period'];//购买期号
            $model->quantity = $query['quantity'];//总注数
            $models->quantitys = $query['quantity'];//总注数
            $models->quantity = $query['my_quantity'];//用户购买的注数
            $model->last_quantity = $query['quantity'] - $query['my_quantity'];    //剩余注数
            //接受到用户选择的信息
            if ($query['my_quantity'] > $query['quantity']) {
                return messages('购买失败,发起购买的注数大于总注数');
            }
            $model->mode = $query['mode'];//元角分模式
            $models->mode = $query['mode'];//元角分模式
            $model->multiple = $query['multiple'];//倍数
            $models->multiple = $query['multiple'];//
            $models->type = 2;//倍数
            Admin::updateAll(['price' => $admin->price - $query['price']], ['id' => $query['admin_id']]);
            $model->save();
            $models->buy_id = $model->id;
            $models->save();
            //保存资金流水
            $change = new Change();
            $arr['admin_id'] = $query['admin_id'];
            $arr['username'] = $admin->username;
            $arr['type'] = '彩票下注';
            $arr['last_price'] = '-' . $query['price'];
            $arr['price'] = $admin->price - $query['price'];
            $arr['front_price'] = $admin->price;
            $arr['game_type'] = '彩票';
            $arr['intro'] = $howtoplay['name'] . $query['period'] . '期下注';
            $change->Change($arr);
            $reports = Reports::findOne(['admin_id'=>$query['admin_id']]);
            $reports->all_price += $query['price'];
            $reports->all_price_num += 1;
            $reports->save();
            return messages('购买成功', 1,$models);
        } else {
            return messages('操作失败,秘钥错误', -2);
        }

    }


    /**
     * 用户查看自己合买记录
     */
    public function actionBuyList()
    {
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];//条数 页数
        if (token($query['admin_id'], $query['token'])) {
            $model = Lottery::find()->orderBy('id DESC')->offset($page)->limit($query['page_number'])->where(['admin_id' => $query['admin_id']])->andWhere(['type' => 2])->all();//用户ID
            if (empty($model)) {
                return messages('操作失败,没有数据', 1);
            }
            foreach ($model as $item) {
                $howtoplay = HowToPlay::findOne(['id' => $item->color]);//彩种
                $playmethod = PlayMethod::find()->where(['lottery_type' => $item->color])->andWhere(['number' => $item->play])->one();
                $item->color = $howtoplay->name;
                $item->play = $playmethod->name;

            }
            $pageSize = Lottery::find()->where(['admin_id' => $query['admin_id']])->andWhere(['type' => 2])->count();
            $page = ceil($pageSize / $query['page_number']);
            $result['msg'] = '操作成功';
            $result['error'] = 1;
            $result['data']['list'] = $model;
            $result['data']['yeshu'] = $page;
            return $result;
        } else {
            return messages('操作失败,秘钥错误', -2);
        }
    }


    /**
     * 其他用户查看别人发起的合买
     */
    public function actionOther()
    {
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];//条数 页数
        $model = BuyTogether::find()->orderBy('id DESC')->offset($page)->limit($query['page_number'])->where(['period' => $query['period']])->all();//期号
        if (empty($model)) {
            return messages('操作失败,没有数据', 1);
        }
        foreach ($model as $item) {
            $howtoplay = HowToPlay::findOne(['id' => $item->color]);//彩种
            $playmethod = PlayMethod::find()->where(['lottery_type' => $item->color])->andWhere(['number' => $item->play])->one();
            $item->color = $howtoplay->name;
            $item->play = $playmethod->name;

        }
        $pageSize = BuyTogether::find()->where(['period' => $query['period']])->count();
        $page = ceil($pageSize / $query['page_number']);
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['list'] = $model;
        $result['data']['yeshu'] = $page;
        return $result;
    }


    /**
     * 用户自己查看自己发起的合买
     */
    public function actionMy()
    {
        $query = \Yii::$app->request->post();
        $page = ($query['page'] - 1) * $query['page_number'];//条数 页数
        $model = BuyTogether::find()->orderBy('id DESC')->where(['admin_id' => $query['admin_id']])->all();//期号
        $pageSize = 0;
        if (empty($model)) {
            return messages('操作失败,没有数据', 1);
        }
        foreach ($model as $v) {
            $lottery[] = Lottery::find()->where(['type' => 2])->andWhere(['admin_id' => $v['admin_id']])->andWhere(['buy_id' => $v['id']])->all();
        }

        if (empty($lottery)) {
            return messages('操作失败,没有数据', 1);
        }
        foreach ($lottery as $value) {
            foreach ($value as $item) {
                $howtoplay = HowToPlay::findOne(['id' => $item->color]);//彩种
                $playmethod = PlayMethod::find()->where(['lottery_type' => $item->color])->andWhere(['number' => $item->play])->one();
                $item->color = $howtoplay->name;
                $item->play = $playmethod->name;
                $shy[] = $item;
            }
        }
        $pages = array_slice($shy, $page, $query['page_number']);
        $count = count($shy);
        $page = ceil($count / $query['page_number']);
        $result['msg'] = '操作成功';
        $result['error'] = 1;
        $result['data']['list'] = $pages;
        $result['data']['yeshu'] = $page;
        return $result;
    }


    /**
     * 跟单合买
     */
    public function actionBuys()
    {
        $query = \Yii::$app->request->post();
        $model = BuyTogether::find()->orderBy('id DESC')->where(['id' => $query['id']])->one();//期号
        $shy = new Lottery();
        if (token($query['admin_id'], $query['token'])) {
            //彩种总开关
            $howtoplay = HowToPlay::findOne(['id' => $model['color']]);
            if ($howtoplay->status == -1) {
                return messages('该玩法已停止');
            } elseif ($howtoplay->status == -2) {
                return messages('该玩法已停售');
            }
            //玩法控制
            $playmethod = PlayMethod::find()->where(['lottery_type' => $model['color']])->andWhere(['number' => $model['play']])->one();
            if ($playmethod->status == 0) {
                return messages('该玩法维护中');
            } elseif ($playmethod->status == -1) {
                return messages('该玩法已停售');
            } elseif ($playmethod->note) {
                if ($query['quantity'] > $playmethod->note) {
                    return messages('注单数不能超过平台限制');
                }
            }
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            if ($model['color'] == 112) {
                if (resis(112) < 10) {
                    return messages('购买失败，官方分分彩已封盘');
                }
            } elseif ($model['color'] == 60) {
                if (resis(60) < 10) {
                    return messages('购买失败，腾讯分分彩已封盘');
                }
            } elseif ($model['color'] == 113) {
                if (resis(113) < 20) {
                    return messages('购买失败，官方两分彩已封盘');
                }
            } elseif ($model['color'] == 114) {
                if (resis(114) < 60) {
                    return messages('购买失败，官方五分彩已封盘');
                }
            } elseif ($model['color'] == 1) {
                if (resis(1) < 90) {
                    return messages('购买失败，重庆时时彩已封盘');
                }
            } elseif ($model['color'] == 3) {
                if (resis(3) < 120) {
                    return messages('购买失败，天津时时彩已封盘');
                }
            } elseif ($model['color'] == 7) {
                if (resis(7) < 120) {
                    return messages('购买失败，新疆时时彩已封盘');
                }
            } elseif ($model['color'] == 9) {
                if (resis(9) < 120) {
                    return messages('购买失败，广东11选5已封盘');
                }
            } elseif ($model['color'] == 6) {
                if (resis(6) < 120) {
                    return messages('购买失败，江西11选5已封盘');
                }
            } elseif ($model['color'] == 115) {
                if (resis(115) < 120) {
                    return messages('购买失败，江苏11选5已封盘');
                }
            } elseif ($model['color'] == 22) {
                if (resis(22) < 120) {
                    return messages('购买失败，上海11选5已封盘');
                }
            } elseif ($model['color'] == 116) {
                if (resis(116) < 120) {
                    return messages('购买失败，官方11选5已封盘');
                }
            } elseif ($model['color'] == 23) {
                if (resis(23) < 120) {
                    return messages('购买失败，江苏快三已封盘');
                }
            } elseif ($model['color'] == 24) {
                if (resis(24) < 120) {
                    return messages('购买失败，安徽快三已封盘');
                }
            } elseif ($model['color'] == 37) {
                if (resis(37) < 120) {
                    return messages('购买失败，北京快三已封盘');
                }
            } elseif ($model['color'] == 38) {
                if (resis(38) < 120) {
                    return messages('购买失败，广西快三已封盘');
                }
            } elseif ($model['color'] == 117) {
                if (resis(117) < 120) {
                    return messages('购买失败，官方快三已封盘');
                }
            } elseif ($model['color'] == 27) {
                if (resis(27) < 120) {
                    return messages('购买失败，北京PK10已封盘');
                }
            } elseif ($model['color'] == 118) {
                if (resis(118) < 120) {
                    return messages('购买失败，官方分分赛车已封盘');
                }
            } elseif ($model['color'] == 119) {
                if (resis(119) < 120) {
                    return messages('购买失败，官方三分赛车已封盘');
                }
            }elseif ($query['color_id'] == 1299) {
                if (resis(1299) < 10) {
                    return messages('购买失败，老腾讯分分彩已封盘');
                }

            }elseif ($query['color_id'] == 42) {
                if (resis(42) < 15) {
                    return messages('购买失败，韩国1.5分彩已封盘');
                }

            }elseif ($query['color_id'] == 1297) {
                if (resis(1297) < 30) {
                    return messages('购买失败，加拿大三分彩已封盘');
                }

            }elseif ($query['color_id'] == 1298) {
                if (resis(1298) < 20) {
                    return messages('购买失败，新加坡两分彩已封盘');
                }

            }elseif ($query['color_id'] == 66) {
                if (resis(66) < 10) {
                    return messages('购买失败，新西兰45秒彩已封盘');
                }

            }
            $shy->admin_id = $query['admin_id'];//用户ID
            $admin = Admin::findOne(['id' => $query['admin_id']]);
            $b = Rebate::findOne(['id'=>1]);
            $price = $query['price'] * $b->brokerage;
            $price = settype($price, 'string');//返点佣金
            if ($admin->price < $query['price'] + $price) {
                return messages('购买失败,余额不足');
            } else {

                $shy->username = $admin->username;//用户名
                $shy->order = $model->order;//订单号
                $shy->created_time = time();//购买时间
                $shy->content = $model->content;//购买号码
                $shy->price = $query['price'];//认购金额
                $shy->prices = $model->prices;//总金额
                $shy->buy_id = $model->id;//总金额
                if ($model->last_price - $query['price'] < 0) {
                    return messages('购买失败');
                } else {
                    BuyTogether::updateAll(['last_price' => $model->last_price - $query['price']], ['id' => $query['id']]);
                }
                $shy->color = $model->color;//彩种
                $shy->play = $model->play;//玩法
                $shy->type = 2;//类型
                $shy->list = 2;//是否跟单 2为合买跟单
                $shy->brokerage = $price;//佣金
                $shy->period = $model->period;//购买期号
                $shy->bonus = $model->bonus;//购买期号
                $shy->quantitys = $model->quantity;//总注数
                $shy->quantity = $query['my_quantity'];//用户购买的注数
                if ($model->last_quantity - $query['my_quantity'] < 0) {
                    return messages('购买失败');
                } else {
                    BuyTogether::updateAll(['last_quantity' => $model->last_quantity - $query['my_quantity']], ['id' => $query['id']]);
                }
                $shy->mode = $model->mode;//元角分模式
                $shy->multiple = $model->multiple;//倍数
                //扣钱
                Admin::updateAll(['price' => $admin->price - $query['price'] ], ['id' => $query['admin_id']]);
                //保存资金流水
                $change = new Change();
                $arr['admin_id'] = $query['admin_id'];
                $arr['username'] = $admin->username;
                $arr['type'] = '彩票下注';
                $arr['last_price'] = '-' . $query['price'];
                $arr['price'] = $admin->price - $query['price'];
                $arr['front_price'] = $admin->price;
                $arr['game_type'] = '彩票';
                $arr['intro'] = $howtoplay['name'] . $query['period'] . '期下注';
                $change->Change($arr);
                $reports = Reports::findOne(['admin_id'=>$query['admin_id']]);
                $reports->all_price += $query['price'];
                $reports->all_price_num += 1;
                $reports->save();
                $shy->save();
                return messages('购买成功', 1,$shy);
            }
        } else {
            return messages('操作失败,秘钥错误', -2);
        }


    }


    /**
     * 根据订单号搜索
     * @return mixed
     */
    public function actionSearch()
    {
        $model = BuyTogether::find()->where(['order' => \Yii::$app->request->post('order')])->one();
        return messages('操作成功', 1, $model);
    }


//-------------------------------------------------------合买功能结束---------------------------------------------------------------//
}